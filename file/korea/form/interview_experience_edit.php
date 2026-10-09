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
// ດຶງຂໍ້ມູນຜູ້ສະໝັກ + ປະສົບການເຮັດວຽກ
// ===================================================
$sql = $conn->prepare("SELECT
    cand.cid,
    ex.exp_farming,
    ex.exp_farming_years,
    ex.exp_orchard,
    ex.exp_orchard_years,
    ex.exp_greenhouse,
    ex.exp_greenhouse_years,
    ex.exp_machine,
    ex.exp_machine_years,
    ex.exp_climate,
    ex.worked_korea,
    ex.worked_korea_year,
    ex.work_history,
    ex.sts_save
FROM candidate_korea AS cand
LEFT JOIN interview_experience AS ex ON ex.data_id = cand.cid
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

// ລະຫັດ CIF ສ້າງອັດຕະໂນມັດ (cid-01) ແລະ ບໍ່ໃຫ້ແກ້ໄຂ
$cif = $row['cid'] . '-01';
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
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-flower1 me-2 text-primary"></i>ແບບຟອມສຳພາດງານ ແຮງງານລະດູການ - ປະສົບການເຮັດວຽກ (Work Experience)
        </h5>
    </div>
</div>

<form method="POST" id="experience_form" enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?= htmlspecialchars($row['cid']) ?>">

    <div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
        <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
        </a>
        <button type="submit" class="btn btn-sm btn-primary px-4 btn-save">
            <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
        </button>
        <button type="button" id="experience_verify" class="btn btn-sm btn-success px-4 btn-verify">
            <i class="bi bi-check2-circle me-1"></i> Verify
        </button>
    </div>

    <div class="card shadow-none mt-2" style="max-width:1920px;">

        <!-- 1. ປະສົບການເຮັດໄຮ່ / ນາ / ປູກຜັກ -->
        <div class="section-head">
            <i class="bi bi-tree me-2"></i>1. ປະສົບການເຮັດໄຮ່ / ເຮັດນາ / ປູກຜັກ (Farming/Vegetables)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label class="form-label">CIF :</label>
                    <input type="text" name="cif" class="form-control form-control-sm" value="<?= htmlspecialchars($cif) ?>" readonly>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ປະສົບການ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="exp_farming" value="no" <?= ($row['exp_farming'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="exp_farming" value="yes" <?= ($row['exp_farming'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-3" id="farming_years_wrap" style="<?= ($row['exp_farming'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                    <label class="form-label">ປະສົບການ (ປີ) :</label>
                    <input type="text" name="exp_farming_years" class="form-control form-control-sm" value="<?= htmlspecialchars($row['exp_farming_years'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 2. ປະສົບການເຮັດສວນໝາກໄມ້ -->
        <div class="section-head">
            <i class="bi bi-apple me-2"></i>2. ປະສົບການເຮັດສວນໝາກໄມ້ (Fruit Orchards)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ປະສົບການ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="exp_orchard" value="no" <?= ($row['exp_orchard'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="exp_orchard" value="yes" <?= ($row['exp_orchard'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-3" id="orchard_years_wrap" style="<?= ($row['exp_orchard'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                    <label class="form-label">ປະສົບການ (ປີ) :</label>
                    <input type="text" name="exp_orchard_years" class="form-control form-control-sm" value="<?= htmlspecialchars($row['exp_orchard_years'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 3. ປະສົບການເຮັດວຽກໃນເຮືອນຮົ່ມ -->
        <div class="section-head">
            <i class="bi bi-house-heart me-2"></i>3. ປະສົບການເຮັດວຽກໃນເຮືອນຮົ່ມ (Greenhouse)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ປະສົບການ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="exp_greenhouse" value="no" <?= ($row['exp_greenhouse'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="exp_greenhouse" value="yes" <?= ($row['exp_greenhouse'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-3" id="greenhouse_years_wrap" style="<?= ($row['exp_greenhouse'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                    <label class="form-label">ປະສົບການ (ປີ) :</label>
                    <input type="text" name="exp_greenhouse_years" class="form-control form-control-sm" value="<?= htmlspecialchars($row['exp_greenhouse_years'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 4. ການນຳໃຊ້ອຸປະກອນກະສິກຳ / ຂັບຣົດໄຖ -->
        <div class="section-head">
            <i class="bi bi-truck-front me-2"></i>4. ການນຳໃຊ້ອຸປະກອນກະສິກຳ / ຂັບຣົດໄຖ (Agriculture Machineries)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ປະສົບການ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="exp_machine" value="no" <?= ($row['exp_machine'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="exp_machine" value="yes" <?= ($row['exp_machine'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-3" id="machine_years_wrap" style="<?= ($row['exp_machine'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                    <label class="form-label">ປະສົບການ (ປີ) :</label>
                    <input type="text" name="exp_machine_years" class="form-control form-control-sm" value="<?= htmlspecialchars($row['exp_machine_years'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 5. ຄວາມອົດທົນຕໍ່ສະພາບອາກາດ -->
        <div class="section-head">
            <i class="bi bi-thermometer-sun me-2"></i>5. ຄວາມອົດທົນຕໍ່ສະພາບອາກາດໜາວ / ຮ້ອນຈັດ (Climate Adaptation)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label">ຄວາມອົດທົນ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="exp_climate" value="no" <?= ($row['exp_climate'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ໄດ້</label>
                        <label><input type="radio" name="exp_climate" value="yes" <?= ($row['exp_climate'] ?? '') == 'yes' ? 'checked' : '' ?>> ໄດ້</label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 6. ເຄີຍໄປເຮັດວຽກຢູ່ເກົາຫຼີມາກ່ອນບໍ່ -->
        <div class="section-head">
            <i class="bi bi-airplane me-2"></i>6. ເຄີຍໄປເຮັດວຽກຢູ່ເກົາຫຼີມາກ່ອນບໍ່? (Use to work in Korea before?)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເຄີຍໄປເຮັດວຽກຢູ່ເກົາຫຼີ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="worked_korea" value="no" <?= ($row['worked_korea'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="worked_korea" value="yes" <?= ($row['worked_korea'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-3" id="korea_year_wrap" style="<?= ($row['worked_korea'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                    <label class="form-label">ໄປປີໃດ :</label>
                    <input type="text" name="worked_korea_year" class="form-control form-control-sm" value="<?= htmlspecialchars($row['worked_korea_year'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 7. ປະຫວັດການເຮັດວຽກ -->
        <div class="section-head">
            <i class="bi bi-journal-text me-2"></i>7. ປະຫວັດການເຮັດວຽກ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">ເຈົ້າເຄີຍເຮັດວຽກຫຍັງມາກ່ອນ? :</label>
                    <input type="text" name="work_history" maxlength="500" class="form-control form-control-sm" value="<?= htmlspecialchars($row['work_history'] ?? '') ?>">
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
<script src="../js/experience.js?v=<?= filemtime('../js/experience.js') ?>"></script>
