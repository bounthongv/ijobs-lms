<?php 
    session_start();
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['checked']);
    unset($_SESSION['status']);
    session_destroy();
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    header("Location:" . $protocol . "://" . $_SERVER['HTTP_HOST'] . "/");
    exit();
?>