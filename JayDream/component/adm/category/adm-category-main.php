<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">카테고리 설정</h1>
            </div>

            <div class="mb-3 text-right">
                <button class="btn btn-primary btn-sm" @click="modal.status = true;">+ 추가</button>
            </div>

            <div class="table">
                <div class="header">
                    <div class="cell">카테고리명</div>
                    <div class="cell">기능</div>
                </div>

                <!-- 최상위 카테고리 -->
                <draggable v-model="rows" item-key="primary" @end="(e) => onDragEnd(e,rows)" tag="ul">
                    <template #item="{ element : first }">
                        <div class="row level-0" >
                            <div class="cell" @click="first.visible = !first.visible">
                                <template v-if="first.$second.count > 0">
                                    <span class="toggle" v-if="first.visible">▼</span>
                                    <span class="toggle" v-if="!first.visible">▶</span>
                                </template>
                                {{first.name}}

                                <span v-if="first.$second.count">({{first.$second.count}})</span>
                            </div>

                            <div class="cell">
                                <button class="btn btn-sm btn-primary" @click="modal.parent = first; modal.status = true;">+ 추가</button>
                                <button class="btn btn-sm btn-warning" @click="modal.primary = first.idx; modal.status = true;">수정</button>
                                <button class="btn btn-sm btn-danger" @click="$deleteData(first)">삭제</button>
                            </div>

                            <!-- 세컨드 -->
                            <draggable v-model="first.$second.data" item-key="primary" @end="(e2) => onDragEnd(e2,first.$second.data)" tag="ul">
                                <template #item="{ element : second }">
                                    <div class="children" :class="{on : first.visible}">
                                        <div class="row level-1">
                                            <div class="cell" @click="second.visible = !second.visible">
                                                <template v-if="second.$third.count > 0">
                                                    <span class="toggle" v-if="second.visible">▼</span>
                                                    <span class="toggle" v-if="!second.visible">▶</span>
                                                </template>
                                                {{second.name}}
                                                <span v-if="second.$third.count">({{second.$third.count}})</span>
                                            </div>
                                            <div class="cell">
                                                <button class="btn btn-sm btn-primary" @click="modal.parent = second; modal.status = true;">+ 추가</button>
                                                <button class="btn btn-sm btn-warning" @click="modal.primary = second.idx; modal.status = true;">수정</button>
                                                <button class="btn btn-sm btn-danger" @click="$deleteData(second)">삭제</button>
                                            </div>

                                            <!-- 써드 -->
                                            <draggable v-model="second.$third.data" item-key="primary" @end="(e2) => onDragEnd(e2,second.$third.data)" tag="ul">
                                                <template #item="{ element : third }">
                                                    <div class="children" :class="{on : second.visible}">
                                                        <div class="row level-2">
                                                            <div class="cell">{{third.name}}</div>
                                                            <div class="cell">
                                                                <button class="btn btn-sm btn-warning" @click="modal.primary = third.idx; modal.status = true;">수정</button>
                                                                <button class="btn btn-sm btn-danger" @click="$deleteData(third)">삭제</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </draggable>

                                        </div>
                                    </div>
                                </template>
                            </draggable>

                        </div>
                    </template>
                </draggable>

            </div>
        </div>

        <!-- 모달을 따로 컴포넌트로 뺄때 거기에 추가 @update:modelValue="value => $emit('update:modelValue', value)" -->
        <adm-category-input v-model="modal" @update:modelValue="value => $emit('update:modelValue', value)"></adm-category-input>
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
                        parent : null,
                    },
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //this.row = await this.$getData({table : "",});
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
                async onDragEnd(evt,array) {
                    console.log(array);
                    if (evt.oldIndex === evt.newIndex) return;

                    const movedItem = array[evt.newIndex];

                    const start = Math.min(evt.oldIndex, evt.newIndex);
                    const end = Math.max(evt.oldIndex, evt.newIndex);

                    for (let i = start; i <= end; i++) {
                        const item = array[i];
                        item.priority = i;

                        this.$postData(item,{return : true});
                    }

                },
                async getCategory() {
                    await this.$getsData({
                        table : "category",

                        where: [
                            {column: "parent_idx",value: `null`},
                        ],

                        add_object: [ // 검색되는 모든 데이터에 해당하는 객체를 추가한다 * vue 데이터 선인식때문에
                            {
                                name: "visible",
                                value: "false"
                            },
                        ],

                        order_by: [
                            { column: "priority", value: "ASC" },
                        ],

                        relations: [// filter 형식으로 똑같이 넣어주면 하위로 들어간다
                            {
                                table: "category",
                                as : "second", // 빈값일시 $table 으로 삽입됌

                                where: [
                                    {column: "parent_idx",value: `$parent.idx`},
                                ],

                                order_by: [
                                    { column: "priority", value: "ASC" },
                                ],

                                add_object: [ // 검색되는 모든 데이터에 해당하는 객체를 추가한다 * vue 데이터 선인식때문에
                                    {
                                        name: "visible",
                                        value: "false"
                                    },
                                ],

                                relations: [// filter 형식으로 똑같이 넣어주면 하위로 들어간다
                                    {
                                        table: "category",
                                        as : "third", // 빈값일시 $table 으로 삽입됌

                                        where: [
                                            {column: "parent_idx",value: `$parent.idx`},
                                        ],

                                        order_by: [
                                            { column: "priority", value: "ASC" },
                                        ],
                                    },
                                ],
                            },
                        ],
                    },this.rows);
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    .table {
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin: auto;
    }

    /* 헤더 */
    .header {
        display: flex;
        background: #6c7ae0;
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    .header .cell {
        flex: 1;
        padding: 14px 16px;
    }

    /* 행 */
    .row {
        display: flex;
        border-bottom: 1px solid #eee;
    }

    .row > .cell {
        flex: 1;
        padding: 14px 16px;
        font-size: 14px;
        color: #333;
    }

    .row:hover {
        background: #f5f6fa;
        cursor: pointer;
    }

    /* 들여쓰기 (level 기반) */
    .row.level-0 .cell:first-child { padding-left: 16px; font-weight: bold; }
    .row.level-1 .cell:first-child { padding-left: 40px; }
    .row.level-2 .cell:first-child { padding-left: 64px; }
    .row.level-3 .cell:first-child { padding-left: 88px; }

    /* 자식 컨테이너 */
    .children {
        display: none;   /* 기본은 숨김 */
        width: 100%;     /* 부모 row와 폭 맞추기 */
        flex-direction: column;
    }

    .children.on {
        display: block;  /* .on 붙으면 표시 */
    }

    /* 토글 아이콘 */
    .toggle {
        color: #6c7ae0;
        font-weight: bold;
        margin-right: 6px;
        user-select: none;
    }
</style>