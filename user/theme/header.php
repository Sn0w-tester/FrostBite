<?php
if (!isset($_SESSION)) {
    session_start();
}

// Lấy tên file hiện tại để kiểm tra trang đang active
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- Stylesheets -->
    <link href="./assets/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/vendors/flat-icon/flaticon.css" rel="stylesheet">
    <!-- Rev slider css -->
    <link href="./assets/vendors/revolution/css/settings.css" rel="stylesheet">
    <link href="./assets/vendors/revolution/css/layers.css" rel="stylesheet">
    <link href="./assets/vendors/revolution/css/navigation.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
    <link href="./assets/css/responsive.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/images/logo-02.png" type="image/x-icon">
    <link rel="icon" href="./assets/images/logo-02.png" type="image/x-icon">
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Open+Sans:wght@400;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700&family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body>
    <div class="page-wrapper">
        <!-- Preloader -->
        <div class="preloader"></div>

        <header class="main-header">
            <!--Header Top-->
            <div class="header-top" style="background-color:#f2e39c; color:black">
                <div class="auto-container clearfix">
                    <div class="top-left">
                        <ul class="info-list">
                            <li><a href="mailto:info@abc.co.in" style="color: black"><span
                                        class="icon far fa-envelope"></span>
                                    ndtan2101102@student.ctuet.edu.vn</a></li>
                        </ul>
                    </div>
                    <div class="top-right clearfix">
                        <ul class="social-box">
                            <li><a href="login.php" style="color: black"><span class="fa fa-user-alt"></span></a></li>
                        </ul>
                        <div class="option-list">
                            <div class="cart-btn">
                                <a href="view_cart.php" class="icon flaticon-shopping-cart" style="color: black"><span
                                        class="total-cart"
                                        style="background-color: #a40301;color:white"><?php echo load_cart_data2(); ?></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Header Top -->

            <!-- Header Upper -->
            <div class="header-upper">
                <div class="inner-container">
                    <div class="auto-container clearfix">
                        <div class="logo-outer">
                            <div class="logo" style="margin-top: -20px;"><a href="index.php"><img
                                        src="./assets/images/logo-02.png" alt="" title=""></a></div>
                        </div>
                        <div class="nav-outer clearfix">
                            <nav class="main-menu navbar-expand-md navbar-light">
                                <div class="navbar-header">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="icon flaticon-menu"></span>
                                    </button>
                                </div>
                                <div class="collapse navbar-collapse clearfix" id="navbarSupportedContent">
                                    <ul class="navigation clearfix">
                                        <li class="<?php echo $current_page == 'index.php' ? 'current' : ''; ?>">
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="<?php echo $current_page == 'gallery.php' ? 'current' : ''; ?>">
                                            <a href="gallery.php">Gallery</a>
                                        </li>
                                        <li class="<?php echo $current_page == 'contact.php' ? 'current' : ''; ?>">
                                            <a href="contact.php">Contact</a>
                                        </li>
                                        <li class="<?php echo in_array($current_page, ['login.php', 'registration.php', 'edit_profile.php', 'change_password.php', 'view_my_order.php', 'logout.php']) ? 'current' : ''; ?> dropdown">
                                            <a href="#">User</a>
                                            <ul>
                                                <?php if (!isset($_SESSION["user_username"])): ?>
                                                    <li><a href="login.php">Login</a></li>
                                                    <li><a href="registration.php">Register</a></li>
                                                <?php else: ?>
                                                    <li><a href="edit_profile.php">Edit Profile</a></li>
                                                    <li><a href="change_password.php">Change Password</a></li>
                                                    <li><a href="view_my_order.php">View Order</a></li>
                                                    <li><a href="logout.php">Log Out</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                            <div class="outer-box">
                                <div class="order">
                                    Order Now
                                    <span><a href="tel:1800-123-4567">0988 441 814</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Header Upper-->

            <!--Sticky Header-->
            <div class="sticky-header">
                <div class="auto-container clearfix">
                    <div class="logo pull-left">
                        <a href="index.php" class="img-responsive"><img src="assets/images/logo-02.png" alt=""
                                title="" height="90" width="90" style="margin-top: -10px;"></a>
                    </div>
                    <div class="right-col pull-right">
                        <nav class="main-menu navbar-expand-md">
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent1">
                                <ul class="navigation clearfix">
                                    <li class="<?php echo $current_page == 'index.php' ? 'current' : ''; ?>">
                                        <a href="index.php">Home</a>
                                    </li>
                                    <li class="<?php echo $current_page == 'gallery.php' ? 'current' : ''; ?>">
                                        <a href="gallery.php">Gallery</a>
                                    </li>
                                    <li class="<?php echo $current_page == 'contact.php' ? 'current' : ''; ?>">
                                        <a href="contact.php">Contact</a>
                                    </li>
                                    <li class="<?php echo in_array($current_page, ['login.php', 'registration.php', 'edit_profile.php', 'change_password.php', 'view_my_order.php', 'logout.php']) ? 'current' : ''; ?> dropdown">
                                        <a href="#">User</a>
                                        <ul>
                                            <?php if (!isset($_SESSION["user_username"])): ?>
                                                <li><a href="login.php">Login</a></li>
                                                <li><a href="registration.php">Register</a></li>
                                            <?php else: ?>
                                                <li><a href="edit_profile.php">Edit Profile</a></li>
                                                <li><a href="change_password.php">Change Password</a></li>
                                                <li><a href="view_my_order.php">View Order</a></li>
                                                <li><a href="logout.php">Log Out</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <!--End Sticky Header-->
        </header>
        <!-- End Main Header -->

        <?php
        function load_cart_data2()
        {
            $count = 0;
            $max = 0;
            if (isset($_SESSION['cart'])) {
                $max = sizeof($_SESSION['cart']);
            }
            for ($i = 0; $i < $max; $i++) {
                if (isset($_SESSION['cart'][$i])) {
                    $img1_session = "";
                    foreach ($_SESSION['cart'][$i] as $key => $val) {
                        if ($key == "img1") {
                            $img1_session = $val;
                        }
                    }
                    if ($img1_session != "" && $img1_session != null) {
                        $count = $count + 1;
                    }
                }
            }
            return $count;
        }
        ?>