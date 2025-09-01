<?php
$componentName = str_replace(".php", "", basename(__FILE__));
?>
<script type="text/x-template" id="<?= $componentName ?>-template">
    <div v-if="load">
        <header class="masthead">
            <!-- Swiper 컨테이너 -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">

                    <template v-for="item in rows">
                        <div class="swiper-slide" :style="{ backgroundImage: `url(${item.$jd_file.data[0].src})`,
                        backgroundSize: 'cover', backgroundPosition: 'center center', backgroundRepeat: 'no-repeat', height: '100vh' }">
                            <div class="container text-center text-white d-flex flex-column justify-content-center h-100">
                                <div class="masthead-subheading">{{item.name}}</div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- 네비게이션 버튼 -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- 페이지네이션 점 -->
                <div class="swiper-pagination"></div>
            </div>
        </header>
    </div>

    <div v-if="!load">
        <div class="loader"></div>
    </div>
</script>

<script>
    JayDream_components.push({
        name: "<?=$componentName?>", object: {
            template: "#<?=$componentName?>-template",
            props: {
                primary: {type: String, default: ""},
            },
            data: function () {
                return {
                    load: false,
                    component_name: "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows: [],
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //this.row = await this.$getData({table : "",});
                await this.$getsData({
                    table: "slide",
                    file_db: true,

                    order_by: [
                        { column: "priority", value: "ASC" },
                    ],
                }, this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                    const swiper = new Swiper(".mySwiper", {
                        loop: true, // 무한 반복
                        autoplay: {
                            delay: 4000, // 4초마다 자동 넘김
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true, // 점 클릭 가능
                        },
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        effect: "fade", // fade, slide, cube, coverflow, flip 등 가능
                        speed: 1000 // 전환 속도 (ms)
                    });
                });
            },
            updated() {

            },
            methods: {},
            computed: {},
            watch: {}
        }
    });
</script>
