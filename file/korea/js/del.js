$(document).ready(function () {
    $(document).on('click', '.del_name', function () {
        var data_id = $(this).data('data_id');
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
                    url: "del/del_name.php",
                    type: "POST",
                    data: { data_id: data_id },
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
    $(document).on('click', '.del_data', function () {
        var id = $(this).data('id');
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
                    url: "del/del_data_entry.php",
                    type: "POST",
                    data: { id: id },
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
    $(document).on('click', '.del_vacancy', function () {
        var id = $(this).data('id');
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
                    url: "del/del_vacancy.php",
                    type: "POST",
                    data: { id: id },
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
    $(document).on('click', '.del_emp', function () {
        var id = $(this).data('emp_id');
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
                    url: "del/del_emp.php",
                    type: "POST",
                    data: { id: id },
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
    $(document).on('click', '.del_prok', function () {
        var id = $(this).data('prok_id');
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
                    url: "del/del_prok.php",
                    type: "POST",
                    data: { id: id },
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
    $(document).on('click', '.del_disk', function () {
        var id = $(this).data('disk_id');
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
                    url: "del/del_disk.php",
                    type: "POST",
                    data: { id: id },
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
    $(document).on('click', '.del_agen', function () {
        var id = $(this).data('agen_id');
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
                    url: "del/del_agen.php",
                    type: "POST",
                    data: { id: id },
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