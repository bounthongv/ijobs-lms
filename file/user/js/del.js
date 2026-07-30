$(document).ready(function () {
    $(document).on('click', '.del_user', function () {
        var user_id = $(this).data('user_id');
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
                    url: "del/del_user.php",
                    type: "POST",
                    data: { id: user_id },
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