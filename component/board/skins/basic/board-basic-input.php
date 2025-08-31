<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container my-5">
            <h3 class="mb-4">게시글 작성</h3>

            <form>
                <!-- 제목 -->
                <div class="mb-3">
                    <label for="title" class="form-label">제목</label>
                    <input type="text" class="form-control" v-model="row.name" placeholder="제목을 입력하세요">
                </div>

                <!-- 작성자 -->
                <div class="mb-3">
                    <label for="writer" class="form-label">작성자</label>
                    <input type="text" class="form-control" v-model="user.name" readonly>
                </div>

                <!-- 본문 -->
                <div class="mb-3">
                    <label for="content" class="form-label">내용</label>

                    <template v-if="setting.editor == 'textarea'">
                        <textarea class="form-control" id="content" rows="8" placeholder="내용을 입력하세요"></textarea>
                    </template>

                    <template v-if="setting.editor == 'summernote'">
                        <external-summernote :row="row" field="content"></external-summernote>
                    </template>

                </div>

                <!-- 파일 첨부 -->
                <div class="mb-4" v-if="setting.file_use">
                    <label for="file" class="form-label">파일 첨부</label>
                    <input class="form-control" type="file" id="file" @change="$jd.vue.changeFile($event,row,'upfiles')">
                </div>

                <div class="mb-4" v-if="row.upfiles?.length > 0 || row.$jd_file.count > 0">
                    <h6 class="mb-2">첨부된 파일</h6>
                    <ul class="list-group">
                        <template v-if="row.$jd_file?.count > 0" v-for="item,index in row.$jd_file.data">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{item.name}}
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="$deleteData(item)">삭제</button>
                            </li>
                        </template>

                        <template v-for="item,index in row.upfiles">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{item.name}}
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="row.upfiles.splice(index,1)">삭제</button>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- 버튼 영역 -->
                <div class="d-flex justify-content-end">
                    <a href="list.html" class="btn btn-secondary me-2">취소</a>
                    <button type="button" class="btn btn-primary" @click="postData()">등록</button>
                </div>
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
                setting : {type : Object, default : null},
                user : {type : Object, default : null},
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    row: {
                        $table : "board",
                        setting_idx : this.setting.primary,
                        user_idx : this.user.primary,
                        name : "",
                        content : "",
                        upfiles : [],
                    },
                    rows : [],

                    option : {
                        required: [ // (추가) 필수값 설정
                            // (String)  row.name 값이 빈값일경우 message가 alert으로 노출됌
                            { name: "name", message: `제목을 입력해주세요.` },
                        ],
                    }
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                if(this.primary) {
                    this.row = await this.$getData({
                        table : "board",
                        file_db : true,

                        where: [
                            {column: "primary",value: this.primary},
                        ],

                        add_object: [ // 검색되는 모든 데이터에 해당하는 객체를 추가한다 * vue 데이터 선인식때문에
                            {
                                name: "upfiles",
                                value: "[]"
                            },
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
                async postData() {
                    let options = {
                        required: [ // (추가) 필수값 설정
                            // (String)  row.name 값이 빈값일경우 message가 alert으로 노출됌
                            { name: "name", message: `제목을 입력해주세요.` },
                        ],

                        return : true,
                    }
                    let res = await this.$postData(this.row,options);

                    await this.$jd.lib.alert("완료 되었습니다.");

                    this.$jd.lib.href("/user/board.php",{
                        mode : "view",
                        setting_idx : this.setting.primary,
                        primary : res.primary,
                    })
                },
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    /* 제목 강조 */
    .container h3 {
        font-weight: 600;
    }

    /* textarea 가독성 */
    #content {
        line-height: 1.6;
    }
</style>