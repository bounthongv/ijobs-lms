<?php 
  // 1. ดึงส่วนหัวและเมนูมาแสดง
  include_once('header.php'); 
?>
<?php
// ============================================================
// ຂໍ້ມູນຕົວຢ່າງ Dashboard ເກົາຫຼີ (Static Data)
// ============================================================

// ສະຖິຕິລວມ
$stats = [
    'total'    => 342,
    'deployed' => 218,
    'pending'  => 89,
    'rejected' => 35,
];

// ປະເພດວຽກ
$jobTypes = [
    ['type' => 'ໂຮງງານ / ການຜະລິດ', 'count' => 142, 'pct' => 88, 'color' => '#3b82f6'],
    ['type' => 'ກໍ່ສ້າງ',             'count' => 78,  'pct' => 48, 'color' => '#22c55e'],
    ['type' => 'ກະສິກຳ',              'count' => 64,  'pct' => 40, 'color' => '#f59e0b'],
    ['type' => 'ບໍລິການ / ຮ້ານອາຫານ', 'count' => 38,  'pct' => 24, 'color' => '#8b5cf6'],
    ['type' => 'ອື່ນໆ',               'count' => 20,  'pct' => 12, 'color' => '#64748b'],
];

// ແນວໂນ້ມລາຍເດືອນ
$monthly = [
    ['month' => 'ມ.ກ', 'count' => 22],
    ['month' => 'ກ.ພ', 'count' => 28],
    ['month' => 'ມ.ນ', 'count' => 35],
    ['month' => 'ມ.ສ', 'count' => 42],
    ['month' => 'ພ.ສ', 'count' => 56],
    ['month' => 'ມ.ຖ', 'count' => 68],
];

// ລາຍຊື່ແຮງງານລ່າສຸດ
$workers = [
    ['name' => 'ສົມສຸດ ວົງສີ',   'age' => 26, 'gender' => 'ຊາຍ',   'job' => 'ໂຮງງານ',    'salary' => '2,500,000', 'date' => '18/06/2568', 'status' => 'ໄປແລ້ວ',   'sc' => 'success'],
    ['name' => 'ນາງ ມາລີ ຄຳ',    'age' => 24, 'gender' => 'ຍິງ',   'job' => 'ບໍລິການ',   'salary' => '2,200,000', 'date' => '17/06/2568', 'status' => 'ລໍຖ້າ',    'sc' => 'info'],
    ['name' => 'ພົມມາ ສີດາ',      'age' => 29, 'gender' => 'ຊາຍ',   'job' => 'ກໍ່ສ້າງ',  'salary' => '2,800,000', 'date' => '16/06/2568', 'status' => 'ລໍຖ້າ',    'sc' => 'info'],
    ['name' => 'ວິໄລ ແກ້ວ',      'age' => 22, 'gender' => 'ຍິງ',   'job' => 'ໂຮງງານ',    'salary' => '2,500,000', 'date' => '15/06/2568', 'status' => 'ຍົກເລີກ',  'sc' => 'danger'],
    ['name' => 'ທອງດີ ສີ',        'age' => 31, 'gender' => 'ຊາຍ',   'job' => 'ກະສິກຳ',    'salary' => '2,100,000', 'date' => '14/06/2568', 'status' => 'ໄປແລ້ວ',   'sc' => 'success'],
    ['name' => 'ບຸນມາ ລາດ',       'age' => 27, 'gender' => 'ຊາຍ',   'job' => 'ໂຮງງານ',    'salary' => '2,500,000', 'date' => '13/06/2568', 'status' => 'ໄປແລ້ວ',   'sc' => 'success'],
    ['name' => 'ນາງ ສຸກ ພອນ',     'age' => 25, 'gender' => 'ຍິງ',   'job' => 'ບໍລິການ',   'salary' => '2,200,000', 'date' => '12/06/2568', 'status' => 'ລໍຖ້າ',    'sc' => 'info'],
    ['name' => 'ຄຳຫຼ້າ ດວງດີ',   'age' => 33, 'gender' => 'ຊາຍ',   'job' => 'ກໍ່ສ້າງ',  'salary' => '2,800,000', 'date' => '11/06/2568', 'status' => 'ໄປແລ້ວ',   'sc' => 'success'],
];

// ກຳນົດສີ badge
function badge(string $sc): array {
    $map = [
        'success' => ['bg' => '#f0fdf4', 'color' => '#166534'],
        'info'    => ['bg' => '#eff6ff', 'color' => '#1d4ed8'],
        'danger'  => ['bg' => '#fef2f2', 'color' => '#991b1b'],
    ];
    return $map[$sc] ?? ['bg' => '#f8fafc', 'color' => '#64748b'];
}

// ກຽມ JSON ສຳລັບ Chart.js
$monthLabels = json_encode(array_column($monthly, 'month'));
$monthData   = json_encode(array_column($monthly, 'count'));
$jobLabels   = json_encode(array_column($jobTypes, 'type'));
$jobData     = json_encode(array_column($jobTypes, 'count'));
$jobColors   = json_encode(array_column($jobTypes, 'color'));
?>

<!-- ===================================================
     CSS: ເພີ່ມໃສ່ໃນ <style> ຫຼື <head> ຂອງ header.php
     =================================================== -->
<style>
  /* ===== Stat Card ===== */
  .stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.2rem 1.4rem;
    transition: box-shadow 0.2s;
    border-top: 3px solid #e2e8f0;
  }
  .stat-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.07); }
  .stat-card.b-blue   { border-top-color: #3b82f6; }
  .stat-card.b-green  { border-top-color: #22c55e; }
  .stat-card.b-orange { border-top-color: #f97316; }
  .stat-card.b-red    { border-top-color: #ef4444; }

  .stat-icon {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 19px;
    margin-bottom: 12px;
  }
  .stat-value { font-size: 28px; font-weight: 700; color: #0f172a; line-height: 1; margin-bottom: 4px; }
  .stat-label { font-size: 13px; color: #64748b; margin-bottom: 10px; }
  .stat-badge {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 12px; font-weight: 500;
    padding: 3px 8px; border-radius: 20px;
  }

  /* ===== Dash Card ===== */
  .dash-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem 1.4rem;
  }
  .dash-card-title {
    font-size: 14px; font-weight: 600; color: #1e293b;
    margin-bottom: 1rem;
    display: flex; align-items: center; gap: 8px;
  }

  /* ===== ແຖວ Progress Bar ປະເພດວຽກ ===== */
  .job-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
  }
  .job-row:last-child { border-bottom: none; }
  .job-name  { font-size: 13px; color: #334155; min-width: 160px; }
  .job-bar-bg { flex: 1; background: #f1f5f9; border-radius: 4px; height: 8px; overflow: hidden; }
  .job-bar-fill { height: 8px; border-radius: 4px; }
  .job-count { font-size: 13px; font-weight: 600; color: #0f172a; min-width: 34px; text-align: right; }

  /* ===== ຕາຕະລາງ ===== */
  .wk-table { width: 100%; font-size: 13px; border-collapse: collapse; }
  .wk-table thead tr { border-bottom: 2px solid #f1f5f9; }
  .wk-table thead th {
    padding: 8px 10px; font-size: 12px; font-weight: 600;
    color: #94a3b8; text-transform: uppercase;
    letter-spacing: 0.05em; text-align: left; white-space: nowrap;
  }
  .wk-table tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
  .wk-table tbody tr:hover { background: #f8fafc; }
  .wk-table tbody td { padding: 10px; color: #334155; vertical-align: middle; }
  .wk-table tbody tr:last-child { border-bottom: none; }

  .wk-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: #eff6ff; color: #3b82f6;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; flex-shrink: 0;
  }
  .wk-badge {
    display: inline-block; font-size: 11px; font-weight: 600;
    padding: 3px 10px; border-radius: 20px;
  }

  /* ===== ຫົວ Dashboard ===== */
  .kr-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.5rem; flex-wrap: wrap; gap: 12px;
  }
  .kr-title-wrap { display: flex; align-items: center; gap: 12px; }
  .kr-flag { width: 42px; height: 30px; object-fit: cover; border-radius: 5px; border: 1px solid #e2e8f0; }
  .kr-title { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0; }
  .kr-sub   { font-size: 13px; color: #94a3b8; margin: 3px 0 0; }
</style>

<!-- ===================================================
     HTML: ວາງໃສ່ໃນ <div class="container-fluid p-4">
     ຂອງໄຟລ໌ korea/index.php (ຕໍ່ຈາກ header)
     =================================================== -->

<!-- ===== ຫົວ Dashboard Japan ===== -->
<div class="kr-header">
  <div class="kr-title-wrap">
    <img src="https://flagcdn.com/w40/jp.png" class="kr-flag" alt="Japan">
    <div>
      <h5 class="kr-title">Dashboard Japan</h5>
      <p class="kr-sub"><i class="bi bi-calendar3 me-1"></i>ຂໍ້ມູນ ณ ວັນທີ 18 ມິຖຸນາ 2568 &nbsp;·&nbsp; <span class="text-warning fw-semibold">ຕົວຢ່າງ Static Data</span></p>
    </div>
  </div>
  <span class="badge px-3 py-2 fw-semibold" style="background:#fef2f2; color:#991b1b; font-size:13px;">
    <i class="bi bi-geo-alt-fill me-1"></i>ສາທາລະນະລັດ Japan
  </span>
</div>

<!-- ===== STAT CARDS ===== -->
<div class="row g-3 mb-4">

  <!-- ສະໝັກທັງໝົດ -->
  <div class="col-6 col-md-3">
    <div class="stat-card b-blue">
      <div class="stat-icon" style="background:#eff6ff;">
        <i class="bi bi-people-fill" style="color:#3b82f6;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['total']) ?></div>
      <div class="stat-label">ສະໝັກທັງໝົດ</div>
      <span class="stat-badge" style="background:#dbeafe; color:#1d4ed8;">
        <i class="bi bi-people"></i> ທັງໝົດ
      </span>
    </div>
  </div>

  <!-- ໄປເຮັດວຽກແລ້ວ -->
  <div class="col-6 col-md-3">
    <div class="stat-card b-green">
      <div class="stat-icon" style="background:#f0fdf4;">
        <i class="bi bi-airplane-fill" style="color:#22c55e;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['deployed']) ?></div>
      <div class="stat-label">ໄປເຮັດວຽກແລ້ວ</div>
      <span class="stat-badge" style="background:#dcfce7; color:#166534;">
        <i class="bi bi-arrow-up-short"></i>
        <?= round($stats['deployed'] / $stats['total'] * 100) ?>%
      </span>
    </div>
  </div>

  <!-- ລໍຖ້າດຳເນີນການ -->
  <div class="col-6 col-md-3">
    <div class="stat-card b-orange">
      <div class="stat-icon" style="background:#fff7ed;">
        <i class="bi bi-hourglass-split" style="color:#f97316;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['pending']) ?></div>
      <div class="stat-label">ລໍຖ້າດຳເນີນການ</div>
      <span class="stat-badge" style="background:#ffedd5; color:#9a3412;">
        <i class="bi bi-clock"></i>
        <?= round($stats['pending'] / $stats['total'] * 100) ?>%
      </span>
    </div>
  </div>

  <!-- ປະຕິເສດ/ຍົກເລີກ -->
  <div class="col-6 col-md-3">
    <div class="stat-card b-red">
      <div class="stat-icon" style="background:#fef2f2;">
        <i class="bi bi-x-circle-fill" style="color:#ef4444;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['rejected']) ?></div>
      <div class="stat-label">ປະຕິເສດ / ຍົກເລີກ</div>
      <span class="stat-badge" style="background:#fee2e2; color:#991b1b;">
        <i class="bi bi-arrow-down-short"></i>
        <?= round($stats['rejected'] / $stats['total'] * 100) ?>%
      </span>
    </div>
  </div>

</div>

<!-- ===== ROW 2: ປະເພດວຽກ + Donut ===== -->
<div class="row g-3 mb-4">

  <!-- ປະເພດວຽກ -->
  <div class="col-md-7">
    <div class="dash-card h-100">
      <div class="dash-card-title">
        <i class="bi bi-briefcase-fill text-primary"></i> ປະເພດວຽກ
      </div>
      <?php foreach ($jobTypes as $j): ?>
      <div class="job-row">
        <span class="job-name"><?= $j['type'] ?></span>
        <div class="job-bar-bg">
          <div class="job-bar-fill" style="width:<?= $j['pct'] ?>%; background:<?= $j['color'] ?>;"></div>
        </div>
        <span class="job-count"><?= number_format($j['count']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Donut Chart -->
  <div class="col-md-5">
    <div class="dash-card h-100">
      <div class="dash-card-title">
        <i class="bi bi-pie-chart-fill text-primary"></i> ເພດ
      </div>
      <div style="display:flex; align-items:center; gap:16px;">
        <div style="width:130px; height:130px; flex-shrink:0;">
          <canvas id="donutKr"
            role="img"
            aria-label="ສັດສ່ວນ: ໄປແລ້ວ <?= $stats['deployed'] ?>, ລໍຖ້າ <?= $stats['pending'] ?>, ຍົກເລີກ <?= $stats['rejected'] ?>">
          </canvas>
        </div>
        <div style="display:flex; flex-direction:column; gap:8px;">
          <?php
          // ລາຍການ Legend Donut
          $legends = [
            ['label' => 'ຊາຍ',   'val' => $stats['deployed'], 'c' => '#22c55e'],
            ['label' => 'ຍິງ',    'val' => $stats['pending'],  'c' => '#f97316'],
          ];
          foreach ($legends as $lg):
          ?>
          <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#334155;">
            <span style="width:10px; height:10px; border-radius:3px; background:<?= $lg['c'] ?>; display:inline-block; flex-shrink:0;"></span>
            <span><?= $lg['label'] ?></span>
            <span style="margin-left:auto; font-weight:700; color:#0f172a;"><?= number_format($lg['val']) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- ===== ROW 3: ກາຟລາຍເດືອນ ===== -->
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="dash-card">
      <div class="dash-card-title">
        <i class="bi bi-graph-up-arrow text-primary"></i> ແນວໂນ້ມການສະໝັກ (ປີ 2568)
      </div>
      <div style="position:relative; height:210px;">
        <canvas id="barKr" role="img" aria-label="ກາຟລາຍເດືອນ ການສະໝັກໄປເກົາຫຼີ"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- ===== END KOREA DASHBOARD ===== -->

<!-- ===== Chart.js ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// ===== Donut Chart ສະຖານະ =====
new Chart(document.getElementById('donutKr'), {
  type: 'doughnut',
  data: {
    labels: ['ໄປແລ້ວ', 'ລໍຖ້າ', 'ຍົກເລີກ'],
    datasets: [{
      data: [<?= $stats['deployed'] ?>, <?= $stats['pending'] ?>, <?= $stats['rejected'] ?>],
      backgroundColor: ['#22c55e', '#f97316', '#ef4444'],
      borderWidth: 3,
      borderColor: '#ffffff',
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '68%',
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(ctx) {
            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
            const pct   = Math.round((ctx.parsed / total) * 100);
            return ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString() + ' (' + pct + '%)';
          }
        }
      }
    }
  }
});

// ===== Bar Chart ລາຍເດືອນ =====
new Chart(document.getElementById('barKr'), {
  type: 'bar',
  data: {
    labels: <?= $monthLabels ?>,
    datasets: [{
      label: 'ຈຳນວນສະໝັກ',
      data: <?= $monthData ?>,
      backgroundColor: 'rgba(239,68,68,0.15)',
      borderColor: '#ef4444',
      borderWidth: 2,
      borderRadius: 6,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(ctx) { return ' ' + ctx.parsed.y.toLocaleString() + ' ຄົນ'; }
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: { autoSkip: false, font: { size: 13 } }
      },
      y: {
        beginAtZero: true,
        grid: { color: 'rgba(0,0,0,0.04)' },
        ticks: {
          font: { size: 12 },
          callback: function(v) { return v + ' ຄົນ'; }
        }
      }
    }
  }
});
</script>

<?php 
  // 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
  include_once('footer.php'); 
?>