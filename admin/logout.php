<?php
session_start();

// Kiểm tra nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Hủy toàn bộ session
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: index.php");
exit();
?>