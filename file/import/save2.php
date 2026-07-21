<?php
// ຮັບໄຟລ໌ Excel ທີ່ອັບໂຫຼດມາ ແລ້ວບັນທຶກເຂົ້າ labor_korea

header("Content-Type: application/json; charset=utf-8");

require '../vendor/autoload.php';
include '../../connect.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// ==================== ຟັງຊັນຊ່ວຍ: ດຶງ Cell ຈາກ Column Index (ຕົວເລກ) + Row ====================
function getCellByColRow($sheet, $colIndex, $row)
{
    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
    return $sheet->getCell($colLetter . $row);
}

// ==================== ຟັງຊັນອ່ານຄ່າແບບ String ທຳມະດາ (ຕັດຊ່ອງຫວ່າງ) ====================
function getStringValue($sheet, $colIndex, $row)
{
    $val = getCellByColRow($sheet, $colIndex, $row)->getValue();
    return $val !== null ? trim((string)$val) : '';
}

// ==================== ຟັງຊັນອ່ານເລກບັນຊີ (bank_acc) ແບບປອດໄພ ====================
// ກັນບັນຫາ Excel ປັດຕົວເລກຍາວໆເປັນ Scientific Notation (ເຊັ່ນ 1.23457E+14)
function getSafeAccountNumber($sheet, $colIndex, $row)
{
    $cell = getCellByColRow($sheet, $colIndex, $row);
    $dataType = $cell->getDataType();
    $rawValue = $cell->getValue();

    if ($rawValue === null || $rawValue === '') {
        return '';
    }

    // ຖ້າ Cell ຖືກເກັບເປັນ Text ຢູ່ແລ້ວ (ຄົນໃສ່ ' ນຳໜ້າ ຫຼື Format ເປັນ Text) → ໃຊ້ຄ່າໄດ້ເລີຍ ປອດໄພສຸດ
    if ($dataType === DataType::TYPE_STRING) {
        return trim((string)$rawValue);
    }

    // ຖ້າ Cell ເປັນຕົວເລກ → ຕ້ອງແປງແບບບໍ່ໃຫ້ມີຈຸດທົດສະນິຍົມ ຫຼື Scientific Notation
    if (is_numeric($rawValue)) {
        return sprintf('%.0f', $rawValue);
    }

    return trim((string)$rawValue);
}

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
// A=no, B=data_id, C=code, D=disk_id, E=prok_id, F=emp_id, G=couple, H=dep_date,
// I=comback_date, J=sts_contract, K=nomfcr, L=sts_visa, M=bank_acc, N=fin_price,
// O=debt_price, P=labor_remark, Q=sts_labor, R=years
const COL_NO            = 1;   // A - ລຳດັບ (ໃຊ້ເຊັກຄວາມຖືກຕ້ອງ ບໍ່ບັນທຶກ)
const COL_DATA_ID       = 2;   // B - data_id
const COL_CODE          = 3;   // C - code
const COL_DISK_ID       = 4;   // D - disk_id
const COL_PROK_ID       = 5;   // E - prok_id
const COL_EMP_ID        = 6;   // F - emp_id
const COL_COUPLE        = 7;   // G - couple
const COL_DEP_DATE      = 8;   // H - dep_date
const COL_COMBACK_DATE  = 9;   // I - comback_date
const COL_STS_CONTRACT  = 10;  // J - sts_contract
const COL_NOMFCR        = 11;  // K - nomfcr
const COL_STS_VISA      = 12;  // L - sts_visa
const COL_BANK_ACC      = 13;  // M - bank_acc (ຕ້ອງອ່ານແບບປອດໄພ)
const COL_FIN_PRICE     = 14;  // N - fin_price
const COL_DEBT_PRICE    = 15;  // O - debt_price
const COL_LABOR_REMARK  = 16;  // P - labor_remark
const COL_STS_LABOR     = 17;  // Q - sts_labor
const COL_YEARS         = 18;  // R - years

try {
    // ==================== ອ່ານໄຟລ໌ Excel ====================
    $spreadsheet = IOFactory::load($tmpPath);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();

    // ==================== ເລີ່ມ Transaction ແລ້ວວົນລູບບັນທຶກທີລະແຖວ ====================
    $conn->beginTransaction();

    $sql = "INSERT INTO labor_korea
                (data_id, code, disk_id, prok_id, emp_id, couple, dep_date, comback_date,
                 sts_contract, nomfcr, sts_visa, bank_acc, fin_price, debt_price,
                 labor_remark, sts_labor, years)
            VALUES
                (:data_id, :code, :disk_id, :prok_id, :emp_id, :couple, :dep_date, :comback_date,
                 :sts_contract, :nomfcr, :sts_visa, :bank_acc, :fin_price, :debt_price,
                 :labor_remark, :sts_labor, :years)";
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

        $depCell    = getCellByColRow($sheet, COL_DEP_DATE, $row);
        $combackCell = getCellByColRow($sheet, COL_COMBACK_DATE, $row);

        $data_id       = getStringValue($sheet, COL_DATA_ID, $row);
        $code          = getStringValue($sheet, COL_CODE, $row);
        $disk_id       = getStringValue($sheet, COL_DISK_ID, $row);
        $prok_id       = getStringValue($sheet, COL_PROK_ID, $row);
        $emp_id        = getStringValue($sheet, COL_EMP_ID, $row);
        $couple        = getStringValue($sheet, COL_COUPLE, $row);
        $dep_date      = convertToYmd($depCell->getValue(), ExcelDate::isDateTime($depCell));
        $comback_date  = convertToYmd($combackCell->getValue(), ExcelDate::isDateTime($combackCell));
        $sts_contract  = getStringValue($sheet, COL_STS_CONTRACT, $row);
        $nomfcr        = getStringValue($sheet, COL_NOMFCR, $row);
        $sts_visa      = getStringValue($sheet, COL_STS_VISA, $row);

        // ==================== ອ່ານເລກບັນຊີແບບປອດໄພ (ກັນ Scientific Notation / ຕັດເລກ) ====================
        $bank_acc      = getSafeAccountNumber($sheet, COL_BANK_ACC, $row);

        $fin_price     = getCellByColRow($sheet, COL_FIN_PRICE, $row)->getValue();
        $debt_price    = getCellByColRow($sheet, COL_DEBT_PRICE, $row)->getValue();
        $labor_remark  = getStringValue($sheet, COL_LABOR_REMARK, $row);
        $sts_labor     = getStringValue($sheet, COL_STS_LABOR, $row);
        $years         = getCellByColRow($sheet, COL_YEARS, $row)->getValue();

        // ຂ້າມແຖວທີ່ບໍ່ມີ data_id (ຖືວ່າແຖວນັ້ນຫວ່າງເປົ່າ)
        if ($data_id === '') {
            $skippedCount++;
            continue;
        }

        $stmt->bindValue(":data_id",      $data_id);
        $stmt->bindValue(":code",         $code ?: null);
        $stmt->bindValue(":disk_id",      $disk_id ?: null);
        $stmt->bindValue(":prok_id",      $prok_id ?: null);
        $stmt->bindValue(":emp_id",       $emp_id ?: null);
        $stmt->bindValue(":couple",       $couple ?: null);
        $stmt->bindValue(":dep_date",     $dep_date);
        $stmt->bindValue(":comback_date", $comback_date);
        $stmt->bindValue(":sts_contract", $sts_contract ?: null);
        $stmt->bindValue(":nomfcr",       $nomfcr ?: null);
        $stmt->bindValue(":sts_visa",     $sts_visa ?: null);
        $stmt->bindValue(":bank_acc",     $bank_acc ?: null);
        $stmt->bindValue(":fin_price",    $fin_price !== '' ? $fin_price : null);
        $stmt->bindValue(":debt_price",   $debt_price !== '' ? $debt_price : null);
        $stmt->bindValue(":labor_remark", $labor_remark ?: null);
        $stmt->bindValue(":sts_labor",    $sts_labor ?: null);
        $stmt->bindValue(":years",        $years !== '' ? $years : null);
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