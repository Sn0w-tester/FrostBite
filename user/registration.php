<?php
include "../admin/conn.php";

// Xử lý đăng ký trước khi xuất bất kỳ HTML nào
if (isset($_POST["register"])) {
    // Làm sạch dữ liệu đầu vào
    $firstname = mysqli_real_escape_string($link, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($link, trim($_POST['lastname']));
    $username = mysqli_real_escape_string($link, trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $email = mysqli_real_escape_string($link, trim($_POST['email']));
    $contact = mysqli_real_escape_string($link, trim($_POST['contact']));
    $address = mysqli_real_escape_string($link, trim($_POST['address']));

    // Kiểm tra username đã tồn tại chưa
    $stmt = $link->prepare("SELECT * FROM user_registration WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "This Username already exists.";
    } else {
        // Thêm người dùng mới
        $stmt = $link->prepare("INSERT INTO user_registration (firstname, lastname, username, password, email, contact, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstname, $lastname, $username, $password, $email, $contact, $address);
        
        if ($stmt->execute()) {
            $success = "Account Registration Successfully";
            echo '<script>setTimeout(function() { window.location = "login.php"; }, 2000);</script>';
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
            $error = "Registration failed. Please try again.";
        }
    }
    $stmt->close();
}

// Nếu không có đăng ký thành công, hiển thị form như bình thường
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
                                <h2>Registration Page</h2>
                            </div>
                            <div class="billing-inner">
                                <div class="row clearfix">
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger col-md-12" role="alert">
                                            <center><strong>Invalid!</strong> <?php echo $error; ?></center>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">First Name</div>
                                        <input type="text" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>" placeholder="First Name" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Last Name</div>
                                        <input type="text" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>" placeholder="Last Name" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Username</div>
                                        <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="Username" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Password</div>
                                        <input type="password" name="password" placeholder="Password" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Email</div>
                                        <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="Email" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Contact Number</div>
                                        <input type="text" name="contact" value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>" placeholder="Contact">
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Address</div>
                                        <textarea name="address" placeholder="Address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <button type="submit" class="theme-btn btn-style-five" name="register"><span class="txt">Register Now</span></button>
                                    </div>
                                </div>
                            </div>
                            <ul class="default-links">
                                <li>Existing User? <a href="login.php">Click here to Login</a></li>
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