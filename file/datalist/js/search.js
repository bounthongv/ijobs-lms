$(document).ready(function () {
    $("#search").click(function (e) { 
        let formData = {
            all:$("#all").val(),
            pro_id:$("#s_pro_id").val(),
        }
        $.ajax({
            type: "post",
            url: "search/search_dis.php",
            data: formData,
            success: function (response) {
                $("#showTbody").html(response);
            }
        });
        
    });
});