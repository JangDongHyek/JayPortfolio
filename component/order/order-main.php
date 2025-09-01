<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load" style="margin-top: 100px;">
        <div class="container py-5">

            <h2 class="mb-4">주문 / 결제</h2>

            <div class="row">
                <!-- 주문 상품 정보 -->
                <div class="col-lg-8">
                    <!-- 주문 상품 -->
                    <div class="card mb-4">
                        <div class="card-header fw-bold">주문 상품</div>
                        <div class="card-body">
                            <!-- 상품 예시 -->
                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                <img :src="row.$product.data[0].$jd_file_main_image.data[0].src" class="img-thumbnail me-3" style="width:80px; height:80px; object-fit:cover;">
                                <div class="flex-fill">
                                    <h5 class="mb-1">{{row.$product.data[0].name}}</h5>
                                    <p class="mb-0 text-muted">수량: {{row.amount}}개</p>
                                </div>
                                <div class="text-end fw-bold">
                                    {{(row.$product.data[0].price).format()}} 원
                                </div>
                            </div>
                            <div class="text-end fs-5 fw-bold">
                                총 결제 금액: <span class="text-primary">{{(row.$product.data[0].price * row.amount).format()}} 원</span>
                            </div>
                        </div>
                    </div>

                    <!-- 주문자 정보 -->
                    <div class="card mb-4">
                        <div class="card-header fw-bold">주문자 정보</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">이름</label>
                                <input type="text" class="form-control" placeholder="홍길동" v-model="row.$user.data[0].name" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">연락처</label>
                                <input type="text" class="form-control" placeholder="010-1234-5678" v-model="row.$user.data[0].phone" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">이메일</label>
                                <input type="email" class="form-control" placeholder="user@email.com" v-model="row.$user.data[0].email" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">주소</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="우편번호" v-model="row.$user.data[0].zipcode" readonly>
                                </div>
                                <input type="text" class="form-control mb-2" placeholder="기본 주소" readonly v-model="row.$user.data[0].address1">
                                <input type="text" class="form-control" placeholder="상세 주소" readonly v-model="row.$user.data[0].address2">
                            </div>
                        </div>
                    </div>

                    <!-- 배송지 정보 -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold">배송지 정보</span>
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="sameAsOrderer" v-model="check">
                                <label class="form-check-label" for="sameAsOrderer">
                                    주문자 정보와 동일
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">받는 사람</label>
                                <input type="text" class="form-control" placeholder="홍길동" v-model="order.name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">연락처</label>
                                <input type="text" class="form-control" placeholder="010-0000-0000" v-model="order.phone" v-phone>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">주소</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="우편번호" v-model="order.zipcode"  @click="modal.status = true" readonly>
                                    <button class="btn btn-outline-secondary" type="button" @click="modal.status = true">검색</button>
                                </div>
                                <input type="text" class="form-control mb-2" placeholder="기본 주소"
                                       @click="modal.status = true" readonly v-model="order.address1">
                                <input type="text" class="form-control" placeholder="상세 주소" v-model="order.address2">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 결제 요약 -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header fw-bold">결제 요약</div>
                        <div class="card-body">
                            <p class="d-flex justify-content-between">
                                <span>상품 금액</span>
                                <span>{{(row.$product.data[0].price * row.amount).format()}} 원</span>
                            </p>

                            <hr>
                            <p class="d-flex justify-content-between fs-5 fw-bold">
                                <span>총 결제 금액</span>
                                <span class="text-primary">{{(row.$product.data[0].price * row.amount).format()}} 원</span>
                            </p>
                            <plugin-innopay ref="pluginInnopay" :pay_core="pay_core" @paySuccess="paySuccess" redirect_url="/index.php">
                                <template v-slot:default>
                                    <button class="btn btn-lg btn-primary w-100 mt-3" @click="postPay()">결제하기</button>
                                </template>
                            </plugin-innopay>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <external-bs-modal v-model="modal">
            <template v-slot:header>

            </template>

            <!-- body -->
            <template v-slot:default>
                <external-daum-postcode v-model="order" field1="address1" field2="zipcode"
                                        @close="modal.status = false;"></external-daum-postcode>
            </template>


            <template v-slot:footer>

            </template>
        </external-bs-modal>
    </div>

    <div v-if="!load"><div class="loader"></div></div>
</script>

<script>
    JayDream_components.push({name : "<?=$componentName?>",object : {
            template: "#<?=$componentName?>-template",
            props: {
                primary : {type : String, default : ""},
                user : {type : Object, default : null},
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows : [],

                    order : {
                        $table : "orders",
                        moid : '',
                        temp_idx : this.primary,
                        product_idx : "",
                        user_idx : "",
                        amount : "",

                        name : "",
                        phone : "",
                        zipcode : "",
                        address1 : "",
                        address2 : "",

                        price : "",
                        
                        status : "결제완료",
                    },

                    modal: {
                        id : "", // modal의 id값을 설정합니다 빈값이라면 고유값을 랜덤으로 생성해 지정합니다
                        class_1: "", // modal fade 부분에 클래스를 추가합니다 ex) "one_class two_class"
                        class_2: "", // modal-dialog 부분에 클래스를 추가합니다
                        status: false,
                        primary : "",
                    },

                    check : false,
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                this.row = await this.$getData({
                    table : "order_temp",

                    where: [
                        {column: "primary",value: this.primary},
                    ],

                    relations: [// filter 형식으로 똑같이 넣어주면 하위로 들어간다
                        {
                            table: "product",
                            file_db: true, // 연관된 파일들 불러옴
                            file_keywords : ["main_image"], // 해당 키워드별로 따로 가져옴

                            where: [
                                {column: "idx",value: "$parent.product_idx"},
                            ],
                        },

                        {
                            table: "user",

                            where: [
                                {column: "idx",value: "$parent.user_idx"},
                            ],
                        },
                    ],
                });

                this.order.product_idx = this.row.product_idx;
                this.order.user_idx = this.row.user_idx;
                this.order.amount = this.row.amount;
                this.order.price = this.row.amount * this.row.$product.data[0].price;
                //await this.$getsData({table : "",},this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async postPay() {
                    this.order.moid = this.$jd.lib.generateUniqueId();
                    await this.$postData(this.order,{return : true});

                    this.$refs.pluginInnopay.pay()
                },
                async paySuccess(data) {
                    //data.tid // 거래 결제번호
                    //data.moid // 거래 주문번호

                    let order = await this.$getData({
                        table : "orders",

                        where: [
                            {column: "moid",value: data.moid},
                        ],
                    });

                    order.tid = data.tid;
                    try {
                        await this.$postData(order,{
                            message : "결제가 완료되었습니다.",
                            href : "/user/order_view.php?primary=" + order.primary
                        });
                    } catch (e) {
                        await this.$jd.lib.alert(e.message)
                    }
                },
            },
            computed: {
                pay_core() {
                    return {
                        payMethod: "CARD",
                        goodsName: this.row.$product.data[0].name,
                        amt: this.order.price,
                        buyerName: this.row.$user.data[0].name,
                        buyerTel: this.order.phone.formatOnlyNumber(),
                        buyerEmail: this.row.$user.data[0].email,
                        moid : this.order.moid, // 주문데이터에 해당 값이 있어야 미리 주문을 만듬 * 결제리턴시 새로고침되어 유지되어있는값이 초기화되는형상떄문에
                    }
                }
            },
            watch: {
                check() {
                    if(this.check) {
                        this.order.name = this.row.$user.data[0].name;
                        this.order.phone = this.row.$user.data[0].phone;
                        this.order.zipcode = this.row.$user.data[0].zipcode;
                        this.order.address1 = this.row.$user.data[0].address1;
                        this.order.address2 = this.row.$user.data[0].address2;
                    }else {
                        this.order.name = "";
                        this.order.phone = "";
                        this.order.zipcode = "";
                        this.order.address1 = "";
                        this.order.address2 = "";
                    }
                }
            }
        }});
</script>