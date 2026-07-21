<?php 
  include_once('../check.php'); 
  include_once('header.php'); 

  $sql = $conn->prepare("SELECT * FROM province_korea ORDER BY prok_id ASC");
  $sql->execute();
  $num = 1;
  $employers = $sql->fetchAll(PDO::FETCH_ASSOC);
  // ============================================================
// ຂໍ້ມູນຕົວຢ່າງ Employer (Static Data)
// ============================================================

// ນັບຈຳນວນ Employer
$totalEmployers = count($employers);

$sql_max = $conn->prepare("SELECT MAX(prok_id) FROM province_korea");
    $sql_max->execute();
    $max_id = $sql_max->fetchColumn();
    $number = 1;
    $number = $max_id ? (int) $max_id + 1 : 1;
    $prok_id = str_pad($number,2,'0',STR_PAD_LEFT);

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
      <i class="bi bi-buildings-fill me-2 text-primary"></i> Province Korea List
    </h5>
  </div>
  <button class="btn btn-primary btn-sm px-3 py-2"
          onclick="openModal('add')"
          style="font-size:13px; font-weight:600; border-radius:8px;">
    <i class="bi bi-plus-lg me-1"></i> ເພີ່ມ
  </button>
</div>
<!-- ===== ຕາຕະລາງ Employer ===== -->
<div class="dash-card">
  <div class="dash-card-title">
    <i class="bi bi-table text-primary"></i> ລາຍຊື່ Province Korea ທັງໝົດ
    <span class="ms-auto badge fw-semibold"
          style="background:#eff6ff; color:#1d4ed8; font-size:12px;">
      <?= $totalEmployers ?> ລາຍການ
    </span>
  </div>

  <!-- Filter Bar -->
  <div class="filter-bar row g-3 mb-3">
            <div class="col-md-4 filter-group">
                <label for="all">ຄົ້ນຫາ</label>
                <input type="text" name="all" id="s_all" placeholder="🔍 ຄົ້ນຫາຊື່ແຂວງ...">
            </div>

            <div class="col-md-2 filter-group btn-mt">
                <button type="button" class="btn btn-secondary btn-sm" id="sc_prok"><i class="bi bi-search"></i> Search</button>
            </div>

        </div>

  <div class="table-responsive">
    <table class="usr-table" id="employerTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Province ID</th>
          <th>Province Name</th>
          <th>Province Name (Lao)</th>
          <th class="text-end">ຈັດການ</th>
        </tr>
      </thead>
      <tbody id="employerTbody">
        <?php foreach ($employers as $i => $emp):?>
        <tr 
            >

          <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>

          <td style="font-weight:600; color:#1e293b;">
            <?= htmlspecialchars($emp['prok_id'] ?? '') ?>
          </td>

          <td style="color:#64748b; font-weight:500;">
            <?= htmlspecialchars($emp['prok_name'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['prok_name_lao'] ?? '') ?>
          </td>

          <td class="text-end">
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
              <!-- ປຸ່ມແກ້ໄຂ -->
              <button class="btn-edit"
                      data-emp="<?php echo htmlspecialchars(json_encode($emp), ENT_QUOTES, 'UTF-8'); ?>"
                      onclick="handleEditButtonClick(this)">
                <i class="bi bi-pencil-fill"></i> ແກ້ໄຂ
              </button>
              <!-- ປຸ່ມລົບ -->
              <button class="btn-del del_prok" data-prok_id="<?= $emp['prok_id'] ?>" >
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<?php 
  // 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
  include_once('form/prok_add.php'); 
  include_once('form/prok_edit.php'); 
  include_once('footer.php'); 
?>