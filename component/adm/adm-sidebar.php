<?php
$componentName = str_replace(".php", "", basename(__FILE__));
?>
<script type="text/x-template" id="<?= $componentName ?>-template">
    <div v-if="load">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/adm">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">{{site.site_name}}</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item" :class="{active : component == 'adm-main-index'}">
                <a class="nav-link" href="/adm">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>대시보드</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <template v-if="user.level == 0">
                <div class="sidebar-heading">
                    최고관리자 메뉴
                </div>

                <li class="nav-item" :class="{active : component == 'adm-main-site'}">
                    <a class="nav-link" href="/adm?component=adm-main-site">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>사이트 설정</span>
                    </a>
                </li>

                <li class="nav-item" :class="{active : component == 'adm-board-list'}">
                    <a class="nav-link" href="/adm?component=adm-board-list">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>게시판설정</span>
                    </a>
                </li>

                <li class="nav-item" :class="{active : component == 'adm-category-main'}">
                    <a class="nav-link" href="/adm?component=adm-category-main">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>카테고리</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

            </template>



            <!-- Heading -->
            <div class="sidebar-heading">
                관리자 메뉴
            </div>

            <li class="nav-item" :class="{active : component == 'adm-slide'}">
                <a class="nav-link" :class="{collapsed : !show2}" data-toggle="collapse" data-target="#collapsePages" @click="show2 = !show2"
                   aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>메인설정</span>
                </a>
                <div id="collapsePages" class="collapse" :class="{show : show2}">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" :class="{active : component == 'adm-slide'}" href="/adm?component=adm-slide">슬라이드</a>
                        <a class="collapse-item" :class="{active : component == 'adm-history'}" href="/adm?component=adm-history">연혁관리</a>
                        <a class="collapse-item" href="login.html">상품</a>
                    </div>
                </div>
            </li>

            <li class="nav-item" :class="{active : component == 'adm-user-main'}">
                <a class="nav-link" href="/adm?component=adm-user-main">
                    <i class="fas fa-user-tie"></i>
                    <span>유저</span>
                </a>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item" :class="{active : component == 'board-main'}">
                <a class="nav-link" :class="{collapsed : !show1}" data-toggle="collapse" data-target="#collapsePages" @click="show1 = !show1"
                   aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>게시판</span>
                </a>
                <div id="collapsePages" class="collapse" :class="{show : show1}">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <template v-for="item in settings">
                            <a class="collapse-item" :href="getBoardUrl(item)" :class="{active : item.primary == setting_idx}">{{item.name}}</a>
                        </template>
                    </div>
                </div>
            </li>

            <li class="nav-item" :class="{active : (component == 'adm-product-list' || component == 'adm-product-input')}">
                <a class="nav-link" href="/adm?component=adm-product-list">
                    <i class="fab fa-product-hunt"></i>
                    <span>상품</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-list"></i>
                    <span>주문목록</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">



        </ul>
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
                component: {type: String, default: ""},
                setting_idx: {type: String, default: ""},
                user: {type: Object, default: {}},
                site: {type: Object, default: {}},
            },
            data: function () {
                return {
                    load: false,
                    component_name: "<?=$componentName?>",
                    component_idx: "",

                    row: {},
                    rows: [],
                    settings : [],

                    show1 : false,
                    show2 : false,
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //this.row = await this.$getData({table : "",});
                //await this.$getsData({table : "",},this.rows);
                await this.getBoardSettings();
                if(this.component == 'adm-slide') this.show2 = true;
                if(this.component == 'adm-history') this.show2 = true;
                if(this.component == 'board-main') this.show1 = true;

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                getBoardUrl(item) {
                    return this.$jd.lib.normalizeUrl("/adm",{
                        component : "board-main",
                        mode : "list",
                        setting_idx : item.primary
                    })
                },
                async getBoardSettings() {
                    await this.$getsData({table : "board_setting",},this.settings);
                },
            },
            computed: {},
            watch: {}
        }
    });
</script>