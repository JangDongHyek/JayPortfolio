<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load" style="margin-top: 100px;">
        <div class="signup-card">
            <h2>회원가입</h2>

            <div class="form-group">
                <label for="user_id">아이디</label>
                <input type="text" v-model="row.user_id">
            </div>
            <div class="form-group">
                <label for="user_pw">비밀번호</label>
                <input type="password" v-model="row.user_pw1">
            </div>
            <div class="form-group">
                <label for="user_pw2">비밀번호 확인</label>
                <input type="password" v-model="row.user_pw2">
            </div>
            <div class="form-group">
                <label for="user_name">이름</label>
                <input type="text" v-model="row.name">
            </div>
            <div class="form-group">
                <label for="user_email">이메일</label>
                <input type="email" v-model="row.email">
            </div>
            <div class="form-group">
                <label for="user_email">연락처</label>
                <input type="text" v-model="row.phone" v-phone>
            </div>

            <div class="form-group">
                <label for="zipcode">우편번호</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" v-model="row.zipcode" placeholder="우편번호" readonly required @click="modal.status = true;">
                    <button type="button" class="btn small" @click="modal.status = true;">우편번호 찾기</button>
                </div>
            </div>

            <div class="form-group">
                <label for="address1">기본주소</label>
                <input type="text" v-model="row.address1" placeholder="기본주소" readonly required @click="modal.status = true;">
            </div>

            <div class="form-group">
                <label for="address2">상세주소</label>
                <input type="text" v-model="row.address2" placeholder="상세주소" required>
            </div>
            <button type="button" class="btn" @click="postUser">회원가입</button>
        </div>

        <external-bs-modal v-model="modal">
            <template v-slot:header>

            </template>

            <!-- body -->
            <template v-slot:default>
                <external-daum-postcode v-model="row" field1="address1" field2="zipcode"
                                        @close="modal.status = false;"></external-daum-postcode>
            </template>


            <template v-slot:footer>

            </template>
        </external-bs-modal>
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

                    row: {
                        user_id : "",
                        user_pw1 : "",
                        user_pw2 : "",
                        name : "",
                        email : "",
                        phone : "",
                        zipcode : "",
                        address1 : "",
                        address2 : "",
                        level : 2,
                    },
                    rows : [],

                    modal: {
                        id : "", // modal의 id값을 설정합니다 빈값이라면 고유값을 랜덤으로 생성해 지정합니다
                        class_1: "", // modal fade 부분에 클래스를 추가합니다 ex) "one_class two_class"
                        class_2: "", // modal-dialog 부분에 클래스를 추가합니다
                        status: false,
                        primary : "",
                    },
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
                async postUser() {
                    if(!this.row.user_id) {
                        await this.$jd.lib.alert("아이디를 입력해주세요.");
                        return false;
                    }
                    if(this.$jd.lib.checkUserId(this.row.user_id)) {
                        await this.$jd.lib.alert("아이디는 영문 + 숫자 조합 4~20자로 해야합니다.");
                        return false;
                    }
                    if(!this.row.user_pw1) {
                        await this.$jd.lib.alert("비밀번호를 입력해주세요.");
                        return false;
                    }
                    if(!this.row.user_pw2) {
                        await this.$jd.lib.alert("비밀번호 확인을 입력해주세요.");
                        return false;
                    }

                    if(this.row.user_pw1 != this.row.user_pw2) {
                        await this.$jd.lib.alert("비밀번호와 비밀번호확인이 다릅니다.");
                        return false;
                    }

                    if(!this.row.name) {
                        await this.$jd.lib.alert("이름을 입력해주세요.");
                        return false;
                    }
                    if(!this.row.email) {
                        await this.$jd.lib.alert("이메일을 입력해주세요.");
                        return false;
                    }
                    if(this.$jd.lib.checkEmail(this.row.email)) {{
                        await this.$jd.lib.alert("정확한 이메일을 입력해주세요.");
                        return false;
                    }}
                    if(!this.row.zipcode) {
                        await this.$jd.lib.alert("주소를 입력해주세요.");
                        return false;
                    }

                    await this.$postData(this.row,{
                        table : "user",
                        hashes: [ // (추가) * 대입방식 row[alias] 값이 암호화되서 row[column]에 대입된다
                            {
                                column: "user_pw",
                                alias: "user_pw1",
                            }
                        ],

                        exists: [ // (추가) 조건에 해당하는 데이터가 있는지 있다면 alert으로 message 노출
                            { // 최상단 filter 방식으로 똑같이 넣어주면된다
                                table: "user",

                                where: [
                                    {column: "user_id",value: this.row.user_id},
                                ],

                                message: "해당 아이디는 이미 존재합니다.",
                            }
                        ],

                        href : "/user/login.php",
                    });
                }
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