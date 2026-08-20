<?php
require '../../../connect.php';

// ===================================================
// ຟັງຊັນຊ່ວຍດຶງຄ່າຈາກ POST (ຖ້າບໍ່ມີ ໃຫ້ເປັນ null)
// ===================================================
function getPost($key) {
    return isset($_POST[$key]) && $_POST[$key] !== "" ? $_POST[$key] : null;
}

// ===================================================
// ຟັງຊັນຊ່ວຍຕັດເຄື່ອງໝາຍ Comma ອອກ ສຳລັບຄ່າຕົວເລກ
// ===================================================
function clearComma($value) {
    if ($value === null) {
        return null;
    }
    return str_replace(",", "", $value);
}

// ===================================================
// ຟັງຊັນຊ່ວຍອັບໂຫລດໄຟລ໌ (ໃຊ້ໄດ້ທັງ Insert ແລະ Update)
// ===================================================
function uploadFile($fieldName, $oldValue = null) {

    $uploadDir = "../uploads/";
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return $oldValue;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
    $newFileName = $fieldName . "_" . time() . "_" . uniqid() . "." . $ext;
    $targetPath = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {

        if ($oldValue) {
            $oldPath = $uploadDir . basename($oldValue);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        return $newFileName;
    }

    return $oldValue;
}

// ===================================================
// ຮັບຄ່າຈາກຟອມ
// ===================================================
$dataId = getPost("data_id"); // primary key ຂອງ data_entry_korea (ຖ້າມີ)
$cid    = getPost("cid");     // id ຂອງ candidate_korea (ຕ້ອງມີສະເໝີ)
$passport = getPost("passport");

$msg = "";

// ===================================================
// ຕ້ອງມີ cid ສະເໝີ ເພາະ candidate_korea ບໍ່ມີການ insert ໃໝ່
// ===================================================
if (!$cid) {
    echo json_encode([
        'message' => 'ບໍ່ພົບ CID (candidate) ທີ່ຈະອັບເດດ',
        'sts' => 'error'
    ]);
    exit;
}

// ===================================================
// ກວດສອບວ່າ data_entry_korea ຂອງ cid ນີ້ມີແລ້ວບໍ່
// ຖ້າມີ -> update, ຖ້າບໍ່ມີ -> insert
// ===================================================
$sqlExist = "SELECT data_id FROM data_entry_korea WHERE data_id = :cid LIMIT 1";
$stmtExist = $conn->prepare($sqlExist);
$stmtExist->bindParam(":cid", $cid);
$stmtExist->execute();
$existRow = $stmtExist->fetch(PDO::FETCH_ASSOC);

if ($existRow) {
    $sub    = "update";
    $dataId = $existRow['data_id']; // ໃຊ້ data_id ທີ່ຄົ້ນເຈິ ເພື່ອຄວາມແນ່ນອນ
} else {
    $sub = "insert";
}

// ===================================================
// ດຶງຂໍ້ມູນເກົ່າຂອງໄຟລ໌ (ໃຊ້ສະເພາະຕອນ update)
// ===================================================
$oldData = [
    'profile' => null, 'file_form' => null, 'doc_passport' => null,
    'doc_farmer_cert' => null, 'doc_labor_contract' => null,
    'doc_census' => null, 'doc_collateral' => null
];

if ($sub === "update" && $dataId) {
        $sqlOld = "SELECT cand.profile, data.file_form, data.doc_passport,
                 data.doc_farmer_cert, data.doc_labor_contract,
                 data.doc_census, data.doc_collateral
             FROM candidate_korea AS cand
             LEFT JOIN data_entry_korea AS data ON data.data_id = cand.cid
             WHERE cand.cid = :cid";
    $stmtOld = $conn->prepare($sqlOld);
        $stmtOld->bindParam(":cid", $cid);
    $stmtOld->execute();
    $fetched = $stmtOld->fetch(PDO::FETCH_ASSOC);

    if ($fetched) {
        $oldData = $fetched;
    }
}

// ===================================================
// ຊຸດຂໍ້ມູນ 1: ໄປຕາຕະລາງ candidate_korea (update ຢ່າງດຽວ)
// ===================================================
$dataCandidate = [
    "interview_date"      => getPost("interview_date"),
    "lname_eng"           => getPost("lname_eng"),
    "fname_eng"           => getPost("fname_eng"),
    "nickname"            => getPost("nickname"),
    "fname"               => getPost("fname"),
    "lname"               => getPost("lname"),
    "phone1"              => getPost("phone1"),
    "phone2"              => getPost("phone2"),
    "fam_phone"           => getPost("fam_phone"),
    "nationality"         => getPost("nationality"),
    "dob"                 => getPost("dob"),
    "age"                 => getPost("age"),
    "gender"              => getPost("gender"),
    "status"              => getPost("status"),
    "weight"              => getPost("weight"),
    "height"              => getPost("height"),
    "family_book_no"      => getPost("family_book_no"),
    "family_book_date"    => getPost("family_book_date"),
    "father"              => getPost("father"),
    "mother"              => getPost("mother"),
    "unit"                => getPost("unit"),
    "home"                => getPost("home"),
    "passport"            => $passport,
    "issue_date"          => getPost("issue_date"),
    "exp_date"            => getPost("exp_date"),
    "driver"              => getPost("driver"),
    "shirt_size"          => getPost("shirt_size"),
    "labor_type"          => getPost("labor_type"),
    "eth"                 => getPost("eth"),
    "agricu"              => getPost("agricu"),
    "interview_location"  => getPost("interview_location"),
    "job"                 => getPost("job"),
    "interview_name"      => getPost("interview_name"),
    "list_type"           => getPost("list"),
    "pro_id"    => getPost("pro_id"),
    "dis_id"    => getPost("dis_id"),
    "vill_id"   => getPost("vill_id"),
    "pro_id_b"  => getPost("pro_id_b"),
    "dis_id_b"  => getPost("dis_id_b"),
    "vill_id_b" => getPost("vill_id_b"),
    "sts_data"  => getPost("sts_data"),
    "profile"   => uploadFile("profile", $oldData['profile']),
];

// ===================================================
// ຊຸດຂໍ້ມູນ 2: ໄປຕາຕະລາງ data_entry_korea (ອັນອື່ນທັງໝົດ)
// ===================================================
$dataEntry = [
    "data_id" => $cid, // ໂຍງກັບ candidate_korea

    // "profile"            => uploadFile("profile", $oldData['profile']),
    "file_form"          => uploadFile("file_form", $oldData['file_form']),
    "doc_passport"       => uploadFile("doc_passport", $oldData['doc_passport']),
    "doc_farmer_cert"    => uploadFile("doc_farmer_cert", $oldData['doc_farmer_cert']),
    "doc_labor_contract" => uploadFile("doc_labor_contract", $oldData['doc_labor_contract']),
    "doc_census"         => uploadFile("doc_census", $oldData['doc_census']),
    "doc_collateral"     => uploadFile("doc_collateral", $oldData['doc_collateral']),

    "cif"   => getPost("cif"),
    "heal_date"   => getPost("heal_date"),
    "diagnose"    => getPost("diagnose"),
    "clinic"      => getPost("clinic"),
    "cli_date"    => getPost("cli_date"),
    "check_up"    => clearComma(getPost("check_up")),
    "heal_date2"  => getPost("heal_date2"),
    "check_up2"   => clearComma(getPost("check_up2")),
    "heal_date3"  => getPost("heal_date3"),
    "check_up3"   => clearComma(getPost("check_up3")),
    "heal_remark" => getPost("heal_remark"),
    "heal_sts"    => getPost("heal_sts"),

    "pay_sts"   => getPost("pay_sts"),
    "labor_fee" => clearComma(getPost("labor_fee")),

    "coll_sts"   => getPost("coll_sts"),
    "coll_type"  => getPost("coll_type"),
    "coll_owner" => getPost("coll_owner"),
    "coll_area"  => getPost("coll_area"),
    "coll_no"    => getPost("coll_no"),
    "coll_date"  => getPost("coll_date"),
    "coll_value" => clearComma(getPost("coll_value")),
    "coll_pro"   => getPost("coll_pro"),
    "coll_dis"   => getPost("coll_dis"),
    "coll_vill"  => getPost("coll_vill"),
    "coll_unit"  => getPost("coll_unit"),
    "coll_map"   => getPost("coll_map"),

    "gua_relation"    => getPost("gua_relation"),
    "gua_fname"       => getPost("gua_fname"),
    "gua_phone"       => getPost("gua_phone"),
    "gua_dob"         => getPost("gua_dob"),
    "gua_nationality" => getPost("gua_nationality"),
    "gua_job"         => getPost("gua_job"),
    "gua_age"         => getPost("gua_age"),
    "gua_gender"      => getPost("gua_gender"),
    "gua_pro"         => getPost("gua_pro"),
    "gua_book"        => getPost("gua_book"),
    "gua_book_date"   => getPost("gua_book_date"),
    "gua_dis"         => getPost("gua_dis"),
    "gua_unit"        => getPost("gua_unit"),
    "gua_home"        => getPost("gua_home"),
    "gua_vill"        => getPost("gua_vill"),

    "da_remark" => getPost("da_remark"),
];

try {

    // ===================================================
    // 0. ອັບເດດ candidate_korea ສະເໝີ (candidate_korea ບໍ່ມີການ insert)
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
    // 1. ກໍລະນີ Insert (ຍັງບໍ່ມີຂໍ້ມູນ data_entry_korea ຂອງ cid ນີ້)
    // ===================================================
    if ($sub === "insert") {

        $columnsE      = implode(", ", array_keys($dataEntry));
        $placeholdersE = ":" . implode(", :", array_keys($dataEntry));
        $sqlE = "INSERT INTO data_entry_korea ($columnsE) VALUES ($placeholdersE)";

        $stmtE = $conn->prepare($sqlE);
        foreach ($dataEntry as $key => $value) {
            $stmtE->bindValue(":" . $key, $value);
        }
        $stmtE->execute();

        $msg = "ບັນທຶກຂໍ້ມູນສຳເລັດ";

    // ===================================================
    // 2. ກໍລະນີ Update (ມີຂໍ້ມູນ data_entry_korea ຂອງ cid ນີ້ຢູ່ແລ້ວ)
    // ===================================================
    } else {

        $setClauseE = "";
        foreach ($dataEntry as $key => $value) {
            $setClauseE .= "$key = :$key, ";
        }
        $setClauseE = rtrim($setClauseE, ", ");

        $sqlE = "UPDATE data_entry_korea SET $setClauseE WHERE data_id = :data_id";
        $stmtE = $conn->prepare($sqlE);
        foreach ($dataEntry as $key => $value) {
            $stmtE->bindValue(":" . $key, $value);
        }
        $stmtE->bindValue(":data_id", $dataId);
        $stmtE->execute();

        $msg = "ແກ້ໄຂຂໍ້ມູນສຳເລັດ";
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
?>