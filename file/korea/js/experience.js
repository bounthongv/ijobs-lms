$(document).ready(function () {

    // ===================================================
    // ລັອກຟອມ ຖ້າຂໍ້ມູນຖືກຢືນຢັນແລ້ວ (Verify)
    // ===================================================
    if (isLocked) {
        $("#experience_form :input").prop("disabled", true);
        $(".btn-save, .btn-verify").addClass("d-none");
    }

    // ===================================================
    // ຟັງຊັນຊ່ວຍ: ສະແດງ/ປິດ ຊ່ອງປ້ອນຕາມຄ່າ radio ທີ່ເລືອກ
    // ===================================================
    function toggleByRadio(radioName, showValue, wrapId) {
        function update() {
            let val = $("input[name='" + radioName + "']:checked").val();
            $("#" + wrapId).toggle(val === showValue);
        }
        update();
        $("input[name='" + radioName + "']").on("change", update);
    }

    // ຊ່ອງປະສົບການ (ປີ) — ສະແດງເມື່ອເລືອກ ເຄີຍ
    toggleByRadio("exp_farming", "yes", "farming_years_wrap");
    toggleByRadio("exp_orchard", "yes", "orchard_years_wrap");
    toggleByRadio("exp_greenhouse", "yes", "greenhouse_years_wrap");
    toggleByRadio("exp_machine", "yes", "machine_years_wrap");

    // ຊ່ອງປີທີ່ເຄີຍໄປເກົາຫຼີ — ສະແດງເມື່ອເລືອກ ເຄີຍ
    toggleByRadio("worked_korea", "yes", "korea_year_wrap");

    // ===================================================
    // ຟັງຊັນບັນທຶກ/ຢືນຢັນ ປະສົບການເຮັດວຽກ
    // ===================================================
    function saveExperience(action) {
        let form = $("#experience_form")[0];
        let formData = new FormData(form);
        formData.append("action", action);

        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_experience.php",
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
    $("#experience_form").on("submit", function (e) {
        e.preventDefault();
        saveExperience("save");
    });

    // ປຸ່ມ Verify (ຢືນຢັນແລ້ວ ຈະລັອກບໍ່ໃຫ້ແກ້ໄຂ)
    $("#experience_verify").on("click", function (e) {
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
                saveExperience("verify");
            }
        });
    });

});
