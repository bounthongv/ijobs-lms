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
// ຟັງຊັນອັບໂຫລດໄຟລ໌ຫຼັກຊັບ
// ເກັບແຍກໂຟນເດີ: uploads/<cid>/<ຊ່ອງ>/<ຊື່ໄຟລ໌>
// ===================================================
function uploadCollateralFile($fieldName, $cid, $slot, $oldValue = null) {

    $baseDir = "../uploads/";

    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return $oldValue;
    }

    $uploadDir = $baseDir . $cid . "/" . $slot . "/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
    $newFileName = $fieldName . "_" . time() . "_" . uniqid() . "." . $ext;
    $targetPath = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {

        // ລົບໄຟລ໌ເກົ່າ (ຖ້າມີ)
        if ($oldValue) {
            $oldPath = $baseDir . $oldValue;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        return $cid . "/" . $slot . "/" . $newFileName;
    }

    return $oldValue;
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
// ກໍລະນີລົບໄຟລ໌ເອກະສານ (ສະເພາະ Admin)
// ===================================================
if ($action === 'delete_file') {

    // ກວດສອບສິດ Admin
    if (($_SESSION['status'] ?? '') !== 'Admin') {
        echo json_encode([
            'message' => 'ສະເພາະ Admin ເທົ່ານັ້ນ ທີ່ລົບໄຟລ໌ໄດ້',
            'sts' => 'error'
        ]);
        exit;
    }

    // ກວດສອບຊ່ອງໄຟລ໌ (1-4)
    $slot = $_POST['slot'] ?? '';
    if (!in_array($slot, ['1', '2', '3', '4'])) {
        echo json_encode([
            'message' => 'ຊ່ອງໄຟລ໌ບໍ່ຖືກຕ້ອງ',
            'sts' => 'error'
        ]);
        exit;
    }

    $stmtFile = $conn->prepare("SELECT coll_file_$slot AS file_val, sts_save FROM interview_collateral WHERE data_id = :cid LIMIT 1");
    $stmtFile->execute([':cid' => $cid]);
    $fileRow = $stmtFile->fetch(PDO::FETCH_ASSOC);

    if (!$fileRow || !$fileRow['file_val']) {
        echo json_encode([
            'message' => 'ບໍ່ມີໄຟລ໌ໃນຊ່ອງນີ້',
            'sts' => 'error'
        ]);
        exit;
    }

    if ($fileRow['sts_save'] === 'Verify') {
        echo json_encode([
            'message' => 'ຂໍ້ມູນຖືກຢືນຢັນແລ້ວ ບໍ່ສາມາດລົບໄຟລ໌ໄດ້',
            'sts' => 'error'
        ]);
        exit;
    }

    // ລົບໄຟລ໌ອອກຈາກໂຟນເດີ
    $filePath = "../uploads/" . $fileRow['file_val'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // ອັບເດດຖານຂໍ້ມູນໃຫ້ເປັນ null
    $upd = $conn->prepare("UPDATE interview_collateral SET coll_file_$slot = NULL WHERE data_id = :cid");
    $upd->execute([':cid' => $cid]);

    echo json_encode([
        'message' => 'ລົບໄຟລ໌ສຳເລັດ',
        'sts' => 'success'
    ]);
    exit;
}

// ===================================================
// ກວດສອບສະຖານະການຢືນຢັນ (ຖ້າ Verify ແລ້ວ ຫ້າມແກ້ໄຂ)
// ===================================================
$stmtExist = $conn->prepare("SELECT * FROM interview_collateral WHERE data_id = :cid LIMIT 1");
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
// ປັບຄ່າ checkbox ຂອງລາຍການຫຼັກຊັບ (ຕິກ = yes, ບໍ່ຕິກ = no)
// ===================================================
$hasCollateral = getPost("has_collateral");

if ($hasCollateral === 'yes') {
    $collLand    = isset($_POST['coll_item_land']) ? 'yes' : 'no';
    $collVehicle = isset($_POST['coll_item_vehicle']) ? 'yes' : 'no';
    $collOther   = isset($_POST['coll_item_other']) ? 'yes' : 'no';
} else {
    $collLand = $collVehicle = $collOther = null;
}

// ===================================================
// ຊຸດຂໍ້ມູນ: interview_collateral
// ===================================================
$dataCollateral = [
    "data_id" => $cid,
    "cif"     => $cif,

    "has_collateral"         => $hasCollateral,
    "coll_item_land"         => $collLand,
    "coll_item_vehicle"      => $collVehicle,
    "coll_item_other"        => $collOther,
    "coll_item_other_detail" => getPost("coll_item_other_detail"),
    // ຟິວ ຈຳນວນ (ຈັກຢ່າງ) — ປິດໄວ້ກ່ອນ
    // "collateral_qty"        => getPost("collateral_qty"),
    "collateral_type"       => getPost("collateral_type"),
    "is_owner"              => getPost("is_owner"),
    "owner_name"            => getPost("owner_name"),
    "area"                  => getPost("area"),
    "witness"               => getPost("witness"),

    // ໄຟລ໌ແນບ 4 ຊ່ອງ (ເກັບໃນ uploads/<cid>/<ຊ່ອງ>/)
    "coll_file_1" => uploadCollateralFile("coll_file_1", $cid, "1", $existRow['coll_file_1'] ?? null),
    "coll_file_2" => uploadCollateralFile("coll_file_2", $cid, "2", $existRow['coll_file_2'] ?? null),
    "coll_file_3" => uploadCollateralFile("coll_file_3", $cid, "3", $existRow['coll_file_3'] ?? null),
    "coll_file_4" => uploadCollateralFile("coll_file_4", $cid, "4", $existRow['coll_file_4'] ?? null),

    "sts_save"    => $sts_save,
    "verify_by"   => $verify_by,
    "verify_date" => $verify_date,
];

try {

    // ===================================================
    // 1. ບັນທຶກ interview_collateral (ມີແລ້ວ = update, ບໍ່ມີ = insert)
    // ===================================================
    if ($existRow) {

        $setClause = "";
        foreach ($dataCollateral as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");

        $sql = "UPDATE interview_collateral SET $setClause WHERE data_id = :data_id";
        $stmt = $conn->prepare($sql);
        foreach ($dataCollateral as $key => $value) {
            $stmt->bindValue(":" . $key, $value);
        }
        $stmt->bindValue(":data_id", $cid);
        $stmt->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ແກ້ໄຂຂໍ້ມູນສຳເລັດ";

    } else {

        $columns      = implode(", ", array_keys($dataCollateral));
        $placeholders = ":" . implode(", :", array_keys($dataCollateral));
        $sql = "INSERT INTO interview_collateral ($columns) VALUES ($placeholders)";

        $stmt = $conn->prepare($sql);
        foreach ($dataCollateral as $key => $value) {
            $stmt->bindValue(":" . $key, $value);
        }
        $stmt->execute();

        $msg = $action === 'verify' ? "ຢືນຢັນຂໍ້ມູນສຳເລັດ" : "ບັນທຶກຂໍ້ມູນສຳເລັດ";
    }

    // ===================================================
    // 2. ປັບລະຫັດ CIF ໃຫ້ຕົງກັນກັບຕາຕະລາງອື່ນຂອງ cid ດຽວກັນ
    // ===================================================
    $syncTables = ['interview_personal', 'interview_physical', 'interview_experience', 'interview_attitude', 'interview_general', 'data_entry_korea'];
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
