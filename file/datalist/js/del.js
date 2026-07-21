$(document).ready(function () {
    $(document).on('click', '.del_pro', function () {
        var pro_id = $(this).data('pro_id');
        Swal.fire({
            title: "ຢືນຢັນການລົບ",
            text: "ທ່ານຕ້ອງການລົບຂໍ້ມູນນີ້ແທ້ຫຼື ບໍ່?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ຕົກລົງ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "del/del_pro.php",
                    type: "POST",
                    data: { id: pro_id },
                    success: function (response) {
                        if (response === "success") {
                            location.reload();
                        } else {
                            Swal.fire(
                                "ຜິດພາດ!",
                                "ເກີດຂໍ້ຜິດພາດໃນການລົບ.",
                                "error"
                            );
                        }
                    }
                });
            }
        });
    });
    $(document).on('click', '.del_dis', function () {
        var dis_id = $(this).data('dis_id');
        Swal.fire({
            title: "ຢືນຢັນການລົບ",
            text: "ທ່ານຕ້ອງການລົບຂໍ້ມູນນີ້ແທ້ຫຼື ບໍ່?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ຕົກລົງ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "del/del_dis.php",
                    type: "POST",
                    data: { id: dis_id },
                    success: function (response) {
                        if (response === "success") {
                            location.reload();
                        } else {
                            Swal.fire(
                                "ຜິດພາດ!",
                                "ເກີດຂໍ້ຜິດພາດໃນການລົບ.",
                                "error"
                            );
                        }
                    }
                });
            }
        });
    });
    $(document).on('click', '.del_vill', function () {
        var vill_id = $(this).data('vill_id');
        Swal.fire({
            title: "ຢືນຢັນການລົບ",
            text: "ທ່ານຕ້ອງການລົບຂໍ້ມູນນີ້ແທ້ຫຼື ບໍ່?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ຕົກລົງ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "del/del_vill.php",
                    type: "POST",
                    data: { id: vill_id },
                    success: function (response) {
                        if (response === "success") {
                            location.reload();
                        } else {
                            Swal.fire(
                                "ຜິດພາດ!",
                                "ເກີດຂໍ້ຜິດພາດໃນການລົບ.",
                                "error"
                            );
                        }
                    }
                });
            }
        });
    });
});