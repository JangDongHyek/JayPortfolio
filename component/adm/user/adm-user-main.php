<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">유저</h1>
            </div>

            <div class="table-wrapper">
                <div class="table-search">
                    <select class="search-select" v-model="search_type">
                        <option value="">전체</option>
                        <option value="user_id">아이디</option>
                        <option value="name">이름</option>
                        <option value="email">이메일</option>
                        <option value="address">주소</option>
                    </select>
                    <input type="text" placeholder="검색어를 입력하세요" class="search-input" v-model="search_value" @keyup.enter="getUser()">
                    <button class="search-btn" @click="getUser()">검색</button>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>이름</th>
                        <th>이메일</th>
                        <th>주소</th>
                        <th>기능</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in rows">
                        <td>{{item.user_id}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.zipcode}} {{item.address1}} {{item.address2}}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" @click="login(item)">로그인</button>
                            <button class="btn btn-sm btn-danger" @click="$deleteData(item)">삭제</button>
                        </td>
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

                    search_type : "",
                    search_value : "",
                };
            },
            async created() {
                this.component_idx = this.$jd.lib.generateUniqueId();
            },
            async mounted() {
                //this.row = await this.$getData({table : "",});
                //await this.$getsData({table : "",},this.rows);
                await this.getUser();
                this.load = true;

                this.$nextTick(async () => {
                    // 해당부분에 퍼블리싱 라이브러리,플러그인 선언부분 하시면 됩니다 ex) swiper
                });
            },
            updated() {

            },
            methods: {
                async login(user) {
                    await this.$jd.lib.ajax("session_set", {
                        user_idx : user.idx
                    }, "/JayDream/api.php");

                    window.open(this.$jd.url)
                },
                async getUser() {
                    let filter = {
                        table : "user",

                        where: [
                            {
                                column: "level",             // join 조건시 user.idx
                                value: `2`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "AND",         // AND,OR,AND NOT
                                operator: ">=",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            },
                        ],
                    };

                    if(this.search_value) {
                        filter.blocks = [
                            { // filter 형식으로 넣어주면된다 , 객체 하나당 () 괄호 조건문이 꾸며진다
                                logical: "AND", // 괄호 전 어떤 논리 연사자가 들어갈지
                                where: [

                                ],
                            },
                        ]
                        if(this.search_type == "address" || this.search_type == "") {
                            filter.blocks[0].where.push({
                                column: "zipcode",             // join 조건시 user.idx
                                value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "OR",         // AND,OR,AND NOT
                                operator: "LIKE",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            });
                            filter.blocks[0].where.push({
                                column: "address1",             // join 조건시 user.idx
                                value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "OR",         // AND,OR,AND NOT
                                operator: "LIKE",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            })
                            filter.blocks[0].where.push({
                                column: "address2",             // join 조건시 user.idx
                                value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "OR",         // AND,OR,AND NOT
                                operator: "LIKE",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            })
                        }

                        if(this.search_type == "") {
                            filter.blocks[0].where.push({
                                column: "user_id",             // join 조건시 user.idx
                                value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "OR",         // AND,OR,AND NOT
                                operator: "LIKE",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            });
                            filter.blocks[0].where.push({
                                column: "name",             // join 조건시 user.idx
                                value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "OR",         // AND,OR,AND NOT
                                operator: "LIKE",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            });
                            filter.blocks[0].where.push({
                                column: "email",             // join 조건시 user.idx
                                value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "OR",         // AND,OR,AND NOT
                                operator: "LIKE",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            });
                        }

                        if(this.search_type != "address" && this.search_type != "") {
                            filter.blocks[0].where.push({
                                column: this.search_type,             // join 조건시 user.idx
                                value: `%${this.search_value}%`,              // LIKE일시 %% 필수 || relations일시  $parent.idx , 공백일경우 __null__ , null 값인경우 null
                                logical: "OR",         // AND,OR,AND NOT
                                operator: "LIKE",          // = ,!= >= <=, LIKE,
                                encrypt: false,        // true시 벨류가 암호화된 값으로 들어감
                            });
                        }

                    }
                    console.log(filter)
                    await this.$getsData(filter,this.rows);
                }
            },
            computed: {

            },
            watch: {

            }
        }});
</script>

<style>
    .search-select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        background: #fff;
        cursor: pointer;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-select:focus {
        border-color: #6c7ae0;
        box-shadow: 0 0 0 2px rgba(108, 122, 224, 0.2);
    }
    .table-search {
        display: flex;
        justify-content: flex-start; /* 🔹 왼쪽 정렬 */
        align-items: center;
        padding: 12px 16px;
        background: #f9f9f9;
        border-bottom: 1px solid #eee;
    }

    .search-input {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        margin-right: 8px;
    }

    .search-input:focus {
        border-color: #6c7ae0;
        box-shadow: 0 0 0 2px rgba(108, 122, 224, 0.2);
    }

    .search-btn {
        padding: 8px 14px;
        background: #6c7ae0;
        border: none;
        border-radius: 4px;
        color: #fff;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .search-btn:hover {
        background: #5a67d8;
    }
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