<?php
ob_start();
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
include "../conn.php";

include "../theme/header.php";

// Xử lý chuyển trạng thái sang Preparing Order
if (isset($_POST['prepare_order'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = 'Preparing Order';
    
    $stmt = $link->prepare("UPDATE order_main SET order_status = ? WHERE id = ? AND order_status = 'Pending'");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $success_message = "Đơn hàng #$order_id đã chuyển sang trạng thái Preparing Order!";
    } else {
        $error_message = "Không thể chuyển trạng thái. Đơn hàng không ở trạng thái Pending.";
    }
    $stmt->close();
}

// Lấy danh sách đơn hàng
$result = mysqli_query($link, "SELECT id, order_number, order_date, order_time, order_address, order_type, order_status, order_username 
                               FROM order_main ORDER BY id DESC");
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Order Management</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Order Management</li>
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
                    <strong class="card-title">All Orders</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Username</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 0;
                                while ($row = mysqli_fetch_array($result)):
                                    $count++;
                                ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo htmlspecialchars($row['order_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['order_username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['order_time']); ?></td>
                                        <td><?php echo htmlspecialchars($row['order_address']); ?></td>
                                        <td><?php echo htmlspecialchars($row['order_type']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $row['order_status'] == 'Delivered' ? 'badge-success' : ($row['order_status'] == 'Pending' ? 'badge-warning' : ($row['order_status'] == 'Preparing Order' ? 'badge-info' : 'badge-danger')); ?>">
                                                <?php echo htmlspecialchars($row['order_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['order_status'] == 'Pending'): ?>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="prepare_order" class="btn btn-sm btn-warning" 
                                                            onclick="return confirm('Bạn có chắc muốn chuyển đơn hàng này sang Preparing Order?');">
                                                        Prepare Order
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="../order/order_details.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <div class="alert alert-info text-center">
                            Have no order now!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> <!-- .content -->

<?php
include "../theme/footer.php";
ob_end_flush();
?>