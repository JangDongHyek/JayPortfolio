<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container my-5">
            <!-- 제목 -->
            <h3 class="mb-4">{{row.name}}</h3>

            <!-- 작성 정보 -->
            <div class="d-flex justify-content-between border-bottom pb-2 mb-3 text-muted small">
                <div>
                    <span class="me-3">작성자: <strong>{{row.$user__name}}</strong></span>
                    <span class="me-3">작성일: {{row.insert_date.formatDate('yyyy-mm-dd')}}</span>
                    <span>조회: {{row.total_view}}</span>
                    <button class="btn-like ms-2" @click="postLike()" :class="{active : like}">
                        ❤ <span>{{ row.total_like || 0 }}</span>
                    </button>
                </div>
            </div>

            <div class="text-center mb-4">
                <img :src="$jd.url + row.$jd_file_thumb.data[0].src" class="img-fluid rounded shadow-sm" alt="대표 이미지">
            </div>

            <!-- 본문 -->
            <div class="mb-5" style="min-height:200px;" v-html="row.content">

            </div>

            <!-- 첨부된 파일 -->
            <div class="mb-4" v-if="row.$jd_file_upfiles.count > 0">
                <h6 class="mb-2">첨부파일</h6>
                <ul class="list-group">
                    <template v-for="item in row.$jd_file_upfiles.data">
                        <li class="list-group-item d-flex justify-content-between align-items-center" v-if="item.keyword == 'upfiles'">
                            {{item.name}}
                            <a :href="$jd.url + item.src" :download="item.name" class="btn btn-sm btn-outline-primary">다운로드</a>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- 이전글/다음글 -->
            <div class="border-top pt-3 mb-4">
                <div class="d-flex justify-content-between">
                    <div v-if="row.$prev.count > 0">
                        <span class="text-muted">이전글</span> :
                        <a :href="getUrl(row.$prev.data[0])">{{row.$prev.data[0].name}}</a>
                    </div>
                    <div v-if="row.$next.count > 0">
                        <span class="text-muted">다음글</span> :
                        <a :href="getUrl(row.$next.data[0])">{{row.$next.data[0].name}}</a>
                    </div>
                </div>
            </div>

            <!-- 버튼 영역 -->
            <div class="d-flex justify-content-end">
                <a :href="goList()" class="btn btn-secondary me-2">목록</a>
                <template v-if="user?.primary == row.user_idx || user?.level <= 1">
                    <a :href="goInput()" class="btn btn-primary me-2">수정</a>
                    <a @click="deleteData()" class="btn btn-danger">삭제</a>
                </template>
            </div>
        </div>
    </div>

    <div v-if="!load"><div class="loader"></div></div>
</script>

<script>
    JayDream_components.push({name : "<?=$componentName?>",object : {
            template: "#<?=$componentName?>-template",
            props: {
                primary : {type : String, default : ""},
                setting : {type : Object, default : null},
                user : {type : Object, default : null},
                component : {type : String, default : ""},
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows : [],

                    like : undefined,
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                await this.postView();
                await this.getLike();
                await this.getData();
                //await this.$getsData({table : "",},this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            methods: {
                async getLike() {
                    if(!this.user) return false;
                    this.like = await this.$getData({
                        table : "board_like",

                        where: [
                            {column: "board_idx",value: this.primary},
                            {column: "user_idx",value: this.user.primary},
                        ],
                    });
                },
                async postLike() {
                    if(!this.user) {
                        await this.$jd.lib.alert("로그인이 필요한 기능입니다.")
                        return false;
                    }

                    if(this.like) {
                        await this.$deleteData(this.like,{return : true});
                    }else {
                        let row = {
                            board_idx : this.row.primary,
                            user_idx : this.user.primary,
                        }
                        let options = {
                            table : "board_like",
                            exists: [ // (추가) 조건에 해당하는 데이터가 있는지 있다면 alert으로 message 노출 message가없다면 alert X
                                { // 최상단 filter 방식으로 똑같이 넣어주면된다
                                    table: "board_like",
                                    message: "",

                                    where: [
                                        {column: "board_idx",value: this.row.primary},
                                        {column: "user_idx",value: this.user.primary},
                                    ],
                                }
                            ],
                            return : true,
                        }

                        this.$postData(row,options)
                    }
                    window.location.reload();
                },
                async postView() {
                    if(!this.user) return false;
                    let row = {
                        board_idx : this.primary,
                        user_idx : this.user.primary,
                    }
                    let options = {
                        table : "board_view",
                        exists: [ // (추가) 조건에 해당하는 데이터가 있는지 있다면 alert으로 message 노출 message가없다면 alert X
                            { // 최상단 filter 방식으로 똑같이 넣어주면된다
                                table: "board_view",
                                message: "",

                                where: [
                                    {column: "board_idx",value: this.primary},
                                    {column: "user_idx",value: this.user.primary},
                                ],
                            }
                        ],
                        return : true,
                    }

                    this.$postData(row,options)
                },
                async deleteData() {
                    this.$deleteData(this.row,{
                        href : this.goList(),
                    });
                },
                async getData() {
                    this.row = await this.$getData({
                        table : "board",
                        file_db : true,
                        file_keywords : ["upfiles","thumb"],

                        where: [
                            {column: "primary",value: this.primary},
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

                        relations: [// filter 형식으로 똑같이 넣어주면 하위로 들어간다
                            {
                                table: "board",
                                as : "prev", // 빈값일시 $table 으로 삽입됌

                                where: [
                                    {
                                        column: "setting_idx",             // join 조건시 user.idx
                                        value: this.setting.idx,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                        logical: "AND",         // AND,OR,AND NOT
                                        operator: "=",          // = ,!= >= <=, LIKE,
                                        encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                                    },

                                    {
                                        column: "primary",             // join 조건시 user.idx
                                        value: this.primary,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                        logical: "AND",         // AND,OR,AND NOT
                                        operator: "<",          // = ,!= >= <=, LIKE,
                                        encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                                    },
                                ],
                            },
                            {
                                table: "board",
                                as : "next", // 빈값일시 $table 으로 삽입됌

                                order_by: [
                                    { column: "idx", value: "ASC" },
                                ],

                                where: [
                                    {
                                        column: "setting_idx",             // join 조건시 user.idx
                                        value: this.setting.idx,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                        logical: "AND",         // AND,OR,AND NOT
                                        operator: "=",          // = ,!= >= <=, LIKE,
                                        encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                                    },

                                    {
                                        column: "primary",             // join 조건시 user.idx
                                        value: this.primary,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                        logical: "AND",         // AND,OR,AND NOT
                                        operator: ">",          // = ,!= >= <=, LIKE,
                                        encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                                    },
                                ],
                            },
                        ],
                    });
                },
                goList() {
                    return this.$jd.lib.normalizeUrl("",{
                        mode : "list",
                        setting_idx : this.setting.primary,
                        component : this.component,
                    })
                },
                goInput() {
                    return this.$jd.lib.normalizeUrl("",{
                        mode : "input",
                        setting_idx : this.setting.primary,
                        primary : this.primary,
                        component : this.component,
                    })
                },
                getUrl(item) {
                    return this.$jd.lib.normalizeUrl("",{
                        mode : "view",
                        setting_idx : this.setting.primary,
                        primary : item.primary,
                        component : this.component,
                    })
                },
            },
            updated() {

            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    /* 제목 강조 */
    .container h3 {
        font-weight: 600;
    }

    /* 본문 가독성 */
    .container p {
        line-height: 1.7;
        font-size: 1rem;
    }

    /* 첨부파일 hover 효과 */
    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .btn-like {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border: 1px solid #dc3545;
        border-radius: 20px;
        background-color: #fff;
        color: #dc3545;
        font-size: 0.9rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }



    /* active 상태 */
    .btn-like.active {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }
</style>