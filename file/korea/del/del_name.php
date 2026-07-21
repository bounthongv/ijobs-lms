<?php 
    include_once __DIR__ . '/../../check.php';
    $data_id = $_POST['data_id'];
    $sql = $conn->prepare("DELETE FROM data_entry WHERE data_id = ?");
    if($sql->execute([$data_id])){
        echo 'success';
    }else{
        echo 'error';
    }

?>