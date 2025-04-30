<?php
ob_start();
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include "../conn.php";
include "../theme/header.php";

// Xử lý chuyển trạng thái
if (isset($_POST['set_delivered'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = 'Delivered';
    
    $stmt = $link->prepare("UPDATE order_main SET order_status = ? WHERE id = ? AND order_status = 'Preparing Order'");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $success_message = "#$order_id has change status to Delivered!";
    } else {
        $error_message = "The order can't be change the status.";
    }
    $stmt->close();
}

if (isset($_POST['set_delivery_failed'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = 'Delivery Failed';
    
    $stmt = $link->prepare("UPDATE order_main SET order_status = ? WHERE id = ? AND order_status = 'Preparing Order'");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $success_message = "#$order_id has change status to Delivery Failed!";
    } else {
        $error_message = "The order can't be change the status.";
    }
    $stmt->close();
}

// Xử lý tìm kiếm
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($link, trim($_POST['search_query']));
}

// Lấy danh sách đơn hàng
$sql = "SELECT id, order_number, order_date, order_time, order_address, order_type, order_status, order_username 
        FROM order_main 
        WHERE order_status IN ('Preparing Order', 'Delivered','Delivery Failed')";
if (!empty($search_query)) {
    $sql .= " AND (order_number LIKE ? OR order_username LIKE ?)";
}
$stmt = $link->prepare($sql);
if (!empty($search_query)) {
    $search_param = "%$search_query%";
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Delivery Management</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Delivery Management</li>
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

    <!-- Form tìm kiếm -->
    <div class="row mb-3">
        <div class="col-lg-12">
            <form method="post" class="form-inline">
                <div class="form-group w-100">
                    <input type="text" name="search_query" class="form-control w-75" placeholder="Search by Order Number or Username" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" name="search" class="btn btn-primary ml-2">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Delivery Orders</strong>
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
                                while ($row = $result->fetch_assoc()):
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
                                            <span class="badge <?php echo $row['order_status'] == 'Delivered' ? 'badge-success' : 'badge-info'; ?>">
                                                <?php echo htmlspecialchars($row['order_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['order_status'] == 'Preparing Order'): ?>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="set_delivered" class="btn btn-sm btn-success mb-1" 
                                                            onclick="return confirm('You has done your Delivery?');">
                                                        Delivered
                                                    </button>
                                                </form>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="set_delivery_failed" class="btn btn-sm btn-danger mb-1" 
                                                            onclick="return confirm('Sooo, the Order has been bomb?');">
                                                        Delivery Failed
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="order_details.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($result->num_rows == 0): ?>
                        <div class="alert alert-info text-center">
                            Notthing order need to Delivery.
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