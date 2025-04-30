<?php
ob_start();
session_start();
include "../conn.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Kiểm tra ID hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: order_manager.php");
    exit();
}
$order_id = (int)$_GET['id'];

// Xử lý cập nhật trạng thái
if (isset($_POST['update_status'])) {
    $new_status = mysqli_real_escape_string($link, $_POST['order_status']);
    $stmt = $link->prepare("UPDATE order_main SET order_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        $success_message = "Cập nhật trạng thái đơn hàng #$order_id thành công!";
    } else {
        $error_message = "Cập nhật trạng thái thất bại.";
    }
    $stmt->close();
}

// Lấy thông tin đơn hàng từ order_main
$stmt = $link->prepare("SELECT order_number, order_username, order_date, order_time, order_address, order_type, order_status 
                        FROM order_main WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Kiểm tra nếu không tìm thấy đơn hàng
if (!$order) {
    header("Location: order_manager.php");
    exit();
}

// Lấy chi tiết món ăn từ order_details
$stmt = $link->prepare("SELECT food_name, food_category, food_original_price, food_discount_price, food_qty, food_image 
                        FROM order_details WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$details_result = $stmt->get_result();
$stmt->close();

include "../theme/header.php";
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Order Details</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="order_manager.php">Order Management</a></li>
                    <li class="active">Order Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content mt-3">
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Order #<?php echo htmlspecialchars($order['order_number']); ?></strong>
                </div>
                <div class="card-body">
                    <!-- Thông tin đơn hàng -->
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            Order Information
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                                    <p><strong>Username:</strong> <?php echo htmlspecialchars($order['order_username']); ?></p>
                                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                                    <p><strong>Order Time:</strong> <?php echo htmlspecialchars($order['order_time']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Order Address:</strong> <?php echo htmlspecialchars($order['order_address']); ?></p>
                                    <p><strong>Order Type:</strong> <?php echo htmlspecialchars($order['order_type']); ?></p>
                                    <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết món ăn -->
                    <?php if ($details_result->num_rows == 0): ?>
                        <div class="alert alert-info text-center">
                            something wrong hereeeeee?!!!!
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
                                                <img src="../Food/<?php echo htmlspecialchars($row['food_image']); ?>" alt="<?php echo htmlspecialchars($row['food_name']); ?>" style="max-width: 50px; height: auto;">
                                            </td>
                                            <td><?php echo htmlspecialchars($row['food_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['food_category']); ?></td>
                                            <td><?php echo number_format((float)$row['food_original_price'], 0, '.', ','); ?> USD</td>
                                            <td><?php echo number_format((float)$row['food_discount_price'], 0, '.', ','); ?> USD</td>
                                            <td><?php echo (int)$row['food_qty']; ?></td>
                                            <td><?php echo number_format($total, 0, '.', ','); ?> USD</td>
                                        </tr>
                                    <?php
                                        $srno++;
                                    endwhile;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-right"><strong>Grand Total:</strong></td>
                                        <td><strong><?php echo number_format($grand_total, 0, '.', ','); ?> USD</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>

                    <a href="index.php" class="btn btn-secondary mt-3">Back to Orders</a>
                </div>
            </div>
        </div>
    </div>
</div> <!-- .content -->

<?php
include "../theme/footer.php";
ob_end_flush();
?>