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
// ດຶງຂໍ້ມູນຜູ້ສະໝັກ + ທັດສະນະຄະຕິ ແລະ ທັກສະອື່ນໆ
// ===================================================
$sql = $conn->prepare("SELECT
    cand.cid,
    at.korean_skill,
    at.reason_korea,
    at.reason_other,
    at.overtime,
    at.discipline,
    at.integrity,
    at.sts_save
FROM candidate_korea AS cand
LEFT JOIN interview_attitude AS at ON at.data_id = cand.cid
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
            <i class="bi bi-chat-square-text me-2 text-primary"></i>ແບບຟອມສຳພາດງານ ແຮງງານລະດູການ - ທັດສະນະຄະຕິ ແລະ ທັກສະອື່ນ
        </h5>
    </div>
</div>

<form method="POST" id="attitude_form" enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?= htmlspecialchars($row['cid']) ?>">

    <div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
        <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
        </a>
        <button type="submit" class="btn btn-sm btn-primary px-4 btn-save">
            <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
        </button>
        <button type="button" id="attitude_verify" class="btn btn-sm btn-success px-4 btn-verify">
            <i class="bi bi-check2-circle me-1"></i> Verify
        </button>
    </div>

    <div class="card shadow-none mt-2" style="max-width:1920px;">

        <!-- 1. ທັກສະພາສາເກົາຫຼີ -->
        <div class="section-head">
            <i class="bi bi-translate me-2"></i>1. ທັກສະພາສາເກົາຫຼີ (Korean Language Skill)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label class="form-label">CIF :</label>
                    <input type="text" name="cif" class="form-control form-control-sm" value="<?= htmlspecialchars($cif) ?>" readonly>
                </div>
                <div class="col-12 col-sm-9">
                    <label class="form-label">ທັກສະພາສາເກົາຫຼີ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="korean_skill" value="none" <?= ($row['korean_skill'] ?? '') == 'none' ? 'checked' : '' ?>> ບໍ່ໄດ້ເລີຍ</label>
                        <label><input type="radio" name="korean_skill" value="little" <?= ($row['korean_skill'] ?? '') == 'little' ? 'checked' : '' ?>> ເວົ້າໄດ້ເລັກນ້ອຍ</label>
                        <label><input type="radio" name="korean_skill" value="good" <?= ($row['korean_skill'] ?? '') == 'good' ? 'checked' : '' ?>> ຟັງ/ເວົ້າໄດ້ດີ</label>
                        <label><input type="radio" name="korean_skill" value="topik" <?= ($row['korean_skill'] ?? '') == 'topik' ? 'checked' : '' ?>> ມີໃບຮັບຮອງ EPS-TOPIK</label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 2. ເຫດຜົນທີ່ຕ້ອງການໄປເຮັດວຽກຢູ່ເກົາຫຼີ -->
        <div class="section-head">
            <i class="bi bi-question-circle me-2"></i>2. ເຫດຜົນທີ່ຕ້ອງການໄປເຮັດວຽກຢູ່ເກົາຫຼີ (Reason for working in Korea)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-8">
                    <label class="form-label">ເຫດຜົນ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="reason_korea" value="income" <?= ($row['reason_korea'] ?? '') == 'income' ? 'checked' : '' ?>> ຫາລາຍໄດ້ໃຫ້ຄອບຄົວ</label>
                        <label><input type="radio" name="reason_korea" value="save" <?= ($row['reason_korea'] ?? '') == 'save' ? 'checked' : '' ?>> ເກັບເງິນຮຽນ/ສ້າງທຸລະກິດ</label>
                        <label><input type="radio" name="reason_korea" value="other" <?= ($row['reason_korea'] ?? '') == 'other' ? 'checked' : '' ?>> ອື່ນໆ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4" id="reason_other_wrap" style="<?= ($row['reason_korea'] ?? '') == 'other' ? '' : 'display:none;' ?>">
                    <label class="form-label">ລະບຸເຫດຜົນອື່ນໆ :</label>
                    <input type="text" name="reason_other" class="form-control form-control-sm" value="<?= htmlspecialchars($row['reason_other'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 3. ຄວາມສາມາດເຮັດວຽກລ່ວງເວລາ ແລະ ວຽກໜັກ -->
        <div class="section-head">
            <i class="bi bi-clock-history me-2"></i>3. ຄວາມສາມາດໃນການເຮັດວຽກລ່ວງເວລາ ແລະ ວຽກໜັກ (Overtime &amp; Hard Work)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12">
                    <div class="check-group">
                        <label><input type="radio" name="overtime" value="ok" <?= ($row['overtime'] ?? '') == 'ok' ? 'checked' : '' ?>> ສາມາດເຮັດໄດ້ທຸກມື້ / ບໍ່ມີບັນຫາ</label>
                        <label><input type="radio" name="overtime" value="reasonable" <?= ($row['overtime'] ?? '') == 'reasonable' ? 'checked' : '' ?>> ເຮັດໄດ້ຕາມຄວາມເໝາະສົມ</label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 4. ການປະຕິບັດຕາມກົດລະບຽບ ແລະ ຕາມສັນຍາຈ້າງ -->
        <div class="section-head">
            <i class="bi bi-clipboard-check me-2"></i>4. ການປະຕິບັດຕາມກົດລະບຽບ ແລະ ຕາມສັນຍາຈ້າງ (Adaptability &amp; Discipline)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12">
                    <div class="check-group">
                        <label><input type="radio" name="discipline" value="strict" <?= ($row['discipline'] ?? '') == 'strict' ? 'checked' : '' ?>> ພ້ອມປະຕິບັດຕາມກົດລະບຽບ ແລະ ສັນຍາຢ່າງເຄັ່ງຄັດ</label>
                        <label><input type="radio" name="discipline" value="adapt" <?= ($row['discipline'] ?? '') == 'adapt' ? 'checked' : '' ?>> ສາມາດປັບຕົວເຂົ້າກັບວັດທະນະທຳເກົາຫຼີໄດ້ດີ</label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 5. ນ້ຳໃຈຮັບຜິດຊອບ ແລະ ຄວາມຊື່ສັດ -->
        <div class="section-head">
            <i class="bi bi-shield-check me-2"></i>5. ນ້ຳໃຈຮັບຜິດຊອບ ແລະ ຄວາມຊື່ສັດ (Accountability &amp; Integrity)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12">
                    <div class="check-group">
                        <label><input type="radio" name="integrity" value="responsible" <?= ($row['integrity'] ?? '') == 'responsible' ? 'checked' : '' ?>> ມີນ້ຳໃຈຮັບຜິດຊອບຕໍ່ພັນທະ ແລະ ໜີ້ສິນ</label>
                        <label><input type="radio" name="integrity" value="honest" <?= ($row['integrity'] ?? '') == 'honest' ? 'checked' : '' ?>> ມີຄວາມຈິງໃຈຕັ້ງໃຈກັບບ້ານພາຍຫຼັງສິ້ນສຸດວີຊ່າ</label>
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
<script src="../js/attitude.js?v=<?= filemtime('../js/attitude.js') ?>"></script>
