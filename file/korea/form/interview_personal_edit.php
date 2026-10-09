<?php
include('../header.php');
$item_id = $_SESSION['item_id'];
$item_ids = explode(',', $item_id);
if (!in_array('0107', $item_ids)) {
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

$cid = $_REQUEST['cid'] ?? '';

// ===================================================
// ດຶງຂໍ້ມູນຜູ້ສະໝັກ + ຂໍ້ມູນສ່ວນຕົວ (ຖ້າມີໃນ interview_personal ໃຫ້ໃຊ້ຄ່ານັ້ນ, ຖ້າບໍ່ມີດຶງຈາກ candidate_korea)
// ===================================================
$sql = $conn->prepare("SELECT
    cand.cid,
    cand.labor_type AS cand_labor_type,
    COALESCE(ip.fname, cand.fname) AS fname,
    COALESCE(ip.lname, cand.lname) AS lname,
    COALESCE(ip.fname_eng, cand.fname_eng) AS fname_eng,
    COALESCE(ip.lname_eng, cand.lname_eng) AS lname_eng,
    COALESCE(ip.pro_id, cand.pro_id) AS pro_id,
    COALESCE(ip.dis_id, cand.dis_id) AS dis_id,
    COALESCE(ip.vill_id, cand.vill_id) AS vill_id,
    COALESCE(ip.dob, cand.dob) AS dob,
    COALESCE(ip.gender, cand.gender) AS gender,
    COALESCE(ip.status, cand.status) AS status,
    COALESCE(ip.phone1, cand.phone1) AS phone1,
    ip.spouse_name,
    ip.has_children,
    ip.children_male,
    ip.children_female,
    COALESCE(ip.pro_id_b, cand.pro_id_b) AS pro_id_b,
    COALESCE(ip.dis_id_b, cand.dis_id_b) AS dis_id_b,
    COALESCE(ip.vill_id_b, cand.vill_id_b) AS vill_id_b,
    COALESCE(ip.father, cand.father) AS father,
    COALESCE(ip.mother, cand.mother) AS mother,
    COALESCE(ip.id_no, cand.id_no) AS id_no,
    ip.id_issue_date,
    ip.id_exp_date,
    ip.id_issue_by,
    COALESCE(ip.passport, cand.passport) AS passport,
    COALESCE(ip.pass_issue_date, cand.issue_date) AS pass_issue_date,
    COALESCE(ip.pass_exp_date, cand.exp_date) AS pass_exp_date,
    ip.pass_issue_by,
    ip.emg1_name, ip.emg1_relation, ip.emg1_phone, ip.emg1_id_no,
    ip.emg1_issue_date, ip.emg1_exp_date, ip.emg1_issue_by,
    ip.emg1_pro_id, ip.emg1_dis_id, ip.emg1_vill_id,
    ip.emg2_name, ip.emg2_relation, ip.emg2_phone, ip.emg2_id_no,
    ip.emg2_issue_date, ip.emg2_exp_date, ip.emg2_issue_by,
    ip.emg2_pro_id, ip.emg2_dis_id, ip.emg2_vill_id,
    COALESCE(ip.project_code, (SELECT code FROM labor_korea WHERE data_id = cand.cid LIMIT 1)) AS project_code,
    ip.set_no,
    ip.labor_type,
    ip.cif,
    ip.sts_save,
    ip.verify_by,
    ip.verify_date,
    dis.dis_name_lao,
    vill.vill_name_lao,
    dis_b.dis_name_lao AS dis_name_b,
    vill_b.vill_name_lao AS vill_name_b,
    dis_e1.dis_name_lao AS emg1_dis_name,
    vill_e1.vill_name_lao AS emg1_vill_name,
    dis_e2.dis_name_lao AS emg2_dis_name,
    vill_e2.vill_name_lao AS emg2_vill_name
FROM candidate_korea AS cand
LEFT JOIN interview_personal AS ip ON ip.data_id = cand.cid
LEFT JOIN district AS dis ON dis.dis_id = COALESCE(ip.dis_id, cand.dis_id)
LEFT JOIN village AS vill ON vill.vill_id = COALESCE(ip.vill_id, cand.vill_id)
LEFT JOIN district AS dis_b ON dis_b.dis_id = COALESCE(ip.dis_id_b, cand.dis_id_b)
LEFT JOIN village AS vill_b ON vill_b.vill_id = COALESCE(ip.vill_id_b, cand.vill_id_b)
LEFT JOIN district AS dis_e1 ON dis_e1.dis_id = ip.emg1_dis_id
LEFT JOIN village AS vill_e1 ON vill_e1.vill_id = ip.emg1_vill_id
LEFT JOIN district AS dis_e2 ON dis_e2.dis_id = ip.emg2_dis_id
LEFT JOIN village AS vill_e2 ON vill_e2.vill_id = ip.emg2_vill_id
WHERE cand.cid = ?");
$sql->execute([$cid]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

// ຖ້າບໍ່ພົບຂໍ້ມູນຜູ້ສະໝັກ
if (!$row) {
?>
    <script>
        Swal.fire({
            icon: "error",
            title: "ບໍ່ພົບຂໍ້ມູນ",
            text: "ບໍ່ພົບຂໍ້ມູນຜູ້ສະໝັກ ທີ່ຕ້ອງການແກ້ໄຂ",
            confirmButtonText: "ກັບຄືນ",
            confirmButtonColor: "#dc3545",
            allowOutsideClick: false
        }).then(() => {
            window.history.back();
        });
    </script>
<?php
    exit();
}

// ສະຖານະການຢືນຢັນ (ຖ້າ Verify ແລ້ວ ຈະລັອກຟອມ)
$locked = ($row['sts_save'] ?? '') === 'Verify';

// ລາຍຊື່ແຂວງ
$pro = $conn->query("SELECT * FROM province ORDER BY pro_id ASC")->fetchAll(PDO::FETCH_ASSOC);

// ຄ່າທີ່ຈະໃຊ້ໃນຟອມ
$gender_val = $row['gender'] ?? '';
$status_val = strtolower($row['status'] ?? '');
$has_children_val = $row['has_children'] ?? '';
// ລະຫັດ CIF ສ້າງອັດຕະໂນມັດ (cid-01) ແລະ ບໍ່ໃຫ້ແກ້ໄຂ
$cif = $row['cid'] . '-01';

// ຄ່າແຮງງານ: ຖ້າຍັງບໍ່ມີໃນ interview_personal ໃຫ້ແປງຈາກ candidate_korea
$labor_type = $row['labor_type'];
if ($labor_type === null || $labor_type === '') {
    if (in_array($row['cand_labor_type'], ['New', 'New(RC)'])) {
        $labor_type = 'ແຮງງານໃໝ່';
    } else if (in_array($row['cand_labor_type'], ['Re-New', 'Re-entry', 'Re-employment'])) {
        $labor_type = 'ແຮງງານເກົ່າ';
    }
}

// ===================================================
// ຟັງຊັນຊ່ວຍສ້າງ dropdown ແຂວງ / ເມືອງ / ບ້ານ
// ===================================================
function ipRenderPro($name, $selected, $disSel, $villSel, $pro)
{
    echo '<select name="' . $name . '" id="' . $name . '" class="form-select form-select-sm sel-pro" data-dis="' . $disSel . '" data-vill="' . $villSel . '">';
    echo '<option value="">ເລືອກ</option>';
    foreach ($pro as $p) {
        $sel = ($selected == $p['pro_id']) ? ' selected' : '';
        echo '<option value="' . $p['pro_id'] . '"' . $sel . '>' . htmlspecialchars($p['pro_name_lao']) . '</option>';
    }
    echo '</select>';
}

function ipRenderDis($name, $selectedId, $selectedName, $villSel)
{
    echo '<select name="' . $name . '" id="' . $name . '" class="form-select form-select-sm sel-dis" data-vill="' . $villSel . '">';
    if ($selectedId) {
        echo '<option value="' . htmlspecialchars($selectedId) . '" selected>' . htmlspecialchars($selectedName ?: $selectedId) . '</option>';
    } else {
        echo '<option value="">ເລືອກ</option>';
    }
    echo '</select>';
}

function ipRenderVill($name, $selectedId, $selectedName)
{
    echo '<select name="' . $name . '" id="' . $name . '" class="form-select form-select-sm">';
    if ($selectedId) {
        echo '<option value="' . htmlspecialchars($selectedId) . '" selected>' . htmlspecialchars($selectedName ?: $selectedId) . '</option>';
    } else {
        echo '<option value="">ເລືອກ</option>';
    }
    echo '</select>';
}
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

    .section-head {
        background: #f5fbf7;
        border-bottom: 1px solid var(--green-border);
        padding: 10px 16px;
        font-size: 13px;
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

    .check-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 20px;
        padding: 6px 2px 2px;
    }

    .check-group label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #222;
        cursor: pointer;
        margin: 0;
    }

    .check-group input {
        width: 15px;
        height: 15px;
        accent-color: var(--green-btn);
        cursor: pointer;
    }

    .form-hint {
        font-size: 11px;
        color: var(--green-text);
        margin-top: 3px;
    }
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-person-vcard me-2 text-primary"></i>ແບບຟອມສຳພາດງານ ແຮງງານລະດູການ - ຂໍ້ມູນສ່ວນຕົວ (PERSIONAL INFORMATION)
        </h5>
    </div>
</div>

<form method="POST" id="personal_form" enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?= htmlspecialchars($row['cid']) ?>">

    <div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
        <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
        </a>
        <button type="submit" class="btn btn-sm btn-primary px-4 btn-save">
            <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
        </button>
        <button type="button" id="personal_verify" class="btn btn-sm btn-success px-4 btn-verify">
            <i class="bi bi-check2-circle me-1"></i> Verify
        </button>
    </div>

    <div class="card shadow-none mt-2" style="max-width:1920px;">

        <!-- 1. ຊື່ ແລະ ນາມສະກຸນ -->
        <div class="section-head">
            <i class="bi bi-person me-2"></i>1. ຊື່ ແລະ ນາມສະກຸນ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">ຊື່ :</label>
                    <input type="text" name="fname" class="form-control form-control-sm" value="<?= htmlspecialchars($row['fname'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">ນາມສະກຸນ :</label>
                    <input type="text" name="lname" class="form-control form-control-sm" value="<?= htmlspecialchars($row['lname'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">ຊື່ ພາສາອັງກິດ :</label>
                    <input type="text" name="fname_eng" class="form-control form-control-sm" value="<?= htmlspecialchars($row['fname_eng'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">ນາມສະກຸນ ພາສາອັງກິດ :</label>
                    <input type="text" name="lname_eng" class="form-control form-control-sm" value="<?= htmlspecialchars($row['lname_eng'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 2. ທີ່ຢູ່ປັດຈຸບັນ -->
        <div class="section-head">
            <i class="bi bi-house-door-fill me-2"></i>2. ທີ່ຢູ່ປັດຈຸບັນ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ :</label>
                    <?php ipRenderPro('pro_id', $row['pro_id'], '#dis_id', '#vill_id', $pro); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ :</label>
                    <?php ipRenderDis('dis_id', $row['dis_id'], $row['dis_name_lao'], '#vill_id'); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ :</label>
                    <?php ipRenderVill('vill_id', $row['vill_id'], $row['vill_name_lao']); ?>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 3. ຂໍ້ມູນສ່ວນຕົວ -->
        <div class="section-head">
            <i class="bi bi-person-lines-fill me-2"></i>3. ຂໍ້ມູນສ່ວນຕົວ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ວັນເດືອນປີເກີດ :</label>
                    <input type="date" name="dob" class="form-control form-control-sm" value="<?= htmlspecialchars($row['dob'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເພດ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="gender" value="M" <?= $gender_val == 'M' ? 'checked' : '' ?>> ຊາຍ</label>
                        <label><input type="radio" name="gender" value="F" <?= $gender_val == 'F' ? 'checked' : '' ?>> ຍິງ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ສະຖານະ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="status" value="single" <?= $status_val == 'single' ? 'checked' : '' ?>> ໂສດ</label>
                        <label><input type="radio" name="status" value="married" <?= $status_val == 'married' ? 'checked' : '' ?>> ແຕ່ງງານ</label>
                        <label><input type="radio" name="status" value="divorced" <?= $status_val == 'divorced' ? 'checked' : '' ?>> ຮ້າງ/ໝ້າຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເບີໂທລະສັບ :</label>
                    <input type="text" name="phone1" class="form-control form-control-sm" value="<?= htmlspecialchars($row['phone1'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4" id="spouse_wrap" style="<?= $status_val == 'married' ? '' : 'display:none;' ?>">
                    <label class="form-label">ຊື່ຜົວ ຫຼື ເມຍ :</label>
                    <input type="text" name="spouse_name" class="form-control form-control-sm" value="<?= htmlspecialchars($row['spouse_name'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ມີລູກຈັກຄົນ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="has_children" value="no" <?= $has_children_val == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        <label><input type="radio" name="has_children" value="yes" <?= $has_children_val == 'yes' ? 'checked' : '' ?>> ມີ</label>
                    </div>
                </div>
                <div class="col-12" id="children_wrap" style="<?= $has_children_val == 'yes' ? '' : 'display:none;' ?>">
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ລູກຍິງ (ຄົນ) :</label>
                            <input type="number" min="0" name="children_female" class="form-control form-control-sm" value="<?= htmlspecialchars($row['children_female'] ?? '') ?>">
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ລູກຊາຍ (ຄົນ) :</label>
                            <input type="number" min="0" name="children_male" class="form-control form-control-sm" value="<?= htmlspecialchars($row['children_male'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 4. ບ້ານເກີດ -->
        <div class="section-head">
            <i class="bi bi-geo-alt-fill me-2"></i>4. ບ້ານເກີດ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ :</label>
                    <?php ipRenderPro('pro_id_b', $row['pro_id_b'], '#dis_id_b', '#vill_id_b', $pro); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ :</label>
                    <?php ipRenderDis('dis_id_b', $row['dis_id_b'], $row['dis_name_b'], '#vill_id_b'); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ :</label>
                    <?php ipRenderVill('vill_id_b', $row['vill_id_b'], $row['vill_name_b']); ?>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 5. ຊື່ພໍ່ ແລະ ຊື່ແມ່ -->
        <div class="section-head">
            <i class="bi bi-people me-2"></i>5. ຊື່ພໍ່ ແລະ ຊື່ແມ່
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label">ຊື່ພໍ່ :</label>
                    <input type="text" name="father" class="form-control form-control-sm" value="<?= htmlspecialchars($row['father'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label">ຊື່ແມ່ :</label>
                    <input type="text" name="mother" class="form-control form-control-sm" value="<?= htmlspecialchars($row['mother'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 6. ບັດປະຈຳໂຕ -->
        <div class="section-head">
            <i class="bi bi-person-badge me-2"></i>6. ບັດປະຈຳໂຕ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບັດປະຈຳໂຕ ເລກທີ :</label>
                    <input type="text" name="id_no" class="form-control form-control-sm" value="<?= htmlspecialchars($row['id_no'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ວັນທີ :</label>
                    <input type="date" name="id_issue_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['id_issue_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ໝົດອາຍຸວັນທີ :</label>
                    <input type="date" name="id_exp_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['id_exp_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ໂດຍ :</label>
                    <input type="text" name="id_issue_by" class="form-control form-control-sm" value="<?= htmlspecialchars($row['id_issue_by'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 7. Passport -->
        <div class="section-head">
            <i class="bi bi-passport me-2"></i>7. Passport
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">Passport ເລກທີ :</label>
                    <input type="text" name="passport" class="form-control form-control-sm" value="<?= htmlspecialchars($row['passport'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ວັນທີ :</label>
                    <input type="date" name="pass_issue_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['pass_issue_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ໝົດອາຍຸວັນທີ :</label>
                    <input type="date" name="pass_exp_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['pass_exp_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ໂດຍ :</label>
                    <input type="text" name="pass_issue_by" class="form-control form-control-sm" value="<?= htmlspecialchars($row['pass_issue_by'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 8. ບຸກຄົນຕິດຕໍ່ສຸກເສີນ ຜູ້ທີ 1 -->
        <div class="section-head">
            <i class="bi bi-telephone-plus me-2"></i>8. ບຸກຄົນຕິດຕໍ່ສຸກເສີນ ຜູ້ທີ 1
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຊື່ ແລະ ນາມສະກຸນ :</label>
                    <input type="text" name="emg1_name" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg1_name'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ສາຍສຳພັນ :</label>
                    <input type="text" name="emg1_relation" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg1_relation'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເບີໂທລະສັບ :</label>
                    <input type="text" name="emg1_phone" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg1_phone'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບັດປະຈຳໂຕ/Passport ເລກທີ :</label>
                    <input type="text" name="emg1_id_no" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg1_id_no'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ວັນທີ :</label>
                    <input type="date" name="emg1_issue_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg1_issue_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ໝົດອາຍຸວັນທີ :</label>
                    <input type="date" name="emg1_exp_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg1_exp_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ໂດຍ :</label>
                    <input type="text" name="emg1_issue_by" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg1_issue_by'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ (ທີ່ຢູ່ປັດຈຸບັນ) :</label>
                    <?php ipRenderPro('emg1_pro_id', $row['emg1_pro_id'], '#emg1_dis_id', '#emg1_vill_id', $pro); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ :</label>
                    <?php ipRenderDis('emg1_dis_id', $row['emg1_dis_id'], $row['emg1_dis_name'], '#emg1_vill_id'); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ :</label>
                    <?php ipRenderVill('emg1_vill_id', $row['emg1_vill_id'], $row['emg1_vill_name']); ?>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 9. ບຸກຄົນຕິດຕໍ່ສຸກເສີນ ຜູ້ທີ 2 -->
        <div class="section-head">
            <i class="bi bi-telephone-plus me-2"></i>9. ບຸກຄົນຕິດຕໍ່ສຸກເສີນ ຜູ້ທີ 2
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຊື່ ແລະ ນາມສະກຸນ :</label>
                    <input type="text" name="emg2_name" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg2_name'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ສາຍສຳພັນ :</label>
                    <input type="text" name="emg2_relation" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg2_relation'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເບີໂທລະສັບ :</label>
                    <input type="text" name="emg2_phone" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg2_phone'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບັດປະຈຳໂຕ/Passport ເລກທີ :</label>
                    <input type="text" name="emg2_id_no" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg2_id_no'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ວັນທີ :</label>
                    <input type="date" name="emg2_issue_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg2_issue_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ໝົດອາຍຸວັນທີ :</label>
                    <input type="date" name="emg2_exp_date" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg2_exp_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ອອກໃຫ້ໂດຍ :</label>
                    <input type="text" name="emg2_issue_by" class="form-control form-control-sm" value="<?= htmlspecialchars($row['emg2_issue_by'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ (ທີ່ຢູ່ປັດຈຸບັນ) :</label>
                    <?php ipRenderPro('emg2_pro_id', $row['emg2_pro_id'], '#emg2_dis_id', '#emg2_vill_id', $pro); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ :</label>
                    <?php ipRenderDis('emg2_dis_id', $row['emg2_dis_id'], $row['emg2_dis_name'], '#emg2_vill_id'); ?>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ :</label>
                    <?php ipRenderVill('emg2_vill_id', $row['emg2_vill_id'], $row['emg2_vill_name']); ?>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 10. ຂໍ້ມູນໂຄງການ -->
        <div class="section-head">
            <i class="bi bi-briefcase me-2"></i>10. ຂໍ້ມູນໂຄງການ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label class="form-label">CIF :</label>
                    <input type="text" name="cif" class="form-control form-control-sm" value="<?= htmlspecialchars($cif) ?>" readonly>
                </div>
                <div class="col-12 col-sm-3">
                    <label class="form-label">ລະຫັດ Project :</label>
                    <input type="text" name="project_code" class="form-control form-control-sm" value="<?= htmlspecialchars($row['project_code'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="form-label">ຊຸດທີ :</label>
                    <input type="text" name="set_no" class="form-control form-control-sm" value="<?= htmlspecialchars($row['set_no'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="form-label">ແຮງງານ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="labor_type" value="ແຮງງານໃໝ່" <?= $labor_type == 'ແຮງງານໃໝ່' ? 'checked' : '' ?>> ແຮງງານໃໝ່</label>
                        <label><input type="radio" name="labor_type" value="ແຮງງານເກົ່າ" <?= $labor_type == 'ແຮງງານເກົ່າ' ? 'checked' : '' ?>> ແຮງງານເກົ່າ</label>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<?php
include('../footer.php');
?>
<script>
    // ສະຖານະການລັອກຟອມ (ຖ້າຢືນຢັນແລ້ວ)
    var isLocked = <?= $locked ? 'true' : 'false' ?>;
</script>
<script src="../js/personal.js?v=<?= filemtime('../js/personal.js') ?>"></script>
