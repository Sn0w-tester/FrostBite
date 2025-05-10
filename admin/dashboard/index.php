<?php
ob_start();
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include "../conn.php";
include "../theme/header.php";

// Tính tổng thu từ đơn hàng Delivered
$stmt = $link->prepare("SELECT SUM(od.food_discount_price * od.food_qty) as total_revenue
                        FROM order_main om
                        JOIN order_details od ON om.id = od.order_id
                        WHERE om.order_status = 'Delivered'");
$stmt->execute();
$total_revenue_result = $stmt->get_result();
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'] ?? 0;
$stmt->close();

// Tính tổng thu theo ngày cho biểu đồ (7 ngày gần nhất)
$end_date = date('d-m-Y'); // Ngày hiện tại: 26-04-2025
$start_date = date('d-m-Y', strtotime('-6 days')); // 7 ngày trước: 20-04-2025
$stmt = $link->prepare("SELECT om.order_date, SUM(od.food_discount_price * od.food_qty) as daily_revenue
                        FROM order_main om
                        JOIN order_details od ON om.id = od.order_id
                        WHERE om.order_status = 'Delivered' AND om.order_date BETWEEN ? AND ?
                        GROUP BY om.order_date");
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$revenue_by_date_result = $stmt->get_result();
$revenue_by_date = [];
while ($row = $revenue_by_date_result->fetch_assoc()) {
    $revenue_by_date[$row['order_date']] = $row['daily_revenue'];
}
$stmt->close();

// Tạo mảng 7 ngày gần nhất với tổng thu (0 nếu không có dữ liệu)
$revenue_dates = [];
$revenue_values = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('d-m-Y', strtotime("-$i days"));
    $revenue_dates[] = $date;
    $revenue_values[] = isset($revenue_by_date[$date]) ? $revenue_by_date[$date] : 0;
}

// Thống kê đơn hàng theo trạng thái
$stmt = $link->prepare("SELECT order_status, COUNT(*) as count
                        FROM order_main
                        GROUP BY order_status");
$stmt->execute();
$status_counts_result = $stmt->get_result();
$status_counts = [
    'Pending' => 0,
    'Preparing Order' => 0,
    'Delivered' => 0,
    'Delivery Failed' => 0,
    'Cancelled' => 0
];
while ($row = $status_counts_result->fetch_assoc()) {
    $status_counts[$row['order_status']] = $row['count'];
}
$stmt->close();

// Lấy 5 đơn hàng gần đây
$stmt = $link->prepare("SELECT id, order_number, order_username, order_date, order_status
                        FROM order_main
                        ORDER BY id DESC
                        LIMIT 5");
$stmt->execute();
$recent_orders = $stmt->get_result();
$stmt->close();

// Tính số lượng user
$stmt = $link->prepare("SELECT COUNT(*) as user_count FROM user_registration");
$stmt->execute();
$user_count_result = $stmt->get_result();
$user_count = $user_count_result->fetch_assoc()['user_count'] ?? 0;
$stmt->close();

// Tính số lượng món ăn
$stmt = $link->prepare("SELECT COUNT(*) as food_count FROM food");
$stmt->execute();
$food_count_result = $stmt->get_result();
$food_count = $food_count_result->fetch_assoc()['food_count'] ?? 0;
$stmt->close();

// Lấy top 5 món ăn bán chạy
$stmt = $link->prepare("SELECT food_name, SUM(food_qty) as total_qty, MIN(food_image) as food_image
                        FROM order_details
                        GROUP BY food_name
                        ORDER BY total_qty DESC
                        LIMIT 5");
$stmt->execute();
$top_foods = $stmt->get_result();
$top_foods_data = [];
while ($row = $top_foods->fetch_assoc()) {
    $top_foods_data[] = $row;
}
$top_foods->data_seek(0); // Reset con trỏ để dùng lại
$stmt->close();

// Chuẩn bị dữ liệu cho biểu đồ Top Foods
$food_names = array_column($top_foods_data, 'food_name');
$food_quantities = array_column($top_foods_data, 'total_qty');
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content mt-3">
    <!-- Tổng thu -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Total Revenue</strong>
                </div>
                <div class="card-body">
                    <h4 class="text-success"><?php echo number_format($total_revenue, 0, '.', ','); ?> VNĐ</h4>
                    <a href="../Revenue" class="btn btn-sm btn-primary mt-2">View Details</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-warning text-white">
                    Pending Orders
                </div>
                <div class="card-body">
                    <h4><?php echo $status_counts['Pending']; ?></h4>
                    <br>
                    <a href="../Order" class="btn btn-sm btn-primary">Manage</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-success text-white">
                    Orders Waiting For Delivered
                </div>
                <div class="card-body">
                    <h4><?php echo $status_counts['Preparing Order']; ?></h4>
                    <br>
                    <a href="../Order/delivery.php" class="btn btn-sm btn-primary">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Revenue Trend (Last 7 Days)</strong>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê User và Món ăn -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Top 5 Best-Selling Foods</strong>
                </div>
                <div class="card-body">
                    <canvas id="topFoodsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Total Users
                </div>
                <div class="card-body">
                    <h4><?php echo $user_count; ?></h4>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Total Foods
                </div>
                <div class="card-body">
                    <h4><?php echo $food_count; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Recent Orders</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr style="background-color: #a41a13; color: #ffffff;">
                                    <th>Order Number</th>
                                    <th>Username</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $recent_orders->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['order_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['order_username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                                        <td>
                                            <span class="badge <?php
                                            switch ($row['order_status']) {
                                                case 'Pending':
                                                    echo 'badge-warning';
                                                    break;
                                                case 'Preparing Order':
                                                    echo 'badge-info';
                                                    break;
                                                case 'Delivered':
                                                    echo 'badge-success';
                                                    break;
                                                case 'Delivery Failed':
                                                    echo 'badge-danger';
                                                    break;
                                                case 'Cancelled':
                                                    echo 'badge-secondary';
                                                    break;
                                                default:
                                                    echo 'badge-light';
                                            }
                                            ?>">
                                                <?php echo htmlspecialchars($row['order_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="../Order/order_details.php?id=<?php echo (int) $row['id']; ?>"
                                                class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($recent_orders->num_rows == 0): ?>
                        <div class="alert alert-info text-center">
                            NoTtHiNg HeRe
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> <!-- .content -->

<!-- Script cho Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ Tổng thu
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($revenue_dates); ?>,
            datasets: [{
                label: 'Daily Revenue',
                data: <?php echo json_encode($revenue_values); ?>,
                borderColor: 'rgba(40, 167, 69, 1)', // Màu xanh lá giống text-success
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (USD)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Biểu đồ Top 5 Món ăn Bán chạy
    const topFoodsCtx = document.getElementById('topFoodsChart').getContext('2d');
    new Chart(topFoodsCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($food_names); ?>,
            datasets: [{
                label: 'Total Quantity Sold',
                data: <?php echo json_encode($food_quantities); ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity Sold'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Food Name'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

<?php
include "../theme/footer.php";
ob_end_flush();
?>