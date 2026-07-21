
    
    $(document).ready(function() {
        
        $("#dob").on("change",function(){
            var birthday = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - birthday.getFullYear();
            $("#age").val(age);
            let yearf = parseFloat(age - 18);
            $("#agricu").val(yearf);
        }) 
        $("#gua_dob").on("change",function(){
            var birthday = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - birthday.getFullYear();
            $("#gua_age").val(age);
        }) 
        $("#pro_id").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_pro.php",
                data: {
                    pro_id: pro_id
                },
                success: function(response) {
                    $("#dis_id").html(response);
                }
            });

        });
        $("#dis_id").change(function(e) {
            let dis_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_dis.php",
                data: {
                    dis_id: dis_id
                },
                success: function(response) {
                    $("#vill_id").html(response);
                }
            });

        });
        
        $("#pro_id_b").change(function(e) {
            let pro_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_pro.php",
                data: {
                    pro_id: pro_id
                },
                success: function(response) {
                    $("#dis_id_b").html(response);
                }
            });

        });
        $("#dis_id_b").change(function(e) {
            let dis_id = $(this).val();
            $.ajax({
                type: "post",
                url: "../get/get_dis.php",
                data: {
                    dis_id: dis_id
                },
                success: function(response) {
                    $("#vill_id_b").html(response);
                }
            });

        });
    });

