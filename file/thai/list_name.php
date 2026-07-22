<?php
include_once('../check.php');
include_once('header.php');

// search

// ພາກສ່ວນການປ່ຽນໜ້າ
$limit = 500;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = $conn->prepare("SELECT * FROM data_entry 
ORDER BY id ASC
LIMIT $limit OFFSET $offset");
$sql->execute();
// ດືງຂໍ້ມູນທັງໝົດເພື່ອຄຳນວນຈຳນວນໜ້າ
$total_result = $conn->prepare("SELECT COUNT(*) as total  FROM data_entry");
$total_result->execute();
$total_row = $total_result->fetch(PDO::FETCH_ASSOC);
$total_pages = ceil($total_row['total'] / $limit);

$num = $offset + 1;
// total
$sql_total = $conn->prepare("SELECT COUNT(*) FROM data_entry");
$sql_total->execute();
$total = $sql_total->fetch(PDO::FETCH_NUM)[0];
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
            <i class="bi bi-people-fill me-2 text-primary"></i>ລາຍການພະແນກບັນທືກຂໍ້ມູນ
        </h5>
    </div>
    <a href="form/name_add.php" class="btn btn-primary btn-sm px-3 py-2"
        style="font-size:13px; font-weight:600; border-radius:8px;">
        <i class="bi bi-plus-lg me-1"></i> ເພີ່ມ
    </a>
</div>
<!-- ===== ຕາຕະລາງ User ===== -->
<div class="dash-card">
    <div class="dash-card-title">
        <i class="bi bi-table text-primary"></i> ລາຍຊື່ ພະແນກບັນທືກຂໍ້ມູນ ທັງໝົດ
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
                <input type="text" name="all" id="all" placeholder="🔍 ຄົ້ນຫາຊື່ / ນາມສະກຸນ, ຊື່ນາຍຈ້າງ...">
            </div>

            <div class="col-md-2 filter-group">
                <label for="filterRole">ປະເພດ</label>
                <select id="filterRole" name="s_data_type">
                    <option value="">ເລືອກ</option>
                    <option value="Re-entry">Re-entry</option>
                    <option value="New">New</option>
                </select>
            </div>

            <div class="col-md-2 filter-group">
                <label for="f1">ເພດ</label>
                <select id="f1" name="s_data_gender">
                    <option value="">ເລືອກ</option>
                    <option value="F">Female</option>
                    <option value="M">Male</option>
                </select>
            </div>

            <div class="col-md-2 filter-group">
                <label for="f2">ແຂວງ</label>
                <select id="f2" name="s_pro_id">
                    <option value="">ເລືອກ</option>
                </select>
            </div>

            <!-- เพิ่มอีกกี่ filter ก็ได้ ใช้ col-md-2 หรือ col-md-3 ตามต้องการ -->
            <div class="col-md-2 filter-group">
                <label for="f3">ເມືອງ</label>
                <select id="f3" name="s_dis_id">
                    <option value="">ເລືອກ</option>
                </select>
            </div>
            <div class="col-md-2 filter-group">
                <label for="f3">ບ້ານ</label>
                <select id="f3" name="s_vill_id">
                    <option value="">ເລືອກ</option>
                </select>
            </div>
            <div class="col-md-2 filter-group">
                <label for="f3">ປະເພດວັນທີ່</label>
                <select id="f3" name="type">
                    <option value="">ເລືອກ</option>
                </select>
            </div>
            <div class="col-md-2 filter-group">
                <label for="f3">Date</label>
                <input type="date" name="date1" id="">
            </div>
            <div class="col-md-2 filter-group">
                <label for="f3">From To</label>
                <input type="date" name="date2" id="">
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
                    <th>ລຳດັບ</th>
                    <th style="width:100px;" class="text-center">ຈັດການ</th>
                    <th>ລະຫັດ</th>
                    
                    <th>ນາມສະກຸນ (ພາສາອັງກິດ)</th>
                    <th>ຊື່ (ພາສາອັງກິດ)</th>
                    <th>ຊື່ (ພາສາລາວ)</th>
                    <th>ນາມສະກຸນ (ພາສາລາວ)</th>
                    <th>ອາຍຸ</th>
                    <th>ເພດ</th>
                    <th>ວັນທີ່</th>
                    <th>ວ/ດ/ປ ເກີດ</th>
                    <th>ໝາຍເລກປະຈຳຕົວ</th>
                    <th>ເລກທີ Passport</th>
                    <th>Passprt Issue date</th>
                    <th>Passport termination date</th>
                    <th>ບ້ານ</th>
                    <th>ເມືອງ</th>
                    <th>ແຂວງ</th>
                    <th>Phone Number</th>
                    <th>Free visa</th>
                    <th>Code</th>
                    <th>Extend 3 months</th>
                    <th>Come back Laos</th>
                    <th>Requested Month</th>
                    <th>ທີ່ຢູ່</th>
                    <th>ເບີໂທ</th>
                    <th>ລະຫັດນາຍຈ້າງ</th>
                    <th>ທີ່ຢູ່ນາຍຈ້າງ</th>
                    <th>ຊື່ນາຍຈ້າງ</th>
                    <th>ປະເພດ</th>
                    <th>Married couple</th>
                </tr>
            </thead>
            <tbody id="userTbody">
                <?php if ($sql->rowCount() == 0): ?>
                    <!-- ຂໍ້ຄວາມ ຖ້າບໍ່ມີຂໍ້ມູນ -->
                    <div id="emptyMsg" style=" text-align:center; padding:28px; color:#94a3b8; font-size:13px;">
                        <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px; opacity:.4;"></i>
                        ບໍ່ພົບຂໍ້ມູນ
                    </div>
                <?php else: ?>
                    <?php foreach ($sql as $i => $row):

                    ?>
                        <tr>
                            <td><?= $num++; ?></td>
                            <td>
                                <a href="form/name_edit.php?data_id=<?= $row['data_id'] ?>" class="btn-edit"><i class="bi bi-pencil-fill"></i></a>
                                <button type="button" data-data_id="<?= $row['data_id'] ?>" class="btn btn-outline-warning btn-sm"><i class="bi bi-printer"></i></button>
                                <button type="button" data-data_id="<?= $row['data_id'] ?>" class="btn btn-outline-danger btn-sm del_name"><i class="bi bi-trash"></i></button>
                            </td>
                            <td><?= htmlspecialchars($row['data_id'] ?? '') ?></td>
                            
                            <td><?= htmlspecialchars($row['data_lname_eng'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_fname_eng'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_fname'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_lname'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_age'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_gender'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_date_create'] == null ? '' : date_format(date_create($row['data_date_create']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['data_dob'] == null ? '' : date_format(date_create($row['data_dob']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['data_identity'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_passport'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_pass_date'] == null ? '' : date_format(date_create($row['data_pass_date']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['data_ex_date'] == null ? '' : date_format(date_create($row['data_ex_date']), 'd/m/Y')) ?></td>
                            <td><?= htmlspecialchars($row['vill_id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['dis_id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['pro_id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_free_visa'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_code'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_exten'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_come_back'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_reques'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_address'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_tel'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_emp_id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_emp_add'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_emp_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_type'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['data_sts'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif ?>

            </tbody>
        </table>
    </div>
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