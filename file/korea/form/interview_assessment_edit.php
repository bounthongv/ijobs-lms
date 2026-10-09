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
// ດຶງຂໍ້ມູນຜູ້ສະໝັກ + ຜົນການປະເມີນຂອງຜູ້ສຳພາດ
// ===================================================
$sql = $conn->prepare("SELECT
    cand.cid,
    ass.eval_score,
    ass.interviewer_comments,
    ass.final_result,
    ass.reason,
    ass.sts_save
FROM candidate_korea AS cand
LEFT JOIN interview_assessment AS ass ON ass.data_id = cand.cid
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

    .signature-line {
        border-bottom: 1px solid #999;
        height: 32px;
        min-width: 260px;
    }
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-clipboard-check me-2 text-primary"></i>ແບບຟອມສຳພາດງານ ແຮງງານລະດູການ - ຜົນການປະເມີນຂອງຜູ້ສຳພາດ (Interviewer Assessment)
        </h5>
    </div>
</div>

<form method="POST" id="assessment_form" enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?= htmlspecialchars($row['cid']) ?>">

    <div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
        <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
        </a>
        <button type="submit" class="btn btn-sm btn-primary px-4 btn-save">
            <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
        </button>
        <button type="button" id="assessment_verify" class="btn btn-sm btn-success px-4 btn-verify">
            <i class="bi bi-check2-circle me-1"></i> Verify
        </button>
    </div>

    <div class="card shadow-none mt-2" style="max-width:1920px;">

        <!-- 1. ຜົນການປະເມີນຂອງຜູ້ສຳພາດ -->
        <div class="section-head">
            <i class="bi bi-clipboard-check me-2"></i>1. ຜົນການປະເມີນຂອງຜູ້ສຳພາດ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label class="form-label">CIF :</label>
                    <input type="text" name="cif" class="form-control form-control-sm" value="<?= htmlspecialchars($cif) ?>" readonly>
                </div>
                <div class="col-12 col-sm-9">
                    <label class="form-label">ຄະແນນການປະເມີນ (Evaluation Score) :</label>
                    <div class="check-group">
                        <label><input type="radio" name="eval_score" value="A" <?= ($row['eval_score'] ?? '') == 'A' ? 'checked' : '' ?>> ດີຫຼາຍ (A)</label>
                        <label><input type="radio" name="eval_score" value="B" <?= ($row['eval_score'] ?? '') == 'B' ? 'checked' : '' ?>> ດີ (B)</label>
                        <label><input type="radio" name="eval_score" value="C" <?= ($row['eval_score'] ?? '') == 'C' ? 'checked' : '' ?>> ປານກາງ (C)</label>
                        <label><input type="radio" name="eval_score" value="D" <?= ($row['eval_score'] ?? '') == 'D' ? 'checked' : '' ?>> ອ່ອນຫຼາຍ (D)</label>
                        <label><input type="radio" name="eval_score" value="F" <?= ($row['eval_score'] ?? '') == 'F' ? 'checked' : '' ?>> ບໍ່ຜ່ານ (F)</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">ຄວາມຄິດເຫັນຂອງຜູ້ສຳພາດ (Interviewer's Comments) :</label>
                    <textarea name="interviewer_comments" rows="6" class="form-control form-control-sm"><?= htmlspecialchars($row['interviewer_comments'] ?? '') ?></textarea>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label">ສະຫຼຸບຜົນການສຳພາດ (Final Result) :</label>
                    <div class="check-group">
                        <label><input type="radio" name="final_result" value="passed" <?= ($row['final_result'] ?? '') == 'passed' ? 'checked' : '' ?>> ຜ່ານການສຳພາດ (Passed)</label>
                        <label><input type="radio" name="final_result" value="conditional" <?= ($row['final_result'] ?? '') == 'conditional' ? 'checked' : '' ?>> ຜ່ານແບບມີເງື່ອນໄຂ (Conditional)</label>
                        <label><input type="radio" name="final_result" value="failed" <?= ($row['final_result'] ?? '') == 'failed' ? 'checked' : '' ?>> ບໍ່ຜ່ານ (Failed)</label>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label">ເຫດຜົນ :</label>
                    <input type="text" name="reason" maxlength="500" class="form-control form-control-sm" value="<?= htmlspecialchars($row['reason'] ?? '') ?>">
                </div>
            </div>

            <!-- ລາຍເຊັນຜູ້ສຳພາດ (ຂວາ + ເສັ້ນຂີດດ້ານລຸ່ມ) -->
            <div class="d-flex justify-content-end mt-4 pe-3">
                <div class="text-center">
                    <div class="form-label" style="font-size:13px; margin-bottom:6px;">ຜູ້ສຳພາດ (Interviewer)</div>
                    <div class="signature-line"></div>
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
<script src="../js/assessment.js?v=<?= filemtime('../js/assessment.js') ?>"></script>
