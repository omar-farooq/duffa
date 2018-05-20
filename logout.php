<?php
include_once "./php/config.php";
$account = new Account();
$account->deleteToken();
session_destroy();
header("Location: ./login");
?>
