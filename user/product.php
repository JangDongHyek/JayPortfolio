<?php
include_once "../head.php";
?>

    <div id="app">
        <user-core component="product-list" primary="<?=$_GET['primary']?>"
        first_category="<?=$_GET['first_category']?>" second_category="<?=$_GET['second_category']?>" third_category="<?=$_GET['third_category']?>"></user-core>
    </div>

<?php
include_once "../footer.php";
$jd->componentLoad("/product");
?>