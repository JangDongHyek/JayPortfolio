<?php
include_once "./adm_head.php";

$component = $_GET['component'] ??  "adm-main-index";;
?>
<body id="page-top">

    <div id="app">
        <adm-main component="<?=$component?>" setting_idx="<?=$_GET['setting_idx']?>" primary="<?=$_GET['primary']?>" mode="<?=$_GET['mode']?>"></adm-main>
    </div>
</body>


<?php
include_once "./adm_footer.php";
$jd->componentLoad("/adm/category");
$jd->componentLoad("/adm/user");
$jd->componentLoad("/adm/history");
$jd->componentLoad("/adm/product");
$jd->componentLoad("/external");
$jd->componentLoad("/adm/slide");
$jd->componentLoad("/adm/board");
$jd->componentLoad("/board");
$jd->componentLoad("/item");
$jd->componentLoad("/board/skins/basic");
$jd->componentLoad("/board/skins/thumb");
?>