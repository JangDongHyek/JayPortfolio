<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container py-5">
            <h2 class="mb-4">주문 목록</h2>

            <table class="table order-table align-middle">
                <thead>
                <tr>
                    <th>주문번호</th>
                    <th>주문자ID</th>
                    <th>상품명</th>
                    <th>수량</th>
                    <th>총 금액</th>
                    <th>상태</th>
                    <th>주문일</th>
                    <th>관리</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in rows">
                    <td>{{item.moid}}</td>
                    <td>{{item.$user__user_id}}</td>
                    <td>{{item.$product__name}}</td>
                    <td>{{item.amount}}</td>
                    <td>{{item.price.format()}} 원</td>
                    <td>
                        <select class="form-select form-select-sm" v-model="item.status" @change="$postData(item,{return : true})">
                            <option value="결제완료">결제완료</option>
                            <option value="배송중">배송중</option>
                            <option value="배송완료">배송완료</option>
                            <option value="주문취소">주문취소</option>
                        </select>
                    </td>
                    <td>{{item.insert_date}}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" @click="goView(item)">상세</button>
                    </td>
                </tr>
                </tbody>
            </table>
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
                //await this.$getsData({table : "",},this.rows);
                await this.getData();
                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                goView(item) {
                    this.$jd.lib.href("/adm",{
                        component : "adm-order-view",
                        primary : item.primary
                    })
                },
                async getData() {
                    let filter = {
                        table : "orders",
                        paging : this.paging,

                        where: [
                            {
                                column: "tid",             // join 조건시 user.idx
                                value: `null`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "!=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],

                        joins: [
                            {
                                table: "product",
                                base: "product_idx",               // filter 테이블의 연결 key
                                foreign: "idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["name"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                                as : "", // 값이 있을경우 $as__column 해당방식으로 들어감
                            },

                            {
                                table: "user",
                                base: "user_idx",               // filter 테이블의 연결 key
                                foreign: "idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["user_id"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                                as : "", // 값이 있을경우 $as__column 해당방식으로 들어감
                            },
                        ],
                    }

                    await this.$getsData(filter,this.rows);
                },
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    .order-table thead tr th {
        background: linear-gradient(180deg, #5a6fdc 0%, #3c5ccf 100%) !important;
        color: #fff !important;
        border-color: #3c5ccf !important;
        text-align: left !important;
        vertical-align: middle;
    }

</style>