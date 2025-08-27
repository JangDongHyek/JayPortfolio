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

            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sunday</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Lawrence Scott</td>
                        <td>8:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                        <td>8:00 AM</td>
                        <td>--</td>
                        <td>5:00 PM</td>
                        <td>8:00 AM</td>
                    </tr>
                    <tr>
                        <td>Jane Medina</td>
                        <td>--</td>
                        <td>5:00 PM</td>
                        <td>5:00 PM</td>
                        <td>--</td>
                        <td>9:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>Billy Mitchell</td>
                        <td>9:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>2:00 PM</td>
                        <td>8:00 AM</td>
                    </tr>
                    <tr>
                        <td>Beverly Reid</td>
                        <td>--</td>
                        <td>5:00 PM</td>
                        <td>5:00 PM</td>
                        <td>--</td>
                        <td>9:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>Tiffany Wade</td>
                        <td>8:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                        <td>8:00 AM</td>
                        <td>--</td>
                        <td>5:00 PM</td>
                        <td>8:00 AM</td>
                    </tr>
                    <tr>
                        <td>Sean Adams</td>
                        <td>--</td>
                        <td>5:00 PM</td>
                        <td>5:00 PM</td>
                        <td>--</td>
                        <td>9:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>Rachel Simpson</td>
                        <td>9:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>2:00 PM</td>
                        <td>8:00 AM</td>
                    </tr>
                    <tr>
                        <td>Mark Salazar</td>
                        <td>8:00 AM</td>
                        <td>--</td>
                        <td>--</td>
                        <td>8:00 AM</td>
                        <td>--</td>
                        <td>5:00 PM</td>
                        <td>8:00 AM</td>
                    </tr>
                    </tbody>
                </table>
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