<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load" style="margin-top: 100px;">
        <div class="container py-5">
            <h2 class="mb-4">주문 상세</h2>

            <!-- 주문 기본 정보 -->
            <div class="card mb-4">
                <div class="card-header fw-bold">주문 기본 정보</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">주문번호</div>
                        <div class="col-md-9">{{row.moid}}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">주문상태</div>
                        <div class="col-md-9">
                            <span>{{row.status}}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">주문일</div>
                        <div class="col-md-9">{{row.insert_date}}</div>
                    </div>
                </div>
            </div>

            <!-- 주문 상품 정보 -->
            <div class="card mb-4">
                <div class="card-header fw-bold">주문 상품</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>상품명</th>
                            <th>수량</th>
                            <th>가격</th>
                            <th>합계</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{row.$product__name}}</td>
                            <td>{{row.amount}}</td>
                            <td>{{row.price.format()}} 원</td>
                            <td>{{row.price.format()}} 원</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-end fw-bold fs-5">
                        총 결제 금액: <span class="text-primary">{{row.price.format()}} 원</span>
                    </div>
                </div>
            </div>

            <!-- 배송지 정보 -->
            <div class="card mb-4">
                <div class="card-header fw-bold">배송지 정보</div>
                <div class="card-body">
                    <p><strong>받는 사람:</strong> {{row.name}}</p>
                    <p><strong>연락처:</strong> {{row.phone}}</p>
                    <p><strong>주소:</strong> ({{row.zipcode}}) {{row.address1}} {{row.address2}}</p>
                </div>
            </div>

            <!-- 카드 결제 정보 -->
            <div class="card mb-4" v-if="row.$jd_plugin_innopay__payMethod == 'CARD'">
                <div class="card-header fw-bold">결제 정보</div>
                <div class="card-body">
                    <p><strong>결제 방법:</strong> 신용카드</p>
                    <p><strong>카드사:</strong> {{row.$jd_plugin_innopay__card.fnName}}</p>
                    <p><strong>승인번호:</strong> {{row.$jd_plugin_innopay__authNum}}</p>
                    <p><strong>할부개월:</strong> {{row.$jd_plugin_innopay__card.cardQuota == '00' ? '일시불' : row.$jd_plugin_innopay__card.cardQuota +'개월'}}</p>
                    <p><strong>결제일시:</strong> {{row.$jd_plugin_innopay__approvedAt}}</p>
                    <p>
                        <strong>영수증:</strong>
                        <a :href="row.$jd_plugin_innopay__receiptUrl" target="_blank" class="text-decoration-underline text-primary ms-2">
                            클릭하여 확인 <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </p>
                </div>
            </div>

            <!-- 하단 버튼 -->
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-secondary">목록으로</button>
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
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows : [],
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                this.row = await this.$getData({
                    table : "orders",

                    where: [
                        {column: "primary",value: this.primary},
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
                            select_column: "*", // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                            as : "", // 값이 있을경우 $as__column 해당방식으로 들어감
                        },

                        {
                            table: "jd_plugin_innopay",
                            base: "tid",               // filter 테이블의 연결 key
                            foreign: "tid",            // join 테이블의 연결 key
                            type: "LEFT",             // INNER, LEFT, RIGHT
                            select_column: "*", // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                            as : "", // 값이 있을경우 $as__column 해당방식으로 들어감
                        },
                    ],
                });
                //await this.$getsData({table : "",},this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {

            },
            computed: {

            },
            watch: {

            }
        }});
</script>