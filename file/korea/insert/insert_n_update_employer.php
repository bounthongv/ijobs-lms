<?php 
    session_start();
    require '../../../connect.php';
    
    $user_id = $_SESSION['user_id'] ?? '';
    $sub = $_POST['sub'] ?? '';
    
    // ນຳຂໍ້ມູນຈາກຟອມທີ່ສົ່ງມາ
    $emp_id = $_POST['emp_id'] ?? '';
    $bus_type = $_POST['bus_type'] ?? '';
    $emp_name = $_POST['emp_name'] ?? '';
    $emp_name_eng = $_POST['emp_name_eng'] ?? '';
    $emp_com = $_POST['emp_com'] ?? '';
    $location = $_POST['location'] ?? '';
    $emp_phone = $_POST['emp_phone'] ?? '';
    $emp_email = $_POST['emp_email'] ?? '';
    $prok_id = $_POST['prok_id'] ?? '';
    $disk_id = $_POST['disk_id'] ?? '';
    $emp_code = $_POST['emp_code'] ?? '';
    
    $emp_date = date('Y-m-d H:i:s');
    
    // ຖ້າກໍາລັງ insert ໃໝ່
    if($sub == 'insert'){
        try {
            $insert = $conn->prepare("INSERT INTO employer(emp_id,bus_type, emp_name, emp_com, location, emp_phone, emp_email, prok_id, disk_id, emp_code,emp_name_eng) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            
            if($insert->execute([$emp_id,$bus_type, $emp_name, $emp_com, $location, $emp_phone, $emp_email, $prok_id, $disk_id, $emp_code,$emp_name_eng])){
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
            if(empty($emp_id)){
                echo json_encode([
                    'message' => 'Employer ID ບໍ່ຖືກຕ້ອງ',
                    'sts' => 'error'
                ]);
                exit;
            }
            
            // ກຳນົດວັນທີອັບເດດ
            $date_updated = date('Y-m-d H:i:s');
            
            $update = $conn->prepare("UPDATE employer SET bus_type=?, emp_name=?, emp_com=?, location=?, emp_phone=?, emp_email=?, prok_id=?, disk_id=?, emp_code=?,emp_name_eng = ? WHERE emp_id=?");
            
            if($update->execute([$bus_type, $emp_name, $emp_com, $location, $emp_phone, $emp_email, $prok_id, $disk_id, $emp_code,$emp_name_eng, $emp_id])){
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