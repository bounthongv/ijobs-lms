<?php
    require '../../../connect.php';
    $cid = $_REQUEST['cid'];
    $sql = $conn->prepare("SELECT * FROM candidate_korea WHERE cid = ?");
    $sql->execute([$cid]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    // ຟັງຊັນຊ່ວຍວາງເຄື່ອງໝາຍ ✓ ໃສ່ກ່ອງ checkbox ຖ້າຄ່າກົງກັນ (ຜູ້ໃຊ້ດຶງຂໍ້ມູນເອງ, ນີ້ເປັນແຕ່ຕົວຢ່າງ)
    function checkbox_lao($current, $target) {
        return ($current == $target) ? '☑' : '☐';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ແບບຟອມເກັບຂໍ້ມູນສະໝັກງານ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../../logo/logo.png">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap');

* {
    font-size: 10.5pt;
    font-family: phetsarath ot;
    box-sizing: border-box;
}

html, body {
    background: #808080;
    margin: 0;
    padding: 0;
}

body {
    line-height: 1.35;
}

/* ===== A4 (จอ) ===== */
.page-a4 {
    width: 210mm;
    min-height: 297mm;
    margin: 20px auto;
    background: #fff;
    padding: 1.5cm;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
}

.print-bar {
    position: sticky;
    top: 10px;
    text-align: center;
    margin-bottom: 10px;
    z-index: 999;
}

.print-bar button {
    padding: 8px 24px;
    font-size: 13pt;
    cursor: pointer;
    border: none;
    border-radius: 6px;
    background: #0d6efd;
    color: #fff;
}

.print-bar button:hover { background: #0b5ed7; }

/* ===== โลโก้ / หัวฟอม ===== */
.form-logo { text-align: center; margin-bottom: 2px; }
.form-logo img { width: 55px; height: auto; }

.form-title {
    text-align: center;
    font-weight: bold;
    font-size: 14pt;
    margin-bottom: 8px;
    text-decoration: underline;
}

.section-title {
    font-weight: bold;
    text-decoration: underline;
    margin-top: 8px;
    margin-bottom: 3px;
}

.form-row {
    margin-bottom: 4px;
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    gap: 3px 8px;
}

.form-row .fld-label { white-space: nowrap; }

.fld-line {
    border-bottom: 1px dotted #000;
    padding: 0 3px;
    display: inline-block;
    min-width: 90px;
    text-align: center;
}

.fld-line.w-sm { min-width: 45px; }
.fld-line.w-md { min-width: 110px; }
.fld-line.w-lg { min-width: 170px; }

.chk-item { white-space: nowrap; margin-right: 10px; }
.chk-item .box { font-size: 12pt; margin-right: 2px; }

.exp-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
.exp-table td { padding: 2px; vertical-align: bottom; }

.lang-row {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 3px 10px;
    margin-bottom: 2px;
}
.lang-row .lang-name { min-width: 95px; font-weight: bold; }

ul.bullet-list { margin: 0; padding-left: 20px; }
ul.bullet-list li { margin-bottom: 3px; }

/* ===== โหมดพิมพ์จริง ===== */
@media print {
    @page {
        size: A4;
        margin: 1.5cm;
    }

    html, body { background: #fff; }

    .page-a4 {
        width: auto;
        min-height: auto;
        margin: 0;
        padding: 0;
        box-shadow: none;
    }

    .no-print { display: none !important; }
}
/* ===== ส่วนหัวฟอม: โลโก้ + ชื่อฟอม + กล่องรูปถ่าย ===== */
.form-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 8px;
}

.form-logo {
    text-align: left;
    margin-bottom: 0;
    flex: 0 0 auto;
}

.form-logo img {
    width: 55px;
    height: auto;
}

.form-title {
    flex: 1 1 auto;
    text-align: center;
    font-weight: bold;
    font-size: 14pt;
    text-decoration: underline;
    margin: 0;
    padding-top: 6px;
}

.photo-box {
    flex: 0 0 auto;
    width: 100px;
    height: 120px;
    border: 1px solid #000;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
}

.photo-box img {
    width: 100%;
    height: 100%;
    /* object-fit: cover; */
}

.photo-box span {
    font-size: 9.5pt;
    color: #444;
}
</style>

<body>

    <div class="page-a4">
        <div class="container-fluid">

            <div class="form-header">
                <div class="form-logo">
                    <img src="../../../logo/IjobsLogo.png" alt="">
                </div>

                <div class="form-title">ແບບຟອມເກັບຂໍ້ມູນສະໝັກງານ</div>

                <div class="photo-box">
                    <?php if (!empty($row['profile'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($row['profile']) ?>" alt="">
                    <?php else: ?>
                        <span>ຮູບ</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== ຂໍ້ມູນສ່ວນຕົວ ===== -->
            <div class="section-title">ຂໍ້ມູນສ່ວນຕົວ:</div>

            <div class="form-row">
                <span class="fld-label">ຊື່ ແລະ ນາມສະກຸນ</span>
                <span class="fld-line w-lg"><?= isset($row['fname']) ? htmlspecialchars($row['fname'] . ' ' . $row['lname']) : '' ?></span>
            </div>

            <div class="form-row">
                <span class="fld-label">ເພດ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['gender'] ?? '', 'M') ?></span>ຊາຍ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['gender'] ?? '', 'F') ?></span>ຍິງ</span>
            </div>

            <div class="form-row">
                <span class="fld-label">ວັນ/ເດືອນ/ປີ ເກີດ</span>
                <span class="fld-line w-sm"><?= isset($row['dob']) ? date_format(date_create($row['dob']), 'd') : '' ?></span> /
                <span class="fld-line w-sm"><?= isset($row['dob']) ? date_format(date_create($row['dob']), 'm') : '' ?></span> /
                <span class="fld-line w-sm"><?= isset($row['dob']) ? date_format(date_create($row['dob']), 'Y') : '' ?></span>
            </div>

            <div class="form-row">
                <span class="fld-label">ລວງສູງ</span>
                <span class="fld-line w-sm"><?= $row['height'] ?? '' ?></span>
                <span class="fld-label">ຊັງຕີແມັດ</span>
            </div>

            <div class="form-row">
                <span class="fld-label">ນ້ຳໜັກ</span>
                <span class="fld-line w-sm"><?= $row['weight'] ?? '' ?></span>
                <span class="fld-label">ກິໂລກຣາມ</span>
            </div>

            <div class="form-row">
                <span class="fld-label">ທີ່ຢູ່ປະຈຸບັນ (ຕາມສຳມະໂນຄົວ) ບ້ານ</span>
                <span class="fld-line w-md"><?= $row['la_vill'] ?? '' ?></span>
                <span class="fld-label">ເມືອງ</span>
                <span class="fld-line w-md"><?= $row['dis_name'] ?? '' ?></span>
                <span class="fld-label">ແຂວງ</span>
                <span class="fld-line w-md"><?= $row['pro_name'] ?? '' ?></span>
            </div>

            <div class="form-row">
                <span class="fld-label">ບັດປະຈຳຕົວ ເລກທີ:</span>
                <span class="fld-line w-md"><?= $row['id_card_no'] ?? '' ?></span>
                <span class="fld-label">ລົງວັນທີ</span>
                <span class="fld-line w-md"><?= !empty($row['id_card_issue_date']) ? date_format(date_create($row['id_card_issue_date']), 'd.m.Y') : '' ?></span>
                <span class="fld-label">ໝົດອາຍຸວັນທີ</span>
                <span class="fld-line w-md"><?= !empty($row['id_card_expire_date']) ? date_format(date_create($row['id_card_expire_date']), 'd.m.Y') : '' ?></span>
            </div>

            <div class="form-row">
                <span class="fld-label">ປັດສະປໍ ເລກທີ</span>
                <span class="fld-line w-md"><?= $row['passport_no'] ?? ($row['la_kyc'] ?? '') ?></span>
                <span class="fld-label">ອອກເມື່ອວັນທີ</span>
                <span class="fld-line w-md"><?= !empty($row['passport_issue_date']) ? date_format(date_create($row['passport_issue_date']), 'd.m.Y') : (!empty($row['la_fist_date_kyc']) ? date_format(date_create($row['la_fist_date_kyc']), 'd.m.Y') : '') ?></span>
                <span class="fld-label">ໝົດອາຍຸວັນທີ</span>
                <span class="fld-line w-md"><?= !empty($row['passport_expire_date']) ? date_format(date_create($row['passport_expire_date']), 'd.m.Y') : '' ?></span>
            </div>

            <div class="form-row">
                <span class="fld-label">ໂທລະສັບ</span>
                <span class="fld-line w-md"><?= $row['phone1'] ?? '' ?></span>
                <span class="fld-label">ວອດແອັບ</span>
                <span class="fld-line w-md"><?= $row['whatsapp'] ?? '' ?></span>
            </div>

            <div class="form-row">
                <span class="fld-label">ສະຖານະ:</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['marital_status'] ?? '', 'ໂສດ') ?></span>ໂສດ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['marital_status'] ?? '', 'ແຕ່ງງານແລ້ວ') ?></span>ແຕ່ງງານແລ້ວ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['marital_status'] ?? '', 'ຢ່າຮ້າງ') ?></span>ຢ່າຮ້າງ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['marital_status'] ?? '', 'ໝ້າຍ') ?></span>ໝ້າຍ</span>
            </div>

            <!-- ===== ຂໍ້ມູນການສຶກສາ ===== -->
            <div class="section-title">ຂໍ້ມູນການສຶກສາ:</div>

            <div class="form-row">
                <span class="fld-label">ລະດັບການສຶກສາ:</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['edu_level'] ?? '', 'ຈົບປະຖົມ') ?></span>ຈົບປະຖົມ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['edu_level'] ?? '', 'ຈົບມັດທະຍົມຕົ້ນ') ?></span>ຈົບມັດທະຍົມຕົ້ນ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['edu_level'] ?? '', 'ຈົບມັດທະຍົມຕອນປາຍ') ?></span>ຈົບມັດທະຍົມຕອນປາຍ</span>
            </div>

            <div class="form-row">
                <span class="fld-label">ລະດັບວິຊາສະເພາະ (ຖ້າມີ):</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['vocational_level'] ?? '', 'ຊັ້ນຕົ້ນ') ?></span>ຊັ້ນຕົ້ນ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['vocational_level'] ?? '', 'ຊັ້ນກາງ') ?></span>ຊັ້ນກາງ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['vocational_level'] ?? '', 'ຊັ້ນສູງ') ?></span>ຊັ້ນສູງ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['vocational_level'] ?? '', 'ປະລິນຍາຕີຂຶ້ນໄປ') ?></span>ປະລິນຍາຕີຂຶ້ນໄປ</span>
            </div>

            <!-- ===== ປະສົບການເຮັດວຽກ ===== -->
            <div class="section-title">ປະສົບການເຮັດວຽກ:</div>

            <?php
            // ຖ້າມີຕາຕະລາງ work_experience ຢູ່ແລ້ວ ຈະດຶງມາສະແດງອັດຕະໂນມັດ, ຖ້າບໍ່ມີ ຈະສະແດງແຖວຫວ່າງ 2 ແຖວ
            $exp_rows = [];
            if (isset($row['data_id'])) {
                $sqlExp = $conn->prepare("SELECT * FROM work_experience WHERE data_id=:id ORDER BY sort_order ASC");
                $sqlExp->bindParam(':id', $row['data_id']);
                $sqlExp->execute();
                $exp_rows = $sqlExp->fetchAll(PDO::FETCH_ASSOC);
            }
            if (empty($exp_rows)) {
                $exp_rows = [['year_from' => '', 'year_to' => '', 'workplace' => '', 'position' => ''], ['year_from' => '', 'year_to' => '', 'workplace' => '', 'position' => '']];
            }
            foreach ($exp_rows as $exp):
            ?>
            <div class="form-row">
                <span class="fld-label">ປີ</span>
                <span class="fld-line w-sm"><?= htmlspecialchars($exp['year_from']) ?></span>
                <span class="fld-label">ຫາ</span>
                <span class="fld-line w-sm"><?= htmlspecialchars($exp['year_to']) ?></span>
                <span class="fld-label">ປີ ເຮັດວຽກຢູ່</span>
                <span class="fld-line w-md"><?= htmlspecialchars($exp['workplace']) ?></span>
                <span class="fld-label">ໜ້າທີ່</span>
                <span class="fld-line w-lg"><?= htmlspecialchars($exp['position']) ?></span>
            </div>
            <?php endforeach; ?>

            <!-- ===== ຄວາມສາມາດ ===== -->
            <div class="section-title">ຄວາມສາມາດ:</div>

            <ul class="bullet-list">
                <li>
                    <span class="fld-label">ສາມາດຂັບລົດ:</span>
                    <span class="chk-item"><span class="box"><?= (!empty($row['drive_light_car'])) ? '☑' : '☐' ?></span>ລົດເບົາ AB</span>
                    <span class="chk-item"><span class="box"><?= (!empty($row['drive_truck'])) ? '☑' : '☐' ?></span>ລົດບັນທຸກ</span>
                    <span class="chk-item"><span class="box"><?= (!empty($row['drive_farm_machine'])) ? '☑' : '☐' ?></span>ກົນຈັກກະສິກໍາ</span>
                </li>
                <li>
                    <span class="fld-label d-block mb-1">ພາສາຕ່າງປະເທດ:</span>

                    <div class="lang-row">
                        <span class="lang-name">ພາສາໄທ ລະດັບ:</span>
                        <?php foreach (['ອ່ອນ', 'ປານກາງ', 'ດີ', 'ດີຫຼາຍ'] as $lv): ?>
                        <span class="chk-item"><span class="box"><?= checkbox_lao($row['lang_thai_level'] ?? '', $lv) ?></span><?= $lv ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="lang-row">
                        <span class="lang-name">ພາສາອັງກິດ ລະດັບ:</span>
                        <?php foreach (['ອ່ອນ', 'ປານກາງ', 'ດີ', 'ດີຫຼາຍ'] as $lv): ?>
                        <span class="chk-item"><span class="box"><?= checkbox_lao($row['lang_english_level'] ?? '', $lv) ?></span><?= $lv ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="lang-row">
                        <span class="lang-name">ພາສາເກົາຫລີ ລະດັບ:</span>
                        <?php foreach (['ອ່ອນ', 'ປານກາງ', 'ດີ', 'ດີຫຼາຍ'] as $lv): ?>
                        <span class="chk-item"><span class="box"><?= checkbox_lao($row['lang_korean_level'] ?? '', $lv) ?></span><?= $lv ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="lang-row">
                        <span class="lang-name">ພາສາຍີ່ປຸ່ນ ລະດັບ:</span>
                        <?php foreach (['ອ່ອນ', 'ປານກາງ', 'ດີ', 'ດີຫຼາຍ'] as $lv): ?>
                        <span class="chk-item"><span class="box"><?= checkbox_lao($row['lang_japanese_level'] ?? '', $lv) ?></span><?= $lv ?></span>
                        <?php endforeach; ?>
                    </div>
                </li>
                <li>
                    <span class="fld-label">ມີຄວາມສາມາດອື່ນໆ</span>
                    <span class="fld-line w-lg"><?= htmlspecialchars($row['other_ability'] ?? '') ?></span>
                </li>
            </ul>

            <!-- ===== ໝາຍເຫດ ===== -->
            <div class="section-title">ໝາຍເຫດ:</div>

            <div class="form-row">
                <span class="fld-label">ຂ້າພະເຈົ້າສະໝັກ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['apply_type'] ?? '', 'ແບບດ່ຽວ') ?></span>ແບບດ່ຽວ</span>
                <span class="chk-item"><span class="box"><?= checkbox_lao($row['apply_type'] ?? '', 'ແບບຄູ່ຜົວເມຍ') ?></span>ແບບຄູ່ຜົວເມຍ</span>
            </div>

        </div>
    </div>
</body>

</html>