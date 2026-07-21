$(document).ready(function () {
    $("#sc_emp").click(function (e) { 
        let formdata = {
            all:$("#s_all").val(),
            prok_id:$("#s_prok_id").val(),
            disk_id:$("#s_disk_id").val(),
        }
        $.ajax({
            type: "post",
            url: "search/search_emp.php",
            data: formdata,
            success: function (response) {
                $("#employerTbody").html(response);
            }
        });
        
    });
    $("#sc_prok").click(function (e) { 
        let formdata = {
            all:$("#s_all").val(),
        }
        $.ajax({
            type: "post",
            url: "search/search_prok.php",
            data: formdata,
            success: function (response) {
                $("#employerTbody").html(response);
            }
        });
        
    });
    $("#sc_disk").click(function (e) { 
        let formdata = {
            all:$("#s_all").val(),
            prok_id:$("#s_prok_id").val(),
        }
        $.ajax({
            type: "post",
            url: "search/search_disk.php",
            data: formdata,
            success: function (response) {
                $("#employerTbody").html(response);
            }
        });
        
    });
    $("#sc_agen").click(function (e) { 
        let formdata = {
            all:$("#s_all").val(),
        }
        $.ajax({
            type: "post",
            url: "search/search_agen.php",
            data: formdata,
            success: function (response) {
                $("#employerTbody").html(response);
            }
        });
        
    });
});