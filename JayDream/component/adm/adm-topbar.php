<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">




            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow" :class="{show : show}">
                    <a class="nav-link dropdown-toggle" id="userDropdown" role="button" @click="show = !show"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{user.name}}</span>
                        <img class="img-profile rounded-circle"
                             src="img/undraw_profile.svg">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" :class="{show : show}"
                         aria-labelledby="userDropdown">
                        <a class="dropdown-item" @click="logout()" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>
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

                    show : false,

                    sessions : {},
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async logout() {
                    if(!await this.$jd.lib.confirm("로그아웃 하시겠습니까?")) return false;

                    await this.$jd.lib.ajax("session_set", {
                        admin_idx: ""
                    }, "/JayDream/api.php");

                    await this.$jd.lib.alert("로그아웃 되었습니다.");
                    this.$jd.lib.href("/adm/adm_login.php")
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>