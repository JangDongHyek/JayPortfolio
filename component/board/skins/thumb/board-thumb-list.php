<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container my-5">
            <h3 class="mb-4">썸네일 게시판</h3>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                <!-- 게시글 카드 1 -->
                <div class="col" v-for="item in rows">
                    <a :href="goView(item)">
                    <div class="card h-100 shadow-sm">
                        <img :src="$jd.url + item.$jd_file.data[0].src" class="card-img-top" alt="썸네일">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="view.html" class="text-decoration-none text-dark">{{item.name}}</a>
                            </h5>
                            <p class="card-text text-truncate" v-html="item.content">
                            </p>
                        </div>
                        <div class="card-footer small text-muted d-flex justify-content-between">
                            <span>{{item.$user__name}}</span>
                            <span>{{item.insert_date.formatDate('yyyy-mm-dd')}}</span>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
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
                        file_db : true,
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
    /* 카드 제목 강조 */
    .card-title a {
        font-weight: 600;
    }

    /* 본문 미리보기 2줄 제한 */
    .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;   /* 2줄까지만 표시 */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>