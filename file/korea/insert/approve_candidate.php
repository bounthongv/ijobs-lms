<?php
session_start();
require '../../../connect.php';
require_once __DIR__ . '/interview_status.php';

// ===================================================
// ຮັບຄ່າຈາກຟອມ
// ===================================================
$cid = $_POST['cid'] ?? '';

if (!$cid) {
    echo json_encode([
        'message' => 'ບໍ່ພົບ CID (ຜູ້ສະໝັກ) ທີ່ຈະອະນຸມັດ',
        'sts' => 'error'
    ]);
    exit;
}

// ===================================================
// ກວດສອບສິດເຂົ້າໃຊ້ (ຕ້ອງມີ 0107)
// ===================================================
$item_ids = explode(',', $_SESSION['item_id'] ?? '');
if (!in_array('0107', $item_ids)) {
    echo json_encode([
        'message' => 'ທ່ານບໍ່ມີສິດອະນຸມັດ',
        'sts' => 'error'
    ]);
    exit;
}

// ===================================================
// ກວດສອບວ່າ candidate ມີຢູ່ແທ້ບໍ່
// ===================================================
$stmtCand = $conn->prepare("SELECT cid FROM candidate_korea WHERE cid = :cid LIMIT 1");
$stmtCand->execute([':cid' => $cid]);
if (!$stmtCand->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode([
        'message' => 'ບໍ່ພົບຂໍ້ມູນຜູ້ສະໝັກໃນລະບົບ',
        'sts' => 'error'
    ]);
    exit;
}

// ===================================================
// ປັບສະຖານະການສຳພາດກ່ອນ ແລ້ວກວດສອບວ່າຢືນຢັນຄົບ 7 ຟອມແລ້ວຫຼືບໍ່
// ===================================================
$stsInterview = refreshInterviewStatus($conn, $cid);

if ($stsInterview !== 'Finished') {
    echo json_encode([
        'message' => 'ຍັງຢືນຢັນຟອມບໍ່ຄົບ 7 ຟອມ ຈຶ່ງອະນຸມັດບໍ່ໄດ້',
        'sts' => 'error'
    ]);
    exit;
}

try {

    // ອະນຸມັດ
    $upd = $conn->prepare("UPDATE candidate_korea SET sts_data = 'Approve' WHERE cid = :cid");
    $upd->execute([':cid' => $cid]);

    echo json_encode([
        'message' => 'ອະນຸມັດສຳເລັດ',
        'sts' => 'success'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'message' => $e->getMessage(),
        'sts' => 'error'
    ]);
}
?>
