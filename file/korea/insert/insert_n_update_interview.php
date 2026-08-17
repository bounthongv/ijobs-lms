<?php
require '../../../connect.php';

// ===================================================
// ຟັງຊັນຊ່ວຍດຶງຄ່າຈາກ POST (ຖ້າບໍ່ມີ ໃຫ້ເປັນ null)
// ===================================================
function getPost($key) {
    return isset($_POST[$key]) && $_POST[$key] !== "" ? $_POST[$key] : null;
}

// ===================================================
// ຮັບ cid ຈາກຟອມ (ໃຊ້ເປັນຕົວກຳນົດວ່າຈະ insert ຫຼື update)
// ===================================================
$cid = getPost("cid");

if (!$cid) {
    echo json_encode([
        'message' => 'ບໍ່ພົບ CID, ກະລຸນາລະບຸ CID ກ່ອນບັນທຶກ',
        'sts' => 'error'
    ]);
    exit;
}

// ===================================================
// ດຶງຄ່າຈາກຟອມທັງໝົດ (ໃຊ້ຮ່ວມກັນທັງ insert ແລະ update)
// ===================================================
$data = [
    "cid"                       => $cid,
    "children_male"             => getPost("children_male"),
    "children_female"           => getPost("children_female"),
    "emergency_contact"         => getPost("emergency_contact"),

    "height_weight_remark"      => getPost("height_weight_remark"),
    "chronic_disease"           => getPost("chronic_disease"),
    "chronic_disease_detail"    => getPost("chronic_disease_detail"),
    "smoking"                   => getPost("smoking"),
    "alcohol"                   => getPost("alcohol"),
    "health_general"            => getPost("health_general"),
    "maneuverable"              => getPost("maneuverable"),

    "exp_farming"                => getPost("exp_farming"),
    "exp_farming_duration"       => getPost("exp_farming_duration"),
    "exp_orchard"                => getPost("exp_orchard"),
    "exp_orchard_duration"       => getPost("exp_orchard_duration"),
    "exp_greenhouse"             => getPost("exp_greenhouse"),
    "exp_greenhouse_duration"    => getPost("exp_greenhouse_duration"),
    "exp_machine"                => getPost("exp_machine"),
    "exp_machine_duration"       => getPost("exp_machine_duration"),
    "exp_climate"                => getPost("exp_climate"),
    "exp_climate_duration"       => getPost("exp_climate_duration"),
    "worked_korea_before"        => getPost("worked_korea_before"),
    "worked_korea_before_year"   => getPost("worked_korea_before_year"),

    "korean_skill"        => getPost("korean_skill"),
    "reason_korea"        => getPost("reason_korea"),
    "reason_korea_other"  => getPost("reason_korea_other"),
    "overtime"            => getPost("overtime"),
    "discipline"          => getPost("discipline"),
    "integrity"           => getPost("integrity"),

    "eval_score_num"          => getPost("eval_score_num"),
    "eval_score"              => getPost("eval_score"),
    "interviewer_comments"    => getPost("interviewer_comments"),
    "final_result"             => getPost("final_result"),
    "final_result_condition"   => getPost("final_result_condition"),
];

$sql    = "";
$params = [];
$msg    = "";

// ===================================================
// ກວດສອບ ວ່າ cid ນີ້ ມີຢູ່ໃນ interview_form ແລ້ວບໍ່
// ===================================================
$sqlCheck = "SELECT id FROM interview_form WHERE cid = :cid";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bindParam(":cid", $cid);
$stmtCheck->execute();
$existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

// ===================================================
// 1. ຖ້າບໍ່ມີຂໍ້ມູນເກົ່າ (ບໍ່ພົບ cid) => Insert ຂໍ້ມູນໃໝ່
// ===================================================
if (!$existing) {
    $columns      = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO interview_form ($columns) VALUES ($placeholders)";
    foreach ($data as $key => $value) {
        $params[":" . $key] = $value;
    }
    $msg = "ບັນທຶກຂໍ້ມູນສຳເລັດ";

// ===================================================
// 2. ຖ້າມີຂໍ້ມູນເກົ່າຢູ່ແລ້ວ (ພົບ cid) => Update ຂໍ້ມູນເກົ່າ
// ===================================================
} else {
    $id = $existing['id'];
    $setClause = "";
    foreach ($data as $key => $value) {
        $setClause .= "$key = :$key, ";
        $params[":" . $key] = $value;
    }
    $setClause = rtrim($setClause, ", ");
    $sql = "UPDATE interview_form SET $setClause WHERE id = :id";
    $params[":id"] = $id;
    $msg = "ບັນທຶກຂໍ້ມູນສຳເລັດ";
}

// ===================================================
// 3. ທຳງານຄຳສັ່ງ SQL ທີ່ເລືອກຜ່ານການກວດສອບ cid
// ===================================================
if (!empty($sql)) {

    try {
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute($params);

        if ($result) {
            echo json_encode([
                'message' => $msg,
                'sts' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'ບໍ່ສາມາດບັນທຶກຂໍ້ມູນໄດ້',
                'sts' => 'error'
            ]);
        }

    } catch (PDOException $e) {
        echo json_encode([
            'message' => $e->getMessage(),
            'sts' => 'error'
        ]);
    }

} else {
    echo json_encode([
        'message' => 'ບໍ່ສາມາດປະມວນຜົນຄຳສັ່ງໄດ້',
        'sts' => 'error'
    ]);
}
?>