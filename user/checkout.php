<?php
// Bắt đầu hoặc tiếp tục một session để lưu trữ thông tin tạm thời của người dùng
session_start();

// Kết nối đến cơ sở dữ liệu bằng cách include file conn.php từ thư mục admin
include "../admin/conn.php";

// Kiểm tra nếu biến session "cart_count" chưa được thiết lập (nghĩa là giỏ hàng chưa tồn tại)
if (!isset($_SESSION["cart_count"])) {
    // Nếu giỏ hàng chưa tồn tại, chuyển hướng người dùng về trang chủ (index.php) bằng JavaScript
    ?>
    <script type="text/javascript">
        window.location="index.php";
    </script>
    <?php
}
// Nếu "cart_count" đã được thiết lập, kiểm tra xem giỏ hàng có rỗng không (cart_count == 0)
else if ($_SESSION["cart_count"] == 0) {
    // Nếu giỏ hàng rỗng, chuyển hướng người dùng về trang chủ (index.php) bằng JavaScript
    ?>
    <script type="text/javascript">
        window.location="index.php";
    </script>
    <?php
}
// Nếu giỏ hàng không rỗng (cart_count > 0), tiếp tục xử lý quy trình thanh toán
else {
    // Đặt biến session "checkout" thành "yes" để đánh dấu rằng người dùng đang trong quá trình thanh toán
    $_SESSION["checkout"] = "yes";
    
    // Đặt biến session "cart_item" thành "yes" để xác nhận có sản phẩm trong giỏ hàng
    $_SESSION["cart_item"] = "yes";
    
    // Kiểm tra nếu người dùng chưa đăng nhập (biến session "user_username" chưa được thiết lập)
    if (!isset($_SESSION["user_username"])) {
        // Nếu chưa đăng nhập, chuyển hướng người dùng đến trang đăng nhập (login.php) bằng JavaScript
        ?>
    <script type="text/javascript">
        window.location="login.php";
    </script>
    <?php
    }
    // Nếu người dùng đã đăng nhập
    else {
        // Chuyển hướng người dùng đến trang xác nhận địa chỉ (address_verify.php) bằng JavaScript
        ?>
    <script type="text/javascript">
        window.location="address_verify.php";
    </script>
    <?php
    }
}