<?php
include('../header.php');
$data_id = $_GET['data_id'];
$sql = $conn->prepare("SELECT * FROM data_entry WHERE data_id = ?");
$sql->execute([$data_id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);
$sql_pro = $conn->prepare("SELECT * FROM province ORDER BY pro_id ASC");
$sql_pro->execute();
$pro = $sql_pro->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    :root {
        --green-dark: #1a4d2e;
        --green-mid: #000;
        --green-btn: #2d9e5f;
        --green-light: #e8f5ee;
        --green-border: #d1e8d8;
        --green-text: #000;
    }

    body {
        background: #f0f4f0;
    }

    .card {
        border: 1px solid var(--green-border);
        border-radius: 10px;
    }

    /* ===== ຫົວ section ຟອມ ===== */
    .section-head {
        background: #f5fbf7;
        border-bottom: 1px solid var(--green-border);
        padding: 10px 16px;
        font-size: 12px;
        font-weight: 700;
        color: var(--green-mid);
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--green-mid);
        margin-bottom: 4px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--green-btn);
        box-shadow: 0 0 0 2px rgba(45, 158, 95, .12);
    }

    .btn-main {
        background: var(--green-dark);
        color: #fff;
        border: none;
        font-weight: 600;
    }

    .btn-main:hover {
        background: var(--green-mid);
        color: #fff;
    }

    .required {
        color: #e24b4a;
    }

    .form-hint {
        font-size: 11px;
        color: var(--green-text);
        margin-top: 3px;
    }

    .custom-card {
        background-color: #212121;
        border: 1px solid #2d2d2d;
        border-radius: 12px;
        overflow: hidden;
    }

    .custom-card-header {
        background-color: #0b5135;
        /* สีเขียวเข้ม */
        color: #ffffff;
        padding: 15px 20px;
        font-size: 1.25rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .upload-box {
        border: 2px dashed var(--green-border);
        border-radius: 8px;
        background-color: #ffffff;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .upload-box:hover {
        border-color: var(--green-btn);
        background-color: #fafcfa;
    }

    /* เมื่อมีการเลือกไฟล์สำเร็จ */
    .upload-box.has-file {
        border-color: var(--green-btn);
        background-color: #f5fbf7;
        border-style: solid;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        background-color: #e8f5e9;
        color: #0b5135;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        font-size: 1.5rem;
    }

    /* สไตล์ของแท็บระบบลายเซ็น */
    .nav-pills .nav-link {
        color: var(--green-mid);
        border: 1px solid var(--green-border);
        margin-bottom: 10px;
        background-color: #ffffff;
        font-size: 12px;
        font-weight: 600;
    }

    .nav-pills .nav-link.active {
        background-color: var(--green-dark);
        color: white;
        border-color: var(--green-dark);
    }

    /* พื้นที่สำหรับเซ็นชื่อ */
    .canvas-container {
        background-color: #fff;
        border: 2px dashed var(--green-border);
        border-radius: 8px;
        overflow: hidden;
        position: relative;
    }

    canvas {
        display: block;
        cursor: crosshair;
        width: 100%;
        height: 155px;
        background: #ffffff;
    }

    .text-muted-custom {
        color: #888;
        font-size: 0.85rem;
    }

    .asterisk {
        color: #dc3545;
    }

    /* สไตล์สำหรับรูปภาพ Preview */
    .preview-img {
        max-height: 140px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>ລາຍການພະແນກແກ້ໄຂຂໍ້ມູນ
        </h5>
    </div>
</div>
<div class="card shadow-none" style="max-width:1920px;">

    <form method="POST" id="edit_name" enctype="multipart/form-data">
        <div class="section-head">
            <i class="bi bi-person me-2"></i>ຂໍ້ມູນສ່ວນຕົວ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ວັນທີ່ <span class="required">*</span></label>
                    <input type="date" name="data_date_create" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_date_create'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ລະຫັດ <span class="required">*</span></label>
                    <input type="text" name="data_id" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_id'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຊື່ (ພາສາລາວ) <span class="required">*</span></label>
                    <input type="text" name="data_fname" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_fname'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ນາມສະກຸນ (ພາສາລາວ) <span class="required">*</span></label>
                    <input type="text" name="data_lname" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_lname'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຊື່ (ອັງກິດ) <span class="required">*</span></label>
                    <input type="text" name="data_fname_eng" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_fname_eng'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ນາມສະກຸນ (ອັງກິດ) <span class="required">*</span></label>
                    <input type="text" name="data_lname_eng" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_lname_eng'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ວັນເດືອນປີເກີດ <span class="required">*</span></label>
                    <input type="date" name="data_dob" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_dob'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເພດ <span class="required">*</span></label>
                    <select name="data_gender" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <option value="F" <?= (isset($row['data_gender']) && $row['data_gender'] == 'F') ? 'selected' : '' ?>>ຍິງ</option>
                        <option value="M" <?= (isset($row['data_gender']) && $row['data_gender'] == 'M') ? 'selected' : '' ?>>ຊາຍ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອາຍຸ <span class="required">*</span></label>
                    <input type="text" name="data_age" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_age'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ໝາຍເລກປະຈຳຕົວ <span class="required">*</span></label>
                    <input type="text" name="data_identity" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_identity'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເລກທີ Passport <span class="required">*</span></label>
                    <input type="text" name="data_passport" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_passport'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Passprt Issue date <span class="required">*</span></label>
                    <input type="date" name="data_pass_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_pass_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Passport termination date <span class="required">*</span></label>
                    <input type="date" name="data_ex_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_ex_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Phone Number <span class="required">*</span></label>
                    <input type="text" name="data_phone" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_phone'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Married couple <span class="required">*</span></label>
                    <input type="text" name="data_sts" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_sts'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-house-door-fill me-2"></i>ທີ່ຢູ່ປັດຈຸບັນ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ <span class="required">*</span></label>
                    <select name="pro_id" id="pro_id" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <?php foreach ($pro as $proa): ?>
                            <option value="<?= $proa['pro_id'] ?>" <?= (isset($row['pro_id']) && $row['pro_id'] == $proa['pro_id']) ? 'selected' : '' ?>><?= $proa['pro_name_lao'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ <span class="required">*</span></label>
                    <select name="dis_id" id="dis_id" class="form-select form-select-sm">
                        <option value=""><?= $row['dis_id'] ?></option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ <span class="required">*</span></label>
                    <select name="vill_id" id="vill_id" class="form-select form-select-sm">
                        <option value=""><?= $row['vill_id'] ?></option>
                    </select>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-credit-card me-2"></i>Free visa
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">Free visa <span class="required">*</span></label>
                    <input type="text" name="data_free_visa" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_free_visa'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Code <span class="required">*</span></label>
                    <input type="text" name="data_code" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_code'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Extend 3 months <span class="required">*</span></label>
                    <input type="text" name="data_exten" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_exten'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Come back Laos <span class="required">*</span></label>
                    <input type="text" name="data_come_back" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_come_back'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-building me-2"></i>Employer
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">Requested Month <span class="required">*</span></label>
                    <input type="text" name="data_reques" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_reques'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ທີ່ຢູ່ <span class="required">*</span></label>
                    <input type="text" name="data_address" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_address'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເບີໂທ <span class="required">*</span></label>
                    <input type="text" name="data_tel" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_tel'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ລະຫັດນາຍຈ້າງ <span class="required">*</span></label>
                    <input type="text" name="data_emp_id" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_emp_id'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ທີ່ຢູ່ນາຍຈ້າງ <span class="required">*</span></label>
                    <input type="text" name="data_emp_add" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_emp_add'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຊື່ນາຍຈ້າງ <span class="required">*</span></label>
                    <input type="text" name="data_emp_name" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_emp_name'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ປະເພດ <span class="required">*</span></label>
                    <input type="text" name="data_type" class="form-control form-control-sm" value="<?= htmlspecialchars($row['data_type'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="section-head">
            <i class="bi bi-house-door-fill me-2"></i>ໝາຍເຫດເພີ່ມເຕີມ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12">
                    <label for="" class="form-label fw-bold mb-2">ໝາຍເຫດ</label>
                    <textarea name="data_remark" rows="3" class="form-control form-control-sm"><?= htmlspecialchars($row['data_remark'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 px-3 py-2 border-top" style="background:#fafcfa;border-color:var(--green-border)!important;">
            <a href="../list_name.php" class="btn btn-sm btn-outline-secondary px-4">
                <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
            </a>
            <button type="submit" class="btn btn-sm btn-primary px-4">
                <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
            </button>
        </div>
    </form>
</div>
<?php
include('../footer.php');
?>
<script>
    $(document).ready(function() {
        $("#pro_id").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_pro.php",
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
                url: "../get/get_dis.php",
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