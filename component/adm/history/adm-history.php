<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">연혁 설정</h1>
                <p class="mt-2 text-muted small">
                    드래그로 순서를 변경할수있습니다.
                </p>
            </div>

            <div class="mb-3 text-right">
                <button class="btn btn-primary btn-sm" @click="modal.status = true;">+ 추가</button>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>이미지</th>
                        <th>년월</th>
                        <th>제목</th>
                        <th>내용</th>
                        <th>기능</th>
                    </tr>
                    </thead>
                    <draggable v-model="rows" item-key="primary" @end="(e) => onDragEnd(e,rows)" tag="tbody">
                        <template #item="{ element : item, index }">
                            <tr>
                                <td>
                                    <template v-if="item.$jd_file.count">
                                        <img :src="$jd.url + item.$jd_file.data[0].src" style="width: 100px;height: 100px;">
                                    </template>
                                </td>
                                <td>{{item.year}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.content}}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" @click="modal.primary = item.idx; modal.status = true;">수정</button>
                                    <button class="btn btn-sm btn-danger" @click="$deleteData(item)">삭제</button>
                                </td>
                            </tr>
                        </template>
                    </draggable>
                </table>
            </div>
        </div>

        <adm-history-input v-model="modal" @update:modelValue="value => $emit('update:modelValue', value)"></adm-history-input>
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
                    table : "company_history",
                    file_db : true,

                    order_by: [
                        { column: "priority", value: "ASC" },
                    ],
                },this.rows);

                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async onDragEnd(evt,array) {
                    if (evt.oldIndex === evt.newIndex) return;

                    const movedItem = array[evt.newIndex];

                    const start = Math.min(evt.oldIndex, evt.newIndex);
                    const end = Math.max(evt.oldIndex, evt.newIndex);

                    // console.log(`이동된 범위: ${start} ~ ${end}`);

                    for (let i = start; i <= end; i++) {
                        const item = array[i];
                        item.priority = i;

                        this.$postData(item,{return : true});
                        // console.log(`수정 대상 idx ${i}:`, item);
                    }

                    // console.log('드래그 완료:', evt.oldIndex, '→', evt.newIndex);
                },
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