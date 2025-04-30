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
$stmt = $link->prepare("SELECT * FROM user_registration WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $current_firstname = $row['firstname'];
    $current_lastname = $row['lastname'];
    $current_username = $row['username'];
    $current_email = $row['email'];
    $current_contact = $row['contact'];
    $current_address = $row['address'];
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

// Xử lý cập nhật thông tin
if (isset($_POST["update"])) {
    // Làm sạch dữ liệu đầu vào
    $firstname = mysqli_real_escape_string($link, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($link, trim($_POST['lastname']));
    $new_username = mysqli_real_escape_string($link, trim($_POST['username']));
    $email = mysqli_real_escape_string($link, trim($_POST['email']));
    $contact = mysqli_real_escape_string($link, trim($_POST['contact']));
    $address = mysqli_real_escape_string($link, trim($_POST['address']));

    // Kiểm tra username trùng lặp (trừ username hiện tại)
    $stmt = $link->prepare("SELECT * FROM user_registration WHERE username = ? AND username != ?");
    $stmt->bind_param("ss", $new_username, $current_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "This Username already exists.";
    } else {
        // Cập nhật thông tin
        $stmt = $link->prepare("UPDATE user_registration SET firstname = ?, lastname = ?, username = ?, email = ?, contact = ?, address = ? WHERE username = ?");
        $stmt->bind_param("sssssss", $firstname, $lastname, $new_username, $email, $contact, $address, $current_username);
        
        if ($stmt->execute()) {
            // Cập nhật session nếu username thay đổi
            if ($new_username !== $current_username) {
                $_SESSION['username'] = $new_username;
            }
            $success = "Profile Updated Successfully";
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
            $error = "Update failed. Please try again.";
        }
    }
    $stmt->close();
}

// Hiển thị form chỉnh sửa
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
                                <h2>Edit Profile</h2>
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
                                        <input type="text" name="firstname" value="<?php echo htmlspecialchars($current_firstname); ?>" placeholder="First Name" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Last Name</div>
                                        <input type="text" name="lastname" value="<?php echo htmlspecialchars($current_lastname); ?>" placeholder="Last Name" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Username</div>
                                        <input type="text" name="username" value="<?php echo htmlspecialchars($current_username); ?>" placeholder="Username" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Email</div>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($current_email); ?>" placeholder="Email" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Contact Number</div>
                                        <input type="text" name="contact" value="<?php echo htmlspecialchars($current_contact); ?>" placeholder="Contact">
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Address</div>
                                        <textarea name="address" placeholder="Address"><?php echo htmlspecialchars($current_address); ?></textarea>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <button type="submit" class="theme-btn btn-style-five" name="update"><span class="txt">Update Profile</span></button>
                                    </div>
                                </div>
                            </div>
                            <ul class="default-links">
                                <li><a href="change_password.php">Change Password</a></li>
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