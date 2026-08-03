// ==========================================
    // 1. ระบบดักจับการเลือกไฟล์และแสดงภาพ Preview
    // ==========================================
    function setupImagePreview(fileInputId, boxId, contentId, previewBoxId, imgPreviewId) {
        document.getElementById(fileInputId).addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // นำข้อมูลรูปไปใส่ในแท็ก <img>
                    document.getElementById(imgPreviewId).src = event.target.result;
                    
                    // ซ่อนหน้าตาอัปโหลดเดิม และแสดงกล่องพรีวิวรูปแทน
                    document.getElementById(contentId).classList.add('d-none');
                    document.getElementById(previewBoxId).classList.remove('d-none');
                    
                    // เพิ่มคลาสตกแต่งกล่องว่ามีไฟล์เข้ามาแล้ว
                    document.getElementById(boxId).classList.add('has-file');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // // เรียกใช้งานระบบ Preview ทั้งฝั่งรูปหน้า และฝั่งอัปโหลดลายเซ็น
    setupImagePreview('file-photo', 'box-photo', 'content-photo', 'preview-box-photo', 'img-preview-photo');
    setupImagePreview('file-interview-form', 'box-interview-form', 'content-interview-form', 'preview-box-interview-form', 'img-preview-interview-form');
    
    $(document).ready(function() {
        
        $("#dob").on("change",function(){
            var birthday = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - birthday.getFullYear();
            $("#age").val(age);
            let yearf = parseFloat(age - 18);
            $("#agricu").val(yearf);
        }) 
        $("#gua_dob").on("change",function(){
            var birthday = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - birthday.getFullYear();
            $("#gua_age").val(age);
        }) 
        $("#pro_id").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_pro.php",
                data: {
                    pro_id: pro_id
                },
                success: function(response) {
                    $("#dis_id").html(response);
                }
            });

        });
        $("#dis_id").change(function(e) {
            let dis_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_dis.php",
                data: {
                    dis_id: dis_id
                },
                success: function(response) {
                    $("#vill_id").html(response);
                }
            });

        });
        
        $("#pro_id_b").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_pro.php",
                data: {
                    pro_id: pro_id
                },
                success: function(response) {
                    $("#dis_id_b").html(response);
                }
            });

        });
        $("#dis_id_b").change(function(e) {
            let dis_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_dis.php",
                data: {
                    dis_id: dis_id
                },
                success: function(response) {
                    $("#vill_id_b").html(response);
                }
            });

        });
    });

