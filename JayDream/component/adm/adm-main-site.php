<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">사이트 설정</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">사이트 설정</h6>
                </div>
                <div class="card-body">
                    <form id="siteSettingsForm" autocomplete="off">

                        <!-- 사이트 이름 -->
                        <div class="form-group">
                            <label for="siteName" class="text-primary font-weight-bold">사이트 이름</label>
                            <input type="text" class="form-control" v-model="row.site_name" placeholder="예) JayDream">
                        </div>

                        <!-- 2칸짜리: 관리자 ID / 관리자 PW -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="adminId" class="text-primary font-weight-bold">관리자 ID</label>
                                <input type="text" class="form-control" value="admin" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="adminPw" class="text-primary font-weight-bold">관리자 PW</label>
                                <input type="text" class="form-control" placeholder="비밀번호 입력" v-model="user_pw">
                            </div>
                        </div>

                        <!-- 허용 IP (버튼 클릭하면 아래에 추가되는 구조) -->
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="mb-0 text-primary font-weight-bold" for="ipInput_0">adm 접근 허용 IP</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-role="add-ip" @click="row.allow_ip.push('')">
                                    + IP 추가
                                </button>
                            </div>

                            <!-- 리스트 컨테이너 -->
                            <div class="ip-list" data-role="ip-list">
                                <template v-if="row.allow_ip.length > 0">
                                    <template v-for="item,index in row.allow_ip">
                                        <div class="ip-row form-row align-items-center" data-role="ip-row">
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="예) 111.111.111.111" v-model="row.allow_ip[index]">
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-role="remove-ip" @click="row.allow_ip.splice(index,1)">삭제</button>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                                <template v-if="row.allow_ip.length == 0">
                                    <span>전체허용</span>
                                </template>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-block" @click="postData()">저장하기</button>
                    </form>
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

                    user_pw : "",
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                this.row = await this.$getData({
                    table : "site",

                    where: [
                        {column: "primary",value: `1`},
                    ],
                });
                //await this.$getsData({table : "",},this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async postData() {
                    if(!this.row.site_name) {
                        await this.$jd.lib.alert("사이트 이름을 입력해주세요.");
                        return false;
                    }

                    if(this.user_pw) {
                        await this.$postData({
                            $table : "user",
                            primary : 2,
                            user_pw_re : this.user_pw
                        },{
                            hashes: [ // (추가) * 대입방식 row[alias] 값이 암호화되서 row[column]에 대입된다
                                {
                                    column: "user_pw",
                                    alias: "user_pw_re",
                                }
                            ],
                            return : true
                        })
                    }


                    await this.$postData(this.row)
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    /* SB Admin 2와 톤 맞추는 약간의 커스텀 */
    #siteSettingsForm label {
        font-weight: 600;
    }

    #siteSettingsForm .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* 허용 IP 리스트 스타일 */
    .ip-list .ip-row + .ip-row {
        margin-top: .5rem;
    }

    .ip-list .ip-row .btn {
        min-width: 56px;
    }

    /* 모바일에서 IP 입력/버튼 간격 */
    @media (max-width: 575.98px) {
        .ip-list .ip-row .col-auto {
            margin-top: .5rem;
        }
    }

    /* primary 버튼 톤 */
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
</style>