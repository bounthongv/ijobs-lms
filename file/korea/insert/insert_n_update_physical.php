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
$stmtExist = $conn->prepare("SELECT id, sts_save FROM interview_physical WHERE data_id = :cid LIMIT 1");
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
// ຄຳນວນຄ່າ BMI ຈາກ ນ້ຳໜັກ / ສ່ວນສູງ (ຝັ່ງ Server)
// ===================================================
$weight = getPost("weight");
$height = getPost("height");
$bmi = null;
if (is_numeric($weight) && is_numeric($height) && $height > 0) {
    $bmi = round($weight / (($height / 100) ** 2), 2);
}

// ===================================================
// ຊຸດຂໍ້ມູນ 1: ອັບເດດ candidate_korea (ນ້ຳໜັກ / ສ່ວນສູງ)
// ===================================================
$dataCandidate = [
    "weight" => $weight,
    "height" => $height,
];

// ===================================================
// ຊຸດຂໍ້ມູນ 2: interview_physical (ທັງໝົດ)
// ===================================================
$dataPhysical = [
    "data_id" => $cid,
    "cif"     => $cif,

    "weight" => $weight,
    "height" => $height,
    "bmi"    => $bmi,

    "chronic_disease" => getPost("chronic_disease"),
    "chronic_detail"  => getPost("chronic_detail"),

    "finger_sts"   => getPost("finger_sts"),
    "finger_count" => getPost("finger_count"),
    "hand_remark"  => getPost("hand_remark"),

    "toe_sts"     => getPost("toe_sts"),
    "toe_count"   => getPost("toe_count"),
    "foot_remark" => getPost("foot_remark"),

    "lift_ability" => getPost("lift_ability"),
    "maneuverable" => getPost("maneuverable"),

    "eye_remark" => getPost("eye_remark"),

    "sts_save"    => $sts_save,
    "verify_by"   => $verify_by,
    "verify_date" => $verify_date,
];

// ພາກທົດສອບສາຍຕາ 10 ລາຍການ
$eyeKeys = [
    "eye_red", "eye_yellow", "eye_blue", "eye_orange", "eye_green",
    "eye_dog", "eye_watermelon", "eye_tomato", "eye_cat", "eye_cucumber"
];
foreach ($eyeKeys as $key) {
    $dataPhysical[$key] = getPost($key);
}

try {

    // ===================================================
    // 1. ອັບເດດ candidate_korea (ນ້ຳໜັກ / ສ່ວນສູງ)
    // ===================================================
    $setClauseC = "";
    foreach ($dataCandidate as $key => $value) {
        $setClauseC .= "$key = :$key, ";
    }
    $setClauseC = rtrim($setClauseC, ", ");

    $sqlC = "UPDATE candidate_korea SET $setClauseC WHERE cid = :cid";
    $stmtC = $conn->prepare($sqlC);
    foreach ($dataCandidate as $key => $value) {
        $stmtC->bindValue(":" . $key, $value);
    }
    $stmtC->bindValue(":cid", $cid);
    $stmtC->execute();

    // ===================================================
    // 2. ບັນທຶກ interview_physical (ມີແລ້ວ = update, ບໍ່ມີ = insert)
    // ===================================================
    if ($existRow) {

        $setClauseP = "";
        foreach ($dataPhysical as $key => $value) {
            $setClauseP .= "$key = :$key, ";
        }
        $setClauseP = rtrim($setClauseP, ", ");

        $sqlP = "UPDATE interview_physical SET $setClauseP WHERE data_id = :data_id";
        $stmtP = $conn->prepare($sqlP);
        foreach ($dataPhysical as $key => $value) {
            $stmtP->bindValue(":" . $key, $value);
        }
        $stmtP->bindValue(":data_id", $cid);
        $stmtP->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ແກ້ໄຂຂໍ້ມູນສຳເລັດ";

    } else {

        $columnsP      = implode(", ", array_keys($dataPhysical));
        $placeholdersP = ":" . implode(", :", array_keys($dataPhysical));
        $sqlP = "INSERT INTO interview_physical ($columnsP) VALUES ($placeholdersP)";

        $stmtP = $conn->prepare($sqlP);
        foreach ($dataPhysical as $key => $value) {
            $stmtP->bindValue(":" . $key, $value);
        }
        $stmtP->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ບັນທຶກຂໍ້ມູນສຳເລັດ";
    }

    // ===================================================
    // 3. ປັບລະຫັດ CIF ໃຫ້ຕົງກັນກັບຕາຕະລາງອື່ນຂອງ cid ດຽວກັນ
    // ===================================================
    $syncTables = ['interview_personal', 'data_entry_korea'];
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
