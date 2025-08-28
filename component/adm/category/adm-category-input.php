<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <external-bs-modal v-model="modelValue" @update:modelValue="value => $emit('update:modelValue', value)">
            <template v-slot:header>
                <h5 class="modal-title" >카테고리 추가</h5>
            </template>

            <!-- body -->
            <template v-slot:default>
                <div class="mb-3" v-if="modelValue.parent">
                    <label for="inputName" class="form-label">부모 카테고리</label>
                    <p>{{modelValue.parent.name}}</p>
                </div>

                <div class="mb-3">
                    <label for="inputName" class="form-label">이름</label>
                    <input type="text" class="form-control" v-model="row.name" placeholder="이름 입력">
                </div>

                <div class="mb-3">
                    <label class="form-label">대표이미지</label>
                    <!-- 이미지가 이미 있을 때 보여줄 영역 -->
                    <div class="d-flex align-items-center" v-if="row.$jd_file?.data.length > 0">
                        <img :src="$jd.url+row.$jd_file.data[0].src" alt="대표이미지" class="img-thumbnail me-2" style="width:60px; height:60px; object-fit:cover;">
                        <button type="button" class="btn btn-outline-danger btn-sm" @click="deleteImage">삭제</button>
                    </div>

                    <!-- 필요하다면 새로 업로드할 input -->
                    <input type="file" class="form-control mt-2" @change="$jd.vue.changeFile($event,row,'main_image')">
                </div>
            </template>


            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" @click="$postData(row)">저장</button>
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
                        $table : "category",
                        name : "",
                    },
                    row: {},
                    rows : [],
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
                async deleteImage() {
                    await this.$whereDelete({
                        table : "jd_file",

                        where: [
                            {
                                column: "table_name",             // join 조건시 user.idx
                                value: `category`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                            {
                                column: "table_primary",             // join 조건시 user.idx
                                value: this.row.primary,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],
                    },{

                        confirm: { // (추가) 추가전 Confirm 으로 물어보고싶을때
                            message: '이미지가 바로 삭제됩니다. 삭제하시겠습니까?',
                        },
                    });
                }
            },
            computed: {

            },
            watch: {
                async "modelValue.status"(value, old_value) {
                    if (value) {
                        if(this.modelValue.primary) {
                            this.row = await this.$getData({
                                table : "category",
                                file_db: true,

                                where: [
                                    {column: "primary",value: this.modelValue.primary},
                                ],
                            });
                        }else {
                            this.row = this.temp;

                            if(this.modelValue.parent) this.row.parent_idx = this.modelValue.parent.idx
                        }
                    }else {
                        this.modelValue.parent = null;
                    }
                }
            }
        }});
</script>