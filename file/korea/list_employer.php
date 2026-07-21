<?php 
  include_once('../check.php'); 
  include_once('header.php'); 

  $sql = $conn->prepare("SELECT * FROM employer as emp
  LEFT JOIN province_korea as prok ON emp.prok_id=prok.prok_id
  LEFT JOIN district_korea as disk ON emp.disk_id=disk.disk_id
  ORDER BY emp_id ASC");
  $sql->execute();
  $num = 1;
  $employers = $sql->fetchAll(PDO::FETCH_ASSOC);
  // ============================================================
// ຂໍ້ມູນຕົວຢ່າງ Employer (Static Data)
// ============================================================

// ນັບຈຳນວນ Employer
$totalEmployers = count($employers);

$sql_max = $conn->prepare("SELECT MAX(emp_id) FROM employer");
    $sql_max->execute();
    $max_id = $sql_max->fetchColumn();
    $number = 1;
    $number = $max_id ? (int) $max_id + 1 : 1;
    $emp_id = str_pad($number,3,'0',STR_PAD_LEFT);
$sql_prok = $conn->prepare("SELECT * FROM province_korea");
$sql_prok->execute();
$s_prok = $sql_prok->fetchAll(PDO::FETCH_ASSOC);
$sql_disk = $conn->prepare("SELECT * FROM district_korea");
$sql_disk->execute();
$s_disk = $sql_disk->fetchAll(PDO::FETCH_ASSOC);
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
      <i class="bi bi-building-fill me-2 text-primary"></i> Employers List
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
    <i class="bi bi-table text-primary"></i> ລາຍຊື່ Employers ທັງໝົດ
    <span class="ms-auto badge fw-semibold"
          style="background:#eff6ff; color:#1d4ed8; font-size:12px;">
      <?= $totalEmployers ?> ລາຍການ
    </span>
  </div>

  <!-- Filter Bar -->
  <div class="filter-bar row g-3 mb-3">
            <div class="col-md-4 filter-group">
                <label for="all">ຄົ້ນຫາ</label>
                <input type="text" name="all" id="s_all" placeholder="🔍 ຄົ້ນຫາຊື່ຜູ້ຈ້າງ...">
            </div>

            

            <div class="col-md-2 filter-group">
                <label for="f2">ແຂວງ</label>
                <select id="s_prok_id" name="prok_id" >
                    <option value="">ທັງໝົດ</option>
                    <?php foreach ($s_prok as $proa): ?>
                        <option value="<?= $proa['prok_id'] ?>" ><?= $proa['prok_name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- เพิ่มอีกกี่ filter ก็ได้ ใช้ col-md-2 หรือ col-md-3 ตามต้องการ -->
            <div class="col-md-2 filter-group">
                <label for="dis_id">ເມືອງ</label>
                <select id="s_disk_id" name="dis_id" data-selected="<?= $dis_id ?>">
                    <option value="">ທັງໝົດ</option>
                </select>
            </div>

            <div class="col-md-2 filter-group btn-mt">
                <button type="button" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Search</button>
            </div>

        </div>

  <div class="table-responsive">
    <table class="usr-table" id="employerTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Emp ID</th>
          <th>Business Type</th>
          <th>Employer Name</th>
          <th>Employer Name (Eng)</th>
          <th>Company Name</th>
          <th>Location</th>
          <th>Phone NO</th>
          <th>Email</th>
          <th>Province</th>
          <th>District</th>
          <th>Code</th>
          <th class="text-end">ຈັດການ</th>
        </tr>
      </thead>
      <tbody id="employerTbody">
        <?php foreach ($employers as $i => $emp):
        ?>
        <tr data-emp_id="<?= htmlspecialchars($emp['emp_id']) ?>"
            data-emp_name="<?= htmlspecialchars($emp['emp_name']) ?>"
            data-emp_email="<?= htmlspecialchars($emp['emp_email']) ?>"
            >

          <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>

          <td style="font-weight:600; color:#1e293b;">
            <?= htmlspecialchars($emp['emp_id'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <span class="badge" style="background:#fef08a; color:#713f12;">
              <?= htmlspecialchars($emp['bus_type'] ?? '') ?>
            </span>
          </td>

          <td style="color:#64748b; font-weight:500;">
            <?= htmlspecialchars($emp['emp_name'] ?? '') ?>
          </td>
          <td style="color:#64748b; font-weight:500;">
            <?= htmlspecialchars($emp['emp_name_eng'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_com'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['location'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_phone'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_email'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['prok_name'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['disk_name'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_code'] ?? '') ?>
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
              <button class="btn-del del_emp" data-emp_id="<?= $emp['emp_id'] ?>" >
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
  include_once('form/employer_add.php'); 
  include_once('form/employer_edit.php'); 
  include_once('footer.php'); 
?>
<script>
  $(document).ready(function () {
    $("#s_prok_id").change(function (e) { 
      let prok_id = $(this).val();
      $.ajax({
        type: "post",
        url: "get/get_prok.php",
        data: {prok_id:prok_id},
        success: function (response) {
          $("#s_disk_id").html(response);
        }
      });
      
    });
  });
</script>