<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <external-bs-modal v-model="modelValue" @update:modelValue="value => $emit('update:modelValue', value)">
            <template v-slot:header>

            </template>

            <!-- body -->
            <template v-slot:default>
                <div class="mb-3">
                    <label for="inputName" class="form-label">이름</label>
                    <input type="text" class="form-control" v-model="row.name" placeholder="이름 입력">
                </div>

                <div class="mb-3">
                    <label for="inputName" class="form-label">게시글 갯수</label>
                    <input type="number" class="form-control" v-model="row.page_count" @input="$jd.vue.inputNumber">
                </div>

                <div class="mb-3">
                    <label for="inputName" class="form-label">첨부파일</label>
                    <label class="switch">
                        <input type="checkbox" v-model="row.file_use">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="mb-3">
                    <label for="inputName" class="form-label">에디터</label>
                    <select id="inputSkin" class="form-select" v-model="row.editor">
                        <option value="textarea">textarea</option>
                        <option value="summernote">summernote</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="inputName" class="form-label">스킨</label>
                    <select id="inputSkin" class="form-select" v-model="row.skin">
                        <option value="basic">basic</option>
                        <option value="thumb">thumb</option>
                    </select>
                </div>


            </template>


            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" @click="$postData(row,option)">저장</button>
            </template>
        </external-bs-modal>
    </div>

    <div v-if="!load"><div class="loader"></div></div>
</script>

<script>
    JayDream_components.push({name : "<?=$componentName?>",object : {
            template: "#<?=$componentName?>-template",
            props: {
                modelValue : {type: Object, default: {}},
            },
            data: function () {
                return {
                    load : false,
                    component_name : "<?=$componentName?>",
                    component_idx: "",

                    temp : {
                        $table : "board_setting",

                        name : "",
                        page_count : 10,
                        file_use : false,
                        editor : "textarea",
                        skin : "basic",
                    },
                    row: {},
                    rows : [],
                    option : {
                        required: [ // (추가) 필수값 설정
                            // (String)  row.name 값이 빈값일경우 message가 alert으로 노출됌
                            { name: "name", message: `이름을 입력해주세요` },
                        ],
                    },
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

            },
            computed: {

            },
            watch: {
                async "modelValue.status"(value, old_value) {
                    if (value) {
                        if(this.modelValue.primary) {
                            this.row = await this.$getData({
                                table : "board_setting",
                                file_db: true,

                                where: [
                                    {column: "primary",value: this.modelValue.primary},
                                ],
                            });
                        }else {
                            this.row = this.temp;
                        }
                    }else {
                        // this.modelValue.parent = null;
                    }
                }
            }
        }});
</script>

<style>
    .form-select {
        border-radius: 6px;
        font-size: 14px;
        padding: 10px 14px;
        border: 1px solid #ccc;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-select:focus {
        border-color: #6c7ae0;
        box-shadow: 0 0 0 3px rgba(108, 122, 224, 0.25);
    }
</style>