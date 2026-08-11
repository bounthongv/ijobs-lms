<?php
    require '../../../connect.php';

    $tri_id = trim($_POST['tri_id'] ?? '');
    $tri_name = trim($_POST['tri_name'] ?? '');
    $tri_name_eng = trim($_POST['tri_name_eng'] ?? '');
    $sub = trim($_POST['sub'] ?? '');


    try {
        if ($sub === 'insert') {
            $check = $conn->prepare("SELECT COUNT(*) FROM tribes WHERE tri_id = ?");
            $check->execute([$tri_id]);

            if ((int) $check->fetchColumn() > 0) {
                echo json_encode([
                    'message' => 'ລະຫັດຊົນເຜົ່ານີ້ມີແລ້ວ',
                    'sts' => 'error'
                ]);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO tribes (tri_id, tri_name, tri_name_eng) VALUES (?, ?, ?)");
            $stmt->execute([$tri_id, $tri_name, $tri_name_eng]);

            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'message' => 'ບັນທຶກຂໍ້ມູນຊົນເຜົ່າສຳເລັດ',
                    'sts' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'ບັນທຶກຜິດຜາດ',
                    'sts' => 'error'
                ]);
            }
        } elseif ($sub === 'update') {
            

            $stmt = $conn->prepare("UPDATE tribes SET  tri_name = ?, tri_name_eng = ? WHERE tri_id = ?");
            $stmt->execute([$tri_name, $tri_name_eng, $tri_id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'message' => 'ແກ້ໄຂຂໍ້ມູນຊົນເຜົ່າສຳເລັດ',
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