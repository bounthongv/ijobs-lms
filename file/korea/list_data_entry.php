<?php
include_once('../check.php');
include_once('header.php');

// search
$all = $_REQUEST['all'] ?? '';
$labor_type = $_REQUEST['labor_type'] ?? '';
$gender = $_REQUEST['gender'] ?? '';
$pro_id = $_REQUEST['pro_id'] ?? '';
$dis_id = $_REQUEST['dis_id'] ?? '';
$vill_id = $_REQUEST['vill_id'] ?? '';
$type = $_REQUEST['type'] ?? '';
$date1 = $_REQUEST['date1'] ?? '';
$date2 = $_REQUEST['date2'] ?? '';

$p1 = $all != "" ? "AND (fname LIKE '%$all%' OR lname LIKE '%$all%' OR fname_eng LIKE '%$all%' OR lname_eng LIKE '%$all%' OR passport LIKE '%$all%' OR nickname LIKE '%$all%')" : "";
$p2 = $labor_type != '' ? "AND labor_type = '$labor_type' " :'';
$p3 = $gender != '' ? "AND gender = '$gender' " :'';
$p4 = $pro_id != '' ? "AND data.pro_id = '$pro_id' " :'';
$p5 = $dis_id != '' ? "AND data.dis_id = '$dis_id' " :'';
$p6 = $vill_id != '' ? "AND data.vill_id = '$vill_id' " :'';
$p7 = '';
if($type == 'Date Of Birth'){
    $p7 = "AND dob BETWEEN '$date1' AND '$date2' ";
}else if($type == 'Interview Date'){
    $p7 = "AND interview_date BETWEEN '$date1' AND '$date2' ";
}
// ພາກສ່ວນການປ່ຽນໜ້າ
$limit = 500;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = $conn->prepare("SELECT *,
-- ປັດຈຸບັນ
pro.pro_name_lao as pro_name_lao,
dis.dis_name_lao as dis_name_lao,
vill.vill_name_lao as vill_name_lao,
pro.pro_id as pro_id,
dis.dis_id as dis_id,
vill.vill_id as vill_id,
-- ບ່ອນເກີດ
pro_b.pro_name_lao as pro_name_b,
dis_b.dis_name_lao as dis_name_b,
vill_b.vill_name_lao as vill_name_b,
data.pro_id_b,
data.dis_id_b,
data.vill_id_b
FROM data_entry_korea as data
LEFT JOIN province as pro ON data.pro_id=pro.pro_id
LEFT JOIN district as dis ON data.dis_id=dis.dis_id
LEFT JOIN village as vill ON data.vill_id=vill.vill_id

LEFT JOIN province as pro_b ON data.pro_id_b=pro_b.pro_id
LEFT JOIN district as dis_b ON data.dis_id_b=dis_b.dis_id
LEFT JOIN village as vill_b ON data.vill_id_b=vill_b.vill_id 
WHERE sts_tb = 'data_entry_korea' $p1 $p2 $p3 $p4 $p5 $p6 $p7
ORDER BY id ASC
LIMIT $limit OFFSET $offset");
$sql->execute();
// ດືງຂໍ້ມູນທັງໝົດເພື່ອຄຳນວນຈຳນວນໜ້າ
$total_result = $conn->prepare("SELECT COUNT(*) as total  FROM data_entry_korea as data
WHERE sts_tb = 'data_entry_korea' $p1 $p2 $p3 $p4 $p5 $p6 $p7");
$total_result->execute();
$total_row = $total_result->fetch(PDO::FETCH_ASSOC);
$total_pages = ceil($total_row['total'] / $limit);

$num = $offset + 1;
// total
$sql_total = $conn->prepare("SELECT COUNT(*) FROM data_entry_korea as data WHERE sts_tb = 'data_entry_korea' $p1 $p2 $p3 $p4 $p5 $p6 $p7");
$sql_total->execute();
$total = $sql_total->fetch(PDO::FETCH_NUM)[0];

// province
$sql_pro = $conn->prepare("SELECT * FROM province ORDER BY pro_id ASC");
$sql_pro->execute();
$pro = $sql_pro->fetchAll(PDO::FETCH_ASSOC);

?>
<style>
    table th,
    table td {
        white-space: nowrap;
        vertical-align: top;
        font-size: 14px;
    }
</style>
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-card-checklist me-2 text-primary"></i> Data Entry List
        </h5>
    </div>
    <a href="form/data_entry_add.php" class="btn btn-primary btn-sm px-3 py-2"
        style="font-size:13px; font-weight:600; border-radius:8px;">
        <i class="bi bi-plus-lg me-1"></i> Add
    </a>
</div>
<!-- ===== ຕາຕະລາງ User ===== -->
<div class="dash-card">
    <div class="dash-card-title">
        <i class="bi bi-table text-primary"></i> ລາຍຊື່ Data Entry ທັງໝົດ
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
                <input type="text" name="all" id="all" placeholder="🔍 ຄົ້ນຫາຊື່ / ນາມສະກຸນ, Passport..." value="<?= $all ?>">
            </div>

            <div class="col-md-2 filter-group">
                <label for="filterRole">ປະເພດ</label>
                <select id="filterRole" name="labor_type">
                    <option value="">ທັງໝົດ</option>
                    <option value="New" <?= $labor_type == 'New' ? 'selected' : '' ?>>New</option>
                    <option value="Re-New" <?= $labor_type == 'Re-New' ? 'selected' : '' ?>>Re-New</option>
                    <option value="New(RC)" <?= $labor_type == 'New(RC)' ? 'selected' : '' ?>>New(RC)</option>
                    <option value="Re-entry" <?= $labor_type == 'Re-entry' ? 'selected' : '' ?>>Re-entry</option>
                    <option value="Re-employment" <?= $labor_type == 'Re-employment' ? 'selected' : '' ?>>Re-employment</option>
                </select>
            </div>

            <div class="col-md-2 filter-group">
                <label for="f1">ເພດ</label>
                <select id="f1" name="gender">
                    <option value="">ທັງໝົດ</option>
                    <option value="F" <?= $gender == 'F' ? 'selected' : '' ?>>Female</option>
                    <option value="M" <?= $gender == 'M' ? 'selected' : '' ?>>Male</option>
                </select>
            </div>

            <div class="col-md-2 filter-group">
                <label for="f2">ແຂວງ</label>
                <select id="pro_id" name="pro_id" >
                    <option value="">ທັງໝົດ</option>
                    <?php foreach ($pro as $proa): ?>
                        <option value="<?= $proa['pro_id'] ?>" <?= $pro_id == $proa['pro_id'] ? 'selected' : '' ?>><?= $proa['pro_name_lao'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- เพิ่มอีกกี่ filter ก็ได้ ใช้ col-md-2 หรือ col-md-3 ตามต้องการ -->
            <div class="col-md-2 filter-group">
                <label for="dis_id">ເມືອງ</label>
                <select id="dis_id" name="dis_id" data-selected="<?= $dis_id ?>">
                    <option value="">ທັງໝົດ</option>
                </select>
            </div>

            <div class="col-md-2 filter-group">
                <label for="vill_id">ບ້ານ</label>
                <select id="vill_id" name="vill_id" data-selected="<?= $vill_id ?>">
                    <option value="">ທັງໝົດ</option>
                </select>
            </div>
            <div class="col-md-2 filter-group">
                <label for="f3">ປະເພດວັນທີ່</label>
                <select id="f3" name="type">
                    <option value="">ເລືອກ</option>
                    <option value="Date Of Birth" <?= $type == 'Date Of Birth' ? 'selected' : '' ?>>Date Of Birth</option>
                    <option value="Interview Date" <?= $type == 'Interview Date' ? 'selected' : '' ?>>Interview Date</option>
                </select>
            </div>
            <div class="col-md-2 filter-group">
                <label for="f3">Date</label>
                <input type="date" name="date1" id="" value="<?= $date1 ?>">
            </div>
            <div class="col-md-2 filter-group">
                <label for="f3">From To</label>
                <input type="date" name="date2" id="" value="<?= $date2 ?>">
            </div>
            <div class="col-md-2 filter-group btn-mt">
                <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Search</button>
            </div>

        </div>
    </form>

    <?php if ($sql->rowCount() == 0): ?>
    <!-- ຂໍ້ຄວາມ ຖ້າບໍ່ມີຂໍ້ມູນ -->
            <div id="emptyMsg" style=" text-align:center; padding:28px; color:#94a3b8; font-size:13px;">
                <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px; opacity:.4;"></i>
                ບໍ່ພົບຂໍ້ມູນ
            </div>
        <?php else: ?>
    <div class="table-responsive">
        <table class="usr-table" id="userTable">
            <thead>
                <tr>
                    <th>ລຳດັບ</th>
                    <th style="width:100px;" class="text-center">ຈັດການ</th>
                    <th>Certificate</th>
                    <th>ໃບຢັ້ງຢືນທີ່ຢູ່</th>
                    <th class="sortable">Picture</th>
                    <th class="sortable">Passport</th>
                    <th class="sortable">ໃບກະສິກອນ</th>
                    <th class="sortable">Labor_contract</th>
                    <th class="sortable">ຟອມສຳພາດ</th>
                    <th class="sortable">ສຳມະໂນຄົວ</th>
                    <th class="sortable">ຫຼັກຊັບຄ້ຳປະກັນ</th>
                    <th>ສົ່ງເອກະສານ</th>
                    <th class="sortable">Name&Surname</th>
                    <th class="sortable">Nickname</th>
                    <th class="sortable">Name&SurnameLao</th>
                    <th class="sortable">Phone NO1</th>
                    <th class="sortable">Phone NO2</th>
                    <th class="sortable">Fam Phone NO</th>
                    <th class="sortable">Nationality</th>
                    <th class="sortable">Date of birth </th>
                    <th class="sortable">Age</th>
                    <th class="sortable">Gender</th>
                    <th class="sortable">Weight</th>
                    <th class="sortable">Height</th>
                    <th class="sortable">Shirt Size</th>
                    <th class="sortable">Status</th>
                    <th class="sortable">Health_Check_Date</th>
                    <th class="sortable">health_check</th>
                    <th class="sortable">Eng Village</th>
                    <th class="sortable">Lao Village</th>
                    <th class="sortable">Birth Village</th>
                    <th class="sortable">Eng District</th>
                    <th class="sortable">Lao District</th>
                    <th class="sortable">Birth District</th>
                    <th class="sortable">Eng Province</th>
                    <th class="sortable">Lao Province</th>
                    <th class="sortable">Birth Province</th>
                    <th class="sortable">Family book NO</th>
                    <th class="sortable">Family book Date</th>
                    <th class="sortable">Unit</th>
                    <th class="sortable">Home NO</th>
                    <th class="sortable">Interview Location</th>
                    <th class="sortable">Passport NO </th>
                    <th class="sortable">Issue Date</th>
                    <th class="sortable">Exp date</th>
                    <th class="sortable">Loan</th>
                    <th class="sortable">Driver License</th>
                    <th class="sortable">experience</th>
                    <th class="sortable">Interview Name</th>
                    <th class="sortable">ແຮງງານ ມີຕົວເລືອກ</th>
                    <th class="sortable">Remark</th>
                    <th class="sortable">Interview Date</th>
                </tr>
            </thead>
            <tbody id="userTbody">
                
                   
                    <?php foreach ($sql as $i => $row):
                        $part = $row['gender'] == 'F' ? 'ນ. ' : 'ທ. ';
                        $full_name_lao = $part.$row['fname']." ".$row['lname'];
                        $full_name = $row['fname_eng']." ".$row['lname_eng'];
                    ?>
                        <tr>
                            <td><?= $num++; ?></td>
                            <td>
                                <a href="form/data_entry_edit.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="bi bi-pencil-fill"></i></a>
                                <button type="button" data-id="<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm del_data"><i class="bi bi-trash"></i></button>
                            </td>
                            <td><a href="print/print_certificate.php?id=<?= $row['id'] ?> ?>" target="_blank" class="btn btn-outline-warning  btn-sm"><i class="bi bi-printer"></i></a></td>
                            <td><a href="print/print_address.php?id=<?= $row['id'] ?> ?>" target="_blank" class="btn btn-outline-warning btn-sm"><i class="bi bi-printer"></i></a></td>
                            <td>
                                <?php if (!empty($row['profile'])): ?>
                                    <a href="uploads/<?= $row['profile'] ?>?t=<?= time() ?>" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if (!empty($row['doc_passport'])): ?>
                                    <a href="uploads/<?= $row['doc_passport'] ?>?t=<?= time() ?>" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if (!empty($row['doc_farmer_cert'])): ?>
                                    <a href="uploads/<?= $row['doc_farmer_cert'] ?>?t=<?= time() ?>" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if (!empty($row['doc_labor_contract'])): ?>
                                    <a href="uploads/<?= $row['doc_labor_contract'] ?>?t=<?= time() ?>" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if (!empty($row['file_form'])): ?>
                                    <a href="uploads/<?= $row['file_form'] ?>?t=<?= time() ?>" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if (!empty($row['doc_census'])): ?>
                                    <a href="uploads/<?= $row['doc_census'] ?>?t=<?= time() ?>" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if (!empty($row['doc_collateral'])): ?>
                                    <a href="uploads/<?= $row['doc_collateral'] ?>?t=<?= time() ?>" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <td><?= htmlspecialchars($row['coll_sts'] ?? '') ?></td>
                            <td><?= htmlspecialchars($full_name ?? '') ?></td>
                            <td><?= htmlspecialchars($row['nickname'] ?? '') ?></td>
                            <td><?= htmlspecialchars($full_name_lao ?? '') ?></td>
                            <td><?= htmlspecialchars($row['phone1'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['phone2'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['fam_phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['nationality'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['dob'] == null ? '' : date_format(date_create($row['dob']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['age'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['gender'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['weight'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['height'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['shirt_size'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['heal_date'] == null ? '' : date_format(date_create($row['heal_date']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['heal_sts'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['vill_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['vill_name_lao'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['vill_name_b'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['dis_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['dis_name_lao'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['dis_name_b'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['pro_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['pro_name_lao'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['pro_name_b'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['family_book_no'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['family_book_date'] == null ? '' : date_format(date_create($row['family_book_date']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['unit'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['home'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['interview_location'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['passport'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['issue_date'] == null ? '' : date_format(date_create($row['issue_date']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['exp_date'] == null ? '' : date_format(date_create($row['exp_date']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['pay_sts'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['driver'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['agricu'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['interview_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['list_type'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['da_remark'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['interview_date'] == null ? '' : date_format(date_create($row['interview_date']), 'd/m/Y')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                

            </tbody>
        </table>
    </div>
    <?php endif ?>
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


<?php
// 3. ดึงส่วนท้ายและ JavaScript มาปิดท้ายไฟล์
include_once('footer.php');
?>
<script>
    $(document).ready(function () {
        // ฟังก์ชันโหลดเมือง
        function loadDistricts(pro_id, selected_dis_id = '') {
            if (!pro_id) {
                $("#dis_id").html('<option value="">ທັງໝົດ</option>');
                $("#vill_id").html('<option value="">ທັງໝົດ</option>');
                return;
            }
            $.ajax({
                type: "post",
                url: "get/get_pro.php",
                data: { pro_id: pro_id },
                success: function(response) {
                    $("#dis_id").html(response);
                    if (selected_dis_id) {
                        $("#dis_id").val(selected_dis_id).change();
                    }
                }
            });
        }

        // ฟังก์ชันโหลดบ้าน
        function loadVillages(dis_id, selected_vill_id = '') {
            if (!dis_id) {
                $("#vill_id").html('<option value="">ເລືອກ</option>');
                return;
            }
            $.ajax({
                type: "post",
                url: "get/get_dis.php",
                data: { dis_id: dis_id },
                success: function(response) {
                    $("#vill_id").html(response);
                    if (selected_vill_id) {
                        $("#vill_id").val(selected_vill_id);
                    }
                }
            });
        }

        // Event เมื่อเปลี่ยนแขวง
        $("#pro_id").change(function() {
            loadDistricts($(this).val());
        });

        // Event เมื่อเปลี่ยนเมือง
        $("#dis_id").change(function() {
            loadVillages($(this).val());
        });

        // ==========================================
        // ส่วนสำคัญ: ตรวจสอบและโหลดค่าเดิมหลังจากการค้นหา (On Load)
        // ==========================================
        let init_pro_id = $("#pro_id").val();
        let init_dis_id = $("#dis_id").attr("data-selected");
        let init_vill_id = $("#vill_id").attr("data-selected");

        if (init_pro_id) {
            // โดนเรียกซ้อนกันเพื่อให้เลือกเมืองค้างไว้ และไปเรียกโหลดบ้านต่อ
            if (init_dis_id) {
                $.ajax({
                    type: "post",
                    url: "get/get_pro.php",
                    data: { pro_id: init_pro_id },
                    success: function(response) {
                        $("#dis_id").html(response).val(init_dis_id);
                        
                        // โหลดบ้านต่อหลังจากเมืองถูกโหลดเข้ามาเสร็จแล้ว
                        if (init_vill_id) {
                            $.ajax({
                                type: "post",
                                url: "get/get_dis.php",
                                data: { dis_id: init_dis_id },
                                success: function(res_vill) {
                                    $("#vill_id").html(res_vill).val(init_vill_id);
                                }
                            });
                        }
                    }
                });
            } else {
                loadDistricts(init_pro_id);
            }
        }
    });
    $(document).ready(function () {
        $("#pro_id").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "get/get_pro.php",
                data: {
                    pro_id: pro_id
                },
                success: function(response) {
                    $("#dis_id").html(response);
                }
            });

        });
        $("#dis_id").change(function(e) {
            let dis_id = $(this).val();
            $.ajax({
                type: "post",
                url: "get/get_dis.php",
                data: {
                    dis_id: dis_id
                },
                success: function(response) {
                    $("#vill_id").html(response);
                }
            });

        });
    });
</script>