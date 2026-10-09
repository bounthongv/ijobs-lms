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
// ດຶງຂໍ້ມູນຜູ້ສະໝັກ + ຂໍ້ມູນສຳພາດຫຼັກຊັບ
// ===================================================
$sql = $conn->prepare("SELECT
    cand.cid,
    cl.has_collateral,
    cl.coll_item_land,
    cl.coll_item_vehicle,
    cl.coll_item_other,
    cl.coll_item_other_detail,
    cl.collateral_qty,
    cl.collateral_type,
    cl.is_owner,
    cl.owner_name,
    cl.area,
    cl.witness,
    cl.coll_file_1,
    cl.coll_file_2,
    cl.coll_file_3,
    cl.coll_file_4,
    cl.sts_save
FROM candidate_korea AS cand
LEFT JOIN interview_collateral AS cl ON cl.data_id = cand.cid
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

// ກວດສອບສິດ Admin (ສຳລັບການລົບໄຟລ໌)
$isAdmin = ($_SESSION['status'] ?? '') === 'Admin';
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

    .upload-box {
        border: 2px dashed var(--green-border);
        border-radius: 10px;
        padding: 14px;
        cursor: pointer;
        background: #fff;
        min-height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        transition: .15s;
    }

    .upload-box:hover {
        border-color: var(--green-btn);
        background: #f8fffb;
    }

    .preview-img {
        max-height: 150px;
        max-width: 100%;
        border-radius: 6px;
    }

    .pdf-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        word-break: break-all;
        text-align: center;
    }

    .pdf-preview i {
        font-size: 36px;
        color: #dc3545;
    }

    .pdf-preview a {
        color: #0d6efd;
        text-decoration: none;
    }
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-shield-lock me-2 text-primary"></i>ແບບຟອມສຳພາດງານ ແຮງງານລະດູການ - ສຳພາດ ຫຼັກຊັບ
        </h5>
    </div>
</div>

<form method="POST" id="collateral_form" enctype="multipart/form-data">
    <input type="hidden" name="cid" value="<?= htmlspecialchars($row['cid']) ?>">

    <div class="d-flex justify-content-start gap-2 px-3 py-2 border-top no-print" style="background:#fafcfa;border-color:var(--green-border)!important;">
        <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
        </a>
        <button type="submit" class="btn btn-sm btn-primary px-4 btn-save">
            <i class="bi bi-floppy me-1"></i> ບັນທຶກຂໍ້ມູນ
        </button>
        <button type="button" id="collateral_verify" class="btn btn-sm btn-success px-4 btn-verify">
            <i class="bi bi-check2-circle me-1"></i> Verify
        </button>
    </div>

    <div class="card shadow-none mt-2" style="max-width:1920px;">

        <!-- 1. ຂໍ້ມູນຫຼັກຊັບ -->
        <div class="section-head">
            <i class="bi bi-journal-bookmark me-2"></i>1. ຂໍ້ມູນຫຼັກຊັບ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-3">
                    <label class="form-label">CIF :</label>
                    <input type="text" name="cif" class="form-control form-control-sm" value="<?= htmlspecialchars($cif) ?>" readonly>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເຈົ້າມີຫຼັກຊັບຄ້ຳປະກັນບໍ່? :</label>
                    <div class="check-group">
                        <label><input type="radio" name="has_collateral" value="no" <?= ($row['has_collateral'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ມີ</label>
                        <label><input type="radio" name="has_collateral" value="yes" <?= ($row['has_collateral'] ?? '') == 'yes' ? 'checked' : '' ?>> ມີ</label>
                    </div>
                </div>
            </div>

            <div id="collateral_detail_wrap">
                <div class="row g-3 mt-1">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">ຖ້າມີແມ່ນຫຍັງ? ຈັກຢ່າງ? :</label>
                        <div class="check-group">
                            <label><input type="checkbox" name="coll_item_land" value="yes" <?= ($row['coll_item_land'] ?? '') == 'yes' ? 'checked' : '' ?>> ໃບຕາດິນ</label>
                            <label><input type="checkbox" name="coll_item_vehicle" value="yes" <?= ($row['coll_item_vehicle'] ?? '') == 'yes' ? 'checked' : '' ?>> ເອກະສານລົດ</label>
                            <label><input type="checkbox" name="coll_item_other" value="yes" <?= ($row['coll_item_other'] ?? '') == 'yes' ? 'checked' : '' ?>> ອື່ນໆ</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3" id="other_detail_wrap" style="<?= ($row['coll_item_other'] ?? '') == 'yes' ? '' : 'display:none;' ?>">
                        <label class="form-label">ລະບຸ ອື່ນໆ :</label>
                        <input type="text" name="coll_item_other_detail" class="form-control form-control-sm" value="<?= htmlspecialchars($row['coll_item_other_detail'] ?? '') ?>">
                    </div>
                    <?php // ຟິວ ຈຳນວນ (ຈັກຢ່າງ) — ປິດໄວ້ກ່ອນ (ຄໍລໍາໃນຕາຕະລາງຍັງມີຢູ່) ?>
                    <?php /*
                    <div class="col-12 col-sm-3">
                        <label class="form-label">ຈຳນວນ (ຈັກຢ່າງ) :</label>
                        <input type="number" min="0" name="collateral_qty" class="form-control form-control-sm" value="<?= htmlspecialchars($row['collateral_qty'] ?? '') ?>">
                    </div>
                    */ ?>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">ປະເພດໃດ :</label>
                        <input type="text" name="collateral_type" class="form-control form-control-sm" value="<?= htmlspecialchars($row['collateral_type'] ?? '') ?>">
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">ເຈົ້າເປັນເຈົ້າຂອງຫຼັກຊັບເອງບໍ່ :</label>
                        <div class="check-group">
                            <label><input type="radio" name="is_owner" value="yes" <?= ($row['is_owner'] ?? '') == 'yes' ? 'checked' : '' ?>> ແມ່ນ</label>
                            <label><input type="radio" name="is_owner" value="no" <?= ($row['is_owner'] ?? '') == 'no' ? 'checked' : '' ?>> ບໍ່ແມ່ນ</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">ຊື່ເຈົ້າຂອງຫຼັກຊັບ :</label>
                        <input type="text" name="owner_name" class="form-control form-control-sm" value="<?= htmlspecialchars($row['owner_name'] ?? '') ?>">
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">ຂໍ້ມູນຫຼັກຊັບ ເນື້ອທີ່ :</label>
                        <input type="text" name="area" class="form-control form-control-sm" value="<?= htmlspecialchars($row['area'] ?? '') ?>">
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">ພະຍານຄ້ຳປະກັນຫຼັກຊັບ :</label>
                        <input type="text" name="witness" class="form-control form-control-sm" value="<?= htmlspecialchars($row['witness'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <hr class="m-0" style="border-color:var(--green-border);">

        <!-- 2. ເອກະສານແນບ -->
        <div class="section-head">
            <i class="bi bi-paperclip me-2"></i>2. ເອກະສານແນບ (ຮູບ / PDF)
        </div>
        <div class="p-3" id="collateral_files_wrap">
            <div class="row g-3">
                <?php
                // ໄຟລ໌ແນບ 4 ຊ່ອງ
                $files = [
                    1 => $row['coll_file_1'] ?? '',
                    2 => $row['coll_file_2'] ?? '',
                    3 => $row['coll_file_3'] ?? '',
                    4 => $row['coll_file_4'] ?? '',
                ];
                foreach ($files as $slot => $fileVal):
                    $isImage = $fileVal && preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $fileVal);
                    $isPdf = $fileVal && preg_match('/\.pdf$/i', $fileVal);
                ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label">ເອກະສານ/ຮູບ ຊ່ອງທີ <?= $slot ?> :</label>
                        <div class="upload-box" onclick="document.getElementById('coll-file-<?= $slot ?>').click()">
                            <div class="text-center <?= $fileVal ? 'd-none' : '' ?>" id="ph-<?= $slot ?>">
                                <i class="bi bi-cloud-arrow-up" style="font-size:28px; color:var(--green-btn);"></i>
                                <h6 class="mb-1 mt-1" style="font-size:13px;">ຖ່າຍຮູບ ຫຼື Upload</h6>
                                <p class="mb-0" style="font-size:11px; color:#6b7280;">ຮອງຮັບ ຮູບ ແລະ PDF</p>
                            </div>
                            <div class="<?= $fileVal ? '' : 'd-none' ?> w-100 text-center" id="pv-<?= $slot ?>">
                                <?php if ($isImage): ?>
                                    <a href="../uploads/<?= htmlspecialchars($fileVal) ?>" target="_blank">
                                        <img src="../uploads/<?= htmlspecialchars($fileVal) ?>" class="preview-img mb-2">
                                    </a>
                                    <p class="text-success small mb-0"><i class="bi bi-check-circle-fill"></i> ມີໄຟລ໌ແລ້ວ (ຄລິກເພື່ອປ່ຽນ)</p>
                                <?php elseif ($fileVal): ?>
                                    <div class="pdf-preview">
                                        <i class="bi <?= $isPdf ? 'bi-file-earmark-pdf' : 'bi-file-earmark' ?>"></i>
                                        <a href="../uploads/<?= htmlspecialchars($fileVal) ?>" target="_blank"><?= htmlspecialchars(basename($fileVal)) ?></a>
                                        <p class="text-success small mb-0 mt-1"><i class="bi bi-check-circle-fill"></i> ຄລິກເພື່ອປ່ຽນ</p>
                                    </div>
                                <?php endif ?>
                            </div>
                            <input type="file" name="coll_file_<?= $slot ?>" id="coll-file-<?= $slot ?>" accept="image/*,application/pdf" class="d-none">
                        </div>
                        <?php if ($fileVal): ?>
                            <div class="d-flex justify-content-center gap-2 mt-2">
                                <a href="../uploads/<?= htmlspecialchars($fileVal) ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="ເບິ່ງໄຟລ໌">
                                    <i class="bi bi-eye"></i> ເບິ່ງ
                                </a>
                                <?php if ($isAdmin): ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-del-file" data-slot="<?= $slot ?>" title="ລົບໄຟລ໌">
                                        <i class="bi bi-trash"></i> ລົບ
                                    </button>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
                <?php endforeach; ?>
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
<script src="../js/collateral.js?v=<?= filemtime('../js/collateral.js') ?>"></script>
