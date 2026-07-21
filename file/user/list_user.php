<?php 
  include_once('../check.php'); 
  include_once('header.php'); 

  $sql = $conn->prepare("SELECT * FROM users ORDER BY user_id ASC");
  $sql->execute();
  $num = 1;
  $users = $sql->fetchAll(PDO::FETCH_ASSOC);
  // ============================================================
// ຂໍ້ມູນຕົວຢ່າງ User (Static Data)
// ============================================================



// ນັບຈຳນວນແຕ່ລະ Role / Status
$totalUsers   = count($users);
$totalAdmin   = count(array_filter($users, fn($u) => $u['status']   === 'Admin'));
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
      <i class="bi bi-people-fill me-2 text-primary"></i>ລາຍການຜູ້ໃຊ້ລະບົບ
    </h5>
  </div>
  <button class="btn btn-primary btn-sm px-3 py-2"
          onclick="openModal('add')"
          style="font-size:13px; font-weight:600; border-radius:8px;">
    <i class="bi bi-plus-lg me-1"></i> ເພີ່ມ User ໃໝ່
  </button>
</div>
<!-- ===== ຕາຕະລາງ User ===== -->
<div class="dash-card">
  <div class="dash-card-title">
    <i class="bi bi-table text-primary"></i> ລາຍຊື່ User ທັງໝົດ
    <span class="ms-auto badge fw-semibold"
          style="background:#eff6ff; color:#1d4ed8; font-size:12px;">
      <?= $totalUsers ?> ລາຍການ
    </span>
  </div>

  <!-- Filter Bar -->
  <div class="filter-bar">
    <input type="text" id="searchInput" placeholder="🔍 ຄົ້ນຫາຊື່ / ອີເມລ..."
           oninput="filterTable()">
    <select id="filterRole" onchange="filterTable()">
      <option value="">Role ທັງໝົດ</option>
      <option value="Admin">Admin</option>
      <option value="User">User</option>
    </select>
    <select id="filterStatus" onchange="filterTable()">
      <option value="">ສະຖານະທັງໝົດ</option>
      <option value="active">ໃຊ້ງານຢູ່</option>
      <option value="inactive">ປິດ</option>
    </select>
  </div>

  <div class="table-responsive">
    <table class="usr-table" id="userTable">
      <thead>
        <tr>
          <th>#</th>
          <th>ຊື່-ນາມສະກຸນ</th>
          <th>ອີເມລ</th>
          <th>Role</th>
          <th>ວັນທີສ້າງ</th>
          <th class="text-end">ຈັດການ</th>
        </tr>
      </thead>
      <tbody id="userTbody">
        <?php foreach ($users as $i => $u):
          // ກຳນົດ class ຂອງ role ແລະ status
          $roleClass   = $u['status']   === 'Admin' ? 'role-admin'      : 'role-user';
          $statusClass = $u['status'] === 'active' ? 'status-active'  : 'status-inactive';
          // $statusLabel = $u['status'] === 'active' ? 'ໃຊ້ງານຢູ່'     : 'ປິດການໃຊ້ງານ';
          $initials    = mb_substr($u['fname'], 0, 1, 'UTF-8');
        ?>
        <tr data-name="<?= htmlspecialchars($u['fname']) ?>"
            data-email="<?= htmlspecialchars($u['email']) ?>"
            data-status="<?= $u['status'] ?>"
            >

          <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>

          <td>
            <div style="display:flex; align-items:center; gap:9px;">
              <div class="usr-av"><?= $initials ?></div>
              <span style="font-weight:600; color:#1e293b;">
                <?= htmlspecialchars($u['fname']) ?>
              </span>
            </div>
          </td>

          <td style="color:#64748b;"><?= htmlspecialchars($u['email']) ?></td>

          <td>
            <span class="role-badge <?= $roleClass ?>">
              <i class="bi <?= $u['status'] === 'Admin' ? 'bi-shield-fill' : 'bi-person-fill' ?>"></i>
              <?= $u['status'] ?>
            </span>
          </td>

          <td style="color:#94a3b8;">
            <i class="bi bi-calendar3 me-1"></i><?= $u['create_user'] ?>
          </td>

          <td class="text-end">
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
              <!-- ປຸ່ມແກ້ໄຂ Role -->
              <button class="btn-edit"
                      data-user="<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8'); ?>"
                      onclick="handleEditButtonClick(this)">
                <i class="bi bi-pencil-fill"></i> ແກ້ໄຂ
              </button>
              <!-- ປຸ່ມລົບ -->
              <button class="btn-del" onclick="confirmDelete(<?= $u['user_id'] ?>, '<?= addslashes($u['fname']) ?>')">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- ຂໍ້ຄວາມ ຖ້າບໍ່ມີຂໍ້ມູນ -->
  <div id="emptyMsg" style="display:none; text-align:center; padding:28px; color:#94a3b8; font-size:13px;">
    <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px; opacity:.4;"></i>
    ບໍ່ພົບຂໍ້ມູນ
  </div>

</div>

<?php 
  // 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
  include_once('form/user_add.php'); 
  include_once('form/user_edit.php'); 
  include_once('footer.php'); 
?>