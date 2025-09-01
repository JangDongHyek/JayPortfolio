<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div id="wrapper">

            <adm-sidebar :component="component" :user="user" :site="site" :setting_idx="setting_idx"></adm-sidebar>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <adm-topbar :user="user"></adm-topbar>

                    <component :is="component" :setting_idx="setting_idx" :mode="mode" :primary="primary" :component="component" :user="user"></component>
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2021</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
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
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    user: {},
                    rows : [],

                    site : {},
                    sessions : {},
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                this.sessions = (await this.$jd.lib.ajax("session_get", {
                    admin_idx: "",
                }, "/JayDream/api.php")).sessions;

                if(!this.sessions.admin_idx) {
                    await this.$jd.lib.alert("로그인이 필요합니다.");
                    this.$jd.lib.href("/adm/adm_login.php")
                    return false;
                }

                await this.getUser();
                this.site = await this.$getData({table : "site",});

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async getUser() {
                    this.user = await this.$getData({
                        table : "user",

                        where: [
                            {column: "idx",value: this.sessions.admin_idx},
                        ],
                    });
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>