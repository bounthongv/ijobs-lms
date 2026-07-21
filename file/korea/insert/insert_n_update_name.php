<?php 
    require '../../../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 0. รับค่าเงื่อนไขว่าจะ insert หรือ update
    $sub = $_POST['sub'] ?? ''; 

    // 1. ดึงค่าจากฟอร์มมาทำเป็นตัวแปร (ใช้ร่วมกันทั้งสองคำสั่ง)
    $data_id          = $_POST['data_id'] ?? '';
    $data_date_create = $_POST['data_date_create'] ?? date('Y-m-d');
    $data_fname       = $_POST['data_fname'] ?? '';
    $data_lname       = $_POST['data_lname'] ?? '';
    $data_fname_eng   = $_POST['data_fname_eng'] ?? '';
    $data_lname_eng   = $_POST['data_lname_eng'] ?? '';
    $data_dob         = $_POST['data_dob'] ?? null;
    $data_gender      = $_POST['data_gender'] ?? '';
    $data_age         = $_POST['data_age'] ?? '';
    $data_identity    = $_POST['data_identity'] ?? '';
    $data_passport    = $_POST['data_passport'] ?? '';
    $data_pass_date   = $_POST['data_pass_date'] ?? null;
    $data_ex_date     = $_POST['data_ex_date'] ?? null;
    $data_phone       = $_POST['data_phone'] ?? '';
    $data_sts         = $_POST['data_sts'] ?? '';
    
    $pro_id           = $_POST['pro_id'] ?? null;
    $dis_id           = $_POST['dis_id'] ?? null;
    $vill_id          = $_POST['vill_id'] ?? null;
    
    $data_free_visa   = $_POST['data_free_visa'] ?? '';
    $data_code        = $_POST['data_code'] ?? '';
    $data_exten       = $_POST['data_exten'] ?? '';
    $data_come_back   = $_POST['data_come_back'] ?? null;
    
    $data_reques      = $_POST['data_reques'] ?? '';
    $data_address     = $_POST['data_address'] ?? '';
    $data_tel         = $_POST['data_tel'] ?? '';
    $data_emp_id      = $_POST['data_emp_id'] ?? '';
    $data_emp_add     = $_POST['data_emp_add'] ?? '';
    $data_emp_name    = $_POST['data_emp_name'] ?? '';
    $data_type        = $_POST['data_type'] ?? '';
    $data_remark      = $_POST['data_remark'] ?? '';

    // จัดการค่าว่างของวันที่ให้เป็น null
    if(empty($data_come_back)) $data_come_back = null;
    if(empty($data_pass_date)) $data_pass_date = null;
    if(empty($data_ex_date)) $data_ex_date = null;
    if(empty($data_dob)) $data_dob = null;

    // มัดรวมตัวแปรใส่ Array สำหรับสวมเข้า execute() จะได้ไม่ต้องเขียนซ้ำ
    $params = [
        ':data_fname'       => $data_fname,
        ':data_lname'       => $data_lname,
        ':data_fname_eng'   => $data_fname_eng,
        ':data_lname_eng'   => $data_lname_eng,
        ':data_dob'         => $data_dob,
        ':data_gender'      => $data_gender,
        ':data_age'         => $data_age,
        ':data_identity'    => $data_identity,
        ':data_passport'    => $data_passport,
        ':data_pass_date'   => $data_pass_date,
        ':data_ex_date'     => $data_ex_date,
        ':data_phone'       => $data_phone,
        ':data_sts'         => $data_sts,
        ':pro_id'           => $pro_id,
        ':dis_id'           => $dis_id,
        ':vill_id'          => $vill_id,
        ':data_free_visa'   => $data_free_visa,
        ':data_code'        => $data_code,
        ':data_exten'       => $data_exten,
        ':data_come_back'   => $data_come_back,
        ':data_reques'      => $data_reques,
        ':data_address'     => $data_address,
        ':data_tel'         => $data_tel,
        ':data_emp_id'      => $data_emp_id,
        ':data_emp_add'     => $data_emp_add,
        ':data_emp_name'    => $data_emp_name,
        ':data_type'        => $data_type,
        ':data_remark'      => $data_remark
    ];

    try {
        // 2. เช็กเงื่อนไขของตัวแปร $sub
        $msg = '';
        if ($sub === 'insert') {
            
            // เพิ่มตัวแปรสำหรับ INSERT เข้าไปใน Array เพิ่มเติม
            $params[':data_id'] = $data_id;
            $params[':data_date_create'] = $data_date_create;

            $sql = "INSERT INTO data_entry (
                        data_date_create, data_id, data_fname, data_lname, data_fname_eng, data_lname_eng, 
                        data_dob, data_gender, data_age, data_identity, data_passport, data_pass_date, 
                        data_ex_date, data_phone, data_sts, pro_id, dis_id, vill_id, 
                        data_free_visa, data_code, data_exten, data_come_back, 
                        data_reques, data_address, data_tel, data_emp_id, data_emp_add, data_emp_name, data_type, data_remark
                    ) VALUES (
                        :data_date_create, :data_id, :data_fname, :data_lname, :data_fname_eng, :data_lname_eng, 
                        :data_dob, :data_gender, :data_age, :data_identity, :data_passport, :data_pass_date, 
                        :data_ex_date, :data_phone, :data_sts, :pro_id, :dis_id, :vill_id, 
                        :data_free_visa, :data_code, :data_exten, :data_come_back, 
                        :data_reques, :data_address, :data_tel, :data_emp_id, :data_emp_add, :data_emp_name, :data_type, :data_remark
                    )";
            
            $msg = "ບັນທືກສຳເລັດ";

        } elseif ($sub === 'update') {
            
            // เพิ่มเงื่อนไข WHERE สำหรับ UPDATE เข้าไปใน Array เพิ่มเติม
            $params[':data_id'] = $data_id;

            $sql = "UPDATE data_entry SET 
                        data_fname = :data_fname, 
                        data_lname = :data_lname, 
                        data_fname_eng = :data_fname_eng, 
                        data_lname_eng = :data_lname_eng, 
                        data_dob = :data_dob, 
                        data_gender = :data_gender, 
                        data_age = :data_age, 
                        data_identity = :data_identity, 
                        data_passport = :data_passport, 
                        data_pass_date = :data_pass_date, 
                        data_ex_date = :data_ex_date, 
                        data_phone = :data_phone, 
                        data_sts = :data_sts, 
                        pro_id = :pro_id, 
                        dis_id = :dis_id, 
                        vill_id = :vill_id, 
                        data_free_visa = :data_free_visa, 
                        data_code = :data_code, 
                        data_exten = :data_exten, 
                        data_come_back = :data_come_back, 
                        data_reques = :data_reques, 
                        data_address = :data_address, 
                        data_tel = :data_tel, 
                        data_emp_id = :data_emp_id, 
                        data_emp_add = :data_emp_add, 
                        data_emp_name = :data_emp_name, 
                        data_type = :data_type, 
                        data_remark = :data_remark
                    WHERE data_id = :data_id";
            
            $msg = "ແກ້ໄຂຂໍ້ມູນສຳເລັດ";
        }

        // 3. ทำงานคำสั่ง SQL ที่เลือกผ่านตัวแปร $sub
        if (!empty($sql)) {
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute($params); // ส่ง Array ตัวแปรไปทำการบันทึก

            if ($result) {
                echo json_encode([
                    'message' => $msg,
                    'sts' => 'success'
                ]);
            }
        }

    } catch (PDOException $e) {
        echo json_encode([
            'message' => 'ເກີດຂໍ້ຜິດຜາດ'. $e->getMessage(),
            'sts' => 'error'
        ]);
    }
}
?>