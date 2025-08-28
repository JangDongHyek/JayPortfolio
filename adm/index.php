<?php
include_once "./adm_head.php";

$component = $_GET['component'] ??  "adm-main-index";;
?>
<body id="page-top">

    <div id="app">
        <adm-main component="<?=$component?>"></adm-main>
    </div>
</body>


<?php
include_once "./adm_footer.php";
$jd->componentLoad("/adm/category");
$jd->componentLoad("/adm/user");
$jd->componentLoad("/external");
$jd->componentLoad("/adm/slide");
?>