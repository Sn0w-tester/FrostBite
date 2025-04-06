<?php
include "conn.php";
session_start(); // Bắt đầu session để quản lý trạng thái đăng nhập
?>

<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FrostBite - Admin</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/scss/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body class="bg-dark">
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html" style="font-size: large; color: cyan; font-weight: bold">Admin Zone</a>
                </div>
                <div class="login-form">
                    <form name="form1" action="" method="post">
                        <div class="form-group">
                            <label>Admin Account</label>
                            <input type="text" class="form-control" placeholder="Admin account" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="password" required>
                        </div>
                        <button type="submit" name="submit1" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
                        <div class="alert alert-danger" id="invalidusernameorpassword" role="alert" style="margin-top:20px; display:none">
                            Invalid! Invalid Username or Password
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>

    <?php
    if (isset($_POST["submit1"])) {
        // Kiểm tra và lấy dữ liệu từ form
        $username = isset($_POST['username']) ? mysqli_real_escape_string($link, $_POST['username']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($link, $_POST['password']) : '';

        // Truy vấn dùng prepared statements để bảo mật
        $stmt = $link->prepare("SELECT * FROM admin_login WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password); // "ss" nghĩa là 2 chuỗi
        $stmt->execute();
        $res = $stmt->get_result();
        $count = $res->num_rows;

        if ($count > 0) {
            // Đăng nhập thành công
            $_SESSION['admin'] = $username; // Lưu thông tin admin vào session
            ?>
            <script type="text/javascript">
                window.location = "dashboard"; // Chuyển hướng đến trang dashboard
            </script>
            <?php
        } else {
            // Đăng nhập thất bại
            ?>
            <script type="text/javascript">
                document.getElementById("invalidusernameorpassword").style.display = "block";
                setTimeout(function() {
                    document.getElementById("invalidusernameorpassword").style.display = "none";
                }, 3000);
            </script>
            <?php
        }
        $stmt->close();
    }
    ?>
</body>
</html>