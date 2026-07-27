<?php 
  include_once('../check.php');
  if (!defined('BASE_URLS')) {
    define('BASE_URLS', 'https://job.apis.com.la/file/');
    define('BASE_URLSS', 'https://job.apis.com.la/file/datalist/');
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= BASE_URLSS ?>css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>">
  <?php   include_once('link.php');  ?>
  
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
      <a href="<?= BASE_URLS ?>" class="nav-item"><span class="nav-text">Dashboard</span></a>
      <a href="<?= BASE_URLS ?>korea/" class="nav-item "><img src="https://flagcdn.com/w40/kr.png" class="nav-flag"><span class="nav-text">Korea</span></a>
      <a href="<?= BASE_URLS ?>thai/" class="nav-item "><img src="https://flagcdn.com/w40/th.png" class="nav-flag"><span class="nav-text">Thailand</span></a>
      <a href="<?= BASE_URLS ?>japan/" class="nav-item "><img src="https://flagcdn.com/w40/jp.png" class="nav-flag"><span class="nav-text">Japan</span></a>
      <a href="<?= BASE_URLS ?>china/" class="nav-item"><img src="https://flagcdn.com/w40/cn.png" class="nav-flag"><span class="nav-text">China</span></a>
      <!-- ===== เมนูใหม่: Data List (มี dropdown) ===== -->
      <button class="nav-item active" type="button" data-bs-toggle="collapse" data-bs-target="#dataListCollapse" aria-expanded="false" aria-controls="dataListCollapse">
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

  <div class="container-fluid p-2">