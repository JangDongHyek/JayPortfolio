<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container my-5">
            <h3 class="mb-4">썸네일 게시판</h3>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                <!-- 게시글 카드 1 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="/JayDream/resource/slide/1/P-68b00931666e413.png" class="card-img-top" alt="썸네일">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="view.html" class="text-decoration-none text-dark">첫 번째 게시글 제목</a>
                            </h5>
                            <p class="card-text text-truncate">
                                게시글 내용 일부 미리보기입니다. 두세 줄 정도 잘려서 보여주면 좋아요.
                            </p>
                        </div>
                        <div class="card-footer small text-muted d-flex justify-content-between">
                            <span>관리자</span>
                            <span>2025-08-31</span>
                        </div>
                    </div>
                </div>

                <!-- 게시글 카드 2 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="https://via.placeholder.com/400x250/ffc107/ffffff" class="card-img-top" alt="썸네일">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="view.html" class="text-decoration-none text-dark">두 번째 게시글 제목</a>
                            </h5>
                            <p class="card-text text-truncate">
                                카드형 게시판은 갤러리나 뉴스 리스트로 활용하기 좋아요.
                            </p>
                        </div>
                        <div class="card-footer small text-muted d-flex justify-content-between">
                            <span>홍길동</span>
                            <span>2025-08-30</span>
                        </div>
                    </div>
                </div>

                <!-- 게시글 카드 3 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="https://via.placeholder.com/400x250/0d6efd/ffffff" class="card-img-top" alt="썸네일">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="view.html" class="text-decoration-none text-dark">세 번째 게시글 제목</a>
                            </h5>
                            <p class="card-text text-truncate">
                                Bootstrap의 card 컴포넌트로 간단하게 만들 수 있습니다.
                            </p>
                        </div>
                        <div class="card-footer small text-muted d-flex justify-content-between">
                            <span>유저1</span>
                            <span>2025-08-29</span>
                        </div>
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
                //this.row = await this.$getData({table : "",});
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