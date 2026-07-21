<?php
// ຮັບໄຟລ໌ Excel ທີ່ອັບໂຫຼດມາ, ອ່ານດ້ວຍ PhpSpreadsheet ແລ້ວບັນທຶກເຂົ້າ data_entry_korea

header("Content-Type: application/json; charset=utf-8");

require '../vendor/autoload.php';
include '../../connect.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// ==================== ຟັງຊັນຊ່ວຍ: ດຶງ Cell ຈາກ Column Index (ຕົວເລກ) + Row ====================
function getCellByColRow($sheet, $colIndex, $row)
{
    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
    return $sheet->getCell($colLetter . $row);
}

// ==================== ກວດສອບວ່າມີໄຟລ໌ອັບໂຫຼດມາຫຼືບໍ່ ====================
if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["status" => "error", "message" => "ບໍ່ພົບໄຟລ໌ທີ່ອັບໂຫຼດ ຫຼື ອັບໂຫຼດບໍ່ສຳເລັດ"]);
    exit;
}

$tmpPath = $_FILES['excel_file']['tmp_name'];
$ext = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
    echo json_encode(["status" => "error", "message" => "ຮອງຮັບສະເພາະໄຟລ໌ .xlsx, .xls, .csv ເທົ່ານັ້ນ"]);
    exit;
}

// ==================== ລຳດັບຄໍລໍາຄົງທີ່ (Index ເລີ່ມຈາກ 1 = ຄໍລໍາ A) ====================
// A=no, B=ID(→data_id), C=lname_eng, D=fname_eng, E=lname, F=fname, G=dob, H=gender,
// I=age, J=nationality, K=passport, L=issue_date, M=exp_date, N=vill_id, O=dis_id, P=pro_id, Q=phone1
const COL_NO          = 1;   // A - ລຳດັບ (ໃຊ້ເຊັກຄວາມຖືກຕ້ອງ)
const COL_ID          = 2;   // B - ID → ບັນທຶກລົງ data_id
const COL_LNAME_ENG   = 3;   // C - Surname
const COL_FNAME_ENG   = 4;   // D - Given Name
const COL_LNAME       = 5;   // E - surname (ພາສາລາວ)
const COL_FNAME       = 6;   // F - Lao) ຊື່ພາສາລາວ
const COL_DOB         = 7;   // G - Date of birth
const COL_GENDER      = 8;   // H - Sex
const COL_AGE         = 9;   // I - Age
const COL_NATIONALITY = 10;  // J - National ID number → nationality
const COL_PASSPORT    = 11;  // K - Passport number
const COL_ISSUE_DATE  = 12;  // L - Passport issue date
const COL_EXP_DATE    = 13;  // M - Passport termination
const COL_VILL_ID     = 14;  // N - Village
const COL_DIS_ID      = 15;  // O - District
const COL_PRO_ID      = 16;  // P - Province
const COL_PHONE1      = 17;  // Q - Phone Number
const COL_PAY_STS     = 18;  // R - Payment Status

// ==================== ຟັງຊັນແປງວັນທີ່ຈາກ Excel (ທຸກຮູບແບບ) → Y-m-d ====================
function convertToYmd($cellValue, $isDateFormatted)
{
    if ($cellValue === null || trim((string)$cellValue) === '') {
        return null;
    }

    if ($isDateFormatted && is_numeric($cellValue)) {
        try {
            return ExcelDate::excelToDateTimeObject($cellValue)->format('Y-m-d');
        } catch (\Exception $e) {}
    }

    if (is_numeric($cellValue) && $cellValue > 20000 && $cellValue < 60000) {
        try {
            return ExcelDate::excelToDateTimeObject($cellValue)->format('Y-m-d');
        } catch (\Exception $e) {}
    }

    $strVal = trim((string)$cellValue);
    $strVal = str_replace(['.', '\\'], '/', $strVal);

    $formats = ['Y-m-d', 'Y/m/d', 'd-m-Y', 'd/m/Y', 'm-d-Y', 'm/d/Y', 'd-m-y', 'd/m/y', 'Y-m-d H:i:s'];

    foreach ($formats as $fmt) {
        $dateObj = DateTime::createFromFormat($fmt, $strVal);
        if ($dateObj && $dateObj->format($fmt) === $strVal) {
            return $dateObj->format('Y-m-d');
        }
    }

    $timestamp = strtotime($strVal);
    return $timestamp !== false ? date('Y-m-d', $timestamp) : null;
}

// ==================== ຟັງຊັນແປງຄ່າ pay_sts ====================
function normalizePayStatus($value)
{
    $value = trim(mb_strtolower((string)$value));

    $map = [
        "loan"     => "Borrow",
        "pay cash" => "Pay",
        "paycash"  => "Pay",
    ];

    return $map[$value] ?? trim((string)$value); // ຖ້າບໍ່ກົງ Map ໃຫ້ເກັບຄ່າເດີມໄວ້
}
// ==================== ຟັງຊັນແປງຄ່າ gender ໃຫ້ກົງກັບ ENUM('F','M') ====================
function normalizeGender($value)
{
    $value = trim(mb_strtoupper((string)$value));

    $map = [
        "M" => "M", "MALE" => "M", "ຊາຍ" => "M", "ชาย" => "M", "1" => "M", "남" => "M", "남성" => "M",
        "F" => "F", "FEMALE" => "F", "ຍິງ" => "F", "หญิง" => "F", "2" => "F", "여" => "F", "여성" => "F",
    ];

    return $map[$value] ?? null;
}

try {
    // ==================== ອ່ານໄຟລ໌ Excel ====================
    $spreadsheet = IOFactory::load($tmpPath);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();

    // ==================== ເລີ່ມ Transaction ແລ້ວວົນລູບບັນທຶກທີລະແຖວ ====================
    $conn->beginTransaction();

    $sql = "INSERT INTO data_entry_korea
            (data_id, lname_eng, fname_eng, fname, lname, dob, gender, age,
             nationality, passport, issue_date, exp_date, vill_id, dis_id, pro_id, phone1, pay_sts)
        VALUES
            (:data_id, :lname_eng, :fname_eng, :fname, :lname, :dob, :gender, :age,
             :nationality, :passport, :issue_date, :exp_date, :vill_id, :dis_id, :pro_id, :phone1, :pay_sts)";
    $stmt = $conn->prepare($sql);

    $insertedCount = 0;
    $skippedCount  = 0;

    // ເລີ່ມແຖວທີ 2 (ຂ້າມແຖວ Header ແຖວທຳອິດ)
    for ($row = 2; $row <= $highestRow; $row++) {

        // ==================== ເຊັກຄໍລໍາທຳອິດ (A / ລຳດັບ) ວ່າມີຄ່າຫຼືບໍ່ ====================
        $noCell = getCellByColRow($sheet, COL_NO, $row)->getValue();

        if ($noCell === null || trim((string)$noCell) === '') {
            $skippedCount++;
            continue;
        }

        $dobCell   = getCellByColRow($sheet, COL_DOB, $row);
        $issueCell = getCellByColRow($sheet, COL_ISSUE_DATE, $row);
        $expCell   = getCellByColRow($sheet, COL_EXP_DATE, $row);

        $data_id     = trim((string)getCellByColRow($sheet, COL_ID, $row)->getValue());
        $lname_eng   = trim((string)getCellByColRow($sheet, COL_LNAME_ENG, $row)->getValue());
        $fname_eng   = trim((string)getCellByColRow($sheet, COL_FNAME_ENG, $row)->getValue());
        $fname       = trim((string)getCellByColRow($sheet, COL_FNAME, $row)->getValue());
        $lname       = trim((string)getCellByColRow($sheet, COL_LNAME, $row)->getValue());
        $dob         = convertToYmd($dobCell->getValue(), ExcelDate::isDateTime($dobCell));
        $gender      = normalizeGender(getCellByColRow($sheet, COL_GENDER, $row)->getValue());
        $age         = getCellByColRow($sheet, COL_AGE, $row)->getValue();
        $nationality = trim((string)getCellByColRow($sheet, COL_NATIONALITY, $row)->getValue());
        $passport    = trim((string)getCellByColRow($sheet, COL_PASSPORT, $row)->getValue());
        $issue_date  = convertToYmd($issueCell->getValue(), ExcelDate::isDateTime($issueCell));
        $exp_date    = convertToYmd($expCell->getValue(), ExcelDate::isDateTime($expCell));
        $vill_id     = trim((string)getCellByColRow($sheet, COL_VILL_ID, $row)->getValue());
        $dis_id      = trim((string)getCellByColRow($sheet, COL_DIS_ID, $row)->getValue());
        $pro_id      = trim((string)getCellByColRow($sheet, COL_PRO_ID, $row)->getValue());
        $phone1      = trim((string)getCellByColRow($sheet, COL_PHONE1, $row)->getValue());
        // ດຶງຄ່າ (ວາງຄູ່ກັບ $phone1)
        $pay_sts = normalizePayStatus(getCellByColRow($sheet, COL_PAY_STS, $row)->getValue());

        // ຂ້າມແຖວທີ່ບໍ່ມີຊື່ ຫຼື ນາມສະກຸນເລີຍ (ກັນຂໍ້ມູນຫວ່າງເປົ່າ)
        if ($fname === '' && $fname_eng === '') {
            $skippedCount++;
            continue;
        }

        $stmt->bindValue(":data_id",    $data_id !== '' ? $data_id : null);
        $stmt->bindValue(":lname_eng",  $lname_eng ?: null);
        $stmt->bindValue(":fname_eng",  $fname_eng ?: null);
        $stmt->bindValue(":fname",      $fname ?: null);
        $stmt->bindValue(":lname",      $lname ?: null);
        $stmt->bindValue(":dob",        $dob);
        $stmt->bindValue(":gender",     $gender);
        $stmt->bindValue(":age",        $age !== '' ? $age : null);
        $stmt->bindValue(":nationality",$nationality ?: null);
        $stmt->bindValue(":passport",   $passport ?: null);
        $stmt->bindValue(":issue_date", $issue_date);
        $stmt->bindValue(":exp_date",   $exp_date);
        $stmt->bindValue(":vill_id",    $vill_id ?: null);
        $stmt->bindValue(":dis_id",     $dis_id ?: null);
        $stmt->bindValue(":pro_id",     $pro_id ?: null);
        $stmt->bindValue(":phone1",     $phone1 ?: null);
        // Bind (ວາງຄູ່ກັບ :phone1)
        $stmt->bindValue(":pay_sts",    $pay_sts ?: null);
        $stmt->execute();

        $insertedCount++;
    }

    $conn->commit();

    echo json_encode([
        "status"   => "success",
        "inserted" => $insertedCount,
        "skipped"  => $skippedCount
    ]);

} catch (PDOException $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(["status" => "error", "message" => "ບັນທຶກຂໍ້ມູນບໍ່ສຳເລັດ: " . $e->getMessage()]);
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => "ອ່ານໄຟລ໌ Excel ບໍ່ສຳເລັດ: " . $e->getMessage()]);
}