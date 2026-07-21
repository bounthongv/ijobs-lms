<?php 
    include_once __DIR__ . '/../../check.php';
    $id = $_POST['id'];
    $sql = $conn->prepare("DELETE FROM district_korea WHERE disk_id = ?");
    if($sql->execute([$id])){
        echo 'success';
    }else{
        echo 'error';
    }

?>