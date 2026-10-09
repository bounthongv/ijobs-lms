<?php 
  include_once('../check.php'); 
  include_once('header.php'); 
  $item_id = $_SESSION['item_id'];
$item_ids = explode(',', $item_id);
if(!in_array('0504', $item_ids)){
    ?>
    <!DOCTYPE html>
    <html lang="lo">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap');

        * {
            font-family: "Noto Sans Lao", serif;
        }
    </style>
    <body>
        <script>
            Swal.fire({
                icon: "error",
                title: "ການເຂົ້າເຖິງຖືກປະຕິເສດ",
                text: "ທ່ານບໍ່ມີສິດເຂົ້າໃຊ້ໜ້ານີ້",
                confirmButtonText: "ກັບຄືນ",
                confirmButtonColor: "#dc3545",
                allowOutsideClick: false
            }).then(() => {
                window.history.back();
            });
        </script>
    </body>
    </html>
    <?php
    exit();
}
  $all = $_REQUEST['all'] ?? '';

  //search
  $p1 = $all == '' ? '' : "AND (tri_name LIKE '%$all%' OR tri_name_eng LIKE '%$all%')";

  // ພາກສ່ວນການປ່ຽນໜ້າ
  $limit = 500;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $sql = $conn->prepare("SELECT * FROM tribes
  WHERE 1=1 $p1 
  ORDER BY tri_id ASC
  LIMIT $limit OFFSET $offset");
  $sql->execute();
  // ດືງຂໍ້ມູນທັງໝົດເພື່ອຄຳນວນຈຳນວນໜ້າ
  $total_result = $conn->prepare("SELECT COUNT(*) as total  FROM tribes
  WHERE 1=1 $p1 ");
  $total_result->execute();
  $total_row = $total_result->fetch(PDO::FETCH_ASSOC);
  $total_pages = ceil($total_row['total'] / $limit);

  $num = $offset + 1;
  $users = $sql->fetchAll(PDO::FETCH_ASSOC);

  // total
  $sql_total = $conn->prepare("SELECT COUNT(*) FROM tribes
  WHERE 1=1 $p1 ");
  $sql_total->execute();
  $total = $sql_total->fetch(PDO::FETCH_NUM)[0];

  $sql_max = $conn->prepare("SELECT MAX(tri_id) FROM tribes");
  $sql_max->execute();
  $max_id = $sql_max->fetch(PDO::FETCH_NUM)[0];
  $number = 1;
  $number = $max_id ? (int) $max_id + 1 : 1;
  $tri_id = str_pad($number, 3, '0', STR_PAD_LEFT);
?>
<style>
    table th,table td{
        white-space: nowrap;
        vertical-align: top;
        font-size: 14px;
    }
</style>
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
  <div>
    <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
      <i class="bi bi-people-fill me-2 text-primary"></i>ລາຍການຊົນເຜົ່າ
    </h5>
  </div>
  <button class="btn btn-primary btn-sm px-3 py-2"
          onclick="openModal('add')"
          style="font-size:13px; font-weight:600; border-radius:8px;">
    <i class="bi bi-plus-lg me-1"></i> ເພີ່ມໃໝ່
  </button>
</div>
<!-- ===== ຕາຕະລາງ User ===== -->
<div class="dash-card">
  <div class="dash-card-title">
    <i class="bi bi-table text-primary"></i> ລາຍຊື່ ຊົນເຜົ່າ ທັງໝົດ
    <span class="ms-auto badge fw-semibold"
          style="background:#eff6ff; color:#1d4ed8; font-size:12px;">
      <?= $total ?> ລາຍການ
    </span>
  </div>

  <!-- Filter Bar -->
   <form action="" method="get">
  <div class="filter-bar row g-3 mb-3">
      <div class="col-md-4 filter-group">
          <label for="all">ຄົ້ນຫາ</label>
          <input type="text" name="all" id="all" placeholder="🔍 ຄົ້ນຫາຊື່ ຊົນເຜົ່າ...." value="<?= $all ?>">
      </div>
      <div class="col-md-2 filter-group btn-mt">
          <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Search</button>
      </div>
  </div>
  </form>

  <?php if($sql->rowCount() == 0): ?>
            <!-- ຂໍ້ຄວາມ ຖ້າບໍ່ມີຂໍ້ມູນ -->
          <div id="emptyMsg" style="text-align:center; padding:28px; color:#94a3b8; font-size:13px;">
          <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px; opacity:.4;"></i>
          ບໍ່ພົບຂໍ້ມູນ
          </div>
<?php else:; ?>
  <div class="table-responsive">
    <table class="usr-table" id="userTable">
      <thead>
        <tr>
          <th>#</th>
          <th>ລະຫັດຊົນເຜົ່າ</th>
          <th>ຊື່ຊົນເຜົ່າ (ລາວ)</th>
          <th>ຊື່ຊົນເຜົ່າ (ອັງກິດ)</th>
          <th class="text-end">ຈັດການ</th>
        </tr>
      </thead>
      <tbody id="showTbody">
        
        <?php foreach ($users as $i => $u):?>
        <tr>
          <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['tri_id']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['tri_name']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['tri_name_eng']) ?></td>
          <td class="text-end">
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
              <!-- ປຸ່ມແກ້ໄຂ Role -->
              <button class="btn-edit"
                      data-user="<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8'); ?>"
                      onclick="handleEditButtonClick(this)">
                <i class="bi bi-pencil-fill"></i> ແກ້ໄຂ
              </button>
              <!-- ປຸ່ມລົບ -->
              <button class="btn-del del_tri" data-tri_id="<?= $u['tri_id'] ?>">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        
        
      </tbody>
    </table>
    <nav>
        <ul class="pagination justify-content-center flex-wrap">
            <!-- ปุ่ม Previous -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a href="?page=<?= $page - 1 ?>" class="page-link">Previous</a>
                </li>
            <?php endif ?>
            <?php
            $range = 5;
            $start = max(1, $page - $range);
            $end = min($total_pages, $page + $range);

            // แสดงหน้าแรก + ... ถ้าจุดเริ่มต้นมากกว่า 1
            if ($start > 1) {
                echo '<li class="page-item"><a href="?page=1" class="page-link">1</a></li>';
                if ($start > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            // ลูปแสดงเลขหน้าตามช่วงที่กำหนด
            for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a href="?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                </li>
            <?php endfor;
            // แสดง ... + หน้าสุดท้าย ถ้าจุดสิ้นสุดน้อยกว่าหน้าทั้งหมด
            if ($end < $total_pages) {
                if ($end < $total_pages - 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a href="?page=' . $total_pages . '" class="page-link">' . $total_pages . '</a></li>';
            }
            ?>
            <!-- ปุ่ม Next -->
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a href="?page=<?= $page + 1 ?>" class="page-link">Next</a>
                </li>
            <?php endif ?>
        </ul>
    </nav>
  </div>
<?php endif; ?>


</div>

<?php 
  // 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
  include_once('form/tribes_add.php'); 
  include_once('form/tribes_edit.php'); 
  include_once('footer.php'); 
?>
