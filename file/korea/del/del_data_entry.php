<?php 
    include_once __DIR__ . '/../../check.php';
    $id = $_POST['id'];
    $sql = $conn->prepare("DELETE FROM data_entry_korea WHERE id = ?");
    if($sql->execute([$id])){
        echo 'success';
    }else{
        echo 'error';
    }

?>