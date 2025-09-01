<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        월 수입
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{monthData[0].total_sum.format()}}원</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        연간수입
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{yearData[0].total_sum.format()}}원</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        총수입
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{totalPrice().format()}}원</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        오늘 문의
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{rows.length}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->

            <div class="row">
                <!-- Area Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">연간수입 그래프</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                     aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">연간 고객 선호도</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                     aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <template v-for="item,index in categories">
                                    <span class="mr-2">
                                        <i class="fas fa-circle" :style="{ color: backgroundColor[index] }"></i> {{ item.name }}
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
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

                    month_rows : [],
                    year_rows : [],
                    categories : [],
                    orders : [],

                    backgroundColor: [
                        '#4e73df', // 파랑
                        '#1cc88a', // 초록
                        '#36b9cc', // 청록
                        '#f6c23e', // 노랑
                        '#e74a3b', // 빨강
                        '#858796', // 회색
                        '#20c9a6'  // 민트
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9', // 파랑 hover
                        '#17a673', // 초록 hover
                        '#2c9faf', // 청록 hover
                        '#dda20a', // 노랑 hover
                        '#be2617', // 빨강 hover
                        '#6c757d', // 회색 hover
                        '#138f75'  // 민트 hover
                    ]
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //this.row = await this.$getData({table : "",});
                //await this.$getsData({table : "",},this.rows);
                await this.getCategory();
                await this.getMonthPay();
                await this.getYearPay();
                await this.getOrder();
                await this.getBoard();
                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                    this.chart();
                    this.pie();
                });
            },
            updated() {

            },
            methods: {
                async getBoard() {
                    await this.$getsData({
                        table : "board",

                        where: [
                            {
                                column: "setting_idx",             // join 조건시 user.idx
                                value: `3`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },

                            {
                                column: "DATE(insert_date)",             // join 조건시 user.idx
                                value: `CURDATE()`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],
                    },this.rows);
                },
                async getOrder() {
                    await this.$getsData({
                        table : "orders",

                        where: [
                            {
                                column: "tid",             // join 조건시 user.idx
                                value: `null`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "!=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],

                        joins: [
                            {
                                table: "product",
                                base: "product_idx",               // filter 테이블의 연결 key
                                foreign: "idx",            // join 테이블의 연결 key
                                type: "LEFT",             // INNER, LEFT, RIGHT
                                select_column: ["first_category"], // 조회할 컬럼 $table__column 식으로 as되서 들어간다 || "*"
                                as : "", // 값이 있을경우 $as__column 해당방식으로 들어감
                            },
                        ],
                    },this.orders);
                },
                pie() {
                    var ctx = document.getElementById("myPieChart");
                    let label = [];
                    let data = [];
                    for (const category of this.categories) {
                        label.push(category.name)

                        let count = 0;
                        for (const order of this.orders) {
                            if(order.$product__first_category == category.idx) count += order.amount;
                        }

                        data.push(count)
                    }

                    let component = this;
                    var myPieChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: label,
                            datasets: [{
                                data: data,
                                backgroundColor: component.backgroundColor,
                                hoverBackgroundColor: component.hoverBackgroundColor,
                                hoverBorderColor: "rgba(234, 236, 244, 1)",
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                caretPadding: 10,
                            },
                            legend: {
                                display: false
                            },
                            cutoutPercentage: 80,
                        },
                    });
                },
                chart() {
                    var ctx = document.getElementById("myAreaChart");
                    let labels = [];
                    let data = [];
                    for (const row of this.month_rows) {
                        labels.push(row.insert_date.formatDate('yyyy-mm'));
                        data.push(row.total_sum);
                    }
                    var myLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Earnings",
                                lineTension: 0.3,
                                backgroundColor: "rgba(78, 115, 223, 0.05)",
                                borderColor: "rgba(78, 115, 223, 1)",
                                pointRadius: 3,
                                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointBorderColor: "rgba(78, 115, 223, 1)",
                                pointHoverRadius: 3,
                                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                pointHitRadius: 10,
                                pointBorderWidth: 2,
                                data: data,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 25,
                                    bottom: 0
                                }
                            },
                            scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'date'
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        maxTicksLimit: 5,
                                        padding: 10,
                                        // Include a dollar sign in the ticks
                                        callback: function(value, index, values) {
                                            return value.format() + "원";
                                        }
                                    },
                                    gridLines: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    }
                                }],
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                titleMarginBottom: 10,
                                titleFontColor: '#6e707e',
                                titleFontSize: 14,
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                        return datasetLabel + '월 : ' + tooltipItem.yLabel.format() + "원";
                                    }
                                }
                            }
                        }
                    });
                },
                totalPrice() {
                    let price = 0;

                    for (const item of this.year_rows) {
                        price += item.total_sum;
                    }

                    return price;
                },
                async getCategory() {
                    await this.$getsData({
                        table : "category",

                        where: [
                            {column: "parent_idx",value: `null`},
                        ],

                    },this.categories);
                },
                async getYearPay() {
                    await this.$getsData({
                        table : "orders",

                        where: [
                            {
                                column: "tid",             // join 조건시 user.idx
                                value: `null`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "!=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],

                        fields : [
                            "YEAR(insert_date) AS year", // 조회하는 부분에 강제 추가한다 조인을 할경우 테이블 명시 필수
                        ],

                        group_bys: {
                            by: ["DATE_FORMAT(insert_date, '%Y')"], // 그룹화 할 컬럼 * 앞에 테이블명시는 필수
                            selects: [
                                {
                                    type: "SUM", // 집계함수
                                    column: "price", // 집계함수 할 컬럼
                                    as: "total_sum", // 필수값
                                },
                            ]
                        },
                    },this.year_rows);
                },
                async getMonthPay() {
                    await this.$getsData({
                        table : "orders",

                        where: [
                            {
                                column: "tid",             // join 조건시 user.idx
                                value: `null`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: "!=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],

                        fields : [
                            "YEAR(insert_date) AS year", // 조회하는 부분에 강제 추가한다 조인을 할경우 테이블 명시 필수
                            "MONTH(insert_date) AS month"
                        ],

                        group_bys: {
                            by: ["DATE_FORMAT(insert_date, '%Y-%m')"], // 그룹화 할 컬럼 * 앞에 테이블명시는 필수
                            selects: [
                                {
                                    type: "SUM", // 집계함수
                                    column: "price", // 집계함수 할 컬럼
                                    as: "total_sum", // 필수값
                                },
                            ]
                        },
                    },this.month_rows);
                },
            },
            computed: {
                yearData() {
                    let targetYear = Number(new Date().format('yyyy'));

                    return this.year_rows.filter(item =>
                        item.year === targetYear
                    );

                },
                monthData() {
                    let targetYear = Number(new Date().format('yyyy'));
                    let targetMonth = Number(new Date().format('mm')); // "09" → 9

                    return this.month_rows.filter(item =>
                        item.year === targetYear && item.month === targetMonth
                    );

                }
            },
            watch: {

            }
        }});
</script>