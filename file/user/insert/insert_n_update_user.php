<?php 
    require '../../../connect.php';
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'] ?? '';
    $conpass = $_POST['conpass'] ?? '';
    $status = $_POST['status'];
    $sub = $_POST['sub'];
    $create_user = date('Y-m-d');
    if($password != $conpass){
        echo json_encode([
            'message' => 'ລະຫັດຜ່ານບໍ່ຕົງກັນ',
            'sts' => 'error'
        ]);
        return;
    }
    if($sub == 'insert'){
        $password_hash = password_hash($password,PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password, status,create_user) 
            VALUES (:fname, :lname, :username, :email, :password, :status,:create_user)");

        // 4. ผูกค่าข้อมูลและสั่ง Execute
        $stmt->execute([
            ':fname'    => $fname,
            ':lname'    => $lname,
            ':username' => $username,
            ':email'    => $email,
            ':password' => $password_hash,
            ':status'   => $status,
            ':create_user'   => $create_user
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
        $user_id = $_POST['user_id'];
        $sql = "UPDATE users 
                SET fname = :fname, 
                    lname = :lname, 
                    username = :username, 
                    email = :email, 
                    status = :status 
                WHERE user_id = :user_id"; 
        
        $stmt = $conn->prepare($sql);

        // 4. ผูกค่าและสั่ง Execute
        $stmt->execute([
            ':fname'    => $fname,
            ':lname'    => $lname,
            ':username' => $username,
            ':email'    => $email,
            ':status'   => $status,
            ':user_id'       => $user_id
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