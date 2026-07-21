$(document).ready(function () {
    $("#save_name").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_name.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#edit_name").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append("sub",'update');
        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_name.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location = '../list_name.php';
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#save_data").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append("sub",'insert');
        if($("#heal_sts").val() == 'Pass'){
            formData.append("diagnose",'(ປົກກະຕິ)')
        }
        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_data_entry.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#edit_data").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append("sub",'update');
        if($("#heal_sts").val() == 'Pass'){
            formData.append("diagnose",'(ປົກກະຕິ)')
        }
        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_data_entry.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location = '../list_data_entry.php';
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#save_vacancy").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append("sub",'insert');
        if($("#heal_sts").val() == 'Pass'){
            formData.append("diagnose",'(ປົກກະຕິ)')
        }
        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_vacancy.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#edit_vacancy").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append("sub",'update');
        if($("#heal_sts").val() == 'Pass'){
            formData.append("diagnose",'(ປົກກະຕິ)')
        }
        $.ajax({
            type: "post",
            url: "../insert/insert_n_update_vacancy.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location = '../list_vacancy.php';
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#btn_emp").on("click", function (e) {
        e.preventDefault();
        let form = $("#save_emp")[0];
        let formData = new FormData(form);
        formData.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_employer.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#e_btn_emp").on("click", function (e) {
        e.preventDefault();
        let form = $("#edit_emp")[0];
        let formData = new FormData(form);
        formData.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_employer.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#btn_prok").on("click", function (e) {
        e.preventDefault();
        let form = $("#save_prok")[0];
        let formData = new FormData(form);
        formData.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_prok.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#e_btn_prok").on("click", function (e) {
        e.preventDefault();
        let form = $("#edit_prok")[0];
        let formData = new FormData(form);
        formData.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_prok.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#btn_disk").on("click", function (e) {
        e.preventDefault();
        let form = $("#save_disk")[0];
        let formData = new FormData(form);
        formData.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_disk.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#e_btn_disk").on("click", function (e) {
        e.preventDefault();
        let form = $("#edit_disk")[0];
        let formData = new FormData(form);
        formData.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_disk.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#btn_agen").on("click", function (e) {
        e.preventDefault();
        let form = $("#save_agen")[0];
        let formData = new FormData(form);
        formData.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_agen.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
    $("#e_btn_agen").on("click", function (e) {
        e.preventDefault();
        let form = $("#edit_agen")[0];
        let formData = new FormData(form);
        formData.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_agen.php",
            data: formData,
            dataType: "json",
            contentType: false, 
            processData: false,
            success: function (response) {
                if(response.sts === 'error'){
                    showToast(response.message, 'error');
                    return;
                }else{
                    showToast(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
                
            },
            error: function (xhr, status, error) {
                showToast('An error occurred: ' + error, 'error');
            }
        });
    });
});