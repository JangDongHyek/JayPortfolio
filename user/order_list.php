<?php
include_once "../head.php";
?>

    <div id="app">
        <user-core component="order-list" primary="<?=$_GET['primary']?>"></user-core>
    </div>

<?php
include_once "../footer.php";
$jd->componentLoad("/order");
$jd->componentLoad("/plugin/innopay");
?>