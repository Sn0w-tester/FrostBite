<?php
ob_start(); // Bật output buffering
if (!isset($_SESSION)) {
    session_start();
}
include "../admin/conn.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION["user_username"])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra ID hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_my_order.php");
    exit();
}
$order_id = (int)$_GET['id'];

// Lấy thông tin đơn hàng từ order_main
$stmt = $link->prepare("SELECT order_number, order_date, order_time, order_address, order_type, order_status 
                        FROM order_main WHERE id = ? AND order_username = ?");
$stmt->bind_param("is", $order_id, $_SESSION["user_username"]);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Kiểm tra nếu không tìm thấy đơn hàng
if (!$order) {
    header("Location: view_my_order.php");
    exit();
}

// Lấy chi tiết món ăn từ order_details
$stmt = $link->prepare("SELECT food_name, food_category, food_original_price, food_discount_price, food_qty, food_image 
                        FROM order_details WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$details_result = $stmt->get_result();
$stmt->close();

include "./theme/header.php";
?>

<title>FrostBite - Order Details</title>

<section class="page-title" style="background-image: url(assets/images/background/11.jpg)">
    <div class="auto-container">
        <h1>Order Details</h1>
    </div>
</section>

<!-- Order Details Section -->
<section class="order-details-section" style="padding: 50px 0;">
    <div class="auto-container">
        <h3 class="mb-4">Order #<?php echo htmlspecialchars($order['order_number']); ?></h3>
        
        <!-- Thông tin đơn hàng -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                Order Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                        <p><strong>Order Time:</strong> <?php echo htmlspecialchars($order['order_time']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Order Address:</strong> <?php echo htmlspecialchars($order['order_address']); ?></p>
                        <p><strong>Order Type:</strong> <?php echo htmlspecialchars($order['order_type']); ?></p>
                        <p><strong>Order Status:</strong> 
                            <span class="badge <?php echo $order['order_status'] == 'Completed' ? 'badge-success' : ($order['order_status'] == 'Pending' ? 'badge-warning' : 'badge-danger'); ?>">
                                <?php echo htmlspecialchars($order['order_status']); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chi tiết món ăn -->
        <?php if ($details_result->num_rows == 0): ?>
            <div class="alert alert-info text-center">
                Không tìm thấy chi tiết món ăn cho đơn hàng này.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr style="background-color: #a41a13; color: #ffffff;">
                            <th>#</th>
                            <th>Image</th>
                            <th>Food Name</th>
                            <th>Category</th>
                            <th>Original Price</th>
                            <th>Discount Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $srno = 1;
                        $grand_total = 0;
                        while ($row = $details_result->fetch_assoc()):
                            $total = (float)$row['food_discount_price'] * (int)$row['food_qty'];
                            $grand_total += $total;
                        ?>
                            <tr>
                                <td><?php echo $srno; ?></td>
                                <td>
                                    <img src="../admin/Food/<?php echo htmlspecialchars($row['food_image']); ?>" alt="<?php echo htmlspecialchars($row['food_name']); ?>" style="max-width: 50px; height: auto;">
                                </td>
                                <td><?php echo htmlspecialchars($row['food_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['food_category']); ?></td>
                                <td><?php echo number_format((float)$row['food_original_price'], 0, '.', ','); ?> VNĐ</td>
                                <td><?php echo number_format((float)$row['food_discount_price'], 0, '.', ','); ?> VNĐ</td>
                                <td><?php echo (int)$row['food_qty']; ?></td>
                                <td><?php echo number_format($total, 0, '.', ','); ?> VNĐ</td>
                            </tr>
                        <?php
                            $srno++;
                        endwhile;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-right"><strong>Grand Total:</strong></td>
                            <td><strong><?php echo number_format($grand_total, 0, '.', ','); ?> VNĐ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>

        <a href="view_my_order.php" class="btn btn-secondary mt-3">Back to Orders</a>
    </div>
</section>
<!-- End Order Details Section -->

<?php
include "./pages/delivery.php";
include "./pages/service.php";
include "./theme/footer.php";
ob_end_flush();
?>