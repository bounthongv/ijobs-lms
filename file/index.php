<?php
include_once('header.php');

// ກຳນົດຂໍ້ມູນຕົວຢ່າງສຳລັບ Dashboard
$stats = [
    'total' => 1248,
    'pending' => 86,
    'approved' => 972,
    'recent' => 34,
];

// ກຳນົດຂໍ້ມູນກາຟພາບລວມລາຍເດືອນ
$monthly = [
    ['month' => 'Jan', 'total' => 78, 'new' => 38],
    ['month' => 'Feb', 'total' => 94, 'new' => 46],
    ['month' => 'Mar', 'total' => 121, 'new' => 62],
    ['month' => 'Apr', 'total' => 108, 'new' => 51],
    ['month' => 'May', 'total' => 138, 'new' => 68],
    ['month' => 'Jun', 'total' => 158, 'new' => 76],
    ['month' => 'Jul', 'total' => 168, 'new' => 82],
    ['month' => 'Aug', 'total' => 184, 'new' => 88],
    ['month' => 'Sep', 'total' => 139, 'new' => 61],
    ['month' => 'Oct', 'total' => 168, 'new' => 80],
    ['month' => 'Nov', 'total' => 202, 'new' => 92],
    ['month' => 'Dec', 'total' => 232, 'new' => 106],
];

// ກຳນົດຂໍ້ມູນສະຖານະສຳລັບກາຟ
$statusSummary = [
    ['label' => 'Approved', 'value' => 972, 'percent' => 77.9, 'color' => 'success'],
    ['label' => 'Pending Review', 'value' => 86, 'percent' => 6.9, 'color' => 'primary'],
    ['label' => 'Rejected', 'value' => 190, 'percent' => 15.2, 'color' => 'warning'],
];

// ກຳນົດລາຍການຜູ້ສະໝັກຫຼ້າສຸດ
$recentList = [
    ['name' => 'Somsack Phommathep', 'country' => 'Thailand', 'flag' => 'th', 'date' => '2025-05-21', 'status' => 'Pending Review', 'status_class' => 'pending'],
    ['name' => 'Khamphou Vongxay', 'country' => 'Japan', 'flag' => 'jp', 'date' => '2025-05-21', 'status' => 'Approved', 'status_class' => 'approved'],
    ['name' => 'Anousone Keomany', 'country' => 'Korea', 'flag' => 'kr', 'date' => '2025-05-20', 'status' => 'Approved', 'status_class' => 'approved'],
    ['name' => 'Phouthone Inthavong', 'country' => 'China', 'flag' => 'cn', 'date' => '2025-05-20', 'status' => 'Pending Review', 'status_class' => 'pending'],
    ['name' => 'Manychanh Boualapha', 'country' => 'Thailand', 'flag' => 'th', 'date' => '2025-05-19', 'status' => 'Rejected', 'status_class' => 'rejected'],
];

$monthLabels = json_encode(array_column($monthly, 'month'), JSON_UNESCAPED_UNICODE);
$monthTotal = json_encode(array_column($monthly, 'total'));
$monthNew = json_encode(array_column($monthly, 'new'));
?>

<style>
  .dashboard-page {
    max-width: 1680px;
    margin: 0 auto;
  }

  .dashboard-heading {
    margin-bottom: 1.15rem;
  }

  .dashboard-heading h1 {
    color: #102a56;
    font-size: clamp(1.55rem, 2vw, 2rem);
    font-weight: 700;
    margin: 0;
  }

  .dashboard-heading p {
    color: #64748b;
    font-size: .96rem;
    margin: .25rem 0 0;
  }

  .summary-card,
  .dashboard-card {
    background: #fff;
    border: 1px solid #e4eaf3;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(30, 64, 175, .06);
  }

  .summary-card {
    min-height: 110px;
    padding: 1rem 1.1rem;
    display: flex;
    align-items: center;
    gap: .9rem;
    transition: transform .2s ease, box-shadow .2s ease;
  }

  .summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(30, 64, 175, .1);
  }

  .summary-icon {
    width: 54px;
    height: 54px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.65rem;
    flex-shrink: 0;
  }

  .summary-icon.blue { background: #eef4ff; color: #1e63c6; }
  .summary-icon.orange { background: #fff5e8; color: #e99213; }
  .summary-icon.green { background: #eaf8ef; color: #27864f; }
  .summary-icon.indigo { background: #eef2ff; color: #3659b6; }

  .summary-label {
    color: #64748b;
    font-size: .82rem;
    margin-bottom: .15rem;
  }

  .summary-value {
    color: #1e40af;
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1.1;
  }

  .dashboard-card {
    padding: 1.05rem 1.15rem;
    height: 100%;
  }

  .dashboard-card-title {
    color: #172554;
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: .9rem;
  }

  .dashboard-card-title i {
    color: #1e40af;
    margin-right: .4rem;
  }

  .chart-wrap {
    height: 235px;
    position: relative;
  }

  .status-row {
    padding: .45rem 0 .8rem;
  }

  .status-row + .status-row {
    border-top: 1px solid #eef2f7;
    padding-top: .85rem;
  }

  .status-meta {
    display: flex;
    justify-content: space-between;
    gap: .5rem;
    color: #172033;
    font-size: .8rem;
    font-weight: 600;
    margin-bottom: .45rem;
  }

  .status-meta span:nth-child(2) {
    color: #2563c7;
  }

  .status-progress {
    height: 6px;
    background: #edf1f6;
    border-radius: 99px;
    overflow: hidden;
  }

  .status-progress > span {
    display: block;
    height: 100%;
    border-radius: inherit;
  }

  .status-progress .success { background: #35a562; }
  .status-progress .primary { background: #256ed5; }
  .status-progress .warning { background: #e99617; }

  .status-total {
    border-top: 1px solid #eef2f7;
    display: flex;
    justify-content: space-between;
    padding-top: .9rem;
    color: #172033;
    font-size: .82rem;
    font-weight: 700;
  }

  .recent-card-header {
    align-items: center;
    display: flex;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .75rem;
  }

  .recent-card-header .dashboard-card-title {
    margin-bottom: 0;
  }

  .recent-table {
    border-collapse: collapse;
    min-width: 720px;
    width: 100%;
  }

  .recent-table th {
    background: #f5f8fe;
    color: #1e293b;
    font-size: .75rem;
    font-weight: 700;
    padding: .62rem .75rem;
    text-align: left;
  }

  .recent-table td {
    border-bottom: 1px solid #edf1f6;
    color: #475569;
    font-size: .8rem;
    padding: .62rem .75rem;
    vertical-align: middle;
  }

  .recent-table tbody tr:hover {
    background: #f8fbff;
  }

  .candidate-name {
    color: #1e293b;
    font-weight: 600;
  }

  .candidate-flag {
    border: 1px solid #dce3ed;
    border-radius: 2px;
    height: 15px;
    object-fit: cover;
    width: 22px;
  }

  .status-badge {
    border-radius: 4px;
    display: inline-block;
    font-size: .7rem;
    font-weight: 600;
    padding: .24rem .5rem;
  }

  .status-badge.approved { background: #e5f5e8; color: #268044; }
  .status-badge.pending { background: #e8f1ff; color: #1f63c3; }
  .status-badge.rejected { background: #fff0df; color: #b96b09; }

  .view-all-link {
    color: #1e63c6;
    font-size: .8rem;
    text-decoration: none;
    white-space: nowrap;
  }

  .view-all-link:hover { color: #1e40af; text-decoration: underline; }

  @media (max-width: 768px) {
    .dashboard-page { padding: 0; }
    .dashboard-heading h1 { font-size: 1.45rem; }
    .summary-card { min-height: 96px; padding: .8rem; }
    .summary-icon { height: 44px; width: 44px; font-size: 1.35rem; }
    .summary-value { font-size: 1.45rem; }
    .chart-wrap { height: 210px; }
    .recent-card-header { align-items: flex-start; flex-direction: column; }
  }
</style>

<main class="dashboard-page">
  <header class="dashboard-heading">
    <h1>Welcome to LMS</h1>
    <p>Labor Management System</p>
  </header>

  <section class="row g-3 mb-4" aria-label="Summary statistics">
    <div class="col-6 col-xl-3">
      <article class="summary-card">
        <div class="summary-icon blue"><i class="bi bi-people"></i></div>
        <div>
          <div class="summary-label">Total Candidates</div>
          <div class="summary-value"><?= number_format($stats['total']) ?></div>
        </div>
      </article>
    </div>
    <div class="col-6 col-xl-3">
      <article class="summary-card">
        <div class="summary-icon orange"><i class="bi bi-clock-history"></i></div>
        <div>
          <div class="summary-label">Pending Review</div>
          <div class="summary-value" style="color:#e99213;"><?= number_format($stats['pending']) ?></div>
        </div>
      </article>
    </div>
    <div class="col-6 col-xl-3">
      <article class="summary-card">
        <div class="summary-icon green"><i class="bi bi-check-circle"></i></div>
        <div>
          <div class="summary-label">Approved</div>
          <div class="summary-value" style="color:#27864f;"><?= number_format($stats['approved']) ?></div>
        </div>
      </article>
    </div>
    <div class="col-6 col-xl-3">
      <article class="summary-card">
        <div class="summary-icon indigo"><i class="bi bi-file-earmark-text"></i></div>
        <div>
          <div class="summary-label">Recent Entries</div>
          <div class="summary-value"><?= number_format($stats['recent']) ?></div>
        </div>
      </article>
    </div>
  </section>

  <section class="row g-3 mb-4">
    <div class="col-xl-8">
      <article class="dashboard-card">
        <h2 class="dashboard-card-title"><i class="bi bi-bar-chart-line"></i>Candidate Overview</h2>
        <div class="chart-wrap"><canvas id="candidateOverviewChart"></canvas></div>
      </article>
    </div>
    <div class="col-xl-4">
      <article class="dashboard-card">
        <h2 class="dashboard-card-title">Status Summary</h2>
        <?php foreach ($statusSummary as $status): ?>
          <div class="status-row">
            <div class="status-meta">
              <span><?= htmlspecialchars($status['label']) ?></span>
              <span><?= number_format($status['percent'], 1) ?>% <strong class="ms-2 text-dark"><?= number_format($status['value']) ?></strong></span>
            </div>
            <div class="status-progress">
              <span class="<?= htmlspecialchars($status['color']) ?>" style="width:<?= $status['percent'] ?>%;"></span>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="status-total"><span>Total</span><span>100% &nbsp; <?= number_format($stats['total']) ?></span></div>
      </article>
    </div>
  </section>

  <section class="dashboard-card mb-4">
    <div class="recent-card-header">
      <h2 class="dashboard-card-title"><i class="bi bi-clock-history"></i>Recent Candidates</h2>
      <a href="#" class="view-all-link">View all candidates <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="table-responsive">
      <table class="recent-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Country</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentList as $index => $row): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td class="candidate-name"><?= htmlspecialchars($row['name']) ?></td>
              <td>
                <span class="d-inline-flex align-items-center gap-2">
                  <img src="https://flagcdn.com/w40/<?= htmlspecialchars($row['flag']) ?>.png" class="candidate-flag" alt="<?= htmlspecialchars($row['country']) ?>">
                  <?= htmlspecialchars($row['country']) ?>
                </span>
              </td>
              <td><span class="status-badge <?= htmlspecialchars($row['status_class']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
              <td><?= htmlspecialchars($row['date']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// ສ້າງກາຟພາບລວມຜູ້ສະໝັກ
new Chart(document.getElementById('candidateOverviewChart'), {
  data: {
    labels: <?= $monthLabels ?>,
    datasets: [
      {
        type: 'line',
        label: 'Total Candidates',
        data: <?= $monthTotal ?>,
        borderColor: '#1e63c6',
        backgroundColor: '#1e63c6',
        borderWidth: 2,
        pointRadius: 3,
        pointHoverRadius: 5,
        tension: .35,
        yAxisID: 'y'
      },
      {
        type: 'bar',
        label: 'New Candidates',
        data: <?= $monthNew ?>,
        backgroundColor: 'rgba(96, 157, 230, .42)',
        borderColor: 'rgba(96, 157, 230, .42)',
        borderWidth: 1,
        borderRadius: 3,
        yAxisID: 'y'
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: { display: true, position: 'top', align: 'center', labels: { usePointStyle: true, boxWidth: 8, color: '#475569', font: { size: 11 } } }
    },
    scales: {
      x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 11 } } },
      y: { beginAtZero: true, grid: { color: '#edf1f6' }, ticks: { color: '#64748b', font: { size: 10 } } }
    }
  }
});
</script>

<?php include_once('footer.php'); ?>
