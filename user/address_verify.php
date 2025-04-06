<?php
ob_start();
session_start();
include "../admin/conn.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION["user_username"])) {
    header("Location: login.php");
    exit();
}

$_SESSION["address_verify"] = "yes";

// Khởi tạo biến
$firstname = "";
$lastname = "";
$email = "";
$contact = "";
$address = "";
$error_message = "";
$success_message = "";

// Lấy thông tin user
$stmt = $link->prepare("SELECT firstname, lastname, email, contact, address FROM user_registration WHERE username = ?");
$stmt->bind_param("s", $_SESSION["user_username"]);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $firstname = $row["firstname"];
    $lastname = $row["lastname"];
    $email = $row["email"];
    $contact = $row["contact"];
    $address = $row["address"];
}
$stmt->close();

// Xử lý form verify
if (isset($_POST["verify"])) {
    $firstname = mysqli_real_escape_string($link, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($link, trim($_POST['lastname']));
    $email = mysqli_real_escape_string($link, trim($_POST['email']));
    $contact = mysqli_real_escape_string($link, trim($_POST['contact']));
    $address = mysqli_real_escape_string($link, trim($_POST['address']));

    $stmt = $link->prepare("UPDATE user_registration SET firstname = ?, lastname = ?, email = ?, contact = ?, address = ? WHERE username = ?");
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $contact, $address, $_SESSION["user_username"]);
    if ($stmt->execute()) {
        $success_message = "Address verified successfully!";
        
        $orderno = rand(111111, 999999);
        $_SESSION["orderno"] = $orderno;
        $pay = $_SESSION["sub_total"];
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $url = explode('/', $actual_link);
        array_pop($url);
        $url = implode('/', $url) . "/order_success.php";

        if ($_SESSION["payment_type"] == "cod") {
            header("Location: $url?orderno=$orderno");
            exit();
        } else {
            // Thanh toán MoMo cá nhân (QR Code hoặc Deep Link)
            $momo_phone = "09884418";
            $momo_deeplink = "momo://?action=payWithApp&phone=$momo_phone&amount=$pay";
            $momo_qr_image = "./assets/images/qrpay.jpg"; // Thay bằng đường dẫn tới hình QR bạn đã lưu

            // Chuyển hướng hoặc hiển thị QR (tùy bạn chọn)
            $success_message .= "<br>Vui lòng thanh toán $pay VNĐ qua MoMo:<br>";
            $success_message .= "<a href='$momo_deeplink' target='_blank'>Mở MoMo để thanh toán</a><br>";
            $success_message .= "<img src='$momo_qr_image' alt='MoMo QR Code' style='max-width: 200px;'><br>";
            $success_message .= "Sau khi thanh toán, vui lòng kiểm tra email hoặc liên hệ để xác nhận đơn hàng #$orderno.";
        }
    } else {
        $error_message = "Cập nhật địa chỉ thất bại. Vui lòng thử lại.";
    }
    $stmt->close();
}

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
                        <div class="col-lg-6 col-md-12 col-sm-12"
                            style="border: 1px solid #c62904; border-radius: 5px; padding: 20px;">
                            <div class="sec-title">
                                <h2>Address Verification</h2>
                            </div>
                            <div class="billing-inner">
                                <div class="row clearfix">
                                    <?php if (!empty($error_message)): ?>
                                        <div class="alert alert-danger col-md-12" role="alert">
                                            <center><strong>Lỗi!</strong> <?php echo $error_message; ?></center>
                                        </div>
                                    <?php elseif (!empty($success_message)): ?>
                                        <div class="alert alert-success col-md-12" role="alert">
                                            <center><strong>Thành công!</strong> <?php echo $success_message; ?></center>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">First Name</div>
                                        <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>"
                                            placeholder="First Name" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Last Name</div>
                                        <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>"
                                            placeholder="Last Name" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Email</div>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                                            placeholder="Email" required>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Contact Number</div>
                                        <input type="text" name="contact" value="<?php echo htmlspecialchars($contact); ?>"
                                            placeholder="Contact">
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">Address</div>
                                        <textarea name="address"
                                            placeholder="Address"><?php echo htmlspecialchars($address); ?></textarea>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <button type="submit" class="theme-btn btn-style-five" name="verify"><span
                                                class="txt">Verify</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "./theme/footer.php";
ob_end_flush();
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