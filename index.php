<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>

 <!--    <link rel="stylesheet" href="style.css"> -->
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap');
    /* การตั้งค่าพื้นฐาน */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Noto Sans Lao", serif;
        /* ใช้ฟอนต์ที่อ่านง่าย */

    }

    body {
        background-color: #f4f7f6;
        /* พื้นหลังสีอ่อน */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
        /* เพิ่ม padding สำหรับมือถือ */
        background-image: url('logo/bg.jpg');
        background-position: center;
        background-size: cover;
        background-attachment: fixed;
    }

    /* ส่วนของฟอร์มเข้าสู่ระบบ */
    .login-container {
        width: 100%;
        max-width: 400px;
        /* จำกัดความกว้างสูงสุดสำหรับหน้าจอใหญ่ */
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* พื้นที่โลโก้ */
    .logo-area {
        text-align: center;
        margin-bottom: 20px;
    }

    .apis-logo {
        display: block;
        margin: 0 auto 10px;
        /* ในการใช้งานจริง แทนที่ SVG ด้วยโค้ดโลโก้ที่ซับซ้อนขึ้น หรือใช้ <img src="apis-logo.png" alt="APIS Logo"> */
    }

    .logo-area h1 {
        font-size: 1.5rem;
        color: #007bff;
        /* สีเดียวกับโลโก้ */
        margin-top: -10px;
    }

    /* .login-form{
    background-color: #ffffff;
} */

    .login-form h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
        font-size: 1.75rem;
    }

    /* กลุ่มช่องกรอกข้อมูล */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        font-size: 0.95rem;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
    }

    /* ปุ่มเข้าสู่ระบบ */
    .login-button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.1s;
        margin-top: 10px;
    }

    .login-button:hover {
        background-color: #0056b3;
    }

    .login-button:active {
        transform: scale(0.99);
    }

    /* ลิงก์เพิ่มเติม (ลืมรหัสผ่าน, ลงทะเบียน) */
    .links-area {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        font-size: 0.9rem;
    }

    .forgot-password-link,
    .register-link {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s;
    }

    .forgot-password-link:hover,
    .register-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* *Media Query สำหรับการตอบสนอง (Responsive)* */
    @media (max-width: 500px) {

        /* สำหรับมือถือจอเล็ก */
        .login-container {
            padding: 20px;
            /* ไม่กำหนด max-width เพื่อให้เต็มจอเล็ก */
            margin: 0 10px;
            box-shadow: none;
            /* อาจจะลบเงาเพื่อให้ดูเป็นธรรมชาติบนมือถือ */
        }

        .login-form h2 {
            font-size: 1.5rem;
        }

        .form-group input,
        .login-button {
            padding: 10px;
            font-size: 1rem;
        }

        .links-area {
            flex-direction: column;
            /* ให้ลิงก์เรียงลงมา */
            align-items: center;
        }

        .forgot-password-link {
            margin-bottom: 10px;
        }
    }

    .apis-logo {
        width: 100px;
        height: auto;
    }
</style>

<body>
    <div class="login-container">
        <div class="login-form">
            <div class="logo-area">
                <!-- <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="apis-logo">
                    <path d="M12 2L2 22H22L12 2ZM12 15L16 7H8L12 15Z" fill="#007BFF"/>
                    <text x="12" y="20" font-size="6" fill="#007BFF" text-anchor="middle">APIS</text>
                </svg> -->
                <img src="logo/apis.JPG" class="apis-logo" alt="">
                <h1>Welcome to Job MANAGEMENT SYSTEM</h1>
            </div>

            <h6 class="text-center">Designed By: APIS Company Limited</h6>

            <form action="#" id="go" method="POST">
                <div class="form-group">
                    <label for="username">ຊື່ຜູ້ໃຊ້</label>
                    <input type="text" id="username" name="username" placeholder="ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້" required>
                </div>

                <div class="form-group">
                    <label for="password">ລະຫັດຜ່ານ</label>
                    <input type="password" id="password" name="password" placeholder="ກະລຸນາປ້ອນລະຫັດຜ່ານ" required>
                </div>

                <button type="submit" class="login-button">ເຂົ້າສູ່ລະບົບ</button>

                <!-- <div class="links-area">
                    <a href="#" class="forgot-password-link">ลืมรหัสผ่าน?</a>
                    <a href="#" class="register-link">ลงทะเบียน</a>
                </div> -->
            </form>
        </div>
    </div>
    <div class="show"></div>
    <script src='https://code.jquery.com/jquery-3.6.1.min.js'></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#go").on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url:'checklog.php',
                type: 'post',
                data: new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success:function(data){
                    $(".show").html(data);
                }
            })
        })
    });
</script>
</body>

</html>