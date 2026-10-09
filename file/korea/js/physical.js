$(document).ready(function () {

    // ===================================================
    // ລັອກຟອມ ຖ້າຂໍ້ມູນຖືກຢືນຢັນແລ້ວ (Verify)
    // ===================================================
    if (isLocked) {
        $("#physical_form :input").prop("disabled", true);
        $(".btn-save, .btn-verify").addClass("d-none");
    }

    // ===================================================
    // ຄຳນວນຄ່າ BMI ອັດຕະໂນມັດຈາກ ນ້ຳໜັກ / ສ່ວນສູງ
    // ===================================================
    function calcBmi() {
        let w = parseFloat($("input[name='weight']").val());
        let h = parseFloat($("input[name='height']").val());
        if (!isNaN(w) && !isNaN(h) && h > 0) {
            let bmi = w / ((h / 100) * (h / 100));
            $("input[name='bmi']").val(bmi.toFixed(2));
        } else {
            $("input[name='bmi']").val("");
        }
    }
    $("input[name='weight'], input[name='height']").on("input", calcBmi);

    // ===================================================
    // ສະແດງ/ປິດ ລາຍລະອຽດໂລກປະຈຳຕົວ (ຖ້າເລືອກ ມີ)
    // ===================================================
    function toggleChronic() {
        $("#chronic_detail_wrap").toggle($("input[name='chronic_disease']:checked").val() === "yes");
    }
    toggleChronic();
    $("input[name='chronic_disease']").on("change", toggleChronic);

    // ===================================================
    // ສະແດງ/ປິດ ຈຳນວນນິ້ວມື (ຖ້າເລືອກ ບໍ່ຄົບ)
    // ===================================================
    function toggleFinger() {
        $("#finger_count_wrap").toggle($("input[name='finger_sts']:checked").val() === "incomplete");
    }
    toggleFinger();
    $("input[name='finger_sts']").on("change", toggleFinger);

    // ===================================================
    // ສະແດງ/ປິດ ຈຳນວນນິ້ວຕີນ (ຖ້າເລືອກ ບໍ່ຄົບ)
    // ===================================================
    function toggleToe() {
        $("#toe_count_wrap").toggle($("input[name='toe_sts']:checked").val() === "incomplete");
    }
    toggleToe();
    $("input[name='toe_sts']").on("change", toggleToe);

    // ===================================================
    // ຟັງຊັນບັນທຶກ/ຢືນຢັນ ຂໍ້ມູນດ້ານຮ່າງກາຍ
    // ===================================================
    function savePhysical(action) {
        calcBmi();
        let form = $("#physical_form")[0];
        let formData = new FormData(form);
        formData.append("action", action);

        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_physical.php",
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
    $("#physical_form").on("submit", function (e) {
        e.preventDefault();
        savePhysical("save");
    });

    // ປຸ່ມ Verify (ຢືນຢັນແລ້ວ ຈະລັອກບໍ່ໃຫ້ແກ້ໄຂ)
    $("#physical_verify").on("click", function (e) {
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
                savePhysical("verify");
            }
        });
    });

});
