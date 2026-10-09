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
// ດຶງຂໍ້ມູນຜູ້ສະໝັກ + ຄຳຖາມສຳພາດທົ່ວໄປ
// ===================================================
$sql = $conn->prepare("SELECT
    cand.cid,
    ge.know_from,
    ge.goal,
    ge.has_friend_korea,
    ge.current_job,
    ge.smoking,
    ge.alcohol,
    ge.drugs,
    ge.criminal,
    ge.abroad,
    ge.abroad_country,
    ge.gov_officer,
    ge.reason_korea,
    ge.overtime,
    ge.discipline,
    ge.integrity,
    ge.sts_save
FROM candidate_korea AS cand
LEFT JOIN interview_general AS ge ON ge.data_id = cand.cid
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
            <i class="bi bi-question-square me-2 text-primary"></i>ແບບຟອມສຳພາດງານ ແຮງງານລະດູການ - ຄຳຖາມສຳພາດທົ່ວໄປ
        </h5>
    </div>
</div>

<form method="POST" id="general_form" enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?= htmlspecialchars($row['cid']) ?>">

    <div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
        <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
        </a>
        <button type="submit" class="btn btn-sm btn-primary px-4 btn-save">
            <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
        </button>
        <button type="button" id="general_verify" class="btn btn-sm btn-success px-4 btn-verify">
            <i class="bi bi-check2-circle me-1"></i> Verify
        </button>
    </div>

    <div class="card shadow-none mt-2" style="max-width:1920px;">

        <!-- 1. ຄຳຖາມທົ່ວໄປ -->
        <div class="section-head">
            <i class="bi bi-chat-left-text me-2"></i>1. ຄຳຖາມທົ່ວໄປ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label class="form-label">CIF :</label>
                    <input type="text" name="cif" class="form-control form-control-sm" value="<?= htmlspecialchars($cif) ?>" readonly>
                </div>
                <div class="col-12 col-sm-9">
                    <label class="form-label">ເຈົ້າຮູ້ຈັກວ່າມີການໄປເຮັດວຽກຢູ່ປະເທດເກົາຫຼີມາຈາກໃສ? :</label>
                    <input type="text" name="know_from" class="form-control form-control-sm" value="<?= htmlspecialchars($row['know_from'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-9">
                    <label class="form-label">ເປົ້າໝາຍໃນການໄປເຮັດວຽກແມ່ນຫຍັງ? :</label>
                    <input type="text" name="goal" class="form-control form-control-sm" value="<?= htmlspecialchars($row['goal'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-9">
                    <label class="form-label">ເຈົ້າມີໝູ່ ຫຼື ພີ່ນ້ອງຢູ່ປະເທດເກົາຫຼີບໍ່? :</label>
                    <input type="text" name="has_friend_korea" class="form-control form-control-sm" value="<?= htmlspecialchars($row['has_friend_korea'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">ປະຈຸບັນນີ້ເຈົ້າເຮັດຫຍັງແດ່? :</label>
                    <input type="text" name="current_job" class="form-control form-control-sm" value="<?= htmlspecialchars($row['current_job'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 2. ພຶດຕິກຳ / ປະຫວັດ -->
        <div class="section-head">
            <i class="bi bi-clipboard2-heart me-2"></i>2. ພຶດຕິກຳ / ປະຫວັດ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ສູບຢາບໍ່? :</label>
                    <div class="check-group">
                        <label><input type="radio" name="smoking" value="smoke" <?= ($row['smoking'] ?? '') == 'smoke' ? 'checked' : '' ?>> ສູບ</label>
                        <label><input type="radio" name="smoking" value="no" <?= ($row['smoking'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ສູບ</label>
                        <label><input type="radio" name="smoking" value="used" <?= ($row['smoking'] ?? '') == 'used' ? 'checked' : '' ?>> ເຄີຍສູບ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ດື່ມເບຍ/ເຫຼົ້າບໍ່? :</label>
                    <div class="check-group">
                        <label><input type="radio" name="alcohol" value="drink" <?= ($row['alcohol'] ?? '') == 'drink' ? 'checked' : '' ?>> ດື່ມ</label>
                        <label><input type="radio" name="alcohol" value="no" <?= ($row['alcohol'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ດື່ມ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເຄີຍເສບສິ່ງເສບຕິດຫຍັງມາກ່ອນບໍ່? :</label>
                    <div class="check-group">
                        <label><input type="radio" name="drugs" value="no" <?= ($row['drugs'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="drugs" value="yes" <?= ($row['drugs'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເຈົ້າເຄີຍຖືກໂທດມາກ່ອນບໍ່? :</label>
                    <div class="check-group">
                        <label><input type="radio" name="criminal" value="no" <?= ($row['criminal'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="criminal" value="yes" <?= ($row['criminal'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເຈົ້າເຄີຍໄປຕ່າງປະເທດບໍ່? :</label>
                    <div class="check-group">
                        <label><input type="radio" name="abroad" value="no" <?= ($row['abroad'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="abroad" value="yes" <?= ($row['abroad'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4" id="abroad_country_wrap" style="<?= ($row['abroad'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                    <label class="form-label">ປະເທດ :</label>
                    <input type="text" name="abroad_country" class="form-control form-control-sm" value="<?= htmlspecialchars($row['abroad_country'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເຄີຍເປັນພະນັກງານລັດມາກ່ອນບໍ່? :</label>
                    <div class="check-group">
                        <label><input type="radio" name="gov_officer" value="no" <?= ($row['gov_officer'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ເຄີຍ</label>
                        <label><input type="radio" name="gov_officer" value="yes" <?= ($row['gov_officer'] ?? '') == 'yes' ? 'checked' : '' ?>> ເຄີຍ</label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 3. ເຫດຜົນ ແລະ ຄວາມພ້ອມ -->
        <div class="section-head">
            <i class="bi bi-patch-question me-2"></i>3. ເຫດຜົນ ແລະ ຄວາມພ້ອມ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">ເຫດຜົນທີ່ຕ້ອງການໄປເຮັດວຽກຢູ່ເກົາຫຼີ (Reason for working in Korea) :</label>
                    <input type="text" name="reason_korea" class="form-control form-control-sm" value="<?= htmlspecialchars($row['reason_korea'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຄວາມສາມາດໃນການເຮັດວຽກລ່ວງເວລາ ແລະ ວຽກໜັກ (Overtime &amp; Hard Work) :</label>
                    <div class="check-group">
                        <label><input type="radio" name="overtime" value="no" <?= ($row['overtime'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ໄດ້</label>
                        <label><input type="radio" name="overtime" value="yes" <?= ($row['overtime'] ?? '') == 'yes' ? 'checked' : '' ?>> ໄດ້</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ການປະຕິບັດຕາມກົດລະບຽບ ແລະ ຕາມສັນຍາຈ້າງ (Adaptability &amp; Discipline) :</label>
                    <div class="check-group">
                        <label><input type="radio" name="discipline" value="no" <?= ($row['discipline'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ໄດ້</label>
                        <label><input type="radio" name="discipline" value="yes" <?= ($row['discipline'] ?? '') == 'yes' ? 'checked' : '' ?>> ໄດ້</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ນ້ຳໃຈຮັບຜິດຊອບ ແລະ ຄວາມຊື່ສັດ (Accountability &amp; Integrity) :</label>
                    <div class="check-group">
                        <label><input type="radio" name="integrity" value="no" <?= ($row['integrity'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ໄດ້</label>
                        <label><input type="radio" name="integrity" value="yes" <?= ($row['integrity'] ?? '') == 'yes' ? 'checked' : '' ?>> ໄດ້</label>
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
<script src="../js/general.js?v=<?= filemtime('../js/general.js') ?>"></script>
