<?php 
    include('../header.php');
    $sql_pro = $conn->prepare("SELECT * FROM province ORDER BY pro_id ASC");
    $sql_pro->execute();
    $pro = $sql_pro->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    :root {
            --green-dark:   #1a4d2e;
            --green-mid:    #000;
            --green-btn:    #2d9e5f;
            --green-light:  #e8f5ee;
            --green-border: #d1e8d8;
            --green-text:   #000;
        }

        body { background: #f0f4f0; }

        .card { border: 1px solid var(--green-border); border-radius: 10px; }

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

        .form-control:focus, .form-select:focus {
            border-color: var(--green-btn);
            box-shadow: 0 0 0 2px rgba(45,158,95,.12);
        }

        .btn-main {
            background: var(--green-dark);
            color: #fff;
            border: none;
            font-weight: 600;
        }

        .btn-main:hover { background: var(--green-mid); color: #fff; }

        .required { color: #e24b4a; }

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
            background-color: #0b5135; /* สีเขียวเข้ม */
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
</style>
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-size:19px; font-weight:700; color:#0f172a; margin:0;">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>ລາຍການພະແນກບັນທືກຂໍ້ມູນ
        </h5>
    </div>
</div>
<div class="card shadow-none" style="max-width:1920px;">

            <form method="POST" id="save_name" enctype="multipart/form-data">

                <div class="section-head">
                    <i class="bi bi-person me-2"></i>ຂໍ້ມູນສ່ວນຕົວ
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ວັນທີ່ <span class="required">*</span></label>
                            <input type="date" name="data_date_create" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ລະຫັດ <span class="required">*</span></label>
                            <input type="text" name="data_id" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ຊື່ (ພາສາລາວ) <span class="required">*</span></label>
                            <input type="text" name="data_fname" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ນາມສະກຸນ (ພາສາລາວ) <span class="required">*</span></label>
                            <input type="text" name="data_lname" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ຊື່ (ອັງກິດ) <span class="required">*</span></label>
                            <input type="text" name="data_fname_eng" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ນາມສະກຸນ (ອັງກິດ) <span class="required">*</span></label>
                            <input type="text" name="data_lname_eng" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ວັນເດືອນປີເກີດ <span class="required">*</span></label>
                            <input type="date" name="data_dob" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ເພດ <span class="required">*</span></label>
                            <select name="data_gender" class="form-select form-select-sm" >
                                <option value="">ເລືອກ</option>
                                <option value="F">ຍິງ</option>
                                <option value="M">ຊາຍ</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ອາຍຸ <span class="required">*</span></label>
                            <input type="text" name="data_age" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ໝາຍເລກປະຈຳຕົວ <span class="required">*</span></label>
                            <input type="text" name="data_identity" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ເລກທີ Passport <span class="required">*</span></label>
                            <input type="text" name="data_passport" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Passprt Issue date <span class="required">*</span></label>
                            <input type="date" name="data_pass_date" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Passport termination date <span class="required">*</span></label>
                            <input type="date" name="data_ex_date" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="text" name="data_phone" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Married couple <span class="required">*</span></label>
                            <input type="text" name="data_sts" class="form-control form-control-sm" >
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
                            <select name="pro_id" id="pro_id" class="form-select form-select-sm" >
                                <option value="">ເລືອກ</option>
                                <?php foreach($pro as $proa): ?>
                                    <option value="<?= $proa['pro_id'] ?>"><?= $proa['pro_name_lao'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ເມືອງ <span class="required">*</span></label>
                            <select name="dis_id" id="dis_id" class="form-select form-select-sm" >
                                <option value="">ເລືອກ</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ບ້ານ <span class="required">*</span></label>
                            <select name="vill_id" id="vill_id" class="form-select form-select-sm" >
                                <option value="">ເລືອກ</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="m-0" style="border-color:var(--green-border);">
                <div class="section-head">
                    <i class="bi bi-credit-card me-2"></i>Free visa
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Free visa <span class="required">*</span></label>
                            <input type="text" name="data_free_visa" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Code <span class="required">*</span></label>
                            <input type="text" name="data_code" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Extend 3 months <span class="required">*</span></label>
                            <input type="text" name="data_exten" class="form-control form-control-sm" >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Come back Laos <span class="required">*</span></label>
                            <input type="text" name="data_come_back" class="form-control form-control-sm" >
                        </div>
                    </div>
                </div>
                <hr class="m-0" style="border-color:var(--green-border);">
                <div class="section-head">
                    <i class="bi bi-building me-2"></i>Employer
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Requested Month <span class="required">*</span></label>
                            <input type="text" name="data_reques" class="form-control form-control-sm"  >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ທີ່ຢູ່ <span class="required">*</span></label>
                            <input type="text" name="data_address" class="form-control form-control-sm"  >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ເບີໂທ <span class="required">*</span></label>
                            <input type="text" name="data_tel" class="form-control form-control-sm"  >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ລະຫັດນາຍຈ້າງ <span class="required">*</span></label>
                            <input type="text" name="data_emp_id" class="form-control form-control-sm"  >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ທີ່ຢູ່ນາຍຈ້າງ <span class="required">*</span></label>
                            <input type="text" name="data_emp_add" class="form-control form-control-sm"  >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ຊື່ນາຍຈ້າງ <span class="required">*</span></label>
                            <input type="text" name="data_emp_name" class="form-control form-control-sm"  >
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">ປະເພດ <span class="required">*</span></label>
                            <input type="text" name="data_type" class="form-control form-control-sm"  >
                        </div>
                    </div>
                </div>
                

                

                <!-- <div class="section-head">
                    <i class="bi bi-camera me-2"></i>ຮູບ ແລະ ລາຍເຊັນ 
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold mb-2">ຮູບໜ້າ <span class="asterisk">*</span></label>
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
                                <input type="file" name="cit_profile" id="file-photo" accept="image/*" class="d-none">
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold mb-2">ລາຍເຊັນ <span class="asterisk">*</span></label>
                            
                            
                            
                            <div class="tab-content" id="signatureTabContent">
                                
                                <div class="tab-pane fade show active" id="sig-upload" role="tabpanel">
                                    <div class="upload-box" id="box-signature" onclick="document.getElementById('file-signature').click()">
                                        <div class="upload-content text-center" id="content-signature">
                                            <div class="icon-circle">
                                                <i class="bi bi-vector-pen"></i>
                                            </div>
                                            <h6 class="mb-1">Upload ຮູບລາຍເຊັນ</h6>
                                            <p class="text-muted-custom mb-0">ຄລິກເພື່ອເລືອກໄຟລ໌ຮູບພາບ</p>
                                        </div>
                                        <div class="preview-container d-none text-center" id="preview-box-sig">
                                            <img src="" class="preview-img mb-2" id="img-preview-sig">
                                            <p class="text-success small mb-0"><i class="bi bi-check-circle-fill"></i> ເລືອກລາຍເຊັນແລ້ວ (ຄລິກເພື່ອປ່ຽນ)</p>
                                        </div>
                                        <input type="file" name="cit_sig" id="file-signature" accept="image/*" class="d-none">
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="sig-canvas" role="tabpanel">
                                    <div class="canvas-container">
                                        <canvas id="signature-pad"></canvas>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="text-muted-custom align-self-center">ໃຊ້ເມົ້າ ຫຼື ນິ້ວມືເຊ็นໃສ່ຫ້ອງຂາວ</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="clear-canvas">
                                            <i class="bi bi-trash3-fill"></i> ລຶບໃໝ่
                                        </button>
                                    </div>
                                    <input type="hidden" name="cit_sig_canvas" id="cit_sig_canvas">
                                </div>
                                
                                
                            </div>
                        </div> 

                    </div>
                </div>-->
                <div class="section-head">
                    <i class="bi bi-house-door-fill me-2"></i>ໝາຍເຫດເພີ່ມເຕີມ
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="" class="form-label fw-bold mb-2">ໝາຍເຫດ</label>
                            <textarea name="data_remark" id="" rows="3" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 px-3 py-2 border-top" style="background:#fafcfa;border-color:var(--green-border)!important;">
                    <a href="../list_name.php" class="btn btn-sm btn-outline-secondary px-4">
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
<script>
    $(document).ready(function () {
        $("#pro_id").change(function (e) { 
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_pro.php",
                data: {pro_id:pro_id},
                success: function (response) {
                    $("#dis_id").html(response);
                }
            });
            
        });
        $("#dis_id").change(function (e) { 
            let dis_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_dis.php",
                data: {dis_id:dis_id},
                success: function (response) {
                    $("#vill_id").html(response);
                }
            });
            
        });
    });
</script>