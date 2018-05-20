<?php
$banner_text = 'Archive';
$banner_image = './layout/random.jpg';
include "./layout/layout-top2.php";
echo "Archive: <br><br>";

$schedule = new Schedule();
$schedule->archive();

include "./layout/layout-bottom.php";
?>
