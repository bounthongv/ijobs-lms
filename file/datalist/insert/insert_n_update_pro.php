<?php 
    require '../../../connect.php';
    $pro_id = $_POST['pro_id'];
    $pro_name_lao = $_POST['pro_name_lao'];
    $pro_name = $_POST['pro_name'];

    $sub = $_POST['sub'];
    
    if($sub == 'insert'){
        $stmt = $conn->prepare("INSERT INTO province (pro_id, pro_name_lao, pro_name) 
            VALUES (:pro_id, :pro_name_lao, :pro_name)");

        // 4. ผูกค่าข้อมูลและสั่ง Execute
        $stmt->execute([
            ':pro_id' => $pro_id,
            ':pro_name_lao' => $pro_name_lao,
            ':pro_name' => $pro_name
        ]);
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'message' => 'ບັນທືກສຳເລັດ',
                'sts' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'ບັນທືກຜິດຜາດ',
                'sts' => 'error'
            ]);
        }
    }else if($sub == 'update'){
        $sql = "UPDATE province 
                SET pro_name_lao = :pro_name_lao, 
                    pro_name = :pro_name 
                WHERE pro_id = :pro_id"; 
        
        $stmt = $conn->prepare($sql);

        // 4. ผูกค่าและสั่ง Execute
        $stmt->execute([
            ':pro_name_lao'    => $pro_name_lao,
            ':pro_name'    => $pro_name,
            ':pro_id'       => $pro_id
        ]);
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'message' => 'ແກ້ໄຂສຳເລັດ',
                'sts' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'ບັນທືກຜິດຜາດ',
                'sts' => 'error'
            ]);
        }
    }
?>