<?php 
    session_start();
    include(__DIR__ . "/../connect.php");
    if(!isset($_SESSION['checked']) || $_SESSION['checked'] <> 1){
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location:https://job.apis.com.la/");
        exit();
    }
?>