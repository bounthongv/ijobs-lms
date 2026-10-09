<?php
session_start();
require '../../../connect.php';

// ===================================================
// ຟັງຊັນຊ່ວຍດຶງຄ່າຈາກ POST (ຖ້າບໍ່ມີ ຫຼື ຫວ່າງ ໃຫ້ເປັນ null)
// ===================================================
function getPost($key) {
    return isset($_POST[$key]) && $_POST[$key] !== "" ? $_POST[$key] : null;
}

// ===================================================
// ຮັບຄ່າຈາກຟອມ
// ===================================================
$cid    = getPost("cid");
$action = $_POST['action'] ?? 'save';

if (!$cid) {
    echo json_encode([
        'message' => 'ບໍ່ພົບ CID (ຜູ້ສະໝັກ) ທີ່ຈະບັນທຶກ',
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
// ກວດສອບສະຖານະການຢືນຢັນ (ຖ້າ Verify ແລ້ວ ຫ້າມແກ້ໄຂ)
// ===================================================
$stmtExist = $conn->prepare("SELECT id, sts_save FROM interview_general WHERE data_id = :cid LIMIT 1");
$stmtExist->execute([':cid' => $cid]);
$existRow = $stmtExist->fetch(PDO::FETCH_ASSOC);

if ($existRow && $existRow['sts_save'] === 'Verify') {
    echo json_encode([
        'message' => 'ຂໍ້ມູນນີ້ຖືກຢືນຢັນແລ້ວ ບໍ່ສາມາດແກ້ໄຂໄດ້',
        'sts' => 'error'
    ]);
    exit;
}

// ກຳນົດສະຖານະ ແລະ ຂໍ້ມູນຜູ້ຢືນຢັນ
$sts_save    = $action === 'verify' ? 'Verify' : 'Pending';
$verify_by   = $action === 'verify' ? ($_SESSION['username'] ?? '') : null;
$verify_date = $action === 'verify' ? date('Y-m-d H:i:s') : null;

// ===================================================
// ລະຫັດ CIF ສ້າງອັດຕະໂນມັດ (cid-01) ບໍ່ຮັບຄ່າຈາກຟອມ ເພື່ອໃຫ້ທຸກຕາຕະລາງຕົງກັນ
// ===================================================
$cif = $cid . "-01";

// ===================================================
// ຊຸດຂໍ້ມູນ: interview_general
// ===================================================
$dataGeneral = [
    "data_id" => $cid,
    "cif"     => $cif,

    "know_from"        => getPost("know_from"),
    "goal"             => getPost("goal"),
    "has_friend_korea" => getPost("has_friend_korea"),
    "current_job"      => getPost("current_job"),

    "smoking"      => getPost("smoking"),
    "alcohol"      => getPost("alcohol"),
    "drugs"        => getPost("drugs"),
    "criminal"     => getPost("criminal"),
    "abroad"       => getPost("abroad"),
    "abroad_country" => getPost("abroad_country"),
    "gov_officer"  => getPost("gov_officer"),

    "reason_korea" => getPost("reason_korea"),
    "overtime"     => getPost("overtime"),
    "discipline"   => getPost("discipline"),
    "integrity"    => getPost("integrity"),

    "sts_save"    => $sts_save,
    "verify_by"   => $verify_by,
    "verify_date" => $verify_date,
];

try {

    // ===================================================
    // 1. ບັນທຶກ interview_general (ມີແລ້ວ = update, ບໍ່ມີ = insert)
    // ===================================================
    if ($existRow) {

        $setClause = "";
        foreach ($dataGeneral as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");

        $sql = "UPDATE interview_general SET $setClause WHERE data_id = :data_id";
        $stmt = $conn->prepare($sql);
        foreach ($dataGeneral as $key => $value) {
            $stmt->bindValue(":" . $key, $value);
        }
        $stmt->bindValue(":data_id", $cid);
        $stmt->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ແກ້ໄຂຂໍ້ມູນສຳເລັດ";

    } else {

        $columns      = implode(", ", array_keys($dataGeneral));
        $placeholders = ":" . implode(", :", array_keys($dataGeneral));
        $sql = "INSERT INTO interview_general ($columns) VALUES ($placeholders)";

        $stmt = $conn->prepare($sql);
        foreach ($dataGeneral as $key => $value) {
            $stmt->bindValue(":" . $key, $value);
        }
        $stmt->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ບັນທຶກຂໍ້ມູນສຳເລັດ";
    }

    // ===================================================
    // 2. ປັບລະຫັດ CIF ໃຫ້ຕົງກັນກັບຕາຕະລາງອື່ນຂອງ cid ດຽວກັນ
    // ===================================================
    $syncTables = ['interview_personal', 'interview_physical', 'interview_experience', 'interview_attitude', 'data_entry_korea'];
    foreach ($syncTables as $tbl) {
        $chk = $conn->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :t");
        $chk->execute([':t' => $tbl]);
        if ($chk->fetchColumn() > 0) {
            $up = $conn->prepare("UPDATE `$tbl` SET cif = :cif WHERE data_id = :cid");
            $up->execute([':cif' => $cif, ':cid' => $cid]);
        }
    }

    echo json_encode([
        'message' => $msg,
        'sts' => 'success'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'message' => $e->getMessage(),
        'sts' => 'error'
    ]);
}
