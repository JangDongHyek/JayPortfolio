<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">상품 등록</h6>
            </div>
            <div class="card-body">
                <!-- 카테고리 -->
                <div class="form-row">
                    <!-- 1차 카테고리 -->
                    <div class="form-group col-md-4">
                        <label for="productCategory1">1차 카테고리</label>
                        <select class="form-control" v-model="row.first_category">
                            <option value="">선택안함</option>
                            <template v-for="item in categories">
                                <option :value="item.idx"  v-if="item.parent_idx == null">{{item.name}}</option>
                            </template>
                        </select>
                    </div>

                    <!-- 2차 카테고리 -->
                    <div class="form-group col-md-4" v-if="row.first_category">
                        <label for="productCategory2">2차 카테고리</label>
                        <select class="form-control" v-model="row.second_category">
                            <option value="">선택안함</option>
                            <template v-for="item in categories">
                                <option :value="item.idx"  v-if="item.parent_idx == row.first_category">{{item.name}}</option>
                            </template>
                        </select>
                    </div>

                    <!-- 3차 카테고리 -->
                    <div class="form-group col-md-4" v-if="row.second_category">
                        <label for="productCategory3">3차 카테고리</label>
                        <select class="form-control" v-model="row.third_category">
                            <option value="">선택안함</option>
                            <template v-for="item in categories">
                                <option :value="item.idx"  v-if="item.parent_idx == row.second_category">{{item.name}}</option>
                            </template>
                        </select>
                    </div>
                </div>
                <!-- 상품명 -->
                <div class="form-group">
                    <label for="productName">상품명</label>
                    <input type="text" class="form-control" id="productName" placeholder="상품명을 입력하세요" v-model="row.name">
                </div>

                <!-- 가격 -->
                <div class="form-group">
                    <label for="productPrice">가격</label>
                    <input type="text" class="form-control" id="productPrice" placeholder="가격을 입력하세요" @input="$jd.vue.inputNumber" v-model="row.price" v-price>
                </div>

                <!-- 이미지 업로드 -->
                <div class="form-group">
                    <label for="productImage">메인 이미지</label>
                    <input type="file" @change="$jd.vue.changeFile($event,row,'main_image')">

                    <div v-if="row.main_image">
                        <img :src="row.main_image.src" style="width: 100px; height: 100px">
                    </div>

                    <div v-if="!row.main_image && row.$jd_file_main_image?.count > 0">
                        <img :src="$jd.url + row.$jd_file_main_image.data[0].src" style="width: 100px; height: 100px">
                    </div>
                </div>

                <div class="form-group">
                    <label for="productImage">서브 이미지</label>
                    <input type="file" @change="$jd.vue.changeFile($event,row,'images')">

                    <div v-if="row.images.length > 0 || row.$jd_file_images?.count > 0" class="mt-2 d-flex flex-wrap">
                        <template v-for="(item, index) in row.$jd_file_images?.data">
                            <div class="me-2 mb-2 text-center">
                                <img :src="$jd.url + item.src" style="width: 100px; height: 100px; display:block; margin-bottom: 5px;">
                                <button type="button" class="btn btn-sm btn-danger"
                                        @click="$deleteData(item,{message : '삭제시 새로고침되며 입력된 데이터는 삭제됩니다. 삭제하시겠습니까?'})">
                                    삭제
                                </button>
                            </div>
                        </template>

                        <template v-for="(item, index) in row.images">
                            <div class="me-2 mb-2 text-center">
                                <img :src="item.src" style="width: 100px; height: 100px; display:block; margin-bottom: 5px;">
                                <button type="button" class="btn btn-sm btn-danger"
                                        @click="row.images.splice(index, 1)">
                                    삭제
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 설명 -->
                <div class="form-group">
                    <label for="productDescription">상품 설명</label>
                    <external-summernote :row="row" field="content"></external-summernote>
                </div>

                <!-- 등록 버튼 -->
                <button type="button" class="btn btn-primary" @click="postData()">등록하기</button>
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

                    row: {
                        $table : "product",

                        first_category : "",
                        second_category : "",
                        third_category : "",

                        name : "",
                        price : 0,
                        content : "",

                        main_image : null,
                        images : [],
                    },
                    rows : [],

                    categories : [],
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                if(this.primary) {
                    this.row = await this.$getData({
                        table : "product",
                        file_db: true, // 연관된 파일들 불러옴
                        file_keywords : ["main_image","images"], // 해당 키워드별로 따로 가져옴

                        where: [
                            {column: "primary",value: this.primary},
                        ],

                        add_object: [ // 검색되는 모든 데이터에 해당하는 객체를 추가한다 * vue 데이터 선인식때문에
                            {
                                name: "main_image",
                                value: null
                            },
                            {
                                name: "images",
                                value: []
                            },
                        ],
                    });
                }
                //await this.$getsData({table : "",},this.rows);
                await this.getCategory();
                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async postData() {
                    if(!this.row.first_category) {
                        await this.$jd.lib.alert("1차 카테고리는 필수값입니다.");
                        return false;
                    }

                    if(!this.row.name) {
                        await this.$jd.lib.alert("상품명은 필수값입니다.");
                        return false;
                    }

                    if(!this.row.price) {
                        await this.$jd.lib.alert("가격은 최소 1원 이상으로 기입해야합니다.");
                        return false;
                    }

                    if(!this.row.main_image) {
                        await this.$jd.lib.alert("상품 이미지는 필수값입니다.");
                        return false;
                    }

                    await this.$postData(this.row,{
                        href : "/adm?component=adm-product-list"
                    })
                },
                async getCategory() {
                    await this.$getsData({
                        table : "category",
                    },this.categories);
                },
            },
            computed: {

            },
            watch: {
                async "row.first_category"(value, old_value) {
                    if(this.load) {
                        this.row.second_category = '';
                        this.row.third_category = '';
                    }
                },

                async "row.second_category"(value, old_value) {
                    if(this.load) this.row.third_category = '';
                }
            }
        }});
</script>