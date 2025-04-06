<?php
ob_start(); // Bật output buffering
session_start();
include "../admin/conn.php"; // Di chuyển conn.php lên đầu để dùng trước

// Xử lý đăng nhập trước khi xuất HTML
$error_message = '';
if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($link, trim($_POST['username']));
    $password = trim($_POST['password']);

    $stmt = $link->prepare("SELECT firstname, lastname, password FROM user_registration WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        if (password_verify($password, $stored_password)) {
            $_SESSION["user_username"] = $username;
            $user_fullname = $row["firstname"] . ' ' . $row["lastname"];
            if (isset($_SESSION["checkout"])) {
                header("Location: checkout.php");
                exit();
            } else {
                header("Location: view_my_order.php");
                exit();
            }
        } else {
            $error_message = "Password is incorrect.";
        }
    } else {
        $error_message = "Username does not exist.";
    }
    $stmt->close();
}

include "./theme/header.php"; // Include header sau khi xử lý logic
?>

<!-- Checkout Page -->
<div class="checkout-page">
    <div class="auto-container">
        <div class="billing-details">
            <div class="shop-form">
                <form method="post" action="" name="form1">
                    <div class="row clearfix">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6 col-md-12 col-sm-12" style="border: 1px solid #c62904; border-radius: 5px; padding: 20px;">
                            <div class="sec-title">
                                <h2>Login Page</h2>
                            </div>
                            <div class="billing-inner">
                                <div class="row clearfix">
                                    <?php if (!empty($error_message)): ?>
                                        <div class="alert alert-danger col-md-12" id="errmsg" role="alert">
                                            <center><strong>Invalid!</strong> <?php echo $error_message; ?></center>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Username</div>
                                        <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="User Name" required>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Password</div>
                                        <input type="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <button type="submit" class="theme-btn btn-style-five" name="login"><span class="txt">Login</span></button>
                                    </div>
                                </div>
                            </div>
                            <ul class="default-links">
                                <li>New User? <a href="registration.php">Click here to Register</a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "./theme/footer.php";
ob_end_flush(); // Kết thúc output buffering và gửi nội dung
?>

<script src="assets/js/jquery.js"></script>
<script src="assets/js/parallax.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/owl.js"></script>
<script src="assets/js/wow.js"></script>
<script src="assets/js/jquery.bootstrap-touchspin.js"></script>
<script src="assets/js/appear.js"></script>
<script src="assets/js/script.js"></script>