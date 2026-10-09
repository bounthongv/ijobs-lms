<?php 
    session_start();
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['checked']);
    unset($_SESSION['status']);
    session_destroy();
    header("Location:" . project_root_url());
    exit();
?>