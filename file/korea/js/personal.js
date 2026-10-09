$(document).ready(function () {

    // ===================================================
    // ລັອກຟອມ ຖ້າຂໍ້ມູນຖືກຢືນຢັນແລ້ວ (Verify)
    // ===================================================
    if (isLocked) {
        $("#personal_form :input").prop("disabled", true);
        $(".btn-save, .btn-verify").addClass("d-none");
    }

    // ===================================================
    // ສະແດງ/ປິດ ຊ່ອງຊື່ຜົວ ຫຼື ເມຍ (ຖ້າແຕ່ງງານ)
    // ===================================================
    function toggleSpouse() {
        $("#spouse_wrap").toggle($("input[name='status']:checked").val() === "married");
    }
    toggleSpouse();
    $("input[name='status']").on("change", toggleSpouse);

    // ===================================================
    // ສະແດງ/ປິດ ຈຳນວນລູກ (ຖ້າມີລູກ)
    // ===================================================
    function toggleChildren() {
        $("#children_wrap").toggle($("input[name='has_children']:checked").val() === "yes");
    }
    toggleChildren();
    $("input[name='has_children']").on("change", toggleChildren);

    // ===================================================
    // ໂຫຼດເມືອງ ຕາມແຂວງທີ່ເລືອກ
    // ===================================================
    $(document).on("change", ".sel-pro", function () {
        let disSel = $(this).data("dis");
        let villSel = $(this).data("vill");
        $.ajax({
            type: "post",
            url: "../get/get_pro.php",
            data: { pro_id: $(this).val() },
            success: function (response) {
                $(disSel).html(response);
                $(villSel).html('<option value="">ເລືອກ</option>');
            }
        });
    });

    // ===================================================
    // ໂຫຼດບ້ານ ຕາມເມືອງທີ່ເລືອກ
    // ===================================================
    $(document).on("change", ".sel-dis", function () {
        let villSel = $(this).data("vill");
        $.ajax({
            type: "post",
            url: "../get/get_dis.php",
            data: { dis_id: $(this).val() },
            success: function (response) {
                $(villSel).html(response);
            }
        });
    });

    // ===================================================
    // ຟັງຊັນບັນທຶກ/ຢືນຢັນ ຂໍ້ມູນສ່ວນຕົວ
    // ===================================================
    function savePersonal(action) {
        let form = $("#personal_form")[0];
        let formData = new FormData(form);
        formData.append("action", action);

        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_personal.php",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.sts === 'error') {
                    showToast(response.message, 'error');
                    return;
                }
                showToast(response.message, 'success');
                setTimeout(function () {
                    location = '../list_data_entry.php';
                }, 1500);
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    }

    // ປຸ່ມບັນທຶກຂໍ້ມູນ
    $("#personal_form").on("submit", function (e) {
        e.preventDefault();
        savePersonal("save");
    });

    // ປຸ່ມຢືນຢັນຂໍ້ມູນ (ຢືນຢັນແລ້ວ ຈະລັອກບໍ່ໃຫ້ແກ້ໄຂ)
    $("#personal_verify").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "ຢືນຢັນຂໍ້ມູນ ແທ້ ຫຼື ບໍ່?",
            text: "ຫຼັງຢືນຢັນແລ້ວ ຈະບໍ່ສາມາດແກ້ໄຂຂໍ້ມູນໄດ້ອີກ",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2d9e5f",
            cancelButtonColor: "#d33",
            confirmButtonText: "ຢືນຢັນ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            if (result.isConfirmed) {
                savePersonal("verify");
            }
        });
    });

});
