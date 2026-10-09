$(document).ready(function () {

    // ===================================================
    // ລັອກຟອມ ຖ້າຂໍ້ມູນຖືກຢືນຢັນແລ້ວ (Verify)
    // ===================================================
    if (isLocked) {
        $("#general_form :input").prop("disabled", true);
        $(".btn-save, .btn-verify").addClass("d-none");
    }

    // ===================================================
    // ສະແດງ/ປິດ ຊ່ອງປະເທດ (ຖ້າເຄີຍໄປຕ່າງປະເທດ)
    // ===================================================
    function toggleAbroadCountry() {
        let val = $("input[name='abroad']:checked").val();
        $("#abroad_country_wrap").toggle(val === "yes");
    }
    toggleAbroadCountry();
    $("input[name='abroad']").on("change", toggleAbroadCountry);

    // ===================================================
    // ຟັງຊັນບັນທຶກ/ຢືນຢັນ ຄຳຖາມສຳພາດທົ່ວໄປ
    // ===================================================
    function saveGeneral(action) {
        let form = $("#general_form")[0];
        let formData = new FormData(form);
        formData.append("action", action);

        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_general.php",
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
    $("#general_form").on("submit", function (e) {
        e.preventDefault();
        saveGeneral("save");
    });

    // ປຸ່ມ Verify (ຢືນຢັນແລ້ວ ຈະລັອກບໍ່ໃຫ້ແກ້ໄຂ)
    $("#general_verify").on("click", function (e) {
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
                saveGeneral("verify");
            }
        });
    });

});
