<?php 
    require '../../../connect.php';
    $id = $_POST['id'];

    // 1. ดึงชื่อไฟล์เดิมจาก database ก่อน
    $sqlSelect = "SELECT profile FROM candidate_korea WHERE id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->execute([$id]);
    $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['profile'])) {
        $filePath = '../uploads/' . $row['profile'];

        // 2. ลบไฟล์จริงถ้ามีอยู่
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // 3. อัปเดต database ให้เป็น NULL
    $sql = "UPDATE candidate_korea SET profile = NULL WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$id])) {
        echo 'success';
    } else {
        echo 'error';
    }
?>