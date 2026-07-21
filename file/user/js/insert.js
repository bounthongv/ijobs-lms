$(document).ready(function () {
    $("#btn_user").click(function (e) { 
        e.preventDefault();
        let form = $("#save_user")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'insert');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_user.php",
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
    $("#e_btn_user").click(function (e) { 
        e.preventDefault();
        let form = $("#edit_user")[0];
        let formdata = new FormData(form);
        formdata.append("sub",'update');
        $.ajax({
            type: "post",
            url: "insert/insert_n_update_user.php",
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