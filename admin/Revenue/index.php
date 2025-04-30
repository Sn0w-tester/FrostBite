<?php
ob_start();
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include "../conn.php";
include "../theme/header.php";

// Xử lý lọc theo khoảng thời gian
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Tính tổng thu từ đơn hàng Delivered
$sql = "SELECT SUM(od.food_discount_price * od.food_qty) as total_revenue
        FROM order_main om
        JOIN order_details od ON om.id = od.order_id
        WHERE om.order_status = 'Delivered'";
if ($start_date && $end_date) {
    $sql .= " AND om.order_date BETWEEN ? AND ?";
}
$stmt = $link->prepare($sql);
if ($start_date && $end_date) {
    $stmt->bind_param("ss", $start_date, $end_date);
}
$stmt->execute();
$total_revenue_result = $stmt->get_result();
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'] ?? 0;
$stmt->close();
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Revenue Overview</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Revenue Overview</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content mt-3">
    <!-- Form lọc theo thời gian -->
    <div class="row mb-3">
        <div class="col-lg-12">
            <form method="post" class="form-inline">
                <div class="form-group mr-2">
                    <label for="start_date" class="mr-1">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                <div class="form-group mr-2">
                    <label for="end_date" class="mr-1">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Hiển thị tổng thu -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Total Revenue</strong>
                </div>
                <div class="card-body">
                    <h4 class="text-success"><?php echo number_format($total_revenue, 0, '.', ','); ?> USD</h4>
                    <?php if ($start_date && $end_date): ?>
                        <p class="text-muted">From <?php echo htmlspecialchars($start_date); ?> to <?php echo htmlspecialchars($end_date); ?></p>
                    <?php else: ?>
                        <p class="text-muted">All time</p>
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