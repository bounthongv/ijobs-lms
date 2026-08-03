<?php 
    require '../../../connect.php';
    $id = $_POST['id'];

    // 1. ดึงชื่อไฟล์เดิมจาก database ก่อน
    $sqlSelect = "SELECT id_profile FROM data_entry_korea WHERE id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->execute([$id]);
    $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['id_profile'])) {
        $filePath = '../uploads/' . $row['id_profile'];

        // 2. ลบไฟล์จริงถ้ามีอยู่
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // 3. อัปเดต database ให้เป็น NULL
    $sql = "UPDATE data_entry_korea SET id_profile = NULL WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$id])) {
        echo 'success';
    } else {
        echo 'error';
    }
?>