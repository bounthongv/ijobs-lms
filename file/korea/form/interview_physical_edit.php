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
// ດຶງຂໍ້ມູນຜູ້ສະໝັກ + ຂໍ້ມູນດ້ານຮ່າງກາຍ (ຖ້າມີໃນ interview_physical ໃຫ້ໃຊ້ຄ່ານັ້ນ, ຖ້າບໍ່ມີດຶງຈາກ candidate_korea)
// ===================================================
$sql = $conn->prepare("SELECT
    cand.cid,
    COALESCE(ph.weight, cand.weight) AS weight,
    COALESCE(ph.height, cand.height) AS height,
    ph.bmi,
    ph.chronic_disease,
    ph.chronic_detail,
    ph.finger_sts,
    ph.finger_count,
    ph.hand_remark,
    ph.toe_sts,
    ph.toe_count,
    ph.foot_remark,
    ph.lift_ability,
    ph.maneuverable,
    ph.eye_red,
    ph.eye_yellow,
    ph.eye_blue,
    ph.eye_orange,
    ph.eye_green,
    ph.eye_dog,
    ph.eye_watermelon,
    ph.eye_tomato,
    ph.eye_cat,
    ph.eye_cucumber,
    ph.eye_remark,
    ph.sts_save
FROM candidate_korea AS cand
LEFT JOIN interview_physical AS ph ON ph.data_id = cand.cid
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

// ຄ່າ BMI ສະແດງ (ຖ້າມີ)
$bmi_val = $row['bmi'] !== null ? number_format($row['bmi'], 2) : '';
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

    .eye-row {
        border-bottom: 1px dashed var(--green-border);
        padding: 8px 0;
    }

    .eye-row:last-child {
        border-bottom: none;
    }

    .eye-label {
        font-size: 13px;
        font-weight: 600;
        color: #222;
    }
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-heart-pulse me-2 text-primary"></i>ແບບຟອມສຳພາດງານ ແຮງງານລະດູການ - ດ້ານຮ່າງກາຍ (Physical)
        </h5>
    </div>
</div>

<form method="POST" id="physical_form" enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?= htmlspecialchars($row['cid']) ?>">

    <div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
        <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
        </a>
        <button type="submit" class="btn btn-sm btn-primary px-4 btn-save">
            <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
        </button>
        <button type="button" id="physical_verify" class="btn btn-sm btn-success px-4 btn-verify">
            <i class="bi bi-check2-circle me-1"></i> Verify
        </button>
    </div>

    <div class="card shadow-none mt-2" style="max-width:1920px;">

        <!-- 1. ນ້ຳໜັກ / ສ່ວນສູງ / ຄ່າ BMI -->
        <div class="section-head">
            <i class="bi bi-activity me-2"></i>1. ນ້ຳໜັກ / ສ່ວນສູງ / ຄ່າ BMI
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label class="form-label">CIF :</label>
                    <input type="text" name="cif" class="form-control form-control-sm" value="<?= htmlspecialchars($cif) ?>" readonly>
                </div>
                <div class="col-12 col-sm-3">
                    <label class="form-label">ນ້ຳໜັກ (kg) :</label>
                    <input type="text" name="weight" class="form-control form-control-sm" value="<?= htmlspecialchars($row['weight'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="form-label">ສ່ວນສູງ (cm) :</label>
                    <input type="text" name="height" class="form-control form-control-sm" value="<?= htmlspecialchars($row['height'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-3">
                    <label class="form-label">ຄ່າ BMI :</label>
                    <input type="text" name="bmi" class="form-control form-control-sm" value="<?= htmlspecialchars($bmi_val) ?>" readonly>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 2. ໂລກປະຈຳຕົວ / ປະຫວັດຜ່າຕັດ -->
        <div class="section-head">
            <i class="bi bi-clipboard2-pulse me-2"></i>2. ໂລກປະຈຳຕົວ / ປະຫວັດຜ່າຕັດ (Chronic Disease)
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ໂລກປະຈຳຕົວ / ປະຫວັດຜ່າຕັດ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="chronic_disease" value="no" <?= ($row['chronic_disease'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        <label><input type="radio" name="chronic_disease" value="yes" <?= ($row['chronic_disease'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-8" id="chronic_detail_wrap" style="<?= ($row['chronic_disease'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                    <label class="form-label">ລາຍລະອຽດ (ຖ້າມີ) :</label>
                    <input type="text" name="chronic_detail" class="form-control form-control-sm" value="<?= htmlspecialchars($row['chronic_detail'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 3. ນິ້ວມື / ລັກສະນະຂອງມື -->
        <div class="section-head">
            <i class="bi bi-hand-index-thumb me-2"></i>3. ນິ້ວມື / ລັກສະນະຂອງມື
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ນິ້ວມື :</label>
                    <div class="check-group">
                        <label><input type="radio" name="finger_sts" value="complete" <?= ($row['finger_sts'] ?? '') == 'complete' ? 'checked' : '' ?>> ຄົບ 10 ນິ້ວ</label>
                        <label><input type="radio" name="finger_sts" value="incomplete" <?= ($row['finger_sts'] ?? '') == 'incomplete' ? 'checked' : '' ?>> ບໍ່ຄົບ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4" id="finger_count_wrap" style="<?= ($row['finger_sts'] ?? '') == 'incomplete' ? '' : 'display:none;' ?>">
                    <label class="form-label">ຈຳນວນນິ້ວມືທີ່ມີ (ນິ້ວ) :</label>
                    <input type="number" min="0" max="10" name="finger_count" class="form-control form-control-sm" value="<?= htmlspecialchars($row['finger_count'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ລັກສະນະຂອງມື :</label>
                    <input type="text" name="hand_remark" class="form-control form-control-sm" value="<?= htmlspecialchars($row['hand_remark'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 4. ນິ້ວຕີນ / ລັກສະນະຂອງຕີນ -->
        <div class="section-head">
            <i class="bi bi-person-arms-up me-2"></i>4. ນິ້ວຕີນ / ລັກສະນະຂອງຕີນ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ນິ້ວຕີນ :</label>
                    <div class="check-group">
                        <label><input type="radio" name="toe_sts" value="complete" <?= ($row['toe_sts'] ?? '') == 'complete' ? 'checked' : '' ?>> ຄົບ 10 ນິ້ວ</label>
                        <label><input type="radio" name="toe_sts" value="incomplete" <?= ($row['toe_sts'] ?? '') == 'incomplete' ? 'checked' : '' ?>> ບໍ່ຄົບ</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4" id="toe_count_wrap" style="<?= ($row['toe_sts'] ?? '') == 'incomplete' ? '' : 'display:none;' ?>">
                    <label class="form-label">ຈຳນວນນິ້ວຕີນທີ່ມີ (ນິ້ວ) :</label>
                    <input type="number" min="0" max="10" name="toe_count" class="form-control form-control-sm" value="<?= htmlspecialchars($row['toe_count'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ລັກສະນະຂອງຕີນ :</label>
                    <input type="text" name="foot_remark" class="form-control form-control-sm" value="<?= htmlspecialchars($row['foot_remark'] ?? '') ?>">
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 5. ຄວາມສາມາດທາງຮ່າງກາຍ -->
        <div class="section-head">
            <i class="bi bi-battery-charging me-2"></i>5. ຄວາມສາມາດທາງຮ່າງກາຍ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label">ຄວາມສາມາດໃນການຍົກຍໍຂອງໜັກ 20-30kg :</label>
                    <input type="text" name="lift_ability" class="form-control form-control-sm" value="<?= htmlspecialchars($row['lift_ability'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label">ຄວາມຄ່ອງແຄ່ວ (Maneuverable) :</label>
                    <div class="check-group">
                        <label><input type="radio" name="maneuverable" value="slow" <?= ($row['maneuverable'] ?? '') == 'slow' ? 'checked' : '' ?>> ຊັກຊ້າ</label>
                        <label><input type="radio" name="maneuverable" value="medium" <?= ($row['maneuverable'] ?? '') == 'medium' ? 'checked' : '' ?>> ປານກາງ</label>
                        <label><input type="radio" name="maneuverable" value="fast" <?= ($row['maneuverable'] ?? '') == 'fast' ? 'checked' : '' ?>> ວ່ອງໄວ</label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 6. ພາກທົດສອບສາຍຕາ -->
        <div class="section-head">
            <i class="bi bi-eye me-2"></i>6. ພາກທົດສອບສາຍຕາ
        </div>
        <div class="p-3">
            <?php
            // ລາຍການທົດສອບສາຍຕາ (ຊື່ field => ຊື່ສະແດງ)
            $eye_items = [
                'eye_red'        => 'ສີແດງ',
                'eye_yellow'     => 'ສີເຫຼືອງ',
                'eye_blue'       => 'ສີຟ້າ',
                'eye_orange'     => 'ສີສົ້ມ',
                'eye_green'      => 'ສີຂຽວ',
                'eye_dog'        => 'ຮູບໝາ',
                'eye_watermelon' => 'ຮູບໝາກເພັດ',
                'eye_tomato'     => 'ຮູບໝາກເລັ່ນ',
                'eye_cat'        => 'ຮູບແມວ',
                'eye_cucumber'   => 'ຮູບໝາກແຕງ',
            ];
            foreach ($eye_items as $field => $label):
                $val = $row[$field] ?? '';
            ?>
                <div class="row g-3 align-items-center eye-row">
                    <div class="col-12 col-sm-3">
                        <div class="eye-label"><?= $label ?></div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="check-group">
                            <label><input type="radio" name="<?= $field ?>" value="correct" <?= $val == 'correct' ? 'checked' : '' ?>> ຖືກ</label>
                            <label><input type="radio" name="<?= $field ?>" value="wrong" <?= $val == 'wrong' ? 'checked' : '' ?>> ຜິດ</label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="row g-3 mt-1">
                <div class="col-12">
                    <label class="form-label">ໝາຍເຫດເພີ່ມເຕີມ :</label>
                    <input type="text" name="eye_remark" class="form-control form-control-sm" value="<?= htmlspecialchars($row['eye_remark'] ?? '') ?>">
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
<script src="../js/physical.js?v=<?= filemtime('../js/physical.js') ?>"></script>
