<?php 
  // 1. ดึงส่วนหัวและเมนูมาแสดง
  include_once('../check.php');
  include_once('header.php'); 

  $all = $conn->prepare("SELECT COUNT(*) FROM users");
  $all->execute();
  $totalUsers = $all->fetch(PDO::FETCH_NUM)[0];
  $admin = $conn->prepare("SELECT COUNT(*) FROM users WHERE status = 'admin'");
  $admin->execute();
  $totalAdmin = $admin->fetch(PDO::FETCH_NUM)[0];

?>

<style>

  /* ===== Dash Card ===== */
  .dash-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.2rem 1.4rem; }
  .dash-card-title { font-size:14px; font-weight:600; color:#1e293b; margin-bottom:1rem; display:flex; align-items:center; gap:8px; }

  /* ===== Search / Filter Bar ===== */
  .filter-bar {
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 1rem;
  }
  .filter-bar input, .filter-bar select {
    font-size: 13px; padding: 7px 12px;
    border: 1px solid #e2e8f0; border-radius: 8px;
    color: #334155; outline: none;
    transition: border-color .15s;
  }
  .filter-bar input:focus, .filter-bar select:focus { border-color: #3b82f6; }
  .filter-bar input { min-width: 220px; }

  /* ===== ຕາຕະລາງ ===== */
  .usr-table { width:100%; font-size:13px; border-collapse:collapse; }
  .usr-table thead tr { border-bottom:2px solid #f1f5f9; }
  .usr-table thead th {
    padding:9px 10px; font-size:11px; font-weight:600;
    color:#94a3b8; text-transform:uppercase;
    letter-spacing:.05em; text-align:left; white-space:nowrap;
  }
  .usr-table tbody tr { border-bottom:1px solid #f8fafc; transition:background .15s; }
  .usr-table tbody tr:hover { background:#f8fafc; }
  .usr-table tbody td { padding:10px; color:#334155; vertical-align:middle; }
  .usr-table tbody tr:last-child { border-bottom:none; }

  /* ===== Avatar ===== */
  .usr-av {
    width:34px; height:34px; border-radius:50%;
    background:#eff6ff; color:#3b82f6;
    display:flex; align-items:center; justify-content:center;
    font-size:11px; font-weight:700; flex-shrink:0;
  }

  /* ===== Role Badge ===== */
  .role-badge {
    display:inline-flex; align-items:center; gap:5px;
    font-size:11px; font-weight:600;
    padding:3px 10px; border-radius:20px;
  }
  .role-admin { background:#ede9fe; color:#5b21b6; }
  .role-user  { background:#f0f9ff; color:#0369a1; }

  /* ===== Status Badge ===== */
  .status-badge { display:inline-block; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; }
  .status-active   { background:#f0fdf4; color:#166534; }
  .status-inactive { background:#f8fafc; color:#64748b; }

  /* ===== ປຸ່ມ Action ===== */
  .btn-edit {
    background:none; border:1px solid #e2e8f0; border-radius:7px;
    padding:5px 10px; font-size:12px; color:#3b82f6; cursor:pointer;
    transition:all .15s; display:inline-flex; align-items:center; gap:4px;
  }
  .btn-edit:hover { background:#eff6ff; border-color:#3b82f6; }

  .btn-del {
    background:none; border:1px solid #e2e8f0; border-radius:7px;
    padding:5px 10px; font-size:12px; color:#ef4444; cursor:pointer;
    transition:all .15s; display:inline-flex; align-items:center; gap:4px;
  }
  .btn-del:hover { background:#fef2f2; border-color:#ef4444; }

  /* ===== Modal ===== */
  .modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.45); z-index:2000;
    align-items:center; justify-content:center;
  }
  .modal-overlay.show { display:flex; }

  .modal-box {
    background:#fff; border-radius:14px;
    width:100%; max-width:440px; margin:16px;
    box-shadow:0 20px 40px rgba(0,0,0,.15);
    overflow:hidden;
    animation:slideUp .2s ease;
  }
  @keyframes slideUp {
    from { transform:translateY(20px); opacity:0; }
    to   { transform:translateY(0);    opacity:1; }
  }

  .modal-head {
    background:#f8fafc; border-bottom:1px solid #e2e8f0;
    padding:14px 20px; display:flex; align-items:center; justify-content:space-between;
  }
  .modal-head-title { font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px; }

  .modal-btn-close {
    background:none; border:none; font-size:20px;
    color:#94a3b8; cursor:pointer; line-height:1;
    padding:2px 6px; border-radius:6px; transition:all .15s;
  }
  .modal-btn-close:hover { background:#f1f5f9; color:#334155; }

  .modal-body { padding:20px; }

  .form-group { margin-bottom:14px; }
  .form-label { font-size:12px; font-weight:600; color:#475569; margin-bottom:5px; display:block; }

  .form-control-custom {
    width:100%; padding:9px 12px; font-size:13px;
    border:1px solid #e2e8f0; border-radius:8px;
    color:#334155; outline:none; transition:border-color .15s;
    box-sizing:border-box;
  }
  .form-control-custom:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
  .form-control-custom:disabled { background:#f8fafc; color:#94a3b8; }

  /* ===== Role Toggle ===== */
  .role-toggle { display:flex; gap:8px; }
  .role-opt {
    flex:1; padding:10px; border:2px solid #e2e8f0; border-radius:10px;
    text-align:center; cursor:pointer; transition:all .15s;
    font-size:13px; font-weight:500; color:#64748b;
  }
  .role-opt:hover { border-color:#8b5cf6; color:#5b21b6; }
  .role-opt.selected-admin { border-color:#8b5cf6; background:#ede9fe; color:#5b21b6; }
  .role-opt.selected-user  { border-color:#3b82f6; background:#eff6ff; color:#1d4ed8; }

  /* ===== Status Toggle ===== */
  .toggle-wrap { display:flex; align-items:center; gap:10px; }
  .toggle-switch {
    position:relative; width:44px; height:24px;
    background:#e2e8f0; border-radius:12px; cursor:pointer;
    transition:background .2s; flex-shrink:0;
  }
  .toggle-switch.on { background:#22c55e; }
  .toggle-switch::after {
    content:''; position:absolute;
    top:3px; left:3px;
    width:18px; height:18px;
    background:#fff; border-radius:50%;
    transition:transform .2s;
    box-shadow:0 1px 3px rgba(0,0,0,.2);
  }
  .toggle-switch.on::after { transform:translateX(20px); }
  .toggle-label { font-size:13px; font-weight:600; color:#334155; }

  .modal-foot {
    padding:14px 20px; border-top:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:flex-end; gap:8px;
  }
  .btn-cancel {
    padding:8px 16px; border:1px solid #e2e8f0; border-radius:8px;
    background:#fff; font-size:13px; color:#64748b; cursor:pointer;
    transition:all .15s;
  }
  .btn-cancel:hover { background:#f8fafc; }

  .btn-save {
    padding:8px 20px; border:none; border-radius:8px;
    background:#3b82f6; color:#fff; font-size:13px;
    font-weight:600; cursor:pointer; transition:background .15s;
    display:flex; align-items:center; gap:6px;
  }
  .btn-save:hover { background:#2563eb; }

  /* ===== Empty row ===== */
  .empty-row td {
    text-align:center; padding:30px; color:#94a3b8; font-size:13px;
  } */
</style>

<!-- ============================================================
     HTML
     ============================================================ -->

<!-- ===== ຫົວ ===== -->
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
  <div>
    <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
      <i class="bi bi-people-fill me-2 text-primary"></i>ຈັດການຜູ້ໃຊ້ລະບົບ
    </h5>
  </div>
  <!-- <button class="btn btn-primary btn-sm px-3 py-2"
          onclick="openModal('add')"
          style="font-size:13px; font-weight:600; border-radius:8px;">
    <i class="bi bi-plus-lg me-1"></i> ເພີ່ມ User ໃໝ່
  </button> -->
</div>

<!-- ===== STAT CARDS ===== -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card b-blue">
      <div class="stat-icon" style="background:#eff6ff;">
        <i class="bi bi-people-fill" style="color:#3b82f6;"></i>
      </div>
      <div class="stat-value"><?= $totalUsers ?></div>
      <div class="stat-label">User ທັງໝົດ</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card b-purple">
      <div class="stat-icon" style="background:#ede9fe;">
        <i class="bi bi-shield-fill-check" style="color:#8b5cf6;"></i>
      </div>
      <div class="stat-value"><?= $totalAdmin ?></div>
      <div class="stat-label">Admin</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card b-green">
      <div class="stat-icon" style="background:#f0fdf4;">
        <i class="bi bi-check-circle-fill" style="color:#22c55e;"></i>
      </div>
      <div class="stat-value"><?= $totalUsers ?></div>
      <div class="stat-label">ໃຊ້ງານຢູ່</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card b-slate">
      <div class="stat-icon" style="background:#f8fafc;">
        <i class="bi bi-x-circle-fill" style="color:#94a3b8;"></i>
      </div>
      <div class="stat-value">0</div>
      <div class="stat-label">ປິດການໃຊ້ງານ</div>
    </div>
  </div>
</div>



<?php 
  // 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
  include_once('footer.php'); 
?>