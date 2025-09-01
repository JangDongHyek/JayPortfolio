<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">{{setting.name}}</h3>
                <!-- 검색 박스 -->
                <!-- 검색 박스 (제목 밑, 왼쪽 정렬) -->
                <div class="d-flex" role="search" style="max-width: 300px;">
                    <input class="form-control me-2 form-control-sm search-input" type="search" placeholder="검색어 입력" aria-label="Search" v-model="search_value" @keyup.enter="paging.page = 1; getData()">
                    <button class="btn btn-warning btn-sm search-btn" type="button" @click="paging.page = 1; getData()">검색</button>
                </div>
            </div>

            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                <tr>
                    <th style="width: 60px;">번호</th>
                    <th>제목</th>
                    <th style="width: 120px;">작성자</th>
                    <th style="width: 160px;">작성일</th>
                    <th style="width: 80px;">조회</th>
                    <th style="width: 80px;">좋아요</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in rows">
                    <td>{{item.__no__}}</td>
                    <td class="text-start"><a :href="goView(item)">{{item.name}}</a></td>
                    <td>{{item.$user__name}}</td>
                    <td>{{item.insert_date.formatDate('yyyy-mm-dd')}}</td>
                    <td>{{item.total_view}}</td>
                    <td>{{item.total_like}}</td>
                </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" @click="goInput()">등록</button>
            </div>
        </div>

        <item-paging :paging="paging" @change="getData()"></item-paging>
    </div>

    <div v-if="!load"><div class="loader"></div></div>
</script>

<script>
    JayDream_components.push({name : "<?=$componentName?>",object : {
            template: "#<?=$componentName?>-template",
            props: {
                primary : {type : String, default : ""},
                component : {type : String, default : ""},
                setting : {type : Object, default : null},
                user : {type : Object, default : null},
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows : [],

                    paging : {
                        page: 1,
                        limit: this.setting.page_count, // 해당 값 수정시 페이지에 노출되는 게시글 갯수가 바뀜
                        count: 0,
                    },

                    search_value : "",
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //this.row = await this.$getData({table : "",});
                // await this.$getsData({table : "",},this.rows);
                await this.getData();
                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async getData() {
                    let filter = {
                        table : "board",
                        paging : this.paging,

                        where: [
                            {column: "setting_idx",value: this.setting.idx},
                        ],

                        joins: [
                            {
                                table: "user",
                                base: "user_idx",               // filter 테이블의 연결 key
                                foreign: "idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["name"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                            },

                            {
                                table: "board_view",
                                base: "idx",               // filter 테이블의 연결 key
                                foreign: "board_idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["idx"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                            },

                            {
                                table: "board_like",
                                base: "idx",               // filter 테이블의 연결 key
                                foreign: "board_idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["idx"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                            },
                        ],

                        group_bys: {
                            by: ['board.idx'], // 그룹화 할 컬럼 * 앞에 테이블명시는 필수
                            selects: [
                                {
                                    type: "COUNT", // 집계함수
                                    column: "DISTINCT board_view.idx", // 집계함수 할 컬럼
                                    as: "total_view", // 필수값
                                },
                                {
                                    type: "COUNT", // 집계함수
                                    column: "DISTINCT board_like.idx", // 집계함수 할 컬럼
                                    as: "total_like", // 필수값
                                },
                            ]
                        },
                    }

                    if(this.search_value != "") {
                        filter.blocks = [
                            { // filter 형식으로 넣어주면된다 , 객체 하나당 () 괄호 조건문이 꾸며진다
                                logical: "AND", // 괄호 전 어떤 논리 연사자가 들어갈지
                                where: [
                                    {
                                        column: "name",             // join 조건시 user.idx
                                        value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                        logical: "OR",         // AND,OR,AND NOT
                                        operator: "LIKE",          // = ,!= >= <=, LIKE,
                                        encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                                    },
                                    {
                                        column: "content",             // join 조건시 user.idx
                                        value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                        logical: "OR",         // AND,OR,AND NOT
                                        operator: "LIKE",          // = ,!= >= <=, LIKE,
                                        encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                                    },
                                    {
                                        column: "user.name",             // join 조건시 user.idx
                                        value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                        logical: "OR",         // AND,OR,AND NOT
                                        operator: "LIKE",          // = ,!= >= <=, LIKE,
                                        encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                                    },
                                ],
                            },
                        ];
                    }

                    await this.$getsData(filter,this.rows);
                },
                goView(item) {
                    return this.$jd.lib.normalizeUrl("",{
                        mode : "view",
                        setting_idx : this.setting.idx,
                        primary : item.primary,
                        component : this.component,
                    })
                },
                goInput() {
                    window.location.href = this.$jd.lib.normalizeUrl("",{
                        mode : "input",
                        setting_idx : this.setting.primary,
                        component : this.component,
                    })
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>

    /* 게시판 제목 */
    .container h3 {
        font-weight: 600;
    }

    /* 테이블 hover 효과 */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* 제목 링크 */
    .table a {
        text-decoration: none;
        color: #212529;
    }
    .table a:hover {
        color: #0d6efd;
    }

    /* 검색 인풋 늘씬하게 */
    .search-input {
        height: 36px;           /* 세로 줄임 */
        font-size: 0.9rem;
    }

    /* 검색 버튼 늘씬하게 */
    .search-btn {
        height: 36px;           /* 인풋과 높이 맞춤 */
        padding: 0 15px;        /* 좌우 여백만 주기 */
        font-size: 0.9rem;
        line-height: 1.2;       /* 글자 세로 정렬 */
        white-space: nowrap;    /* 줄바꿈 방지 → '검색' 가로로 유지 */
    }
</style>