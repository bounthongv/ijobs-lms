<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>ນຳເຂົ້າໄຟລ໌ Excel (2)</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<style>
    *{
        font-family: 'Noto Sans Lao', 'Segoe UI', sans-serif;
    }
    .import-excel-card {
      max-width: 480px;
      margin: 0 auto;
      background: #ffffff;
      border: 1px solid #e5e5e5;
      border-radius: 12px;
      padding: 24px;
    }
    .import-excel-title {
      font-weight: 500;
      font-size: 16px;
      margin: 0 0 4px;
      color: #1a1a1a;
    }
    .import-excel-subtitle {
      font-size: 13px;
      color: #6b6b6b;
      margin: 0 0 20px;
    }
    .import-excel-dropzone {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 8px;
      border: 1.5px dashed #c9c9c9;
      border-radius: 8px;
      padding: 32px 16px;
      cursor: pointer;
      text-align: center;
      transition: border-color 0.15s ease;
    }
    .import-excel-dropzone.dragging {
      border-color: #378add;
      background: #f5f9fe;
    }
    .import-excel-dropzone-label {
      font-size: 14px;
      color: #1a1a1a;
    }
    .import-excel-dropzone-meta {
      font-size: 12px;
      color: #9a9a9a;
    }
    .import-excel-actions {
      display: flex;
      gap: 8px;
      margin-top: 20px;
    }
    .btn-primary {
      flex: 1;
      height: 36px;
      background: #1a1a1a;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      cursor: pointer;
    }
    .btn-primary:hover { background: #333333; }
    .btn-secondary {
      flex: 0 0 auto;
      height: 36px;
      padding: 0 16px;
      background: transparent;
      color: #1a1a1a;
      border: 1px solid #c9c9c9;
      border-radius: 8px;
      font-size: 14px;
      cursor: pointer;
    }
    .btn-secondary:hover { background: #f5f5f5; }
</style>
<body>

<div class="import-excel-card">
  <p class="import-excel-title">ນຳເຂົ້າໄຟລ໌ Excel (ຕາຕະລາງທີ 2)</p>
  <p class="import-excel-subtitle">ຮອງຮັບໄຟລ໌ .xlsx, .xls, .csv ຂະໜາດບໍ່ເກີນ 10MB</p>

  <label for="excelFile2" id="dropZone2" class="import-excel-dropzone">
    <span class="import-excel-dropzone-label" id="dropLabel2">ລາກໄຟລ໌ມາວາງ ຫຼື ກົດເພື່ອເລືອກໄຟລ໌</span>
    <span class="import-excel-dropzone-meta" id="fileMeta2"></span>
    <input type="file" id="excelFile2" accept=".xlsx,.xls,.csv" hidden />
  </label>

  <div class="import-excel-actions">
    <button type="button" id="importBtn2" class="btn-primary">ນຳເຂົ້າ</button>
    <button type="button" id="clearBtn2" class="btn-secondary">ຍົກເລີກ</button>
  </div>
</div>

<script>
  $(function () {
    let selectedFile2 = null;

    const $dropZone  = $("#dropZone2");
    const $fileInput = $("#excelFile2");
    const $dropLabel = $("#dropLabel2");
    const $fileMeta  = $("#fileMeta2");
    const $importBtn = $("#importBtn2");
    const $clearBtn  = $("#clearBtn2");

    $fileInput.on("change", function (e) {
        handleFile(e.target.files[0]);
    });

    $dropZone.on("dragover", function (e) {
        e.preventDefault();
        $(this).addClass("dragging");
    });

    $dropZone.on("dragleave", function (e) {
        e.preventDefault();
        $(this).removeClass("dragging");
    });

    $dropZone.on("drop", function (e) {
        e.preventDefault();
        $(this).removeClass("dragging");
        handleFile(e.originalEvent.dataTransfer.files[0]);
    });

    function handleFile(file) {
        if (!file) return;

        const allowExt = ["xlsx", "xls", "csv"];
        const ext = file.name.split(".").pop().toLowerCase();

        if (!allowExt.includes(ext)) {
            Swal.fire({
                icon: "error",
                title: "ໄຟລ໌ບໍ່ຖືກຕ້ອງ",
                text: "ກະລຸນາເລືອກໄຟລ໌ .xlsx, .xls ຫຼື .csv ເທົ່ານັ້ນ",
                confirmButtonColor: "#1a1a1a"
            });
            return;
        }

        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({
                icon: "error",
                title: "ໄຟລ໌ໃຫຍ່ເກີນໄປ",
                text: "ຂະໜາດໄຟລ໌ຕ້ອງບໍ່ເກີນ 10MB",
                confirmButtonColor: "#1a1a1a"
            });
            return;
        }

        selectedFile2 = file;
        $dropLabel.text(file.name);
        $fileMeta.text((file.size / 1024).toFixed(1) + " KB");
    }

    $clearBtn.on("click", function () {
        resetForm();
    });

    function resetForm() {
        selectedFile2 = null;
        $fileInput.val("");
        $dropLabel.text("ລາກໄຟລ໌ມາວາງ ຫຼື ກົດເພື່ອເລືອກໄຟລ໌");
        $fileMeta.text("");
    }

    // ==================== ປຸ່ມນຳເຂົ້າ: ສົ່ງໄຟລ໌ຕົງໆດ້ວຍ FormData ໄປ save2.php ====================
    $importBtn.on("click", function () {
        if (!selectedFile2) {
            Swal.fire({
                icon: "warning",
                title: "ຍັງບໍ່ໄດ້ເລືອກໄຟລ໌",
                text: "ກະລຸນາເລືອກໄຟລ໌ Excel ກ່ອນນຳເຂົ້າ",
                confirmButtonColor: "#1a1a1a"
            });
            return;
        }

        const formData = new FormData();
        formData.append("excel_file", selectedFile2);

        Swal.fire({
            title: "ກຳລັງນຳເຂົ້າຂໍ້ມູນ...",
            html: "ກະລຸນາລໍຖ້າ ຢ່າປິດໜ້ານີ້",
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: "save2.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "ນຳເຂົ້າສຳເລັດ!",
                        text: `ບັນທຶກຂໍ້ມູນຈຳນວນ ${res.inserted} ແຖວ ສຳເລັດແລ້ວ`,
                        confirmButtonColor: "#1a1a1a"
                    }).then(() => { resetForm(); });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "ນຳເຂົ້າບໍ່ສຳເລັດ",
                        text: res.message || "ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກຂໍ້ມູນ",
                        confirmButtonColor: "#1a1a1a"
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "ເຊື່ອມຕໍ່ Server ບໍ່ສຳເລັດ",
                    text: "ກະລຸນາລອງໃໝ່ອີກຄັ້ງ",
                    confirmButtonColor: "#1a1a1a"
                });
            }
        });
    });
  });
</script>
</body>
</html>