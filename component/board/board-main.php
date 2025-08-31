<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <component :is="isComponent" :setting="row" :user="user" :primary="primary"></component>
    </div>

    <div v-if="!load"><div class="loader"></div></div>
</script>

<script>
    JayDream_components.push({name : "<?=$componentName?>",object : {
            template: "#<?=$componentName?>-template",
            props: {
                primary : {type : String, default : ""},
                mode : {type : String, default : ""},
                setting_idx : {type : String, default : ""},
                user : {type : Object, default : null},
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
                this.row = await this.$getData({
                    table : "board_setting",

                    where: [
                        {column: "primary",value: this.setting_idx},
                    ],
                });
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
                isComponent() {
                    return `board-${this.row.skin}-${this.mode}`
                }
            },
            watch: {

            }
        }});
</script>

<style>

</style>