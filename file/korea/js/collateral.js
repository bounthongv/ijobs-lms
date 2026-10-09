$(document).ready(function () {

    // ===================================================
    // ລັອກຟອມ ຖ້າຂໍ້ມູນຖືກຢືນຢັນແລ້ວ (Verify)
    // ===================================================
    if (isLocked) {
        $("#collateral_form :input").prop("disabled", true);
        $(".btn-save, .btn-verify").addClass("d-none");
    }

    // ===================================================
    // ສະແດງ/ປິດ ຊ່ອງລະບຸ ອື່ນໆ
    // ===================================================
    function toggleOtherDetail() {
        let val = $("input[name='coll_item_other']:checked").val();
        $("#other_detail_wrap").toggle(val === "yes");
    }
    toggleOtherDetail();
    $("input[name='coll_item_other']").on("change", toggleOtherDetail);

    // ===================================================
    // ສະແດງ preview ໄຟລ໌ 4 ຊ່ອງ (ຮູບ = ສະແດງຮູບ, PDF = ສະແດງໄຟລ໌)
    // ===================================================
    function setupFilePreview(slot) {
        $("#coll-file-" + slot).on("change", function () {
            const file = this.files[0];
            if (!file) {
                return;
            }

            const isImage = file.type.indexOf("image/") === 0;
            const isPdf = file.type === "application/pdf" || /\.pdf$/i.test(file.name);
            const reader = new FileReader();

            reader.onload = function (e) {
                let html = "";
                if (isImage) {
                    html = '<img src="' + e.target.result + '" class="preview-img mb-2">' +
                        '<p class="text-success small mb-0"><i class="bi bi-check-circle-fill"></i> ເລືອກຮູບແລ້ວ (ຄລິກເພື່ອປ່ຽນ)</p>';
                } else if (isPdf) {
                    html = '<div class="pdf-preview">' +
                        '<i class="bi bi-file-earmark-pdf"></i>' +
                        '<a href="' + e.target.result + '" target="_blank">' + file.name + '</a>' +
                        '<p class="text-success small mb-0 mt-1"><i class="bi bi-check-circle-fill"></i> ເລືອກໄຟລ໌ແລ້ວ</p>' +
                        '</div>';
                } else {
                    html = '<div class="pdf-preview"><i class="bi bi-file-earmark"></i><span>' + file.name + '</span></div>';
                }

                $("#pv-" + slot).html(html).removeClass("d-none");
                $("#ph-" + slot).addClass("d-none");
            };

            reader.readAsDataURL(file);
        });
    }

    setupFilePreview("1");
    setupFilePreview("2");
    setupFilePreview("3");
    setupFilePreview("4");

    // ===================================================
    // ລົບໄຟລ໌ເອກະສານ (ສະເພາະ Admin)
    // ===================================================
    $(document).on("click", ".btn-del-file", function (e) {
        e.preventDefault();
        e.stopPropagation();

        let slot = $(this).data("slot");
        let cid = $("#collateral_form input[name='cid']").val();

        Swal.fire({
            title: "ລົບໄຟລ໌ ແທ້ ຫຼື ບໍ່?",
            text: "ໄຟລ໌ຈະຖືກລົບອອກຈາກລະບົບຢ່າງຖາວອນ",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "ລົບ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            let formData = new FormData();
            formData.append("cid", cid);
            formData.append("action", "delete_file");
            formData.append("slot", slot);

            $.ajax({
                type: "post",
                url: "../insert/insert_n_update_collateral.php",
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
                        location.reload();
                    }, 1200);
                },
                error: function (xhr, status, error) {
                    showToast('An error occurred: ' + error, 'error');
                }
            });
        });
    });

    // ===================================================
    // ຟັງຊັນບັນທຶກ/ຢືນຢັນ ສຳພາດຫຼັກຊັບ
    // ===================================================
    function saveCollateral(action) {
        let form = $("#collateral_form")[0];
        let formData = new FormData(form);
        formData.append("action", action);

        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_collateral.php",
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
    $("#collateral_form").on("submit", function (e) {
        e.preventDefault();
        saveCollateral("save");
    });

    // ປຸ່ມ Verify (ຢືນຢັນແລ້ວ ຈະລັອກບໍ່ໃຫ້ແກ້ໄຂ)
    $("#collateral_verify").on("click", function (e) {
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
                saveCollateral("verify");
            }
        });
    });

});
