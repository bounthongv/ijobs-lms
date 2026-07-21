<?php 
    include_once __DIR__ . '/../../check.php';
    $id = $_POST['id'];
    $sql = $conn->prepare("DELETE FROM village WHERE vill_id = ?");
    if($sql->execute([$id])){
        echo 'success';
    }else{
        echo 'error';
    }

?>