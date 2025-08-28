<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">메인 슬라이드 설정</h1>
            </div>

            <div class="mb-3 text-right">
                <button class="btn btn-primary btn-sm" @click="modal.status = true;">+ 추가</button>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>이미지</th>
                        <th>제목</th>
                        <th>기능</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in rows">
                        <td>
                            <template v-if="item.$jd_file.count">
                                <img :src="$jd.url + item.$jd_file.data[0].src" style="width: 100px;height: 100px;">
                            </template>
                        </td>
                        <td>{{item.name}}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" @click="modal.primary = item.idx; modal.status = true;">수정</button>
                            <button class="btn btn-sm btn-danger" @click="$deleteData(item)">삭제</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <adm-slide-input v-model="modal" @update:modelValue="value => $emit('update:modelValue', value)"></adm-slide-input>
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
                await this.$getsData({
                    table : "slide",
                    file_db : true,
                },this.rows);

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
    .table-wrapper {
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        width: 100%;
        margin: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    thead {
        background: #6c7ae0;
        color: #fff;
    }

    thead th {
        padding: 14px 16px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #333;
    }

    tbody tr:hover {
        background: #f5f6fa;
    }
</style>