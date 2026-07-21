<?php
    require '../../../connect.php';

    $vill_id = trim($_POST['vill_id'] ?? '');
    $old_vill_id = trim($_POST['old_vill_id'] ?? $vill_id);
    $pro_id = trim($_POST['pro_id'] ?? '');
    $dis_id = trim($_POST['dis_id'] ?? '');
    $vill_name_lao = trim($_POST['vill_name_lao'] ?? '');
    $vill_name = trim($_POST['vill_name_en'] ?? $_POST['vill_name'] ?? '');
    $sub = trim($_POST['sub'] ?? '');

    if ($vill_id === '' || $vill_name_lao === '') {
        echo json_encode([
            'message' => 'ກະລຸນາປ້ອນລະຫັດບ້ານ ແລະ ຊື່ບ້ານໃຫ້ຄົບ',
            'sts' => 'error'
        ]);
        exit;
    }

    try {
        if ($sub === 'insert') {
            $check = $conn->prepare("SELECT COUNT(*) FROM village WHERE vill_id = ?");
            $check->execute([$vill_id]);

            if ((int) $check->fetchColumn() > 0) {
                echo json_encode([
                    'message' => 'ລະຫັດບ້ານນີ້ມີແລ້ວ',
                    'sts' => 'error'
                ]);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO village (vill_id, pro_id, dis_id, vill_name_lao, vill_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$vill_id, $pro_id, $dis_id, $vill_name_lao, $vill_name]);

            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'message' => 'ບັນທຶກຂໍ້ມູນບ້ານສຳເລັດ',
                    'sts' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'ບັນທຶກຜິດຜາດ',
                    'sts' => 'error'
                ]);
            }
        } elseif ($sub === 'update') {
            $check = $conn->prepare("SELECT COUNT(*) FROM village WHERE vill_id = ? AND vill_id <> ?");
            $check->execute([$vill_id, $old_vill_id]);

            if ((int) $check->fetchColumn() > 0) {
                echo json_encode([
                    'message' => 'ລະຫັດບ້ານນີ້ມີແລ້ວ',
                    'sts' => 'error'
                ]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE village SET vill_id = ?, pro_id = ?, dis_id = ?, vill_name_lao = ?, vill_name = ? WHERE vill_id = ?");
            $stmt->execute([$vill_id, $pro_id, $dis_id, $vill_name_lao, $vill_name, $old_vill_id]);

            if ($stmt->rowCount() > 0 || $old_vill_id === $vill_id) {
                echo json_encode([
                    'message' => 'ແກ້ໄຂຂໍ້ມູນບ້ານສຳເລັດ',
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