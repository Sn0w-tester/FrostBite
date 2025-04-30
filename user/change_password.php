<?php
session_start();
include "../admin/conn.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_username'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng
$username = $_SESSION['user_username'];
$stmt = $link->prepare("SELECT password FROM user_registration WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $current_password_hashed = $row['password'];
} else {
    $error = "User not found.";
    $stmt->close();
    include "./theme/header.php";
    ?>
    <div class="checkout-page">
        <div class="auto-container">
            <div class="billing-details">
                <div class="shop-form">
                    <div class="alert alert-danger col-md-12" role="alert">
                        <center><strong>Error!</strong> <?php echo $error; ?></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include "./theme/footer.php";
    exit();
}
$stmt->close();

// Xử lý đổi mật khẩu
if (isset($_POST["change_password"])) {
    // Lấy dữ liệu
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra dữ liệu
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!password_verify($current_password, $current_password_hashed)) {
        $error = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirmation do not match.";
    } elseif (strlen($new_password) < 8) {
        $error = "New password must be at least 8 characters long.";
    } else {
        // Mã hóa mật khẩu mới
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        // Cập nhật mật khẩu
        $stmt = $link->prepare("UPDATE user_registration SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password_hashed, $username);
        
        if ($stmt->execute()) {
            $success = "Password Changed Successfully";
            include "./theme/header.php";
            ?>
            <div class="checkout-page">
                <div class="auto-container">
                    <div class="billing-details">
                        <div class="shop-form">
                            <div class="alert alert-success col-md-12" role="alert">
                                <center><strong>Success!</strong> <?php echo $success; ?></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            include "./theme/footer.php";
            exit();
        } else {
            $error = "Failed to change password. Please try again.";
        }
        $stmt->close();
    }
}

// Hiển thị form đổi mật khẩu
include "./theme/header.php";
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
                                <h2>Change Password</h2>
                            </div>
                            <div class="billing-inner">
                                <div class="row clearfix">
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger col-md-12" role="alert">
                                            <center><strong>Invalid!</strong> <?php echo $error; ?></center>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Current Password</div>
                                        <input type="password" name="current_password" placeholder="Current Password" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">New Password</div>
                                        <input type="password" name="new_password" placeholder="New Password" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Confirm New Password</div>
                                        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <button type="submit" class="theme-btn btn-style-five" name="change_password"><span class="txt">Change Password</span></button>
                                    </div>
                                </div>
                            </div>
                            <ul class="default-links">
                                <li><a href="edit_profile.php">Back to Edit Profile</a></li>
                                <li><a href="index.php">Back to Home</a></li>
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
?>
<!--Scroll to top-->
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