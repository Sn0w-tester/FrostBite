<?php
ob_start();
if (!isset($_SESSION)) {
    session_start();
}
include "../admin/conn.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION["user_username"])) {
    header("Location: login.php");
    exit();
}

// Xử lý hủy đơn hàng
$cancel_message = '';
if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $order_id = (int) $_GET['id'];
    // Kiểm tra đơn hàng tồn tại và ở trạng thái Pending
    $stmt = $link->prepare("SELECT order_status FROM order_main WHERE id = ? AND order_username = ?");
    $stmt->bind_param("is", $order_id, $_SESSION["user_username"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0 && $result->fetch_assoc()['order_status'] == 'Pending') {
        // Cập nhật trạng thái thành Cancelled
        $stmt = $link->prepare("UPDATE order_main SET order_status = 'Cancelled' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            $cancel_message = '<div class="alert alert-success text-center">The order has been cancel!</div>';
        } else {
            $cancel_message = '<div class="alert alert-danger text-center">Error.</div>';
        }
    } else {
        $cancel_message = '<div class="alert alert-danger text-center">Can not cancel this order.</div>';
    }
    $stmt->close();
}

include "./theme/header.php";

// Phân trang
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy tổng số đơn hàng
$stmt = $link->prepare("SELECT COUNT(*) as total FROM order_main WHERE order_username = ?");
$stmt->bind_param("s", $_SESSION["user_username"]);
$stmt->execute();
$total_orders = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
$total_pages = ceil($total_orders / $limit);

// Lấy danh sách đơn hàng
$stmt = $link->prepare("SELECT id, order_number, order_date, order_time, order_address, order_type, order_status 
                        FROM order_main WHERE order_username = ? ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bind_param("sii", $_SESSION["user_username"], $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<title>FrostBite - Your Orders</title>

<section class="page-title" style="background-image: url(assets/images/background/11.jpg)">
    <div class="auto-container">
        <h1>Your Orders</h1>
    </div>
</section>

<!-- Orders Section -->
<section class="orders-section" style="padding: 50px 0;">
    <div class="auto-container">
        <?php if (!empty($cancel_message)): ?>
            <?php echo $cancel_message; ?>
        <?php endif; ?>
        <?php if ($total_orders == 0): ?>
            <div class="alert alert-info text-center">
                Notthing here? <a href="index.php">Buy it nowwwwww!</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr style="background-color: #a41a13; color: #ffffff;">
                            <th>#</th>
                            <th>Order Number</th>
                            <th>Order Date</th>
                            <th>Order Time</th>
                            <th>Order Address</th>
                            <th>Order Type</th>
                            <th>Order Status</th>
                            <th>Details</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $srno = $total_orders - ($page - 1) * $limit;
                        while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $srno; ?></td>
                                <td><?php echo htmlspecialchars($row["order_number"]); ?></td>
                                <td><?php echo htmlspecialchars($row["order_date"]); ?></td>
                                <td><?php echo htmlspecialchars($row["order_time"]); ?></td>
                                <td><?php echo htmlspecialchars($row["order_address"]); ?></td>
                                <td><?php echo htmlspecialchars($row["order_type"]); ?></td>
                                <td>
                                    <span
                                        class="badge <?php echo $row['order_status'] == 'Delivered' ? 'badge-success' : ($row['order_status'] == 'Pending' ? 'badge-warning' : ($row['order_status'] == 'Preparing Order' ? 'badge-info' : 'badge-danger')); ?>">
                                        <?php echo htmlspecialchars($row['order_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="order_details.php?id=<?php echo (int) $row["id"]; ?>"
                                        class="btn btn-sm btn-primary">Check</a>
                                </td>
                                <td>
                                    <?php if ($row["order_status"] == 'Pending'): ?>
                                        <a href="?action=cancel&id=<?php echo (int) $row["id"]; ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('You really want to cancel this order :((((?');">Cancel</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                            $srno--;
                        endwhile;
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<!-- End Orders Section -->

<?php
include "./pages/delivery.php";
include "./pages/service.php";
include "./theme/footer.php";
ob_end_flush();
?>