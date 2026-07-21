$(document).ready(function () {
    $("#btn_pro").click(function (e) { 
        e.preventDefault();
        let form = $("#save_pro")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_pro.php",
            data: formdata,
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
            }
        });
        
    });
    $("#e_btn_pro").click(function (e) { 
        e.preventDefault();
        let form = $("#edit_pro")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_pro.php",
            data: formdata,
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
            }
        });
        
    });
    $("#btn_dis").click(function (e) { 
        e.preventDefault();
        let form = $("#save_dis")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_dis.php",
            data: formdata,
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
            }
        });
        
    });
    $("#e_btn_dis").click(function (e) { 
        e.preventDefault();
        let form = $("#edit_dis")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_dis.php",
            data: formdata,
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
            }
        });
        
    });
    $("#btn_vill").click(function (e) { 
        e.preventDefault();
        let form = $("#save_vill")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_vill.php",
            data: formdata,
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
            }
        });
        
    });
    $("#e_btn_vill").click(function (e) { 
        e.preventDefault();
        let form = $("#edit_vill")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_vill.php",
            data: formdata,
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
            }
        });
        
    });
});