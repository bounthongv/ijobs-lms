<?php 
    require '../../../connect.php';

    $dis_id = trim($_POST['dis_id'] ?? '');
    $old_dis_id = trim($_POST['old_dis_id'] ?? $dis_id);
    $pro_id = trim($_POST['pro_id'] ?? '');
    $dis_name_lao = trim($_POST['dis_name_lao'] ?? '');
    $dis_name = trim($_POST['dis_name'] ?? '');
    $sub = $_POST['sub'] ?? '';

    if ($dis_id === '' || $dis_name_lao === '') {
        echo json_encode([
            'message' => 'ກະລຸນາປ້ອນລະຫັດເມືອງ ແລະ ຊື່ເມືອງ',
            'sts' => 'error'
        ]);
        exit;
    }

    try {
        if ($sub === 'insert') {
            $check = $conn->prepare("SELECT COUNT(*) FROM district WHERE dis_id = ?");
            $check->execute([$dis_id]);

            if ((int) $check->fetchColumn() > 0) {
                echo json_encode([
                    'message' => 'ລະຫັດເມືອງນີ້ມີແລ້ວ',
                    'sts' => 'error'
                ]);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO district (dis_id, pro_id, dis_name_lao, dis_name) VALUES (?, ?, ?, ?)");
            $stmt->execute([$dis_id, $pro_id, $dis_name_lao, $dis_name]);

            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'message' => 'ບັນທຶກຂໍ້ມູນເມືອງສຳເລັດ',
                    'sts' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'ບັນທຶກຜິດຜາດ',
                    'sts' => 'error'
                ]);
            }
        } elseif ($sub === 'update') {
            $check = $conn->prepare("SELECT COUNT(*) FROM district WHERE dis_id = ? AND dis_id <> ?");
            $check->execute([$dis_id, $old_dis_id]);

            if ((int) $check->fetchColumn() > 0) {
                echo json_encode([
                    'message' => 'ລະຫັດເມືອງນີ້ມີແລ້ວ',
                    'sts' => 'error'
                ]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE district SET dis_id = ?, pro_id = ?, dis_name_lao = ?, dis_name = ? WHERE dis_id = ?");
            $stmt->execute([$dis_id, $pro_id, $dis_name_lao, $dis_name, $old_dis_id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'message' => 'ແກ້ໄຂຂໍ້ມູນເມືອງສຳເລັດ',
                    'sts' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'ບັນທຶກຜິດຜາດ',
                    'sts' => 'error'
                ]);
            }
        } else {
            echo json_encode([
                'message' => 'ບໍ່ພົບຄຳສັ່ງທີ່ຕ້ອງການ',
                'sts' => 'error'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'message' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            'sts' => 'error'
        ]);
    }
?>