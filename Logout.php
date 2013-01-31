<?php
session_start();
$_SESSION['userName'] = '';
header("location:index.php");
?>