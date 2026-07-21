<?php 
    session_start();
    require_once 'connect.php';
    require_once 'loading.php';
    $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $sql->execute([$username]);
        if($sql->rowCount() > 0){
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $password_hash = $row['password'];
            // if($row['u_sts'] == 1){
            //     echo '<script>
            //     swal("ຜິດຜາດ!!", "ບັນຊີຂອງທ່ານຖືກລະງັບການໃຊ້ງານ", "error");
            //     </script>';
            // }else
            if(password_verify($password,$password_hash)){
                $_SESSION['checked'] = 1;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['status'] = $row['status'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['lname'] = $row['lname'];
                // เช็ค redirect ที่เก็บไว้
                if (!empty($_SESSION['redirect_after_login'])) {
                    $redirectTo = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                } else {
                    $redirectTo = 'file/';
                }

                // ✅ ใช้ echo script แทน header() เพราะเรียกผ่าน AJAX
                echo "<script>location='" . $redirectTo . "'</script>";
                exit(); // ควรใส่กันโค้ดรันต่อโดยไม่ตั้งใจ
                // $_SESSION['menu_id'] = $row['menu_id'];
                // echo"
                // <script>
                // $('#show_loading').modal('show');
                // window.setTimeout(function(){
                //     location='file/';
                // },2000);
                // </script>
                // ";
            }else{
                echo '<script>
                swal("ຜິດຜາດ!!", "ຊື່ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ", "error");
                </script>';
            }
        }else{
            echo '<script>
            swal("ຜິດຜາດ!!", "ຊື່ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ", "error");
            </script>';
        }
?>
