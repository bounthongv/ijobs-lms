<?php 
    session_start();
    require '../../../connect.php';
    
    $user_id = $_SESSION['user_id'] ?? '';
    $sub = $_POST['sub'] ?? '';
    
    // ນຳຂໍ້ມູນຈາກຟອມທີ່ສົ່ງມາ
    $prok_id = $_POST['prok_id'] ?? '';
    $prok_name = $_POST['prok_name'] ?? '';
    $prok_name_lao = $_POST['prok_name_lao'] ?? '';
    
    // ຖ້າກໍາລັງ insert ໃໝ່
    if($sub == 'insert'){
        try {
            $insert = $conn->prepare("INSERT INTO province_korea(prok_id,prok_name, prok_name_lao) VALUES(?,?,?)");
            
            if($insert->execute([$prok_id,$prok_name, $prok_name_lao])){
                echo json_encode([
                    'message' => 'ບັນທືກຂໍ້ມູນສຳເລັດ',
                    'sts' => 'success'
                ]);
            }else{
                echo json_encode([
                    'message' => 'ບັນທືກຂໍ້ມູນບໍ່ສຳເລັດ',
                    'sts' => 'error'
                ]);
            }
        } catch(Exception $e){
            echo json_encode([
                'message' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
                'sts' => 'error'
            ]);
        }
    }else if($sub == 'update'){
        try {
            if(empty($prok_id)){
                echo json_encode([
                    'message' => 'Province ID ບໍ່ຖືກຕ້ອງ',
                    'sts' => 'error'
                ]);
                exit;
            }
            
            // ກຳນົດວັນທີອັບເດດ
            $date_updated = date('Y-m-d H:i:s');
            
            $update = $conn->prepare("UPDATE province_korea SET prok_name=?, prok_name_lao=? WHERE prok_id=?");
            
            if($update->execute([$prok_name, $prok_name_lao,  $prok_id])){
                echo json_encode([
                    'message' => 'ແກ້ໄຂຂໍ້ມູນສຳເລັດ',
                    'sts' => 'success'
                ]);
            }else{
                echo json_encode([
                    'message' => 'ແກ້ໄຂຂໍ້ມູນບໍ່ສຳເລັດ',
                    'sts' => 'error'
                ]);
            }
        } catch(Exception $e){
            echo json_encode([
                'message' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
                'sts' => 'error'
            ]);
        }
    }
?>