<?php
session_start();
session_unset();
session_destroy();


if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
?>