<?php
include('../header.php');
$item_id = $_SESSION['item_id'];
$item_ids = explode(',', $item_id);
if (!in_array('0106', $item_ids)) {
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
$cid = $_REQUEST['cid'];
$sql = $conn->prepare("SELECT *,cand.cid FROM candidate_korea as cand 
LEFT JOIN interview_form as inter ON cand.cid=inter.cid
WHERE cand.cid = ?");
$sql->execute([$cid]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

$interviewSql = $conn->prepare("SELECT * FROM interview_form WHERE cid = ?");
$interviewSql->execute([$cid]);
$interview = $interviewSql->fetch(PDO::FETCH_ASSOC) ?: [];
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

    .required {
        color: #e24b4a;
    }

    .form-hint {
        font-size: 11px;
        color: var(--green-text);
        margin-top: 3px;
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

    .eval-table {
        width: 100%;
        border-collapse: collapse;
    }

    .eval-table th, .eval-table td {
        border: 1px solid var(--green-border);
        padding: 8px 10px;
        font-size: 13px;
        vertical-align: top;
    }

    .eval-table th {
        background: #f5fbf7;
        font-weight: 700;
        text-align: left;
    }

    .signature-box {
        border: 1px dashed var(--green-border);
        border-radius: 8px;
        padding: 14px;
        background: #fafcfa;
    }

    @media print {
        body { background: #fff; }
        .no-print { display: none !important; }
        .card { border: 1px solid #999; box-shadow: none; break-inside: avoid; }
        .section-head { background: #eee !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .eval-table th { background: #eee !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>ແບບຟອມສໍາພາດງານ ແຮງງານລະດູການ
        </h5>
    </div>
</div>

<form method="POST" id="interviewForm" enctype="multipart/form-data">

<div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
    <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
        <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
    </a>
    <a href="../print/print_interview.php?cid=<?= $row['cid'] ?>" class="btn btn-sm btn-warning px-4" target="_blank">
        <i class="bi bi-printer me-1"></i> ພິມ
    </a>
    <button type="button" id="inter_save" class="btn btn-sm btn-primary px-4">
        <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
    </button>
    <button type="button" id="inter_save_print" class="btn btn-sm btn-info px-4">
        <i class="bi bi-floppy me-1"></i> ບັນທຶກ ແລະ ພິມ
    </button>
</div>

<div class="card shadow-none mt-2" style="max-width:1920px;">

    <!-- 1. ຂໍ້ມູນສ່ວນຕົວ -->
    <div class="section-head">
        <i class="bi bi-person me-2"></i>1. ຂໍ້ມູນສ່ວນຕົວ (Personal Information)
    </div>
    <div class="p-3">
        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="form-label">ຊື່ ແລະ ນາມສະກຸນ <span class="required">*</span></label>
                <input type="text" name="fullname" class="form-control form-control-sm" value="<?= $row['fname']." ".$row['lname'] ?>" readonly>
                <input type="hidden" name="cid" id="cid" value="<?= $row['cid'] ?>">
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">ວັນເດືອນປີເກີດ <span class="required">*</span></label>
                <input type="date" name="dob" class="form-control form-control-sm" value="<?= $row['dob'] ?>" readonly>
            </div>

            <div class="col-12 col-sm-6">
                <label class="form-label">ເພດ <span class="required">*</span></label>
                <div class="check-group">
                    <label><input type="radio" name="gender" value="M" <?= ($row['gender'] ?? '') == 'M' ? 'checked' : '' ?>> ຊາຍ</label>
                    <label><input type="radio" name="gender" value="F" <?= ($row['gender'] ?? '') == 'F' ? 'checked' : '' ?>> ຍິງ</label>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">ສະຖານະຄອບຄົວ</label>
                <div class="check-group">
                    <label><input type="radio" name="status" value="SINGLE" <?= ($row['status'] ?? '') == 'SINGLE' ? 'checked' : '' ?>> ໂສດ</label>
                    <label><input type="radio" name="status" value="MARRIED" <?= ($row['status'] ?? '') == 'MARRIED' ? 'checked' : '' ?>> ແຕ່ງງານ</label>
                    <label><input type="radio" name="status" value="DIVORCED" <?= ($row['status'] ?? '') == 'DIVORCED' ? 'checked' : '' ?>> ຮ້າງ/ໝ້າຍ</label>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <label class="form-label">ເບີໂທລະສັບ</label>
                <input type="text" name="phone" class="form-control form-control-sm" value="<?= $row['phone1'] ?>" readonly>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">ວອດແອັບ (WhatsApp)</label>
                <input type="text" name="whatsapp" class="form-control form-control-sm" value="<?= htmlspecialchars($interview['whatsapp'] ?? '') ?>">
            </div>

            <div class="col-12 col-sm-6">
                <label class="form-label">ເລກບັດປະຈໍາຕົວ</label>
                <input type="text" name="id_card_no" class="form-control form-control-sm" value="<?= $row['id_no'] ?>" readonly>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">ພາດສະປອດ</label>
                <input type="text" name="passport_no" class="form-control form-control-sm" value="<?= $row['passport'] ?>" readonly>
            </div>

            <div class="col-12 col-sm-6">
                <label class="form-label">ຈຳນວນລູກ</label>
                <div class="d-flex align-items-center gap-2">
                    <span class="form-hint">ຊາຍ</span>
                    <input type="number" name="children_male" min="0" class="form-control form-control-sm" style="width:90px;" value="<?= htmlspecialchars($interview['children_male'] ?? '') ?>">
                    <span class="form-hint">ຍິງ</span>
                    <input type="number" name="children_female" min="0" class="form-control form-control-sm" style="width:90px;" value="<?= htmlspecialchars($interview['children_female'] ?? '') ?>">
                    <span class="form-hint">ຄົນ</span>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label">ບຸກຄົນຕິດຕໍ່ສຸກເສີນ (ໂທ)</label>
                <input type="text" name="emergency_contact" class="form-control form-control-sm" placeholder="ຊື່ ແລະ ເບີໂທ" value="<?= htmlspecialchars($interview['emergency_contact'] ?? '') ?>">
            </div>
        </div>
    </div>

    <hr class="m-0" style="border-color:var(--green-border);">

    <!-- 2. ຄວາມພ້ອມດ້ານຮ່າງກາຍ ແລະ ສຸຂະພາບ -->
    <div class="section-head">
        <i class="bi bi-heart-pulse me-2"></i>2. ຄວາມພ້ອມດ້ານຮ່າງກາຍ ແລະ ສຸຂະພາບ (Physical &amp; Health Readiness)
    </div>
    <div class="p-3">
        <table class="eval-table">
            <thead>
                <tr>
                    <th style="width:34%;">ລາຍການປະເມີນ (Evaluation Item)</th>
                    <th style="width:44%;">ຜົນການປະເມີນ</th>
                    <th>ໝາຍເຫດ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2.1 ສ່ວນສູງ / ນໍ້າໜັກ (Height / Weight)</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" name="height" readonly value="<?= $row['height'] ?>" class="form-control form-control-sm" style="width:110px;" placeholder="cm"> cm /
                            <input type="text" name="weight" readonly value="<?= $row['weight'] ?>" class="form-control form-control-sm" style="width:110px;" placeholder="kg"> kg
                        </div>
                    </td>
                    <td><input type="text" name="height_weight_remark" class="form-control form-control-sm" value="<?= htmlspecialchars($interview['height_weight_remark'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>2.2 ໂລກປະຈໍາຕົວ / ປະຫວັດຜ່າຕັດ (Chronic Disease)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="chronic_disease" value="none" <?= ($interview['chronic_disease'] ?? '') == 'none' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                            <label><input type="radio" name="chronic_disease" value="yes" <?= ($interview['chronic_disease'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                        </div>
                        <input type="text" name="chronic_disease_detail" class="form-control form-control-sm mt-1" placeholder="ລາຍລະອຽດ (ຖ້າມີ)" value="<?= htmlspecialchars($interview['chronic_disease_detail'] ?? '') ?>">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>2.3 ການສູບຢາ (Smoking)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="smoking" value="no" <?= ($interview['smoking'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່</label>
                            <label><input type="radio" name="smoking" value="sometimes" <?= ($interview['smoking'] ?? '') == 'sometimes' ? 'checked' : '' ?>> ສູບ ບາງຄັ້ງ</label>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>2.4 ດື່ມເຫລົ້າ (Alcohol)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="alcohol" value="no" <?= ($interview['alcohol'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່</label>
                            <label><input type="radio" name="alcohol" value="sometimes" <?= ($interview['alcohol'] ?? '') == 'sometimes' ? 'checked' : '' ?>> ດື່ມບາງຄັ້ງ</label>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>2.5 ສຸຂະພາບທົ່ວໄປ &amp; ຄວາມສາມາດຍົກຂອງໜັກ (20-30kg)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="health_general" value="great" <?= ($interview['health_general'] ?? '') == 'great' ? 'checked' : '' ?>> ດີຫຼາຍ</label>
                            <label><input type="radio" name="health_general" value="ok" <?= ($interview['health_general'] ?? '') == 'ok' ? 'checked' : '' ?>> ພໍໃຊ້</label>
                            <label><input type="radio" name="health_general" value="bad" <?= ($interview['health_general'] ?? '') == 'bad' ? 'checked' : '' ?>> ບໍ່ດີ</label>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>2.6 ຄວາມຄ່ອງແຄ່ວ (Maneuverable)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="maneuverable" value="great" <?= ($interview['maneuverable'] ?? '') == 'great' ? 'checked' : '' ?>> ດີຫຼາຍ</label>
                            <label><input type="radio" name="maneuverable" value="ok" <?= ($interview['maneuverable'] ?? '') == 'ok' ? 'checked' : '' ?>> ພໍໃຊ້</label>
                            <label><input type="radio" name="maneuverable" value="bad" <?= ($interview['maneuverable'] ?? '') == 'bad' ? 'checked' : '' ?>> ບໍ່ດີ</label>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr class="m-0" style="border-color:var(--green-border);">

    <!-- 3. ປະສົບການເຮັດວຽກກະສິກໍາ -->
    <div class="section-head">
        <i class="bi bi-flower1 me-2"></i>3. ປະສົບການເຮັດວຽກກະສິກໍາ (Agricultural Experience)
    </div>
    <div class="p-3">
        <table class="eval-table">
            <thead>
                <tr>
                    <th style="width:40%;">ທັກສະ / ປະສົບການ (Skill / Experience)</th>
                    <th style="width:26%;">ປະສົບການ (Yes/No)</th>
                    <th>ລະຍະເວລາ (Duration)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>3.1 ປະສົບການເຮັດໄຮ່ / ເຮັດນາ / ປູກຜັກ (Farming/Vegetables)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="exp_farming" value="yes" <?= ($interview['exp_farming'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                            <label><input type="radio" name="exp_farming" value="no" <?= ($interview['exp_farming'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        </div>
                    </td>
                    <td><input type="text" name="exp_farming_duration" class="form-control form-control-sm" placeholder="ປີ/ເດືອນ" value="<?= htmlspecialchars($interview['exp_farming_duration'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>3.2 ປະສົບການເຮັດສວນໝາກໄມ້ (Fruit Orchards)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="exp_orchard" value="yes" <?= ($interview['exp_orchard'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                            <label><input type="radio" name="exp_orchard" value="no" <?= ($interview['exp_orchard'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        </div>
                    </td>
                    <td><input type="text" name="exp_orchard_duration" class="form-control form-control-sm" placeholder="ປີ/ເດືອນ" value="<?= htmlspecialchars($interview['exp_orchard_duration'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>3.3 ປະສົບການເຮັດວຽກໃນເຮືອນຮົ່ມ (Greenhouse)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="exp_greenhouse" value="yes" <?= ($interview['exp_greenhouse'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                            <label><input type="radio" name="exp_greenhouse" value="no" <?= ($interview['exp_greenhouse'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        </div>
                    </td>
                    <td><input type="text" name="exp_greenhouse_duration" class="form-control form-control-sm" placeholder="ປີ/ເດືອນ" value="<?= htmlspecialchars($interview['exp_greenhouse_duration'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>3.4 ການນໍາໃຊ້ອຸປະກອນກະສິກໍາ/ຂັບຮົດໄຖ (Agriculture Machineries)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="exp_machine" value="yes" <?= ($interview['exp_machine'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                            <label><input type="radio" name="exp_machine" value="no" <?= ($interview['exp_machine'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        </div>
                    </td>
                    <td><input type="text" name="exp_machine_duration" class="form-control form-control-sm" placeholder="ປີ/ເດືອນ" value="<?= htmlspecialchars($interview['exp_machine_duration'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>3.5 ຄວາມອົດທົນຕໍ່ສະພາບອາກາດໜາວ / ຮ້ອນຈັດ (Climate Adaptation)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="exp_climate" value="yes" <?= ($interview['exp_climate'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                            <label><input type="radio" name="exp_climate" value="no" <?= ($interview['exp_climate'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        </div>
                    </td>
                    <td><input type="text" name="exp_climate_duration" class="form-control form-control-sm" placeholder="ປີ/ເດືອນ" value="<?= htmlspecialchars($interview['exp_climate_duration'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td>3.6 ເຄີຍໄປເຮັດວຽກຢູ່ເກົາຫຼີ ມາກ່ອນບໍ? (Worked in Korea before?)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="worked_korea_before" value="yes" <?= ($interview['worked_korea_before'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                            <label><input type="radio" name="worked_korea_before" value="no" <?= ($interview['worked_korea_before'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        </div>
                    </td>
                    <td><input type="text" name="worked_korea_before_year" class="form-control form-control-sm" placeholder="ໄປປີໃດ" value="<?= htmlspecialchars($interview['worked_korea_before_year'] ?? '') ?>"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr class="m-0" style="border-color:var(--green-border);">

    <!-- 4. ທັດສະນະຄະຕິ ແລະ ທັກສະພາສາ -->
    <div class="section-head">
        <i class="bi bi-chat-square-text me-2"></i>4. ທັດສະນະຄະຕິ ແລະ ທັກສະພາສາ (Attitude &amp; Language Ability)
    </div>
    <div class="p-3">
        <table class="eval-table">
            <tbody>
                <tr>
                    <td style="width:38%;">4.1 ທັກສະພາສາເກົາຫຼີ (Korean Language Skill)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="korean_skill" value="none" <?= ($interview['korean_skill'] ?? '') == 'none' ? 'checked' : '' ?>> ບໍ່ໄດ້ເລີຍ</label>
                            <label><input type="radio" name="korean_skill" value="little" <?= ($interview['korean_skill'] ?? '') == 'little' ? 'checked' : '' ?>> ເວົ້າໄດ້ເລັກນ້ອຍ</label>
                            <label><input type="radio" name="korean_skill" value="good" <?= ($interview['korean_skill'] ?? '') == 'good' ? 'checked' : '' ?>> ຟັງ/ເວົ້າໄດ້ດີ</label>
                            <label><input type="radio" name="korean_skill" value="topik" <?= ($interview['korean_skill'] ?? '') == 'topik' ? 'checked' : '' ?>> ມີໃບຮັບຮອງ EPS-TOPIK</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>4.2 ເຫດຜົນທີ່ຕ້ອງການໄປເຮັດວຽກຢູ່ເກົາຫຼີ (Reason for working in Korea)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="reason_korea" value="income" <?= ($interview['reason_korea'] ?? '') == 'income' ? 'checked' : '' ?>> ຫາລາຍໄດ້ໃຫ້ຄອບຄົວ</label>
                            <label><input type="radio" name="reason_korea" value="save" <?= ($interview['reason_korea'] ?? '') == 'save' ? 'checked' : '' ?>> ເກັບເງິນຮຽນ/ສ້າງທຸລະກິດ</label>
                            <label><input type="radio" name="reason_korea" value="other" <?= ($interview['reason_korea'] ?? '') == 'other' ? 'checked' : '' ?>> ອື່ນໆ</label>
                        </div>
                        <input type="text" name="reason_korea_other" class="form-control form-control-sm mt-1" placeholder="ລະບຸ (ຖ້າເລືອກ ອື່ນໆ)" value="<?= htmlspecialchars($interview['reason_korea_other'] ?? '') ?>">
                    </td>
                </tr>
                <tr>
                    <td>4.3 ຄວາມສາມາດໃນການເຮັດວຽກລ່ວງເວລາ ແລະ ວຽກໜັກ (Overtime &amp; Hard Work)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="overtime" value="ok" <?= ($interview['overtime'] ?? '') == 'ok' ? 'checked' : '' ?>> ສາມາດເຮັດໄດ້ທຸກມື້ / ບໍ່ມີບັນຫາ</label>
                            <label><input type="radio" name="overtime" value="reasonable" <?= ($interview['overtime'] ?? '') == 'reasonable' ? 'checked' : '' ?>> ເຮັດໄດ້ຕາມຄວາມເໝາະສົມ</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>4.4 ການປະຕິບັດຕາມກົດລະບຽບ ແລະ ຕາມສັນຍາຈ້າງ (Adaptability &amp; Discipline)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="discipline" value="strict" <?= ($interview['discipline'] ?? '') == 'strict' ? 'checked' : '' ?>> ພ້ອມປະຕິບັດຕາມກົດລະບຽບ ແລະ ສັນຍາຢ່າງເຄັ່ງຄັດ</label>
                            <label><input type="radio" name="discipline" value="adapt" <?= ($interview['discipline'] ?? '') == 'adapt' ? 'checked' : '' ?>> ສາມາດປັບຕົວເຂົ້າກັບວັດທະນະທໍາເກົາຫຼີໄດ້ດີ</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>4.5 ນ້ຳໃຈຮັບຜິດຊອບ ແລະ ຄວາມຊື່ສັດ (Accountability &amp; Integrity)</td>
                    <td>
                        <div class="check-group">
                            <label><input type="radio" name="integrity" value="responsible" <?= ($interview['integrity'] ?? '') == 'responsible' ? 'checked' : '' ?>> ມີນ້ຳໃຈຮັບຜິດຊອບຕໍ່ພັນທະ ແລະ ໜີ້ສິນ</label>
                            <label><input type="radio" name="integrity" value="honest" <?= ($interview['integrity'] ?? '') == 'honest' ? 'checked' : '' ?>> ມີຄວາມຈິງໃຈຕັ້ງໃຈກັບບ້ານພາຍຫຼັງສິ້ນສຸດວີຊ່າ</label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr class="m-0" style="border-color:var(--green-border);">

    <!-- 5. ຜົນການປະເມີນຂອງຜູ້ສຳພາດ -->
    <div class="section-head">
        <i class="bi bi-clipboard-check me-2"></i>5. ຜົນການປະເມີນຂອງຜູ້ສຳພາດ (Interviewer Assessment)
    </div>
    <div class="p-3">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">ຄະແນນການປະເມີນ (Evaluation Score)</label>
                <div class="row">
                    <div class="col-4">
                        <input type="text" name="eval_score_num" id="" class="form-control" value="<?= htmlspecialchars($interview['eval_score_num'] ?? '') ?>">
                    </div>
                    <div class="col-8">
                        <div class="check-group">
                            <label><input type="radio" name="eval_score" value="A" <?= ($interview['eval_score'] ?? '') == 'A' ? 'checked' : '' ?>> ດີຫຼາຍ (A)</label>
                            <label><input type="radio" name="eval_score" value="B" <?= ($interview['eval_score'] ?? '') == 'B' ? 'checked' : '' ?>> ດີ (B)</label>
                            <label><input type="radio" name="eval_score" value="C" <?= ($interview['eval_score'] ?? '') == 'C' ? 'checked' : '' ?>> ປານກາງ (C)</label>
                            <label><input type="radio" name="eval_score" value="D" <?= ($interview['eval_score'] ?? '') == 'D' ? 'checked' : '' ?>> ອ່ອນຫຼາຍ (D)</label>
                            <label><input type="radio" name="eval_score" value="F" <?= ($interview['eval_score'] ?? '') == 'F' ? 'checked' : '' ?>> ບໍ່ຜ່ານ (F)</label>
                        </div>
                    </div>
                </div>
                
                
            </div>
            <div class="col-12">
                <label class="form-label">ຄວາມຄິດເຫັນຂອງຜູ້ສຳພາດ (Interviewer's Comments)</label>
                <textarea name="interviewer_comments" rows="3" class="form-control form-control-sm"><?= htmlspecialchars($interview['interviewer_comments'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">ສະຫຼຸບຜົນການສຳພາດ (Final Result)</label>
                <div class="check-group">
                    <label><input type="radio" name="final_result" value="passed" <?= ($interview['final_result'] ?? '') == 'passed' ? 'checked' : '' ?>> ຜ່ານການສຳພາດ (Passed)</label>
                    <label><input type="radio" name="final_result" value="conditional" <?= ($interview['final_result'] ?? '') == 'conditional' ? 'checked' : '' ?>> ຜ່ານແບບມີເງື່ອນໄຂ (Conditional)</label>
                    <label><input type="radio" name="final_result" value="failed" <?= ($interview['final_result'] ?? '') == 'failed' ? 'checked' : '' ?>> ບໍ່ຜ່ານ (Failed)</label>
                </div>
                <input type="text" name="final_result_condition" class="form-control form-control-sm mt-1" placeholder="ເງື່ອນໄຂ (ຖ້າມີ)" value="<?= htmlspecialchars($interview['final_result_condition'] ?? '') ?>">
            </div>

            <!-- <div class="col-12 col-sm-6">
                <div class="signature-box">
                    <label class="form-label mb-2">ລາຍເຊັນຜູ້ສຳພາດ (Interviewer Signature)</label>
                    <div class="mb-2" style="border-bottom:1px dotted #999; height:40px;"></div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">ວັນທີ</label>
                            <input type="date" name="interviewer_signed_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label">ຊື່ຜູ້ສຳພາດ</label>
                            <input type="text" name="interviewer_name" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

</div>
</form>

<?php
include('../footer.php');
?>