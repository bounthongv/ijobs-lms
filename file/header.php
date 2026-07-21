<?php 
  include_once('check.php');
  if (!defined('BASE_URLS')) {
    define('BASE_URLS', 'https://job.apis.com.la/file/');
    define('BASE_URLSS', 'https://job.apis.com.la/file/');
  }
?>
<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JOB</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --theme-dark:    #1e40af; 
      --theme-mid:     #1a3696; 
      --theme-blue:    #1e40af; 
      --theme-light:   #eef3ff; 
      --theme-border:  #e5e9f2; 
      --theme-text:    #1e40af; 
      --sidebar-w:     280px;
      --sidebar-collapsed-w: 70px;
    }

    body {
      background: #f7f9fc; 
      font-family: 'Noto Sans Lao', 'Segoe UI', sans-serif;
      overflow-x: hidden;
    }

    /* ===== SIDEBAR (LEFT MENU) ===== */
    #sidebar {
      width: var(--sidebar-w);
      height: 100vh;
      background: var(--theme-dark);
      position: fixed;
      top: 0; left: 0;
      z-index: 1040;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      transition: width 0.3s ease, transform 0.3s ease;
    }

    .logo-area {
      background: var(--theme-dark); 
      padding: 15px 16px;
      border-bottom: 1px solid rgba(255,255,255,0.12);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      gap: 12px;
      height: 60px;
    }

    .logo-icon {
      width: 38px; height: 38px;
      background: var(--theme-blue);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }

    .logo-title {
      color: #fff;
      font-size: 20px;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .sidebar-menu-scroll {
      flex: 1 1 auto;
      min-height: 0;
      overflow-y: auto;
      overflow-x: hidden;
      padding: 12px 0;
    }

    .nav-label {
      color: #93a8dd;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 16px 8px;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 15px; 
      padding: 12px 20px;
      color: #c3d2f5;
      font-size: 15px;
      cursor: pointer;
      border-left: 3px solid transparent;
      text-decoration: none;
      transition: all 0.15s;
      width: 100%;
      background: none;
      border-top: 0; border-right: 0; border-bottom: 0;
      text-align: left;
      font-family: inherit;
      white-space: nowrap;
    }

    .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
    .nav-item.active {
      background: rgba(255,255,255,0.16);
      color: #fff;
      border-radius: 10px;
      margin: 2px 12px;
      border-left-color: transparent;
    }

    .nav-flag {
      width: 24px; height: 18px;
      object-fit: cover;
      border-radius: 3px;
      flex-shrink: 0;
      box-shadow: 0 0 0 1px rgba(255,255,255,0.15);
    }

    .sidebar-footer {
      padding: 10px 0;
      border-top: 1px solid rgba(255,255,255,0.12);
    }

    body.sidebar-collapsed #sidebar { width: var(--sidebar-collapsed-w); }
    body.sidebar-collapsed #sidebar .logo-title,
    body.sidebar-collapsed #sidebar .nav-label,
    body.sidebar-collapsed #sidebar .nav-text { display: none !important; }
    body.sidebar-collapsed #main-content { margin-left: var(--sidebar-collapsed-w); }

    #main-content {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      transition: margin-left 0.3s ease;
    }

    .topbar {
      background: #ffffff; 
      border-bottom: 1px solid #eef1f6; 
      height: 60px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: space-between; 
      position: sticky;
      top: 0;
      z-index: 1030;
    }

    .topbar-left { display: flex; align-items: center; padding-left: 20px; height: 100%; }

    .btn-hamburger {
      background: none; border: none; color: #64748b; font-size: 24px;
      cursor: pointer; padding: 6px 10px; border-radius: 6px;
      display: flex; align-items: center; transition: all 0.2s;
    }
    .btn-hamburger:hover { background: var(--theme-light); color: var(--theme-blue); }

    .system-menu-tabs { display: flex; align-items: center; height: 100%; }
    .system-menu-tabs .dropdown { height: 100%; display: flex; align-items: center; }

    .tab-btn {
      background: transparent; border: none; color: #64748b; font-size: 14px; font-weight: 500;
      padding: 0 20px; height: 100%; display: flex; align-items: center; gap: 8px;
      cursor: pointer; text-decoration: none; transition: all 0.15s; white-space: nowrap;
    }
    .tab-btn:hover, .tab-btn:focus { color: var(--theme-blue); background: var(--theme-light); }
    .tab-btn.active { background: var(--theme-blue); color: #fff; }
    .tab-btn::after { display: none; }

    .tab-badge { background: #ef4444; color: #fff; font-size: 11px; font-weight: 600; padding: 1px 6px; border-radius: 10px; }
    .tab-badge.badge-green { background: #10b981; }

    .custom-dropdown-menu {
      border-radius: 10px !important;
      border: 1px solid var(--theme-border) !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1) !important;
      margin-top: 0px !important;
    }

    #sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1039; }

    @media (max-width: 768px) {
      #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); width: var(--sidebar-w) !important; }
      #sidebar.open { transform: translateX(0); }
      #main-content { margin-left: 0 !important; }
      body.sidebar-collapsed #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
      #sidebar-overlay.show { display: block; }
    }

    @media (min-width: 769px) {
      #sidebar { transform: translateX(0) !important; }
      #sidebar-overlay { display: none !important; }
    }
    /* ===== ເສັ້ນຂັ້ນລະຫວ່າງໂລໂກ້ ແລະ ລາຍຊື່ Online ===== */
  .logo-sep {
    width: 1px;
    height: 28px;
    background: rgba(255,255,255,0.18);
    flex-shrink: 0;
    margin: 0 4px;
  }
 
  /* ===== ກ່ອງ Chip ຊື່ຜູ້ໃຊ້ Online ===== */
  .online-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 4px 10px;
    flex-shrink: 0;
    white-space: nowrap;
  }
 
  .chip-dot {
    width: 6px;
    height: 6px;
    background: #22c55e;
    border-radius: 50%;
    flex-shrink: 0;
    animation: pulse-dot 2s infinite;
  }
 
  @keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 0 2px rgba(34,197,94,0.25); }
    50%       { box-shadow: 0 0 0 5px rgba(34,197,94,0); }
  }
 
  .chip-name { font-size: 12px; font-weight: 600; color: #ffffff; line-height: 1; }
  .chip-role { font-size: 10px; color: #a9bcec; line-height: 1; margin-top: 2px; }
 
  /* ===== ປຸ່ມ +N Online ===== */
  .more-online {
    display: flex;
    align-items: center;
    gap: 5px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 20px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #ffffff;
    flex-shrink: 0;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.15s;
  }
  .more-online:hover { background: rgba(255,255,255,0.18); }
  .nav-item.d-flex-caret,
button.nav-item {
  justify-content: space-between;
}

.nav-caret { transition: transform 0.2s ease; font-size: 12px; }
button.nav-item[aria-expanded="true"] .nav-caret { transform: rotate(180deg); }

.nav-subitem {
  padding-left: 52px;
  font-size: 13.5px;
  color: #a9bcec;
}
.nav-subitem:hover { color: #ffffff; }
  </style>
</head>
<body>

<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<nav id="sidebar">
  <div class="logo-area">
    <div class="logo-icon">
      <i class="bi bi-briefcase-fill" style="color:#fff; font-size:18px;"></i>
    </div>
    <div class="logo-title">JOB</div>
  
    <!-- ເສັ້ນຂັ້ນ -->
    <div class="logo-sep"></div>
    <div class="online-chip">
      <span class="chip-dot"></span>
      <div>
        <div class="chip-name"><?= $_SESSION['fname']; ?></div>
        <div class="chip-role"><?= $_SESSION['status']; ?></div>
      </div>
    </div>
  </div>

  <div class="sidebar-menu-scroll">
    <div style="padding: 8px 0;">
      <a href="<?= BASE_URLS ?>" class="nav-item active"><span class="nav-text">Dashboard</span></a>
      <a href="<?= BASE_URLS ?>korea/" class="nav-item "><img src="https://flagcdn.com/w40/kr.png" class="nav-flag"><span class="nav-text">Korea</span></a>
      <a href="<?= BASE_URLS ?>thai/" class="nav-item"><img src="https://flagcdn.com/w40/th.png" class="nav-flag"><span class="nav-text">Thailand</span></a>
      <a href="<?= BASE_URLS ?>japan/" class="nav-item"><img src="https://flagcdn.com/w40/jp.png" class="nav-flag"><span class="nav-text">Japan</span></a>
      <a href="<?= BASE_URLS ?>china/" class="nav-item"><img src="https://flagcdn.com/w40/cn.png" class="nav-flag"><span class="nav-text">China</span></a>
      <!-- ===== เมนูใหม่: Data List (มี dropdown) ===== -->
      <button class="nav-item" type="button" data-bs-toggle="collapse" data-bs-target="#dataListCollapse" aria-expanded="false" aria-controls="dataListCollapse">
        <i class="bi bi-folder2-open"></i>
        <span class="nav-text flex-grow-1">Data List</span>
        <i class="bi bi-chevron-down nav-caret"></i>
      </button>
      <div class="collapse" id="dataListCollapse">
        <a href="<?= BASE_URLS ?>datalist/list_pro.php" class="nav-item nav-subitem">
          <span class="nav-text">Lao Province</span>
        </a>
        <a href="<?= BASE_URLS ?>datalist/list_dis.php" class="nav-item nav-subitem">
          <span class="nav-text">Lao District</span>
        </a>
        <a href="<?= BASE_URLS ?>datalist/list_vill.php" class="nav-item nav-subitem">
          <span class="nav-text">Lao Village</span>
        </a>
      </div>
      <!-- ===== จบเมนู Data List ===== -->
      <a href="<?= BASE_URLS ?>user/" class="nav-item"><img src="https://th.bing.com/th/id/R.b2b34517339101a111716be1c203f354?rik=e5WHTShSpipi3Q&pid=ImgRaw&r=0" class="nav-flag"><span class="nav-text">Users</span></a>
    </div>
  </div>

  <div class="sidebar-footer">
    <a href="#" class="nav-item" id="logout"><i class="bi bi-box-arrow-left"></i> <span class="nav-text">Logout</span></a>
  </div>
</nav>

<div id="main-content">
  <header class="topbar">
    <div class="topbar-left">
      <button class="btn-hamburger" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
    </div>

    
  </header>

  <div class="container-fluid p-4">