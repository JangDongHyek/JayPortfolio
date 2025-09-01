<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <inc-nav :user="user"></inc-nav>

        <component :is="component" :user="user" :setting_idx="setting_idx" :mode="mode" :primary="primary"
        :first_category="first_category" :second_category="second_category" :third_category="third_category"
        ></component>

        <inc-footer></inc-footer>
    </div>

    <div v-if="!load"><div class="loader"></div></div>
</script>

<script>
    JayDream_components.push({name : "<?=$componentName?>",object : {
            template: "#<?=$componentName?>-template",
            props: {
                primary : {type : String, default : ""},
                component : {type : String, default : ""},
                setting_idx : {type : String, default : ""},
                mode : {type : String, default : ""},
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

                    user : null,
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                this.sessions = (await this.$jd.lib.ajax("session_get", {
                    user_idx: "",
                }, "/JayDream/api.php")).sessions;

                if(this.sessions.user_idx) {
                    this.user = await this.$getData({
                        table : "user",

                        where: [
                            {column: "idx",value: this.sessions.user_idx},
                        ],
                    });
                }


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