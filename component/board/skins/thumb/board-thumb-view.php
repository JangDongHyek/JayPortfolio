<?php
$componentName = str_replace(".php","",basename(__FILE__));
?>
<script type="text/x-template" id="<?=$componentName?>-template">
    <div v-if="load">
        <div class="container my-5">
            <!-- 제목 -->
            <h3 class="mb-3">첫 번째 게시글 제목</h3>

            <!-- 작성 정보 -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4 text-muted small">
                <div>
                    <span class="me-3">작성자: <strong>관리자</strong></span>
                    <span class="me-3">작성일: 2025-08-31</span>
                    <span>조회: 123</span>
                </div>
            </div>

            <!-- 대표 이미지 -->
            <div class="text-center mb-4">
                <img src="https://via.placeholder.com/800x450" class="img-fluid rounded shadow-sm" alt="대표 이미지">
            </div>

            <!-- 본문 -->
            <div class="mb-5" style="min-height:200px;">
                <p>
                    이곳은 게시판 본문 내용이 들어가는 영역입니다.<br>
                    썸네일 게시판에서는 대표 이미지를 상단에 배치하여 시각적인 강조를 줄 수 있습니다.<br>
                    여러 줄 작성 가능하며 이미지, 리스트 등 다양한 콘텐츠를 넣을 수 있습니다.
                </p>
            </div>

            <!-- 첨부된 파일 -->
            <div class="mb-4">
                <h6 class="mb-2">첨부파일</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        example1.png
                        <a href="#" class="btn btn-sm btn-outline-primary">다운로드</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        문서파일.docx
                        <a href="#" class="btn btn-sm btn-outline-primary">다운로드</a>
                    </li>
                </ul>
            </div>

            <!-- 이전글/다음글 -->
            <div class="border-top pt-3 mb-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted">이전글</span> :
                        <a href="#">게시판 레이아웃은 Bootstrap table 활용</a>
                    </div>
                    <div>
                        <span class="text-muted">다음글</span> :
                        <a href="#">검색 기능은 form 으로 구현</a>
                    </div>
                </div>
            </div>

            <!-- 버튼 영역 -->
            <div class="d-flex justify-content-end">
                <a href="list.html" class="btn btn-secondary me-2">목록</a>
                <a href="edit.html" class="btn btn-primary me-2">수정</a>
                <a href="#" class="btn btn-danger">삭제</a>
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
    /* 제목 강조 */
    .container h3 {
        font-weight: 600;
    }

    /* 본문 */
    .container p {
        line-height: 1.7;
        font-size: 1rem;
    }

    /* 첨부파일 hover 효과 */
    .list-group-item:hover {
        background-color: #f8f9fa;
    }

</style>