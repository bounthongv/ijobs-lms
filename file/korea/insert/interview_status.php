<?php
// ===================================================
// ຟັງຊັນກາງ: ກວດສອບ ແລະ ປັບສະຖານະການສຳພາດ (7 ຟອມ)
// ===================================================

// ກວດສອບວ່າຢືນຢັນຄົບ 7 ຟອມແລ້ວຫຼືບໍ່
function checkInterviewFinished($conn, $cid) {

    $tables = [
        'interview_personal',
        'interview_physical',
        'interview_experience',
        'interview_attitude',
        'interview_general',
        'interview_collateral',
        'interview_assessment',
    ];

    foreach ($tables as $tbl) {

        // ຖ້າຕາຕະລາງຍັງບໍ່ມີ = ຖືວ່າຍັງບໍ່ຄົບ
        $chk = $conn->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :t");
        $chk->execute([':t' => $tbl]);
        if ($chk->fetchColumn() == 0) {
            return false;
        }

        // ຕ້ອງມີແຖວ ແລະ ສະຖານະ = Verify
        $stmt = $conn->prepare("SELECT sts_save FROM `$tbl` WHERE data_id = :cid LIMIT 1");
        $stmt->execute([':cid' => $cid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || $row['sts_save'] !== 'Verify') {
            return false;
        }
    }

    return true;
}

// ປັບສະຖານະການສຳພາດໃນ candidate_korea ໃຫ້ຕົງກັບຂໍ້ມູນປັດຈຸບັນ
function refreshInterviewStatus($conn, $cid) {

    $sts = checkInterviewFinished($conn, $cid) ? 'Finished' : 'Pending';

    $upd = $conn->prepare("UPDATE candidate_korea SET sts_interview = :sts WHERE cid = :cid");
    $upd->execute([':sts' => $sts, ':cid' => $cid]);

    return $sts;
}
?>
