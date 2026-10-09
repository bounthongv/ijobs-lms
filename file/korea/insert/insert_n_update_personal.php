<?php
session_start();
require '../../../connect.php';
require_once __DIR__ . '/interview_status.php';

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
$stmtExist = $conn->prepare("SELECT id, sts_save FROM interview_personal WHERE data_id = :cid LIMIT 1");
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
// ຊຸດຂໍ້ມູນ 1: ອັບເດດ candidate_korea (ສະເພາະຄໍລໍາທີ່ມີຢູ່)
// ===================================================
$dataCandidate = [
    "fname"        => getPost("fname"),
    "lname"        => getPost("lname"),
    "fname_eng"    => getPost("fname_eng"),
    "lname_eng"    => getPost("lname_eng"),
    "pro_id"       => getPost("pro_id"),
    "dis_id"       => getPost("dis_id"),
    "vill_id"      => getPost("vill_id"),
    "pro_id_b"     => getPost("pro_id_b"),
    "dis_id_b"     => getPost("dis_id_b"),
    "vill_id_b"    => getPost("vill_id_b"),
    "dob"          => getPost("dob"),
    "gender"       => getPost("gender"),
    "status"       => getPost("status"),
    "phone1"       => getPost("phone1"),
    "father"       => getPost("father"),
    "mother"       => getPost("mother"),
    "id_no"        => getPost("id_no"),
    "passport"     => getPost("passport"),
    "issue_date"   => getPost("pass_issue_date"),
    "exp_date"     => getPost("pass_exp_date"),
];

// ===================================================
// ຊຸດຂໍ້ມູນ 2: interview_personal (ທັງໝົດ)
// ===================================================
$dataPersonal = [
    "data_id" => $cid,
    "cif"     => $cif,

    "fname"     => getPost("fname"),
    "lname"     => getPost("lname"),
    "fname_eng" => getPost("fname_eng"),
    "lname_eng" => getPost("lname_eng"),

    "pro_id"  => getPost("pro_id"),
    "dis_id"  => getPost("dis_id"),
    "vill_id" => getPost("vill_id"),

    "dob"             => getPost("dob"),
    "gender"          => getPost("gender"),
    "status"          => getPost("status"),
    "phone1"          => getPost("phone1"),
    "spouse_name"     => getPost("spouse_name"),
    "has_children"    => getPost("has_children"),
    "children_male"   => getPost("children_male"),
    "children_female" => getPost("children_female"),

    "pro_id_b"  => getPost("pro_id_b"),
    "dis_id_b"  => getPost("dis_id_b"),
    "vill_id_b" => getPost("vill_id_b"),

    "father" => getPost("father"),
    "mother" => getPost("mother"),

    "id_no"         => getPost("id_no"),
    "id_issue_date" => getPost("id_issue_date"),
    "id_exp_date"   => getPost("id_exp_date"),
    "id_issue_by"   => getPost("id_issue_by"),

    "passport"        => getPost("passport"),
    "pass_issue_date" => getPost("pass_issue_date"),
    "pass_exp_date"   => getPost("pass_exp_date"),
    "pass_issue_by"   => getPost("pass_issue_by"),

    "emg1_name"       => getPost("emg1_name"),
    "emg1_relation"   => getPost("emg1_relation"),
    "emg1_phone"      => getPost("emg1_phone"),
    "emg1_id_no"      => getPost("emg1_id_no"),
    "emg1_issue_date" => getPost("emg1_issue_date"),
    "emg1_exp_date"   => getPost("emg1_exp_date"),
    "emg1_issue_by"   => getPost("emg1_issue_by"),
    "emg1_pro_id"     => getPost("emg1_pro_id"),
    "emg1_dis_id"     => getPost("emg1_dis_id"),
    "emg1_vill_id"    => getPost("emg1_vill_id"),

    "emg2_name"       => getPost("emg2_name"),
    "emg2_relation"   => getPost("emg2_relation"),
    "emg2_phone"      => getPost("emg2_phone"),
    "emg2_id_no"      => getPost("emg2_id_no"),
    "emg2_issue_date" => getPost("emg2_issue_date"),
    "emg2_exp_date"   => getPost("emg2_exp_date"),
    "emg2_issue_by"   => getPost("emg2_issue_by"),
    "emg2_pro_id"     => getPost("emg2_pro_id"),
    "emg2_dis_id"     => getPost("emg2_dis_id"),
    "emg2_vill_id"    => getPost("emg2_vill_id"),

    "project_code" => getPost("project_code"),
    "set_no"       => getPost("set_no"),
    "labor_type"   => getPost("labor_type"),

    "sts_save"    => $sts_save,
    "verify_by"   => $verify_by,
    "verify_date" => $verify_date,
];

try {

    // ===================================================
    // 1. ອັບເດດ candidate_korea (ຂໍ້ມູນພື້ນຖານ)
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
    // 2. ບັນທຶກ interview_personal (ມີແລ້ວ = update, ບໍ່ມີ = insert)
    // ===================================================
    if ($existRow) {

        $setClauseP = "";
        foreach ($dataPersonal as $key => $value) {
            $setClauseP .= "$key = :$key, ";
        }
        $setClauseP = rtrim($setClauseP, ", ");

        $sqlP = "UPDATE interview_personal SET $setClauseP WHERE data_id = :data_id";
        $stmtP = $conn->prepare($sqlP);
        foreach ($dataPersonal as $key => $value) {
            $stmtP->bindValue(":" . $key, $value);
        }
        $stmtP->bindValue(":data_id", $cid);
        $stmtP->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ແກ້ໄຂຂໍ້ມູນສຳເລັດ";

    } else {

        $columnsP      = implode(", ", array_keys($dataPersonal));
        $placeholdersP = ":" . implode(", :", array_keys($dataPersonal));
        $sqlP = "INSERT INTO interview_personal ($columnsP) VALUES ($placeholdersP)";

        $stmtP = $conn->prepare($sqlP);
        foreach ($dataPersonal as $key => $value) {
            $stmtP->bindValue(":" . $key, $value);
        }
        $stmtP->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ບັນທຶກຂໍ້ມູນສຳເລັດ";
    }

    // ===================================================
    // 3. ປັບລະຫັດ CIF ໃຫ້ຕົງກັນກັບຕາຕະລາງອື່ນຂອງ cid ດຽວກັນ
    // ===================================================
    $syncTables = ['interview_physical', 'data_entry_korea'];
    foreach ($syncTables as $tbl) {
        $chk = $conn->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :t");
        $chk->execute([':t' => $tbl]);
        if ($chk->fetchColumn() > 0) {
            $up = $conn->prepare("UPDATE `$tbl` SET cif = :cif WHERE data_id = :cid");
            $up->execute([':cif' => $cif, ':cid' => $cid]);
        }
    }

    // ປັບສະຖານະການສຳພາດ (ຢືນຢັນຄົບ 7 ຟອມ = Finished)
    refreshInterviewStatus($conn, $cid);

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
