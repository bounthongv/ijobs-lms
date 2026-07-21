<?php 
    session_start();
    require '../../../connect.php';
    
    $user_id = $_SESSION['user_id'] ?? '';
    $sub = $_POST['sub'] ?? '';
    
    // ນຳຂໍ້ມູນຈາກຟອມທີ່ສົ່ງມາ
    $agen_id = $_POST['agen_id'] ?? '';
    $agen_name = $_POST['agen_name'] ?? '';
    $agen_name_eng = $_POST['agen_name_eng'] ?? '';
    $agen_address = $_POST['agen_address'] ?? '';
    $agen_tel = $_POST['agen_tel'] ?? '';
    $agen_email = $_POST['agen_email'] ?? '';
    
    // ຖ້າກໍາລັງ insert ໃໝ່
    if($sub == 'insert'){
        try {
            $insert = $conn->prepare("INSERT INTO agency_korea(agen_id,agen_name, agen_name_eng, agen_address, agen_tel, agen_email) VALUES(?,?,?,?,?,?)");
            
            if($insert->execute([$agen_id,$agen_name, $agen_name_eng, $agen_address, $agen_tel, $agen_email])){
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
            if(empty($agen_id)){
                echo json_encode([
                    'message' => 'agency ID ບໍ່ຖືກຕ້ອງ',
                    'sts' => 'error'
                ]);
                exit;
            }
            
            // ກຳນົດວັນທີອັບເດດ
            $date_updated = date('Y-m-d H:i:s');
            
            $update = $conn->prepare("UPDATE agency_korea SET agen_name=?,agen_name_eng=?, agen_address=?, agen_tel=?, agen_email=? WHERE agen_id=?");
            
            if($update->execute([$agen_name, $agen_name_eng, $agen_address, $agen_tel, $agen_email, $agen_id])){
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