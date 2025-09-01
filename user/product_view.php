<?php
include_once "../head.php";
?>

    <div id="app">
        <user-core component="product-view" primary="<?=$_GET['primary']?>"></user-core>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/js-image-zoom/js-image-zoom.min.js"></script>
<?php
include_once "../footer.php";
$jd->componentLoad("/product");
?>