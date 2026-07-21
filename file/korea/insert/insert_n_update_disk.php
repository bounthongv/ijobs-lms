<?php 
    session_start();
    require '../../../connect.php';
    
    $user_id = $_SESSION['user_id'] ?? '';
    $sub = $_POST['sub'] ?? '';
    
    // ນຳຂໍ້ມູນຈາກຟອມທີ່ສົ່ງມາ
    $disk_id = $_POST['disk_id'] ?? '';
    $prok_id = $_POST['prok_id'] ?? '';
    $agen_name = $_POST['agen_name'] ?? '';
    $disk_name = $_POST['disk_name'] ?? '';
    $disk_name_lao = $_POST['disk_name_lao'] ?? '';
    $disk_code = $_POST['disk_code'] ?? '';
    $disk_location = $_POST['disk_location'] ?? '';
    $disk_phone = $_POST['disk_phone'] ?? '';
    
    // ຖ້າກໍາລັງ insert ໃໝ່
    if($sub == 'insert'){
        try {
            $insert = $conn->prepare("INSERT INTO district_korea(disk_id,prok_id, disk_name, disk_name_lao, disk_code, disk_location, disk_phone,agen_name) VALUES(?,?,?,?,?,?,?,?)");
            
            if($insert->execute([$disk_id,$prok_id, $disk_name, $disk_name_lao, $disk_code, $disk_location, $disk_phone,$agen_name])){
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
            if(empty($disk_id)){
                echo json_encode([
                    'message' => 'district_korea ID ບໍ່ຖືກຕ້ອງ',
                    'sts' => 'error'
                ]);
                exit;
            }
            
            // ກຳນົດວັນທີອັບເດດ
            $date_updated = date('Y-m-d H:i:s');
            
            $update = $conn->prepare("UPDATE district_korea SET prok_id=?, disk_name=?, disk_name_lao=?, disk_code=?, disk_location=?, disk_phone=?,agen_name = ? WHERE disk_id=?");
            
            if($update->execute([$prok_id, $disk_name, $disk_name_lao, $disk_code, $disk_location, $disk_phone,$agen_name, $disk_id])){
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