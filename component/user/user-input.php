<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="signup-card">
            <h2>회원가입</h2>

            <div class="form-group">
                <label for="user_id">아이디</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="user_pw">비밀번호</label>
                <input type="password" id="user_pw" name="user_pw" required>
            </div>
            <div class="form-group">
                <label for="user_pw2">비밀번호 확인</label>
                <input type="password" id="user_pw2" name="user_pw2" required>
            </div>
            <div class="form-group">
                <label for="user_name">이름</label>
                <input type="text" id="user_name" name="user_name" required>
            </div>
            <div class="form-group">
                <label for="user_email">이메일</label>
                <input type="email" id="user_email" name="user_email" required>
            </div>
            <button type="submit" class="btn">회원가입</button>
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
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #eef2f3 0%, #8e9eab 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .signup-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        width: 400px;
        max-width: 90%;
        padding: 2rem;
        text-align: center;
    }
    .signup-card h2 {
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
        border-color: #2a5298;
        outline: none;
    }
    .btn {
        width: 100%;
        padding: 0.75rem;
        border: none;
        border-radius: 4px;
        background-color: #2a5298;
        color: #fff;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.2s;
    }
    .btn:hover {
        background-color: #1e3c72;
    }
    .extra {
        margin-top: 0.75rem;
        font-size: 0.9rem;
        color: #555;
    }
    .extra a {
        color: #2a5298;
        text-decoration: none;
    }
    .extra a:hover {
        text-decoration: underline;
    }
</style>