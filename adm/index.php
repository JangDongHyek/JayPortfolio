<?php
include_once "./adm_head.php";

$url = $_GET['url'] ??  "adm-main-index";;
?>
<body id="page-top">

    <div id="app">
        <adm-main url="<?=$url?>"></adm-main>
    </div>
</body>


<?php
include_once "./adm_footer.php";
?>