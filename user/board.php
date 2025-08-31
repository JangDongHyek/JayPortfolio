<?php
include_once "../head.php";
?>

    <div id="app">
        <user-core component="board-main" setting_idx="<?=$_GET['setting_idx']?>" primary="<?=$_GET['primary']?>" mode="<?=$_GET['mode']?>"></user-core>
    </div>

<?php
include_once "../footer.php";
$jd->componentLoad("/board");
$jd->componentLoad("/board/skins/basic");
$jd->componentLoad("/board/skins/thumb");
?>