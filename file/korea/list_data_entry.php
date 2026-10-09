<?php
include_once('../check.php');
include_once('header.php');
$item_id = $_SESSION['item_id'];
$item_ids = explode(',', $item_id);
if(!in_array('0107', $item_ids)){
    ?>
    <!DOCTYPE html>
    <html lang="lo">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap');

        * {
            font-family: "Noto Sans Lao", serif;
        }
    </style>
    <body>
        <script>
            Swal.fire({
                icon: "error",
                title: "ການເຂົ້າເຖິງຖືກປະຕິເສດ",
                text: "ທ່ານບໍ່ມີສິດເຂົ້າໃຊ້ໜ້ານີ້",
                confirmButtonText: "ກັບຄືນ",
                confirmButtonColor: "#dc3545",
                allowOutsideClick: false
            }).then(() => {
                window.history.back();
            });
        </script>
    </body>
    </html>
    <?php
    exit();
}

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

// ສ້າງເງື່ອນໄຂ WHERE ແລະ ຄ່າ bind ສຳລັບ Prepared Statement
$where = ["cand.sts_save IN ('Data Entry')"];
$params = [];

if ($all != "") {
    $where[] = "(cand.fname LIKE :all1 OR cand.lname LIKE :all2 OR cand.fname_eng LIKE :all3
        OR cand.lname_eng LIKE :all4 OR cand.passport LIKE :all5 OR cand.nickname LIKE :all6)";
    for ($i = 1; $i <= 6; $i++) {
        $params[":all$i"] = "%$all%";
    }
}
if ($labor_type != '') {
    $where[] = "cand.labor_type = :labor_type";
    $params[':labor_type'] = $labor_type;
}
if ($gender != '') {
    $where[] = "cand.gender = :gender";
    $params[':gender'] = $gender;
}
if ($pro_id != '') {
    $where[] = "cand.pro_id = :pro_id";
    $params[':pro_id'] = $pro_id;
}
if ($dis_id != '') {
    $where[] = "cand.dis_id = :dis_id";
    $params[':dis_id'] = $dis_id;
}
if ($vill_id != '') {
    $where[] = "cand.vill_id = :vill_id";
    $params[':vill_id'] = $vill_id;
}
if ($date1 != '' && $date2 != '') {
    if ($type == 'Date Of Birth') {
        $where[] = "cand.dob BETWEEN :date1 AND :date2";
        $params[':date1'] = $date1;
        $params[':date2'] = $date2;
    } else if ($type == 'Interview Date') {
        $where[] = "cand.interview_date BETWEEN :date1 AND :date2";
        $params[':date1'] = $date1;
        $params[':date2'] = $date2;
    }
}
$whereSql = implode(' AND ', $where);
// ພາກສ່ວນການປ່ຽນໜ້າ
$limit = 500;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = $conn->prepare("SELECT
cand.*,
pro.pro_name_lao,
dis.dis_name_lao,
vill.vill_name_lao,
COALESCE(ip.project_code, (SELECT code FROM labor_korea WHERE data_id = cand.cid LIMIT 1)) AS project_code,
ip.sts_save AS personal_sts,
ph.sts_save AS physical_sts,
ex.sts_save AS experience_sts,
at.sts_save AS attitude_sts,
gn.sts_save AS general_sts,
cl.sts_save AS collateral_sts,
asmt.sts_save AS assessment_sts
FROM candidate_korea as cand
LEFT JOIN province as pro ON cand.pro_id=pro.pro_id
LEFT JOIN district as dis ON cand.dis_id=dis.dis_id
LEFT JOIN village as vill ON cand.vill_id=vill.vill_id
LEFT JOIN interview_personal as ip ON ip.data_id=cand.cid
LEFT JOIN interview_physical as ph ON ph.data_id=cand.cid
LEFT JOIN interview_experience as ex ON ex.data_id=cand.cid
LEFT JOIN interview_attitude as at ON at.data_id=cand.cid
LEFT JOIN interview_general as gn ON gn.data_id=cand.cid
LEFT JOIN interview_collateral as cl ON cl.data_id=cand.cid
LEFT JOIN interview_assessment as asmt ON asmt.data_id=cand.cid
WHERE $whereSql
ORDER BY cand.cid ASC
LIMIT $limit OFFSET $offset");
$sql->execute($params);
// ດືງຂໍ້ມູນທັງໝົດເພື່ອຄຳນວນຈຳນວນໜ້າ
$total_result = $conn->prepare("SELECT COUNT(*) as total
FROM candidate_korea as cand
WHERE $whereSql");
$total_result->execute($params);
$total_row = $total_result->fetch(PDO::FETCH_ASSOC);
$total = $total_row['total'];
$total_pages = ceil($total / $limit);

$num = $offset + 1;

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
    <!-- <a href="form/data_entry_add.php" class="btn btn-primary btn-sm px-3 py-2"
        style="font-size:13px; font-weight:600; border-radius:8px;">
        <i class="bi bi-plus-lg me-1"></i> Add
    </a> -->
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
                <input type="text" name="all" id="all" placeholder="ຄົ້ນຫາຊື່ / ນາມສະກຸນ, Passport..." value="<?= $all ?>">
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
                    <th class="text-center" style="width:130px;">
                        <label class="mb-0" style="font-weight:600; cursor:pointer;">
                            <input type="checkbox" id="checkAll"> ເລືອກທັງໝົດ
                        </label>
                    </th>
                    <th>ສະຖານະ ການອານຸມັດ</th>
                    <th>ສະຖານະ ການສຳພາດ</th>
                    <th>ລຳດັບ</th>
                    <th>CID</th>
                    <th>ຊື່ ແລະ ນາມສະກຸນ</th>
                    <th>ເບີໂທຕິດຕໍ່</th>
                    <th>Passport</th>
                    <th>project code/Job No.</th>
                    <th>ບ້ານ</th>
                    <th>ເມືອງ</th>
                    <th>ແຂວງ</th>
                    <th>ຂໍ້ມູນສ່ວນຕົວ (PERSONAL INFORMATION)</th>
                    <th>ດ້ານຮ່າງກາຍ (Physical)</th>
                    <th>ປະສົບການ ເຮັດວຽກ (Work Experience)</th>
                    <th>ທັດສະນະຄະຕິ ແລະ ທັກສະອື່ນ</th>
                    <th>ຄຳຖາມສຳພາດທົ່ວໄປ</th>
                    <th>ສຳພາດ ຫຼັກຊັບ</th>
                    <th>ຜົນການປະເມິນຂອງຜູ້ສຳພາດ (INTERVIEWER ASSESSMENT)</th>
                </tr>
            </thead>
            <tbody id="userTbody">
                
                   
                    <?php foreach ($sql as $row):
                        $part = $row['gender'] == 'F' ? 'ນ. ' : 'ທ. ';
                        $full_name_lao = $part.$row['fname']." ".$row['lname'];
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-check" value="<?= htmlspecialchars($row['cid'] ?? '') ?>">
                            </td>
                            <td>
                                <?php
                                    $text = '';
                                    $colors = '';
                                    if($row['sts_data'] == 'Approve'){
                                        $text = 'Approve';
                                        $colors = 'success';
                                    }else if($row['sts_data'] == 'Pending' || $row['sts_data'] == ''){
                                        $text = 'Pending';
                                        $colors = 'warning text-light';
                                    }
                                ?>
                                <div class="badge badge-approve bg-<?= $colors ?>" style="font-size: 14px;"><?= $text ?></div>
                            </td>
                            <?php // ສະຖານະການສຳພາດ (ຢືນຢັນຄົບ 7 ຟອມ = Finished) ?>
                            <td>
                                <?php if (($row['sts_interview'] ?? 'Pending') === 'Finished'): ?>
                                    <div class="badge badge-interview bg-success" style="font-size: 14px;">Finished</div>
                                <?php else: ?>
                                    <div class="badge badge-interview bg-warning text-light" style="font-size: 14px;">Pending</div>
                                <?php endif ?>
                            </td>
                            <td><?= $num++; ?></td>
                            <td><?= htmlspecialchars($row['cid'] ?? '') ?></td>
                            <td><?= htmlspecialchars($full_name_lao ?? '') ?></td>
                            <td><?= htmlspecialchars($row['phone1'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['passport'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['project_code'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['vill_name_lao'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['dis_name_lao'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['pro_name_lao'] ?? '') ?></td>
                            <?php // ລະຫັດ cid ຂອງແຖວນີ້ (ໃຊ້ສຳລັບປຸ່ມຕ່າງໆ)
                            $btn_cid = htmlspecialchars($row['cid'] ?? '');
                            ?>
                            <?php // ຂໍ້ມູນສ່ວນຕົວ: ຖ້າ Verify ແລ້ວ ໃຫ້ສະແດງເຄື່ອງໝາຍຕິກຖືກ ແທນປຸ່ມແກ້ໄຂ ?>
                            <td>
                                <a href="print/print_interview.php?cid=<?= $btn_cid ?>" target="_blank" class="btn btn-outline-warning btn-sm" title="ພິມ">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php if (($row['personal_sts'] ?? '') === 'Verify'): ?>
                                    <span class="text-success" title="ຢືນຢັນແລ້ວ" style="font-size:20px; vertical-align:middle;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="form/interview_personal_edit.php?cid=<?= $btn_cid ?>" class="btn-edit" title="ແກ້ໄຂ">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php // ດ້ານຮ່າງກາຍ: ຖ້າ Verify ແລ້ວ ໃຫ້ສະແດງເຄື່ອງໝາຍຕິກຖືກ ແທນປຸ່ມແກ້ໄຂ ?>
                            <td>
                                <a href="print/print_interview.php?cid=<?= $btn_cid ?>" target="_blank" class="btn btn-outline-warning btn-sm" title="ພິມ">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php if (($row['physical_sts'] ?? '') === 'Verify'): ?>
                                    <span class="text-success" title="ຢືນຢັນແລ້ວ" style="font-size:20px; vertical-align:middle;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="form/interview_physical_edit.php?cid=<?= $btn_cid ?>" class="btn-edit" title="ແກ້ໄຂ">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php // ປະສົບການເຮັດວຽກ: ຖ້າ Verify ແລ້ວ ໃຫ້ສະແດງເຄື່ອງໝາຍຕິກຖືກ ແທນປຸ່ມແກ້ໄຂ ?>
                            <td>
                                <a href="print/print_interview.php?cid=<?= $btn_cid ?>" target="_blank" class="btn btn-outline-warning btn-sm" title="ພິມ">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php if (($row['experience_sts'] ?? '') === 'Verify'): ?>
                                    <span class="text-success" title="ຢືນຢັນແລ້ວ" style="font-size:20px; vertical-align:middle;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="form/interview_experience_edit.php?cid=<?= $btn_cid ?>" class="btn-edit" title="ແກ້ໄຂ">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php // ທັດສະນະຄະຕິ ແລະ ທັກສະອື່ນ: ຖ້າ Verify ແລ້ວ ໃຫ້ສະແດງເຄື່ອງໝາຍຕິກຖືກ ແທນປຸ່ມແກ້ໄຂ ?>
                            <td>
                                <a href="print/print_interview.php?cid=<?= $btn_cid ?>" target="_blank" class="btn btn-outline-warning btn-sm" title="ພິມ">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php if (($row['attitude_sts'] ?? '') === 'Verify'): ?>
                                    <span class="text-success" title="ຢືນຢັນແລ້ວ" style="font-size:20px; vertical-align:middle;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="form/interview_attitude_edit.php?cid=<?= $btn_cid ?>" class="btn-edit" title="ແກ້ໄຂ">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php // ຄຳຖາມສຳພາດທົ່ວໄປ: ຖ້າ Verify ແລ້ວ ໃຫ້ສະແດງເຄື່ອງໝາຍຕິກຖືກ ແທນປຸ່ມແກ້ໄຂ ?>
                            <td>
                                <a href="print/print_interview.php?cid=<?= $btn_cid ?>" target="_blank" class="btn btn-outline-warning btn-sm" title="ພິມ">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php if (($row['general_sts'] ?? '') === 'Verify'): ?>
                                    <span class="text-success" title="ຢືນຢັນແລ້ວ" style="font-size:20px; vertical-align:middle;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="form/interview_general_edit.php?cid=<?= $btn_cid ?>" class="btn-edit" title="ແກ້ໄຂ">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php // ສຳພາດ ຫຼັກຊັບ: ຖ້າ Verify ແລ້ວ ໃຫ້ສະແດງເຄື່ອງໝາຍຕິກຖືກ ແທນປຸ່ມແກ້ໄຂ ?>
                            <td>
                                <a href="print/print_interview.php?cid=<?= $btn_cid ?>" target="_blank" class="btn btn-outline-warning btn-sm" title="ພິມ">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php if (($row['collateral_sts'] ?? '') === 'Verify'): ?>
                                    <span class="text-success" title="ຢືນຢັນແລ້ວ" style="font-size:20px; vertical-align:middle;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="form/interview_collateral_edit.php?cid=<?= $btn_cid ?>" class="btn-edit" title="ແກ້ໄຂ">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php // ຜົນການປະເມີນຂອງຜູ້ສຳພາດ: ຖ້າ Verify ແລ້ວ ໃຫ້ສະແດງເຄື່ອງໝາຍຕິກຖືກ ແທນປຸ່ມແກ້ໄຂ ?>
                            <td>
                                <a href="print/print_interview.php?cid=<?= $btn_cid ?>" target="_blank" class="btn btn-outline-warning btn-sm" title="ພິມ">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php if (($row['assessment_sts'] ?? '') === 'Verify'): ?>
                                    <span class="text-success" title="ຢືນຢັນແລ້ວ" style="font-size:20px; vertical-align:middle;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php else: ?>
                                    <a href="form/interview_assessment_edit.php?cid=<?= $btn_cid ?>" class="btn-edit" title="ແກ້ໄຂ">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                <?php endif ?>
                            </td>
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
        // ເລືອກທັງໝົດໃນຕາຕະລາງ (Select All)
        $("#checkAll").on("change", function () {
            $("#userTbody .row-check:not(:disabled)").prop("checked", $(this).prop("checked"));
        });

        // ===================================================
        // ອະນຸມັດດ້ວຍການຕິກ checkbox ຄໍລໍາແລກ
        // ເງື່ອນໄຂ: ສະຖານະການສຳພາດຕ້ອງເປັນ Finished (ຢືນຢັນຄົບ 7 ຟອມ)
        // ===================================================
        $("#userTbody").on("change", ".row-check", function () {
            if (!this.checked) {
                return;
            }

            let $cb = $(this);
            let cid = $cb.val();

            $.ajax({
                type: "post",
                url: "insert/approve_candidate.php",
                data: { cid: cid },
                dataType: "json",
                success: function (response) {
                    if (response.sts === 'error') {
                        showToast(response.message, 'error');
                        $cb.prop("checked", false);
                        return;
                    }

                    showToast(response.message, 'success');

                    // ອັບເດດ badge ໃນແຖວນັ້ນ
                    $cb.closest("tr").find(".badge-approve")
                        .removeClass("bg-warning text-light")
                        .addClass("bg-success")
                        .text("Approve");

                    $cb.prop("disabled", true);
                },
                error: function (xhr, status, error) {
                    showToast('An error occurred: ' + error, 'error');
                    $cb.prop("checked", false);
                }
            });
        });

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