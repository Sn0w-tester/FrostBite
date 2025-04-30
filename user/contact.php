<?php
session_start();
include "../admin/conn.php";

// Xử lý form liên hệ
if (isset($_POST['submit'])) {
    // Làm sạch dữ liệu
    $username = mysqli_real_escape_string($link, trim($_POST['username']));
    $email = mysqli_real_escape_string($link, trim($_POST['email']));
    $subject = mysqli_real_escape_string($link, trim($_POST['subject']));
    $message = mysqli_real_escape_string($link, trim($_POST['message']));

    // Xác thực dữ liệu
    if (empty($username) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Lưu vào bảng contact_messages
        $stmt = $link->prepare("INSERT INTO contact_messages (username, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $subject, $message);

        if ($stmt->execute()) {
            $success = "Your message has been sent successfully.";
        } else {
            $error = "Failed to send message. Please try again.";
        }
        $stmt->close();
    }
}

include "./theme/header.php";
?>

<!-- Contact Page Title -->
<section class="contact-page-title" style="background-image: url(assets/images/background/17.jpg)">
    <div class="auto-container">
        <h1>We are available <br> in your city with tasty food</h1>
    </div>
</section>
<!-- End Contact Page Title -->

<!-- Contact Page Section -->
<section class="contact-page-section">
    <div class="auto-container">
        <div class="row clearfix">
            <!-- Form Column -->
            <div class="form-column col-lg-8 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <h3>We Love To Hear From You</h3>
                        <div class="text">If it's not too much trouble informed us regarding whether you have an inquiry, need to leave a remark, or might want additional data about us</div>
                    </div>

                    <!-- Contact Form -->
                    <div class="contact-form">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success col-md-12" role="alert">
                                <center><strong>Success!</strong> <?php echo $success; ?></center>
                            </div>
                        <?php elseif (isset($error)): ?>
                            <div class="alert alert-danger col-md-12" role="alert">
                                <center><strong>Error!</strong> <?php echo $error; ?></center>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="" id="contact-form">
                            <div class="row clearfix">
                                <div class="form-group col-lg-6 col-md-12 col-sm-12">
                                    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="Name" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-12 col-sm-12">
                                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="Email" required>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <input type="text" name="subject" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" placeholder="Title" required>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <textarea name="message" placeholder="Text"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <button type="submit" name="submit" class="theme-btn btn-style-five"><span class="txt">Sent It</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Column -->
			<div class="info-column col-lg-4 col-md-12 col-sm-12">
                <div class="inner-column">
                    <h3>Our Office Address</h3>
                    <ul>
                        <li><strong>Main Restaurant:</strong>256, Nguyen Van Cu, An Khanh, Ninh Kieu, Can Tho, Viet Nam</li>
                        <li><strong>Branch, Raurance Road:</strong>698, Vo Van Kiet, Long Hoa, Binh Thuy, Can Tho</li>
                        <li><strong>Have any query:</strong>Call us on : 0988 441 814</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Page Section -->

<!-- Map Section -->
<section class="map-section">
    <!-- Map Boxed -->
    <div class="map-boxed">
        <!-- Map Outer -->
        <div class="map-outer">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.841518408529!2d105.74469861475764!3d10.029933992830614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0882139720a77%3A0x2c2e0b0d54f3a029!2sCan%20Tho%20University%20of%20Technology!5e0!3m2!1sen!2s!4v1698765432109!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>
<!-- End Map Section -->

<?php
include "./theme/footer.php";
?>

<!-- Scroll to top -->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>

<!-- Search Popup -->
<div id="search-popup" class="search-popup">
    <div class="close-search theme-btn"><span class="fas fa-window-close"></span></div>
    <div class="popup-inner">
        <div class="overlay-layer"></div>
        <div class="search-form">
            <form method="post" action="index.php">
                <div class="form-group">
                    <fieldset>
                        <input type="search" class="form-control" name="search-input" value="" placeholder="Tìm kiếm tại đây" required>
                        <input type="submit" value="Tìm kiếm ngay!" class="theme-btn">
                    </fieldset>
                </div>
            </form>
            <br>
            <h3>Từ khóa tìm kiếm gần đây</h3>
            <ul class="recent-searches">
                <li><a href="#">Bánh ngọt</a></li>
                <li><a href="#">Socola</a></li>
                <li><a href="#">Dâu tây</a></li>
                <li><a href="#">Vanila</a></li>
                <li><a href="#">Kem xoài</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/owl.js"></script>
<script src="assets/js/wow.js"></script>
<script src="assets/js/validate.js"></script>
<script src="assets/js/appear.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>