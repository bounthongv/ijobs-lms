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

    // ===== รับค่า JSON menu_permissions แล้วแปลงเป็น comma-separated string =====
    // รูปแบบ: {"01":["0101","0102"],"02":["0201"]}
    $menu_permissions_raw = $_POST['menu_permissions'] ?? '{}';
    $menu_permissions = json_decode($menu_permissions_raw, true);

    if (!is_array($menu_permissions)) {
        $menu_permissions = [];
    }

    $menu_id_list = [];
    $item_id_list = [];

    foreach ($menu_permissions as $menu_id => $items) {
        if (!is_array($items) || empty($items)) continue; // ข้ามเมนูที่ไม่มีการเลือกรายการย่อยเลย
        $menu_id_list[] = $menu_id;
        foreach ($items as $item_id) {
            $item_id_list[] = $item_id;
        }
    }

    $menu_id_str = implode(',', $menu_id_list); // เช่น "01,02"
    $item_id_str = implode(',', $item_id_list); // เช่น "0101,0102,0201"

    if($password != $conpass){
        echo json_encode([
            'message' => 'ລະຫັດຜ່ານບໍ່ຕົງກັນ',
            'sts' => 'error'
        ]);
        return;
    }

    try {
        if($sub == 'insert'){
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password, status, menu_id, item_id, create_user) 
                VALUES (:fname, :lname, :username, :email, :password, :status, :menu_id, :item_id, :create_user)");

            $stmt->execute([
                ':fname'    => $fname,
                ':lname'    => $lname,
                ':username' => $username,
                ':email'    => $email,
                ':password' => $password_hash,
                ':status'   => $status,
                ':menu_id'  => $menu_id_str,
                ':item_id'  => $item_id_str,
                ':create_user' => $create_user
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

        } else if($sub == 'update'){
            $user_id = $_POST['user_id'];

            $sql = "UPDATE users 
                    SET fname = :fname, 
                        lname = :lname, 
                        username = :username, 
                        email = :email, 
                        status = :status,
                        menu_id = :menu_id,
                        item_id = :item_id
                    WHERE user_id = :user_id"; 

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':fname'    => $fname,
                ':lname'    => $lname,
                ':username' => $username,
                ':email'    => $email,
                ':status'   => $status,
                ':menu_id'  => $menu_id_str,
                ':item_id'  => $item_id_str,
                ':user_id'  => $user_id
            ]);

            // update ที่ข้อมูลไม่เปลี่ยนแปลงเลย rowCount จะเป็น 0 แต่ไม่ใช่ error
            echo json_encode([
                'message' => 'ແກ້ໄຂສຳເລັດ',
                'sts' => 'success'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'message' => 'ບັນທືກຜິດຜາດ: ' . $e->getMessage(),
            'sts' => 'error'
        ]);
    }
?>