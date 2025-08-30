<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="login-card">
            <h2>로그인</h2>
            <form action="/user/login.php" method="post">
                <div class="form-group">
                    <label for="user_id">아이디</label>
                    <input type="text" v-model="user_id" @keyup.enter="login">
                </div>
                <div class="form-group">
                    <label for="user_pw">비밀번호</label>
                    <input type="password" v-model="user_pw" @keyup.enter="login">
                </div>
                <button type="button" class="btn" @click="login">로그인</button>
                <button type="button" class="btn" @click="$jd.lib.href('/user/join.php')">회원가입</button>
            </form>
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
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //await this.$getsData({table : "",},this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async login() {
                    if(!this.user_id) {
                        await this.$jd.lib.alert("아이디를 입력해주세요.");
                        return false;
                    }
                    if(!this.user_pw) {
                        await this.$jd.lib.alert("비밀번호를 입력해주세요.");
                        return false;
                    }
                    this.row = await this.$getData({
                        table : "user",

                        where: [
                            {
                                column: "user_id",             // join 조건시 user.idx
                                value: this.user_id,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                            {
                                column: "user_pw",             // join 조건시 user.idx
                                value: this.user_pw,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: true,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],
                    });

                    if(!this.row) {
                        await this.$jd.lib.alert("정보에 해당하는 계정이없습니다.");
                        return false;
                    }else {
                        await this.$jd.lib.ajax("session_set", {
                            user_idx : this.row.idx
                        }, "/JayDream/api.php");

                        this.$jd.lib.href("/")
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
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
    }
    .login-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        width: 360px;
        max-width: 90%;
        padding: 2rem;
        text-align: center;
    }
    .login-card h2 {
        margin-bottom: 1.5rem;
        font-size: 1.75rem;
        color: #333;
    }
    .form-group {
        margin-bottom: 1rem;
        text-align: left;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.3rem;
        font-size: 0.9rem;
        color: #555;
    }
    .form-group input {
        width: 100%;
        padding: 0.7rem 0.75rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }
    .form-group input:focus {
        border-color: #6b73ff;
        outline: none;
    }
    .btn {
        width: 100%;
        padding: 0.75rem;
        border: none;
        border-radius: 4px;
        background-color: #6b73ff;
        color: #fff;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.2s;
    }
    .btn:hover {
        background-color: #595ce6;
    }
    .extra {
        margin-top: 0.75rem;
        font-size: 0.9rem;
        color: #555;
    }
    .extra a {
        color: #6b73ff;
        text-decoration: none;
    }
    .extra a:hover {
        text-decoration: underline;
    }
</style>