<?php
include('../header.php');
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
    /* ค้างสีตอนถูกเลือก (Active State) */
.btn-borrow.active {
    background: linear-gradient(135deg, #1565c0 0%, #0091ea 100%);
    box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.4), 0 6px 14px rgba(21, 101, 192, 0.5);
    transform: translateY(-2px);
}

.btn-borrow.active::before {
    content: "\F26A"; /* bi-check-circle-fill */
    font-family: "bootstrap-icons";
    position: absolute;
    top: -8px;
    right: -8px;
    background: #fff;
    color: #0091ea;
    border-radius: 50%;
    font-size: 16px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
}

.btn-pay.active {
    background: linear-gradient(135deg, #2e7d32 0%, #00c853 100%);
    box-shadow: 0 0 0 3px rgba(67, 233, 123, 0.4), 0 6px 14px rgba(46, 125, 50, 0.5);
    transform: translateY(-2px);
}

.btn-pay.active::before {
    content: "\F26A"; /* bi-check-circle-fill */
    font-family: "bootstrap-icons";
    position: absolute;
    top: -8px;
    right: -8px;
    background: #fff;
    color: #00c853;
    border-radius: 50%;
    font-size: 16px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
}

/* ปุ่มที่ไม่ถูกเลือก ให้จางลงเล็กน้อยตอนมีการเลือกอันอื่นแล้ว */
.custom-action-row.has-selection .btn:not(.active) {
    opacity: 0.55;
}
</style>
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>Vacancy Add
        </h5>
    </div>
</div>
<div class="card shadow-none" style="max-width:1920px;">

    <form method="POST" id="save_vacancy" enctype="multipart/form-data">

        <div class="section-head">
            <i class="bi bi-person me-2"></i>ຂໍ້ມູນສ່ວນຕົວ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">Interview Date <span class="required">*</span></label>
                    <input type="date" name="interview_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Eng Sure Name <span class="required">*</span></label>
                    <input type="text" name="lname_eng" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Eng Name <span class="required">*</span></label>
                    <input type="text" name="fname_eng" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Nickname <span class="required">*</span></label>
                    <input type="text" name="nickname" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Lao Name <span class="required">*</span></label>
                    <input type="text" name="fname" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Lao Sure Name <span class="required">*</span></label>
                    <input type="text" name="lname" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Phone NO1 <span class="required">*</span></label>
                    <input type="text" name="phone1" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Phone NO2 <span class="required">*</span></label>
                    <input type="text" name="phone2" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Fam Phone NO <span class="required">*</span></label>
                    <input type="text" name="fam_phone" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Nationality <span class="required">*</span></label>
                    <input type="text" name="nationality" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Date of birth <span class="required">*</span></label>
                    <input type="date" name="dob" id="dob" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Age <span class="required">*</span></label>
                    <input type="text" name="age" id="age" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Gender <span class="required">*</span></label>
                    <select name="gender" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <option value="F">ຍິງ</option>
                        <option value="M">ຊາຍ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <option value="SINGLE">SINGLE</option>
                        <option value="MARRIED">MARRIED</option>
                        <option value="DIVORCED">DIVORCED</option>
                        <option value="MARRIED(COUPLE)">MARRIED(COUPLE)</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Weight <span class="required">*</span></label>
                    <input type="text" name="weight" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Height <span class="required">*</span></label>
                    <input type="text" name="height" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">family book NO <span class="required">*</span></label>
                    <input type="text" name="family_book_no" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">family book Date <span class="required">*</span></label>
                    <input type="date" name="family_book_date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Father Name <span class="required">*</span></label>
                    <input type="text" name="father" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Mother Name <span class="required">*</span></label>
                    <input type="text" name="mother" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Unit <span class="required">*</span></label>
                    <input type="text" name="unit" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Home NO <span class="required">*</span></label>
                    <input type="text" name="home" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Passport NO <span class="required">*</span></label>
                    <input type="text" name="passport" class="form-control form-control-sm" required>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Issue Date <span class="required">*</span></label>
                    <input type="date" name="issue_date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Exp date <span class="required">*</span></label>
                    <input type="date" name="exp_date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Driver License <span class="required">*</span></label>
                    <select name="driver" class="form-select form-select-sm">
                        <option value="NO">NO</option>
                        <option value="A">A</option>
                        <option value="AB">AB</option>
                        <option value="ABC">ABC</option>
                        <option value="ABCD">ABCD</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="BC">BC</option>
                        <option value="CD">CD</option>
                        <option value="BCD">BCD</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Shirt Size <span class="required">*</span></label>
                    <select name="shirt_size" class="form-select form-select-sm">
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Labor type <span class="required">*</span></label>
                    <select name="labor_type" class="form-select form-select-sm">
                        <option value="New">New</option>
                        <option value="Re-New">Re-New</option>
                        <option value="New(RC)">New(RC)</option>
                        <option value="Re-entry">Re-entry</option>
                        <option value="Re-employment">Re-employment</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຊົນເຜົ່າ <span class="required">*</span></label>
                    <select name="eth" class="form-select form-select-sm" >
                                <option value="ລາວລຸ່ມ">ລາວລຸ່ມ</option>
                                <option value="ລາວເທິງ">ລາວເທິງ</option>
                                <option value="ລາວສູງ">ລາວສູງ</option>
                                <option value="ມົ້ງ">ມົ້ງ</option>
                                <option value="ໄຕ">ໄຕ</option>
                                <option value="ຜູ້ໄທ">ຜູ້ໄທ</option>
                                <option value="ລື້">ລື້</option>
                                <option value="ຍວນ">ຍວນ</option>
                                <option value="ຢັ້ງ">ຢັ້ງ</option>
                                <option value="ແຊກ">ແຊກ</option>
                                <option value="ໄທເໜືອ">ໄທເໜືອ</option>
                                <option value="ກຶມມຸ">ກຶມມຸ</option>
                                <option value="ກະຕາງ">ກະຕາງ</option>
                                <option value="ກະຕູ">ກະຕູ</option>
                                <option value="ກຣຽງ">ກຣຽງ</option>
                                <option value="ກຣີ">ກຣີ</option>
                                <option value="ຂະແມ">ຂະແມ</option>
                                <option value="ງວນ">ງວນ</option>
                                <option value="ສາມຕ່າວ">ສາມຕ່າວ</option>
                                <option value="ເຈັງ">ເຈັງ</option>
                                <option value="ສະດາງ">ສະດາງ</option>
                                <option value="ຊ່ວຍ">ຊ່ວຍ</option>
                                <option value="ຊິງມູນ">ຊິງມູນ</option>
                                <option value="ຍະເຫີນ">ຍະເຫີນ</option>
                                <option value="ຕະໂອ້ຍ">ຕະໂອ້ຍ</option>
                                <option value="ຕຣຽງ">ຕຣຽງ</option>
                                <option value="ຕຣີ">ຕຣີ</option>
                                <option value="ຕູມ">ຕູມ</option>
                                <option value="ແທ່ນ">ແທ່ນ</option>
                                <option value="ບິດ">ບິດ</option>
                                <option value="ບຣູ">ບຣູ</option>
                                <option value="ເບຣົາ">ເບຣົາ</option>
                                <option value="ປະໂກະ">ປະໂກະ</option>
                                <option value="ໄປຣ">ໄປຣ</option>
                                <option value="ຜ້ອງ">ຜ້ອງ</option>
                                <option value="ມະກອງ">ມະກອງ</option>
                                <option value="ມ້ອຍ">ມ້ອຍ</option>
                                <option value="ຢຣຸ">ຢຣຸ</option>
                                <option value="ແຢະ">ແຢະ</option>
                                <option value="ລະເມດ">ລະເມດ</option>
                                <option value="ລະວີ">ລະວີ</option>
                                <option value="ໂອຍ">ໂອຍ</option>
                                <option value="ເອີດູ">ເອີດູ</option>
                                <option value="ຮ່າຣັກ">ຮ່າຣັກ</option>
                                <option value="ລາຫູ">ລາຫູ</option>
                                <option value="ສີລາ">ສີລາ</option>
                                <option value="ຮ່າຍີ່">ຮ່າຍີ່</option>
                                <option value="ໂລໂລ">ໂລໂລ</option>
                                <option value="ຫໍ້">ຫໍ້</option>
                                <option value="ໂລໂລ">ໂລໂລ</option>
                                <option value="ສິງສີລິ/ພູນ້ອຍ">ສິງສີລິ/ພູນ້ອຍ</option>
                                <option value="ອິວມ້ຽນ">ອິວມ້ຽນ</option>
                            </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Agricultural experience <span class="required">*</span></label>
                    <input type="text" name="agricu" id="agricu" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Interview Location <span class="required">*</span></label>
                    <select name="interview_location" class="form-select form-select-sm" required>
                        <option value="Outside">Outside</option>
                        <option value="Inside">Inside</option>
                        <option value="Re-employment">Re-employment</option>
                        <option value="NEW(RC)">NEW(RC)</option>
                        <option value="Re-New">Re-New</option>
                        <option value="Online">Online</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Job <span class="required">*</span></label>
                    <input type="text" name="job" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Interview Name <span class="required">*</span></label>
                    <input type="text" name="interview_name" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຮງງານ ມີຕົວເລືອກ <span class="required">*</span></label>
                    <select name="list" class="form-select form-select-sm">
                        <option value="ຄົນດຽວ">ຄົນດຽວ</option>
                        <option value="ຄູ່ຜົວ-ເມຍ">ຄູ່ຜົວ-ເມຍ</option>
                    </select>
                </div>
            </div>
        </div>
        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-house-door-fill me-2"></i>ທີ່ຢູ່ປัดຈຸບັນ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ <span class="required">*</span></label>
                    <select name="pro_id" id="pro_id" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <?php foreach ($pro as $proa): ?>
                            <option value="<?= $proa['pro_id'] ?>"><?= $proa['pro_name_lao'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ <span class="required">*</span></label>
                    <select name="dis_id" id="dis_id" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ <span class="required">*</span></label>
                    <select name="vill_id" id="vill_id" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
            </div>
        </div>
        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-house-door-fill me-2"></i>ທີ່ຢູ່ບ່ອນເກີດ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ <span class="required">*</span></label>
                    <select name="pro_id_b" id="pro_id_b" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <?php foreach ($pro as $proa): ?>
                            <option value="<?= $proa['pro_id'] ?>"><?= $proa['pro_name_lao'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ <span class="required">*</span></label>
                    <select name="dis_id_b" id="dis_id_b" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ <span class="required">*</span></label>
                    <select name="vill_id_b" id="vill_id_b" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="section-head">
            <i class="bi bi-camera me-2"></i>Upload File
        </div>
        <div class="p-3">
            <div class="row g-3">
                <!-- ຮູບພາບ -->
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-bold mb-2">ຮູບພາບ <span class="asterisk">*</span></label>
                    <div class="upload-box" id="box-photo" onclick="document.getElementById('file-photo').click()">
                        <div class="upload-content text-center" id="content-photo">
                            <div class="icon-circle">
                                <i class="bi bi-camera"></i>
                            </div>
                            <h6 class="mb-1">ຖ່າຍຮູບ ຫຼື Upload</h6>
                            <p class="text-muted-custom mb-0">ຄລິກເພື່ອເລືອກ</p>
                        </div>
                        <div class="preview-container d-none text-center" id="preview-box-photo">
                            <img src="" class="preview-img mb-2" id="img-preview-photo">
                            <p class="text-success small mb-0"><i class="bi bi-check-circle-fill"></i> ເລືອກຮູບແລ້ວ (ຄລິກເພື່ອປ່ຽນ)</p>
                        </div>
                        <input type="file" name="profile" id="file-photo" accept="image/*" class="d-none">
                    </div>
                </div>

                <!-- ຟອມສຳພາດ -->
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-bold mb-2">ຟອມສຳພາດ <span class="asterisk">*</span></label>
                    <div class="upload-box" id="box-interview-form" onclick="document.getElementById('file-interview-form').click()">
                        <div class="upload-content text-center" id="content-interview-form">
                            <div class="icon-circle">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <h6 class="mb-1">ຖ່າຍຮູບ ຫຼື Upload</h6>
                            <p class="text-muted-custom mb-0">ຄລິກເພື່ອເລືອກ</p>
                        </div>
                        <div class="preview-container d-none text-center" id="preview-box-interview-form">
                            <img src="" class="preview-img mb-2" id="img-preview-interview-form">
                            <p class="text-success small mb-0"><i class="bi bi-check-circle-fill"></i> ເລືອກຮູບແລ້ວ (ຄລິກເພື່ອປ່ຽນ)</p>
                        </div>
                        <input type="file" name="file_form" id="file-interview-form" accept="image/*" class="d-none">
                    </div>
                </div>

            </div>
        </div>

        <!-- ເອກະສານປະກອບ -->
        <div class="section-head mt-3">
            <i class="bi bi-folder2-open me-2"></i>ເອກະສານປະກອບ
        </div>
        <div class="p-3">
            <div class="row g-3">

                <!-- Passport -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label fw-bold mb-2">Passport</label>
                    <div class="input-group">
                        <input type="file" name="doc_passport" id="file-passport" class="form-control d-none">
                        <input type="text" class="form-control" id="text-passport" placeholder="ຍັງບໍ່ໄດ້ເລືອກໄຟລ໌" readonly>
                        <button class="btn btn-outline-primary" type="button" id="btn-passport" onclick="document.getElementById('file-passport').click()">
                            <i class="bi bi-upload"></i> ເລືອກໄຟລ໌
                        </button>
                    </div>
                </div>

                <!-- Farmer's Certificate -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label fw-bold mb-2">Farmer's Certificate</label>
                    <div class="input-group">
                        <input type="file" name="doc_farmer_cert" id="file-farmer-cert" class="form-control d-none">
                        <input type="text" class="form-control" id="text-farmer-cert" placeholder="ຍັງບໍ່ໄດ້ເລືອກໄຟລ໌" readonly>
                        <button class="btn btn-outline-primary" type="button" id="btn-farmer-cert" onclick="document.getElementById('file-farmer-cert').click()">
                            <i class="bi bi-upload"></i> ເລືອກໄຟລ໌
                        </button>
                    </div>
                </div>

                <!-- Labor Contract -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label fw-bold mb-2">Labor Contract</label>
                    <div class="input-group">
                        <input type="file" name="doc_labor_contract" id="file-labor-contract" class="form-control d-none">
                        <input type="text" class="form-control" id="text-labor-contract" placeholder="ຍັງບໍ່ໄດ້ເລືອກໄຟລ໌" readonly>
                        <button class="btn btn-outline-primary" type="button" id="btn-labor-contract" onclick="document.getElementById('file-labor-contract').click()">
                            <i class="bi bi-upload"></i> ເລືອກໄຟລ໌
                        </button>
                    </div>
                </div>

                <!-- ສຳມະໂນຄົວ -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label fw-bold mb-2">ສຳມະໂນຄົວ</label>
                    <div class="input-group">
                        <input type="file" name="doc_census" id="file-census" class="form-control d-none">
                        <input type="text" class="form-control" id="text-census" placeholder="ຍັງບໍ່ໄດ້ເລືອກໄຟລ໌" readonly>
                        <button class="btn btn-outline-primary" type="button" id="btn-census" onclick="document.getElementById('file-census').click()">
                            <i class="bi bi-upload"></i> ເລືອກໄຟລ໌
                        </button>
                    </div>
                </div>

                <!-- ຫຼັກຊັບຄ້ຳປະກັນ -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="form-label fw-bold mb-2">ຫຼັກຊັບຄ້ຳປະກັນ</label>
                    <div class="input-group">
                        <input type="file" name="doc_collateral" id="file-collateral" class="form-control d-none">
                        <input type="text" class="form-control" id="text-collateral" placeholder="ຍັງບໍ່ໄດ້ເລືອກໄຟລ໌" readonly>
                        <button class="btn btn-outline-primary" type="button" id="btn-collateral" onclick="document.getElementById('file-collateral').click()">
                            <i class="bi bi-upload"></i> ເລືອກໄຟລ໌
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-heart-pulse-fill me-2"></i>Health
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">Health Check date <span class="required">*</span></label>
                    <input type="date" name="heal_date" id="heal_date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Diagnose <span class="required">*</span></label>
                    <select name="diagnose" id="diagnose" class="form-select form-select-sm">
                        <option value="(ປົກກະຕິ)">(ປົກກະຕິ)</option>
                        <option value="(HIV)">(HIV)</option>
                        <option value="(Virus ຕັບ B)">(Virus ຕັບ B)</option>
                        <option value="(ວັນນະໂລກ)">(ວັນນະໂລກ)</option>
                        <option value="(ຊີຟີລິບ)">(ຊີຟີລິບ)</option>
                        <option value="(ລົມຊັກ,ບ້າໝູ)">(ລົມຊັກ,ບ້າໝູ)</option>
                        <option value="(ພູມແພ້)">(ພູມແພ້)</option>
                        <option value="(ພູມແພ້ຫອບຫືດ)">(ພູມແພ້ຫອບຫືດ)</option>
                        <option value="(ຕີນຈັງມືຈັງ)">(ຕີນຈັງມືຈັງ)</option>
                        <option value="(ເບົາຫວານ)">(ເບົາຫວານ)</option>
                        <option value="(ຄວາມດັນ)">(ຄວາມດັນ)</option>
                        <option value="(ໂລກຫົວໃຈ)">(ໂລກຫົວໃຈ)</option>
                        <option value="(ສານເສບຕິດ)">(ສານເສບຕິດ)</option>
                        <option value="(ຖືພາ)">(ຖືພາ)</option>
                        <option value="(ຕາບອດສີ)">(ຕາບອດສີ)</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຊື່ຄລີນີກ <span class="required">*</span></label>
                    <input type="text" name="clinic" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ວັນທີ່ <span class="required">*</span></label>
                    <input type="date" name="cli_date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຄ່າກວດສຸຂະພາບ <span class="required">*</span></label>
                    <input type="text" name="check_up" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Health Check date2 <span class="required">*</span></label>
                    <input type="date" name="heal_date2" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຄ່າກວດສຸຂະພາບ2 <span class="required">*</span></label>
                    <input type="text" name="check_up2" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Health Check date3 <span class="required">*</span></label>
                    <input type="date" name="heal_date3" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຄ່າກວດສຸຂະພາບ3 <span class="required">*</span></label>
                    <input type="text" name="check_up3" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Remark <span class="required">*</span></label>
                    <input type="text" name="heal_remark" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4" style="margin-top:30px;">
                    <button type="button" class="btn btn-danger" id="not-pass">Not Pass</button>
                    <button type="button" class="btn btn-success" id="pass">Pass</button>
                    <button type="button" class="btn btn-primary" id="re-pass">Re-Pass</button>
                    <input type="hidden" name="heal_sts" id="heal_sts" value="">
                </div>
            </div>
        </div>
        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-cash-stack me-2"></i>Loan Information
        </div>
        <div class="p-3">
            <div class="row g-3 align-items-end custom-action-row">

    <!-- ปุ่มยืม -->
    <div class="col-12 col-sm-4">
        <label class="form-label d-block invisible">Action</label>
        <button type="button" class="btn btn-borrow btn-outline-info w-100" id="btn-borrow" data-action="borrow">
            <i class="bi bi-box-arrow-in-down me-1"></i> Borrow
        </button>
    </div>

    <!-- ปุ่มจ่าย -->
    <div class="col-12 col-sm-4">
        <label class="form-label d-block invisible">Action</label>
        <button type="button" class="btn btn-pay btn-outline-success w-100" id="btn-pay" data-action="pay">
            <i class="bi bi-cash-coin me-1"></i> Pay
        </button>
    </div>
    <input type="hidden" name="pay_sts" id="pay_sts">

    <!-- ຄ່າແຮງງານ -->
    <div class="col-12 col-sm-4">
        <label class="form-label fw-bold mb-2">LABOURESE FEE : <span class="required text-danger">*</span></label>
        <div class="input-group input-group-sm fee-group">
            <span class="input-group-text"><i class="bi bi-cash"></i></span>
            <input type="text" name="labor_fee" id="labor_fee" class="form-control"  placeholder="0.00">
        </div>
    </div>

    <!-- ເກັບຄ່າທີ່ເລືອກໄວ້ ເພື່ອສົ່ງໄປ Backend -->
    <input type="hidden" name="selected_action" id="selected_action" value="">

</div>
        </div>
        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-shield-lock-fill me-2"></i>Collateral
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">ສົ່ງເອກະສານ <span class="required">*</span></label>
                    <select name="coll_sts" id="coll_sts" class="form-select form-select-sm">
                        <option value="ຍັງບໍ່ສົ່ງເອກະສານ">ຍັງບໍ່ສົ່ງເອກະສານ</option>
                        <option value="ສົ່ງເອກະສານແລ້ວ">ສົ່ງເອກະສານແລ້ວ</option>
                        <option value="ເອກະສານບໍ່ຄົບ">ເອກະສານບໍ່ຄົບ</option>
                        <option value="ຖອນເອກະສານ">ຖອນເອກະສານ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Collateral Type <span class="required">*</span></label>
                    <select name="coll_type" id="coll_type" class="form-select form-select-sm">
                        <option value="ໃບຕາດິນຂອບທອງ">ໃບຕາດິນຂອບທອງ</option>
                        <option value="ໃບຕາດິນນຳໃຊ້">ໃບຕາດິນນຳໃຊ້</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Owner <span class="required">*</span></label>
                    <input type="text" name="coll_owner" class="form-control form-select-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Area Size <span class="required">*</span></label>
                    <input type="text" name="coll_area" class="form-control form-select-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">NO <span class="required">*</span></label>
                    <input type="text" name="coll_no" class="form-control form-select-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Date <span class="required">*</span></label>
                    <input type="date" name="coll_date" class="form-control form-select-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Valuation of Assets <span class="required">*</span></label>
                    <select name="coll_value" id="coll_value" class="form-select form-select-sm">
                        <option value="150,000,000">150,000,000</option>
                        <option value="200,000,000">200,000,000</option>
                        <option value="250,000,000">250,000,000</option>
                        <option value="300,000,000">300,000,000</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ແຂວງ <span class="required">*</span></label>
                    <select name="coll_pro" id="coll_pro" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <?php foreach ($pro as $proa): ?>
                            <option value="<?= $proa['pro_id'] ?>"><?= $proa['pro_name_lao'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ເມືອງ <span class="required">*</span></label>
                    <select name="coll_dis" id="coll_dis" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ບ້ານ <span class="required">*</span></label>
                    <select name="coll_vill" id="coll_vill" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ຫົວໜ່ວຍ <span class="required">*</span></label>
                    <select name="coll_unit" id="coll_unit" class="form-select form-select-sm">
                        <option value="m2">m2</option>
                        <option value="m">m</option>
                        <option value="Ha">Ha</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">ລະຫັດແຜນທີ່ <span class="required">*</span></label>
                    <input type="text" name="coll_map" class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <hr class="m-0" style="border-color:var(--green-border);">
        <div class="section-head">
            <i class="bi bi-person-check-fill me-2"></i>Guarantor Profile
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <label class="form-label">Relation <span class="required">*</span></label>
                    <input type="text" name="gua_relation" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Lao Name <span class="required">*</span></label>
                    <input type="text" name="gua_fname" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Phone NO <span class="required">*</span></label>
                    <input type="text" name="gua_phone" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Date of birth <span class="required">*</span></label>
                    <input type="date" name="gua_dob" id="gua_dob" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Nationality <span class="required">*</span></label>
                    <input type="text" name="gua_nationality" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Job <span class="required">*</span></label>
                    <input type="text" name="gua_job" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Age <span class="required">*</span></label>
                    <input type="text" name="gua_age" id="gua_age" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Gender <span class="required">*</span></label>
                    <input type="text" name="gua_gender" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Province <span class="required">*</span></label>
                    <select name="gua_pro" id="gua_pro" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                        <?php foreach ($pro as $proa): ?>
                            <option value="<?= $proa['pro_id'] ?>"><?= $proa['pro_name_lao'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Family Book NO <span class="required">*</span></label>
                    <input type="text" name="gua_book" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Family Book date <span class="required">*</span></label>
                    <input type="text" name="gua_book_date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">District <span class="required">*</span></label>
                    <select name="gua_dis" id="gua_dis" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
                
                
                
                <div class="col-12 col-sm-4">
                    <label class="form-label">Unit <span class="required">*</span></label>
                    <input type="text" name="gua_unit" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Home NO <span class="required">*</span></label>
                    <input type="text" name="gua_home" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">Village <span class="required">*</span></label>
                    <select name="gua_vill" id="gua_vill" class="form-select form-select-sm">
                        <option value="">ເລືອກ</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="section-head">
            <i class="bi bi-chat-left-text-fill me-2"></i>ໝາຍເຫດເພີ່ມເຕີມ
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-12">
                    <label for="" class="form-label fw-bold mb-2">ໝາຍເຫດ</label>
                    <textarea name="da_remark" id="" rows="3" class="form-control form-control-sm"></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 px-3 py-2 border-top" style="background:#fafcfa;border-color:var(--green-border)!important;">
            <a href="../list_data_entry.php" class="btn btn-sm btn-outline-secondary px-4">
                <i class="bi bi-x-lg me-1"></i> ຍົກເລີກ
            </a>
            <button type="submit" class="btn btn-sm btn-primary px-4">
                <i class="bi bi-floppy me-1"></i> ບັນທຶกຂໍ້ມູນ
            </button>
        </div>

    </form>
</div>
<?php
include('../footer.php');
?>
<script src="../js/data_entry.js?v=<?= filemtime('../js/data_entry.js') ?>"></script>