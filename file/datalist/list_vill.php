<?php 
  include_once('../check.php'); 
  include_once('header.php'); 
  $all = $_REQUEST['all'] ?? '';
  $pro_id = $_REQUEST['s_pro_id'] ?? '';
  $dis_id = $_REQUEST['s_dis_id'] ?? '';

  //search
  $p1 = $all == '' ? '' : "AND (vill_name LIKE '%$all%' OR vill_name_lao LIKE '%$all%' OR vill_id LIKE '%$all%' )";
  $p2 = $pro_id != '' ? "AND vill.pro_id = '$pro_id' " :'';
  $p3 = $dis_id != '' ? "AND vill.dis_id = '$dis_id' " :'';

  // ພາກສ່ວນການປ່ຽນໜ້າ
  $limit = 500;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $sql = $conn->prepare("SELECT * FROM village as vill
  INNER JOIN district as dis ON dis.dis_id=vill.dis_id
  INNER JOIN province as pro ON vill.pro_id=pro.pro_id
  WHERE 1=1 $p1 $p2 $p3
  ORDER BY vill_id ASC
  LIMIT $limit OFFSET $offset");
  $sql->execute();
  // ດືງຂໍ້ມູນທັງໝົດເພື່ອຄຳນວນຈຳນວນໜ້າ
  $total_result = $conn->prepare("SELECT COUNT(*) as total  FROM village as vill
  INNER JOIN district as dis ON dis.dis_id=vill.dis_id
  INNER JOIN province as pro ON vill.pro_id=pro.pro_id
  WHERE 1=1 $p1 $p2 $p3");
  $total_result->execute();
  $total_row = $total_result->fetch(PDO::FETCH_ASSOC);
  $total_pages = ceil($total_row['total'] / $limit);

  $num = $offset + 1;
  $users = $sql->fetchAll(PDO::FETCH_ASSOC);

  // total
  $sql_total = $conn->prepare("SELECT COUNT(*) FROM village as vill
  INNER JOIN district as dis ON dis.dis_id=vill.dis_id
  INNER JOIN province as pro ON vill.pro_id=pro.pro_id
  WHERE 1=1 $p1 $p2 $p3");
  $sql_total->execute();
  $total = $sql_total->fetch(PDO::FETCH_NUM)[0];
  $sql_pro = $conn->prepare("SELECT * FROM province");
  $sql_pro->execute();
  $pro = $sql_pro->fetchAll(PDO::FETCH_ASSOC);
  $dis = $conn->prepare("SELECT * FROM district");
  $dis->execute();
  $dis = $dis->fetchAll(PDO::FETCH_ASSOC);

  
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
      <i class="bi bi-houses-fill me-2 text-primary"></i>ລາຍການບ້ານ
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
    <i class="bi bi-table text-primary"></i> ລາຍຊື່ ບ້ານ ທັງໝົດ
    <span class="ms-auto badge fw-semibold"
          style="background:#eff6ff; color:#1d4ed8; font-size:12px;">
      <?= $total ?> ລາຍການ
    </span>
  </div>

  <!-- Filter Bar -->
   <form action="" method="get">
  <div class="filter-bar row g-3 mb-3">
      <div class="col-md-4 filter-group">
          <label for="all">ຄົ້ນຫາ</label>
          <input type="text" name="all" id="all" placeholder="🔍 ຄົ້ນຫາຊື່ ບ້ານ...." value="<?= $all ?>">
      </div>
      <div class="col-md-2 filter-group">
          <label for="all">ແຂວງ</label>
          <select name="s_pro_id" id="s_pro_id" class="form-select">
            <option value="">ເລືອກ</option>
            <?php foreach($pro as $p): ?>
              <option value="<?= $p['pro_id'] ?>" <?= $pro_id == $p['pro_id'] ? 'selected' : '' ?>><?= $p['pro_name_lao'] ?></option>
            <?php endforeach ?>
          </select>
      </div>
      <div class="col-md-2 filter-group">
          <label for="all">ເມືອງ</label>
          <select name="s_dis_id" id="s_dis_id" class="form-select" data-selected="<?= $dis_id ?>">
            <option value="">ເລືອກ</option>
          </select>
      </div>
      <div class="col-md-2 filter-group btn-mt">
          <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Search</button>
      </div>
  </div>
  </form>

  <div class="table-responsive">
    <table class="usr-table" id="userTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Village Id</th>
          <th>Province Name</th>
          <th>District Name (Lao)</th>
          <th>Village Name (Lao)</th>
          <th>Village Name (Eng)</th>
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
          <td style="color:#64748b;"><?= htmlspecialchars($u['vill_id']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['pro_name_lao']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['dis_name_lao']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['vill_name_lao']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['vill_name']) ?></td>
          <td class="text-end">
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
              <!-- ປຸ່ມແກ້ໄຂ Role -->
              <button class="btn-edit"
                      data-user="<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8'); ?>"
                      onclick="handleEditButtonClick(this)">
                <i class="bi bi-pencil-fill"></i> ແກ້ໄຂ
              </button>
              <!-- ປຸ່ມລົບ -->
              <button class="btn-del del_vill" data-vill_id="<?= $u['vill_id'] ?>">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        
      </tbody>
    </table>
    <nav>
        <ul class="pagination justify-content-center flex-wrap">
            <!-- ปุ่ม Previous -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a href="?page=<?= $page - 1 ?>" class="page-link">Previous</a>
                </li>
            <?php endif ?>
            <?php
            $range = 5;
            $start = max(1, $page - $range);
            $end = min($total_pages, $page + $range);

            // แสดงหน้าแรก + ... ถ้าจุดเริ่มต้นมากกว่า 1
            if ($start > 1) {
                echo '<li class="page-item"><a href="?page=1" class="page-link">1</a></li>';
                if ($start > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            // ลูปแสดงเลขหน้าตามช่วงที่กำหนด
            for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a href="?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                </li>
            <?php endfor;
            // แสดง ... + หน้าสุดท้าย ถ้าจุดสิ้นสุดน้อยกว่าหน้าทั้งหมด
            if ($end < $total_pages) {
                if ($end < $total_pages - 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a href="?page=' . $total_pages . '" class="page-link">' . $total_pages . '</a></li>';
            }
            ?>
            <!-- ปุ่ม Next -->
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a href="?page=<?= $page + 1 ?>" class="page-link">Next</a>
                </li>
            <?php endif ?>
        </ul>
    </nav>
  </div>



</div>

<?php 
  // 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
  include_once('form/vill_add.php'); 
  include_once('form/vill_edit.php'); 
  include_once('footer.php'); 
?>
<script>
  $(document).ready(function () {
    // ฟังก์ชันโหลดเมือง
        function loadDistricts(pro_id, selected_dis_id = '') {
            if (!pro_id) {
                $("#s_dis_id").html('<option value="">ທັງໝົດ</option>');
                return;
            }
            $.ajax({
                type: "post",
                url: "get/get_pro.php",
                data: { pro_id: pro_id },
                success: function(response) {
                    $("#s_dis_id").html(response);
                    if (selected_dis_id) {
                        $("#s_dis_id").val(selected_dis_id).change();
                    }
                }
            });
        }


        // Event เมื่อเปลี่ยนแขวง
        $("#s_pro_id").change(function() {
            loadDistricts($(this).val());
        });


        // ==========================================
        // ส่วนสำคัญ: ตรวจสอบและโหลดค่าเดิมหลังจากการค้นหา (On Load)
        // ==========================================
        let init_pro_id = $("#s_pro_id").val();
        let init_dis_id = $("#s_dis_id").attr("data-selected");

        if (init_pro_id) {
            // โดนเรียกซ้อนกันเพื่อให้เลือกเมืองค้างไว้ และไปเรียกโหลดบ้านต่อ
            if (init_dis_id) {
                $.ajax({
                    type: "post",
                    url: "get/get_pro.php",
                    data: { pro_id: init_pro_id },
                    success: function(response) {
                        $("#s_dis_id").html(response).val(init_dis_id);
                        
                        
                    }
                });
            } else {
                loadDistricts(init_pro_id);
            }
        }
        $("#s_pro_id").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "get/get_pro.php",
                data: {
                    pro_id: pro_id
                },
                success: function(response) {
                    $("#s_dis_id").html(response);
                }
            });

        });
  });
  
</script>