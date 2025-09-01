<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">상품 리스트</h6>
                <a href="/adm?component=adm-product-input" class="btn btn-sm btn-primary">+ 상품 등록</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-light">
                        <tr>
                            <th style="width: 80px;">이미지</th>
                            <th>상품명</th>
                            <th>카테고리</th>
                            <th>가격</th>
                            <th>등록일</th>
                            <th style="width: 150px;">기능</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in rows">
                            <td>
                                <img :src="$jd.url + item.$jd_file_main_image.data[0].src" class="img-thumbnail">
                            </td>
                            <td>{{item.name}}</td>
                            <td>
                                <span v-if="item.$first_category__name">{{item.$first_category__name}}</span>
                                <span v-if="item.$second_category__name">-{{item.$second_category__name}}</span>
                                <span v-if="item.$third_category__name">-{{item.$third_category__name}}</span>
                            </td>
                            <td>{{item.price.format()}}원</td>
                            <td>{{item.insert_date}}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" @click="getUrl(item)">수정</button>
                                <button class="btn btn-sm btn-danger" @click="$deleteData(item)">삭제</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <item-paging :paging="paging" @change="getData()"></item-paging>
        </div>
    </div>

    <div v-if="!load"><div class="loader"></div></div>
</script>

<script>
    JayDream_components.push({name : "<?=$componentName?>",object : {
            template: "#<?=$componentName?>-template",
            props: {
                primary : {type : String, default : ""},
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
                        limit: 10, // 해당 값 수정시 페이지에 노출되는 게시글 갯수가 바뀜
                        count: 0,
                    },
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
                getUrl(item) {
                    this.$jd.lib.href("/adm",{
                        component : "adm-product-input",
                        primary : item.primary
                    })
                },
                async getData() {
                    await this.$getsData({
                        table : "product",
                        paging : this.paging,

                        file_db : true,
                        file_keywords : ["main_image"], // 해당 키워드별로 따로 가져옴

                        joins: [
                            {
                                table: "category",
                                base: "first_category",               // filter 테이블의 연결 key
                                foreign: "idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["name"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                                as : "first_category", // 값이 있을경우 $as__column 해당방식으로 들어감
                            },
                            {
                                table: "category",
                                base: "second_category",               // filter 테이블의 연결 key
                                foreign: "idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["name"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                                as : "second_category", // 값이 있을경우 $as__column 해당방식으로 들어감
                            },
                            {
                                table: "category",
                                base: "third_category",               // filter 테이블의 연결 key
                                foreign: "idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["name"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                                as : "third_category", // 값이 있을경우 $as__column 해당방식으로 들어감
                            },
                        ],
                    },this.rows);
                },
            },
            computed: {

            },
            watch: {

            }
        }});
</script>