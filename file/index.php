<?php 
  // 1. ดึงส่วนหัวและเมนูมาแสดง
  include_once('header.php'); 
?>
<?php
// ============================================================
// ຂໍ້ມູນຕົວຢ່າງ (Static Data) - ຍັງບໍ່ໄດ້ດຶງຈາກຖານຂໍ້ມູນ
// ============================================================

// ສະຖິຕິລວມ
$stats = [
    'total'    => 1284,
    'deployed' => 892,
    'pending'  => 247,
    'rejected' => 145,
];

// ແຮງງານແຍກຕາມປະເທດ
$byCountry = [
    ['name' => 'ເກົາຫຼີ',     'flag' => 'kr', 'count' => 342, 'pct' => 85],
    ['name' => 'ໄທ',          'flag' => 'th', 'count' => 264, 'pct' => 65],
    ['name' => 'ຍີ່ປຸ່ນ',     'flag' => 'jp', 'count' => 210, 'pct' => 52],
    ['name' => 'ຈີນ',         'flag' => 'cn', 'count' => 152, 'pct' => 37],
    ['name' => 'ເຢຍລະມັນ',   'flag' => 'de', 'count' => 88,  'pct' => 22],
];

// ແນວໂນ້ມລາຍເດືອນ
$monthly = [
    ['month' => 'ມ.ກ', 'count' => 95],
    ['month' => 'ກ.ພ', 'count' => 112],
    ['month' => 'ມ.ນ', 'count' => 138],
    ['month' => 'ມ.ສ', 'count' => 156],
    ['month' => 'ພ.ສ', 'count' => 178],
    ['month' => 'ມ.ຖ', 'count' => 203],
];

// ລາຍການລ່າສຸດ
$recentList = [
    ['name' => 'ສົມສຸດ ວົງສີ',  'country' => 'ເກົາຫຼີ', 'flag' => 'kr', 'date' => '18/06/2568', 'status' => 'ໄປແລ້ວ',  'status_class' => 'success'],
    ['name' => 'ນາງ ມາລີ ຄຳ',   'country' => 'ໄທ',      'flag' => 'th', 'date' => '17/06/2568', 'status' => 'ລໍຖ້າ',   'status_class' => 'info'],
    ['name' => 'ພົມມາ ສີດາ',     'country' => 'ຍີ່ປຸ່ນ', 'flag' => 'jp', 'date' => '16/06/2568', 'status' => 'ລໍຖ້າ',   'status_class' => 'info'],
    ['name' => 'ວິໄລ ແກ້ວ',     'country' => 'ຈີນ',     'flag' => 'cn', 'date' => '15/06/2568', 'status' => 'ຍົກເລີກ', 'status_class' => 'danger'],
    ['name' => 'ທອງດີ ສີ',       'country' => 'ເກົາຫຼີ', 'flag' => 'kr', 'date' => '14/06/2568', 'status' => 'ໄປແລ້ວ',  'status_class' => 'success'],
    ['name' => 'ບຸນມາ ລາດ',      'country' => 'ເຢຍລະມັນ','flag' => 'de', 'date' => '13/06/2568', 'status' => 'ລໍຖ້າ',   'status_class' => 'info'],
    ['name' => 'ນາງ ສຸກ ພອນ',    'country' => 'ໄທ',      'flag' => 'th', 'date' => '12/06/2568', 'status' => 'ໄປແລ້ວ',  'status_class' => 'success'],
];

// ກຳນົດສີ badge ຕາມສະຖານະ
function getStatusBadge(string $class): array {
    // ສີພື້ນຫຼັງ ແລະ ສີຕົວໜັງສືຂອງ badge
    $map = [
        'success' => ['bg' => '#eaf3de', 'color' => '#3b6d11'],
        'info'    => ['bg' => '#e6f1fb', 'color' => '#185fa5'],
        'danger'  => ['bg' => '#fcebeb', 'color' => '#a32d2d'],
    ];
    return $map[$class] ?? ['bg' => '#f1efe8', 'color' => '#5f5e5a'];
}

// ກຽມ JSON ສຳລັບ Chart.js
$monthLabels = json_encode(array_column($monthly, 'month'));
$monthData   = json_encode(array_column($monthly, 'count'));
?>

<!-- ===== DASHBOARD CONTENT ===== -->
<style>
  /* ===== ກຳນົດສີ ແລະ ຮູບແບບ stat card ===== */
  .stat-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.2rem 1.4rem;
    transition: box-shadow 0.2s;
    border-top: 3px solid #e2e8f0;
  }
  .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.07); }

  /* ===== ສີຂອບດ້ານເທິງແຕ່ລະ Card ===== */
  .stat-card.border-blue   { border-top-color: #3b82f6; }
  .stat-card.border-green  { border-top-color: #22c55e; }
  .stat-card.border-orange { border-top-color: #f97316; }
  .stat-card.border-red    { border-top-color: #ef4444; }

  .stat-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 12px;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 10px;
  }

  .stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 20px;
  }

  /* ===== ກ່ອງ card ທົ່ວໄປ ===== */
  .dash-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem 1.4rem;
  }

  .dash-card-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* ===== ແຖວ Progress Bar ປະເທດ ===== */
  .country-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 0;
    border-bottom: 1px solid #f1f5f9;
  }
  .country-row:last-child { border-bottom: none; }

  .flag-img {
    width: 28px; height: 19px;
    object-fit: cover;
    border-radius: 3px;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
  }

  .country-name {
    font-size: 13px;
    color: #334155;
    min-width: 70px;
  }

  .progress-bg {
    flex: 1;
    background: #f1f5f9;
    border-radius: 4px;
    height: 8px;
    overflow: hidden;
  }

  .progress-fill {
    height: 8px;
    border-radius: 4px;
    transition: width 0.6s ease;
  }

  .country-count {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    min-width: 34px;
    text-align: right;
  }

  /* ===== ຕາຕະລາງລາຍການລ່າສຸດ ===== */
  .recent-table { width: 100%; font-size: 13px; border-collapse: collapse; }
  .recent-table thead tr { border-bottom: 2px solid #f1f5f9; }
  .recent-table thead th {
    padding: 8px 10px;
    font-size: 12px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-align: left;
  }
  .recent-table tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.15s; }
  .recent-table tbody tr:hover { background: #f8fafc; }
  .recent-table tbody td { padding: 11px 10px; color: #334155; vertical-align: middle; }
  .recent-table tbody tr:last-child { border-bottom: none; }

  .status-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
  }

  /* ===== ຫົວ Dashboard ===== */
  .dash-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 12px;
  }

  .dash-title { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0; }
  .dash-sub   { font-size: 13px; color: #94a3b8; margin: 4px 0 0; }

  /* ===== ລວມ Donut ===== */
  .donut-legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
    justify-content: center;
    flex: 1;
  }
  .donut-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #334155;
  }
  .donut-legend-dot {
    width: 10px; height: 10px;
    border-radius: 3px;
    flex-shrink: 0;
  }
  .donut-legend-count {
    margin-left: auto;
    font-weight: 600;
    color: #0f172a;
  }
</style>

<!-- ===== ຫົວ Dashboard ===== -->
<div class="dash-header">
  <div>
    <h5 class="dash-title"><i class="bi bi-speedometer2 me-2 text-primary"></i>ພາບລວມລະບົບແຮງງານ</h5>
    <p class="dash-sub"><i class="bi bi-calendar3 me-1"></i>ຂໍ້ມູນ ณ ວັນທີ 18 ມິຖຸນາ 2568 &nbsp;·&nbsp; <span class="text-warning">ຕົວຢ່າງ Static Data</span></p>
  </div>
  <div>
    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2">
      <i class="bi bi-bar-chart-fill me-1"></i>ປີ 2568
    </span>
  </div>
</div>

<!-- ===== STAT CARDS ===== -->
<div class="row g-3 mb-4">

  <!-- ສະໝັກທັງໝົດ -->
  <div class="col-6 col-md-3">
    <div class="stat-card border-blue">
      <div class="stat-icon" style="background:#eff6ff;">
        <i class="bi bi-people-fill" style="color:#3b82f6;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['total']) ?></div>
      <div class="stat-label">ສະໝັກທັງໝົດ</div>
      <span class="stat-badge" style="background:#dcfce7; color:#166534;">
        <i class="bi bi-arrow-up-short"></i>+12%
      </span>
    </div>
  </div>

  <!-- ໄປເຮັດວຽກແລ້ວ -->
  <div class="col-6 col-md-3">
    <div class="stat-card border-green">
      <div class="stat-icon" style="background:#f0fdf4;">
        <i class="bi bi-airplane-fill" style="color:#22c55e;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['deployed']) ?></div>
      <div class="stat-label">ໄປເຮັດວຽກແລ້ວ</div>
      <span class="stat-badge" style="background:#dcfce7; color:#166534;">
        <i class="bi bi-arrow-up-short"></i>+8%
      </span>
    </div>
  </div>

  <!-- ລໍຖ້າດຳເນີນການ -->
  <div class="col-6 col-md-3">
    <div class="stat-card border-orange">
      <div class="stat-icon" style="background:#fff7ed;">
        <i class="bi bi-hourglass-split" style="color:#f97316;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['pending']) ?></div>
      <div class="stat-label">ລໍຖ້າດຳເນີນການ</div>
      <span class="stat-badge" style="background:#fee2e2; color:#991b1b;">
        <i class="bi bi-arrow-down-short"></i>-3%
      </span>
    </div>
  </div>

  <!-- ປະຕິເສດ/ຍົກເລີກ -->
  <div class="col-6 col-md-3">
    <div class="stat-card border-red">
      <div class="stat-icon" style="background:#fef2f2;">
        <i class="bi bi-x-circle-fill" style="color:#ef4444;"></i>
      </div>
      <div class="stat-value"><?= number_format($stats['rejected']) ?></div>
      <div class="stat-label">ປະຕິເສດ / ຍົກເລີກ</div>
      <span class="stat-badge" style="background:#fee2e2; color:#991b1b;">
        <i class="bi bi-arrow-down-short"></i>-5%
      </span>
    </div>
  </div>

</div>

<!-- ===== ROW 2: ປະເທດ + Donut ===== -->
<div class="row g-3 mb-4">

  <!-- ແຮງງານແຍກຕາມປະເທດ -->
  <div class="col-md-7">
    <div class="dash-card h-100">
      <div class="dash-card-title">
        <i class="bi bi-flag-fill text-primary"></i> ແຮງງານແຍກຕາມປະເທດ
      </div>

      <?php
      // ສີ Progress Bar ແຕ່ລະປະເທດ
      $barColors = ['#3b82f6','#22c55e','#f59e0b','#ef4444','#8b5cf6'];
      foreach ($byCountry as $i => $c):
        $color = $barColors[$i] ?? '#64748b';
      ?>
      <div class="country-row">
        <img src="https://flagcdn.com/w40/<?= $c['flag'] ?>.png" class="flag-img" alt="<?= $c['name'] ?>">
        <span class="country-name"><?= $c['name'] ?></span>
        <div class="progress-bg">
          <div class="progress-fill" style="width:<?= $c['pct'] ?>%; background:<?= $color ?>;"></div>
        </div>
        <span class="country-count"><?= number_format($c['count']) ?></span>
      </div>
      <?php endforeach; ?>

    </div>
  </div>

  <!-- Donut Chart ສັດສ່ວນສະຖານະ -->
  <div class="col-md-5">
    <div class="dash-card h-100">
      <div class="dash-card-title">
        <i class="bi bi-pie-chart-fill text-primary"></i> ສັດສ່ວນສະຖານະ
      </div>

      <div style="display:flex; align-items:center; gap:20px;">
        <!-- ກາຟ Donut -->
        <div style="width:140px; height:140px; flex-shrink:0;">
          <canvas id="donutChart"
            role="img"
            aria-label="ກາຟສັດສ່ວນສະຖານະ: ໄປແລ້ວ <?= $stats['deployed'] ?>, ລໍຖ້າ <?= $stats['pending'] ?>, ຍົກເລີກ <?= $stats['rejected'] ?>">
          </canvas>
        </div>

        <!-- Legend -->
        <div class="donut-legend">
          <div class="donut-legend-item">
            <span class="donut-legend-dot" style="background:#22c55e;"></span>
            <span>ໄປແລ້ວ</span>
            <span class="donut-legend-count"><?= number_format($stats['deployed']) ?></span>
          </div>
          <div class="donut-legend-item">
            <span class="donut-legend-dot" style="background:#3b82f6;"></span>
            <span>ລໍຖ້າ</span>
            <span class="donut-legend-count"><?= number_format($stats['pending']) ?></span>
          </div>
          <div class="donut-legend-item">
            <span class="donut-legend-dot" style="background:#ef4444;"></span>
            <span>ຍົກເລີກ</span>
            <span class="donut-legend-count"><?= number_format($stats['rejected']) ?></span>
          </div>
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
      <div style="position:relative; height:220px;">
        <canvas id="barChart"
          role="img"
          aria-label="ກາຟລາຍເດືອນ ຈຳນວນຜູ້ສະໝັກ ມັງກອນ ຫາ ມິຖຸນາ">
        </canvas>
      </div>
    </div>
  </div>
</div>

<!-- ===== ROW 4: ຕາຕະລາງລາຍການລ່າສຸດ ===== -->
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="dash-card">
      <div class="dash-card-title">
        <i class="bi bi-clock-history text-primary"></i> ລາຍການລ່າສຸດ
        <span class="ms-auto badge bg-primary bg-opacity-10 text-primary fw-semibold">
          <?= count($recentList) ?> ລາຍການ
        </span>
      </div>

      <div class="table-responsive">
        <table class="recent-table">
          <thead>
            <tr>
              <th>#</th>
              <th>ຊື່-ນາມສະກຸນ</th>
              <th>ປະເທດ</th>
              <th>ວັນທີສະໝັກ</th>
              <th>ສະຖານະ</th>
              <th class="text-end">ຈັດການ</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentList as $i => $row):
              // ດຶງສີ badge ຕາມສະຖານະ
              $badge = getStatusBadge($row['status_class']);
            ?>
            <tr>
              <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>
              <td>
                <!-- ວົງກົມຕົວໜັງສືຕ້ນ -->
                <div style="display:flex; align-items:center; gap:8px;">
                  <div style="
                    width:32px; height:32px; border-radius:50%;
                    background:#eff6ff; color:#3b82f6;
                    display:flex; align-items:center; justify-content:center;
                    font-size:11px; font-weight:700; flex-shrink:0;">
                    <?= mb_substr($row['name'], 0, 1, 'UTF-8') ?>
                  </div>
                  <span style="font-weight:500; color:#1e293b;"><?= htmlspecialchars($row['name']) ?></span>
                </div>
              </td>
              <td>
                <!-- ທຸງຊາດ + ຊື່ປະເທດ -->
                <div style="display:flex; align-items:center; gap:6px;">
                  <img src="https://flagcdn.com/w40/<?= $row['flag'] ?>.png"
                       style="width:22px; height:15px; object-fit:cover; border-radius:2px; border:1px solid #e2e8f0;"
                       alt="<?= $row['country'] ?>">
                  <?= htmlspecialchars($row['country']) ?>
                </div>
              </td>
              <td style="color:#64748b;">
                <i class="bi bi-calendar3 me-1"></i><?= $row['date'] ?>
              </td>
              <td>
                <span class="status-badge"
                      style="background:<?= $badge['bg'] ?>; color:<?= $badge['color'] ?>;">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-primary btn-sm px-2 py-1" style="font-size:12px;">
                  <i class="bi bi-eye me-1"></i>ລາຍລະອຽດ
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
<!-- ===== END DASHBOARD CONTENT ===== -->

<!-- ===== Chart.js ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// ===== ກາຟ Donut ສະຖານະ =====
new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: ['ໄປແລ້ວ', 'ລໍຖ້າ', 'ຍົກເລີກ'],
    datasets: [{
      data: [<?= $stats['deployed'] ?>, <?= $stats['pending'] ?>, <?= $stats['rejected'] ?>],
      backgroundColor: ['#22c55e', '#3b82f6', '#ef4444'],
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
          // ສະແດງຈຳນວນ ແລະ ເປີເຊັນ
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

// ===== ກາຟ Bar ລາຍເດືອນ =====
new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: <?= $monthLabels ?>,
    datasets: [{
      label: 'ຈຳນວນສະໝັກ',
      data: <?= $monthData ?>,
      backgroundColor: 'rgba(59,130,246,0.18)',
      borderColor:     '#3b82f6',
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
          // ເພີ່ມຄຳວ່າ "ຄົນ" ຫຼັງຕົວເລກ
          label: function(ctx) {
            return ' ' + ctx.parsed.y.toLocaleString() + ' ຄົນ';
          }
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
        grid: { color: 'rgba(0,0,0,0.05)' },
        ticks: {
          font: { size: 12 },
          // ເພີ່ມຄຳວ່າ "ຄົນ" ທີ່ແກນ Y
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