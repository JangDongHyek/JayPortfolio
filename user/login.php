<?php
include_once "../head.php";
?>

<div id="app">
    <user-core component="user-login"></user-core>
</div>

<?php
include_once "../footer.php";
$jd->componentLoad("/user");
?>