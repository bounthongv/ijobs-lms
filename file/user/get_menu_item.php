<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// ============================================================
// get_menu_item.php
// ດຶງລາຍການ Menu ຍ່ອຍ (menu_item) ຕາມ menu_id ທີ່ຖືກເລືອກ
// ໃຊ້ຮ່ວມກັບ Checkbox Tree ໃນຟອມ User (Add / Edit)
// ============================================================

require '../../connect.php'; // TODO: ປັບ path ໃຫ້ຕົງກັບໂຄງສ້າງໂຟນເດີຈິງ

header('Content-Type: application/json; charset=utf-8');

// ກວດສອບຄ່າ menu_id ທີ່ສົ່ງເຂົ້າມາ
$menu_id = $_POST['menu_id'] ?? '';

if ($menu_id === '') {
    echo json_encode([
        'sts'     => 'error',
        'message' => 'ບໍ່ພົບ menu_id',
        'data'    => []
    ]);
    exit;
}

// ດຶງລາຍການ Menu ຍ່ອຍຕາມ menu_id ດ້ວຍ Prepared Statement
$sql = "SELECT item_id, item_name FROM menu_item WHERE menu_id = :menu_id ORDER BY item_id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([':menu_id' => $menu_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'sts'     => 'success',
    'message' => 'ດຶງຂໍ້ມູນສຳເລັດ',
    'data'    => $items
]);