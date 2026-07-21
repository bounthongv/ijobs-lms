<?php 
  include_once('../check.php'); 
  include_once('header.php'); 

  $sql = $conn->prepare("SELECT * FROM district as dis
  INNER JOIN province as pro ON dis.pro_id=pro.pro_id
  ORDER BY dis_id ASC");
  $sql->execute();
  $num = 1;
  $users = $sql->fetchAll(PDO::FETCH_ASSOC);

  // ນັບຈຳນວນແຕ່ລະ Role / Status
  $totalUsers   = count($users);
  $sql_pro = $conn->prepare("SELECT * FROM province");
  $sql_pro->execute();
  $pro = $sql_pro->fetchAll(PDO::FETCH_ASSOC);
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
      <i class="bi bi-buildings-fill me-2 text-primary"></i>ລາຍການເມືອງ
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
    <i class="bi bi-table text-primary"></i> ລາຍຊື່ District ທັງໝົດ
    <span class="ms-auto badge fw-semibold"
          style="background:#eff6ff; color:#1d4ed8; font-size:12px;">
      <?= $totalUsers ?> ລາຍການ
    </span>
  </div>

  <!-- Filter Bar -->
  <div class="filter-bar row g-3 mb-3">
      <div class="col-md-4 filter-group">
          <label for="all">ຄົ້ນຫາ</label>
          <input type="text" name="all" id="all" placeholder="🔍 ຄົ້ນຫາຊື່ ເມືອງ....">
      </div>
      <div class="col-md-2 filter-group">
          <label for="all">ແຂວງ</label>
          <select name="" id="s_pro_id" class="form-select">
            <option value="">ເລືອກ</option>
            <?php foreach($pro as $p): ?>
              <option value="<?= $p['pro_id'] ?>"><?= $p['pro_name_lao'] ?></option>
            <?php endforeach ?>
          </select>
      </div>
      <div class="col-md-2 filter-group btn-mt">
          <button type="button" class="btn btn-secondary btn-sm" id="search"><i class="bi bi-search"></i> Search</button>
      </div>
  </div>

  <div class="table-responsive">
    <table class="usr-table" id="userTable">
      <thead>
        <tr>
          <th>#</th>
          <th>District Id</th>
          <th>Province Name</th>
          <th>District Name (Lao)</th>
          <th>District Name (Eng)</th>
          <th class="text-end">ຈັດການ</th>
        </tr>
      </thead>
      <tbody id="showTbody">
        <?php if($sql->rowCount() == 0): ?>
            <!-- ຂໍ້ຄວາມ ຖ້າບໍ່ມີຂໍ້ມູນ -->
          <div id="emptyMsg" style="text-align:center; padding:28px; color:#94a3b8; font-size:13px;">
          <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px; opacity:.4;"></i>
          ບໍ່ພົບຂໍ້ມູນ
          </div>
        <?php else:; ?>
        <?php foreach ($users as $i => $u):?>
        <tr>
          <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['dis_id']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['pro_name_lao']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['dis_name_lao']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['dis_name']) ?></td>
          <td class="text-end">
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
              <!-- ປຸ່ມແກ້ໄຂ Role -->
              <button class="btn-edit"
                      data-user="<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8'); ?>"
                      onclick="handleEditButtonClick(this)">
                <i class="bi bi-pencil-fill"></i> ແກ້ໄຂ
              </button>
              <!-- ປຸ່ມລົບ -->
              <button class="btn-del del_dis" data-dis_id="<?= $u['dis_id'] ?>">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        
      </tbody>
    </table>
  </div>



</div>

<?php 
  // 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
  include_once('form/dis_add.php'); 
  include_once('form/dis_edit.php'); 
  include_once('footer.php'); 
?>