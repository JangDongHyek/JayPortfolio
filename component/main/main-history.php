<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <section class="page-section" id="about">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">About</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
                <ul class="timeline">
                    <template v-for="item,index in rows">
                        <li :class="{'timeline-inverted' : index % 2 == 1}">
                            <div class="timeline-image"><img class="rounded-circle img-fluid" :src="$jd.url + item.$jd_file.data[0].src" alt="..." /></div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>{{item.year}}</h4>
                                    <h4 class="subheading">{{item.name}}</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">
                                        {{item.content}}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </template>

                </ul>
            </div>
        </section>
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
                await this.$getsData({
                    table : "company_history",
                    file_db : true,
                    order_by: [
                        { column: "priority", value: "ASC" },
                    ],
                },this.rows);

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


    .timeline > li .timeline-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* 원 안을 꽉 채우되 비율 유지하면서 잘림 */
        border-radius: 50%; /* 원 형태 유지 */
    }
</style>