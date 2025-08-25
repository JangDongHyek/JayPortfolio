<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                        </div>
                                        <form class="user">
                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" v-model="user_id" @keyup.enter="getUser()"
                                                       id="exampleInputEmail" aria-describedby="emailHelp"
                                                       placeholder="Enter ID...">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user" v-model="user_pw" @keyup.enter="getUser()"
                                                       id="exampleInputPassword" placeholder="Password">
                                            </div>

                                            <a class="btn btn-primary btn-user btn-block" @click="getUser()">
                                                Login
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
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

                    user_id : "",
                    user_pw : "",

                    sessions : {}
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                this.sessions = (await this.$jd.lib.ajax("session_get", {
                    admin_idx: "",
                }, "/JayDream/api.php")).sessions;

                if(this.sessions.admin_idx) {
                    await this.$jd.lib.alert("이미 로그인이 되어있습니다.");
                    this.$jd.lib.href("/adm")
                }

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async getUser() {
                    if(!this.user_id) {
                        await this.$jd.lib.alert("아이디를 입력해주세요.")
                        return false;
                    }

                    if(!this.user_pw) {
                        await this.$jd.lib.alert("비밀번호를 입력해주세요.")
                        return false;
                    }
                    
                    let row = await this.$getData({
                        table : "user",

                        where: [
                            {
                                column: "user_id",             // join 조건시 user.idx
                                value: this.user_id,              // LIKE일시 %% 필수 || relations일시  $parent.idx
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },

                            {
                                column: "user_pw",             // join 조건시 user.idx
                                value: this.user_pw,              // LIKE일시 %% 필수 || relations일시  $parent.idx
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],
                    });

                    if(row) {
                        await this.$jd.lib.ajax("session_set", {
                            admin_idx : row.idx
                        }, "/JayDream/api.php");

                        this.$jd.lib.href("/adm")
                    }else {
                        this.$jd.lib.alert("아이디 혹은 비밀번호가 잘못되었습니다.")
                    }
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>