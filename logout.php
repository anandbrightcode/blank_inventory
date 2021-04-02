<?php
session_start();
$shop="suntech";
session_destroy();
if (isset($_COOKIE['userdata'])) {
    unset($_COOKIE['userdata']);
    setcookie('userdata', null, -1, '/');
} 
header("Location:backup/backup.php?time=logout&shop=$shop");
?>