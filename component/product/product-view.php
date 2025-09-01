<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load" style="margin-top: 100px;">
        <div class="container py-5">

            <div class="row">
                <!-- 상품 이미지 영역 -->
                <div class="col-md-6">
                    <div class="border p-3 mb-3">
                        <img :src="main_image.src" alt="상품 이미지" class="img-fluid w-100">
                    </div>

                    <!-- 썸네일 목록 -->
                    <div class="d-flex gap-2 product-thumbs mt-3">
                        <img :src="row.$jd_file_main_image.data[0].src" class="img-thumbnail" @mouseover="main_image = row.$jd_file_main_image.data[0]">
                        <template v-for="item in row.$jd_file_images.data">
                            <img :src="item.src" class="img-thumbnail" @mouseover="main_image = item">
                        </template>
                    </div>
                </div>

                <!-- 상품 정보 영역 -->
                <div class="col-md-6">
                    <h2 class="mb-3">{{row.name}}</h2>
                    <p class="text-muted">브랜드명 또는 카테고리</p>

                    <h3 class="text-primary mb-3">{{(row.price).format()}}원</h3>


                    <!-- 수량 선택 -->
                    <div class="mb-3 d-flex align-items-center">
                        <label for="quantity" class="form-label me-3 mb-0">수량</label>
                        <input type="text" id="quantity" class="form-control" @input="$jd.vue.inputNumber" style="width:100px;" v-model="order_temp.amount">
                    </div>

                    <div class="mb-3">
                        <label class="form-label me-3 mb-0 fw-bold">총 구매가격</label>
                        <div class="text-danger fs-5">
                            {{ (row.price * order_temp.amount).format() }} 원
                        </div>
                    </div>

                    <!-- 장바구니 & 구매 버튼 -->
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary flex-fill" @click="postData()">바로 구매</button>
                    </div>
                </div>
            </div>

            <!-- 상세 정보 / 리뷰 / Q&A -->
            <div class="mt-5">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button">상세정보</button>
                    </li>
                </ul>

                <div class="tab-content p-4 border border-top-0" id="productTabContent">
                    <!-- 상세정보 -->
                    <div class="tab-pane fade show active" id="detail" v-html="row.content">

                    </div>
                </div>
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
                user : {type : Object, default : null},
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows : [],

                    order_temp : {
                        $table : "order_temp",
                        product_idx : this.primary,
                        user_idx : this.user?.idx,
                        amount : 1,
                    },

                    main_image : null,
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                if(!this.primary) {
                    await this.$jd.lib.alert("잘못된 접근입니다.");
                    window.history.back();
                    return false;
                }
                this.row = await this.$getData({
                    table : "product",
                    file_db: true, // 연관된 파일들 불러옴
                    file_keywords : ["main_image","images"], // 해당 키워드별로 따로 가져옴

                    where: [
                        {column: "primary",value: this.primary},
                    ],
                });

                this.main_image = this.row.$jd_file_main_image.data[0];
                //await this.$getsData({table : "",},this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper

                });
            },
            updated() {

            },
            methods: {
                async postData() {
                    if(!this.order_temp.user_idx) {
                        await this.$jd.lib.alert("로그인이 필요한 기능입니다.");
                        return false;
                    }
                    if(!this.order_temp.amount) {
                        await this.$jd.lib.alert("최소 주무 수량은 1개입니다.");
                        return false;
                    }

                    let res = await this.$postData(this.order_temp,{return :true});

                    if(res.success) {
                        this.$jd.lib.href("/user/order.php",{primary : res.primary})
                    }
                },
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    .product-thumbs img {
        width: 80px;   /* 원하는 크기 */
        height: 80px;
        object-fit: cover; /* 잘리더라도 정사각형 유지 */
    }
</style>