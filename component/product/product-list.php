<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load" style="margin-top: 100px;">
        <div class="container-fluid py-4">
            <div class="row">

                <!-- 사이드바 (카테고리) -->
                <aside class="col-12 col-md-3 col-lg-2 mb-4 mb-md-0">
                    <div class="list-group">
                        <a :href="getUrl()"
                           class="list-group-item list-group-item-action category-first"
                           :class="{ active : !first_category }">
                            전체
                        </a>
                        <template v-for="first in categories">
                                <!-- 1차 카테고리 -->
                                <a :href="getUrl(first)" class="list-group-item list-group-item-action category-first" :class="{ active : first_category == first.primary}">
                                    {{first.name}}
                                </a>
                                <template v-for="second in first.$second.data">
                                    <div class="collapse show" id="cat1">
                                        <!-- 2차 -->
                                        <a :href="getUrl(first,second)" class="list-group-item list-group-item-action category-second" :class="{ active : second_category == second.primary}">
                                            {{second.name}}
                                        </a>

                                        <div class="collapse show" id="cat1-1" v-if="second.$third.count > 0">
                                            <template v-for="third in second.$third.data">
                                            <!-- 3차 -->
                                                <a :href="getUrl(first,second,third)" class="list-group-item list-group-item-action category-third"
                                                   :class="{ active : third_category == third.primary}">
                                                    {{third.name}}
                                                </a>
                                            </template>
                                        </div>

                                    </div>
                                </template>

                        </template>
                    </div>


                </aside>

                <!-- 메인 컨텐츠 (상품 리스트) -->
                <main class="col-12 col-md-9 col-lg-10">
                    <div class="row g-4">
                        <template v-for="item in rows">
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 shadow-sm">
                                    <img :src="item.$jd_file_main_image.data[0].src" class="card-img-top" alt="상품">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{item.name}}</h5>
                                        <p class="card-text text-muted mb-2">{{item.price.format()}}원</p>
                                        <a :href="'/user/product_view.php?primary=' + item.primary" class="btn btn-primary mt-auto">보러가기</a>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                </main>

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
                first_category : {type : String, default : ""},
                second_category : {type : String, default : ""},
                third_category : {type : String, default : ""},
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows : [],
                    categories : [],
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //this.row = await this.$getData({table : "",});
                //await this.$getsData({table : "",},this.rows);
                await this.getCategory();
                await this.getProduct();
                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async getProduct() {
                    let filter = {
                        table : "product",
                        file_db : true,
                        file_keywords : ["main_image"], // 해당 키워드별로 따로 가져옴

                        where: [

                        ],
                    }

                    if(this.first_category) {
                        filter.where.push({
                            column: "first_category",             // join 조건시 user.idx
                            value: this.first_category,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                            logical: "AND",         // AND,OR,AND NOT
                            operator: "=",          // = ,!= >= <=, LIKE,
                            encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                        })
                    }
                    if(this.second_category) {
                        filter.where.push({
                            column: "second_category",             // join 조건시 user.idx
                            value: this.second_category,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                            logical: "AND",         // AND,OR,AND NOT
                            operator: "=",          // = ,!= >= <=, LIKE,
                            encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                        })
                    }
                    if(this.third_category) {
                        filter.where.push({
                            column: "third_category",             // join 조건시 user.idx
                            value: this.third_category,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                            logical: "AND",         // AND,OR,AND NOT
                            operator: "=",          // = ,!= >= <=, LIKE,
                            encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                        })
                    }
                    await this.$getsData(filter,this.rows);
                },
                getUrl(first = null,second = null,third = null) {
                    let obj = {};

                    if(first) obj.first_category = first.primary;
                    if(second) obj.second_category = second.primary;
                    if(third) obj.third_category = third.primary;

                    return this.$jd.lib.normalizeUrl("/user/product.php",obj)
                },
                async getCategory() {
                    await this.$getsData({
                        table : "category",

                        where: [
                            {column: "parent_idx",value: `null`},
                        ],

                        add_object: [ // 검색되는 모든 데이터에 해당하는 객체를 추가한다 * vue 데이터 선인식때문에
                            {
                                name: "visible",
                                value: "false"
                            },
                        ],

                        order_by: [
                            { column: "priority", value: "ASC" },
                        ],

                        relations: [// filter 형식으로 똑같이 넣어주면 하위로 들어간다
                            {
                                table: "category",
                                as : "second", // 빈값일시 $table 으로 삽입됌

                                where: [
                                    {column: "parent_idx",value: `$parent.idx`},
                                ],

                                order_by: [
                                    { column: "priority", value: "ASC" },
                                ],

                                add_object: [ // 검색되는 모든 데이터에 해당하는 객체를 추가한다 * vue 데이터 선인식때문에
                                    {
                                        name: "visible",
                                        value: "false"
                                    },
                                ],

                                relations: [// filter 형식으로 똑같이 넣어주면 하위로 들어간다
                                    {
                                        table: "category",
                                        as : "third", // 빈값일시 $table 으로 삽입됌

                                        where: [
                                            {column: "parent_idx",value: `$parent.idx`},
                                        ],

                                        order_by: [
                                            { column: "priority", value: "ASC" },
                                        ],
                                    },
                                ],
                            },
                        ],
                    },this.categories);
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    .category-first {
        font-weight: 600;
        background-color: #f8f9fa; /* 연한 회색 */
        border-left: 4px solid #0d6efd; /* 파란색 라인 */
    }

    /* 2차 카테고리 */
    .category-second {
        font-weight: 500;
        background-color: #fff;
        padding-left: 2rem !important; /* 들여쓰기 */
        border-left: 4px solid #adb5bd; /* 연한 회색 라인 */
    }

    /* 3차 카테고리 */
    .category-third {
        font-size: 0.9rem;
        color: #555;
        padding-left: 3rem !important;
    }
</style>