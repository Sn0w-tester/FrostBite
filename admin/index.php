<?php
include "conn.php";
session_start();

// Kiểm tra nếu đã đăng nhập
if (isset($_SESSION['admin'])) {
    header("Location: Dashboard");
    exit();
}

// Khởi tạo CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Giới hạn đăng nhập sai
$max_attempts = 5;
$lockout_time = 15 * 60; // 15 phút

// Kiểm tra số lần đăng nhập sai
if (isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= $max_attempts) {
    if (time() - $_SESSION['last_failed_at'] < $lockout_time) {
        $error = "Too many failed attempts. Please try again after " . ceil(($lockout_time - (time() - $_SESSION['last_failed_at'])) / 60) . " minutes.";
    } else {
        // Reset sau khi hết thời gian khóa
        $_SESSION['failed_attempts'] = 0;
        unset($_SESSION['last_failed_at']);
    }
}

// Xử lý đăng nhập
if (isset($_POST["submit1"]) && empty($error)) {
    // Kiểm tra CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $username = isset($_POST['user_username']) ? mysqli_real_escape_string($link, trim($_POST['user_username'])) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($username) || empty($password)) {
            $error = "Please enter both username and password.";
        } else {
            // Truy vấn kiểm tra username
            $stmt = $link->prepare("SELECT username, password, failed_attempts FROM admin_login WHERE username = ?");
            if (!$stmt) {
                $error = "Database error: " . mysqli_error($link);
            } else {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    // Kiểm tra mật khẩu
                    if (password_verify($password, $row['password'])) {
                        // Reset failed attempts
                        $stmt = $link->prepare("UPDATE admin_login SET failed_attempts = 0, last_failed_at = NULL WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();

                        // Lưu session
                        $_SESSION['admin'] = $username;

                        header("Location: Dashboard");
                        exit();
                    } else {
                        // Tăng failed attempts
                        $failed_attempts = $row['failed_attempts'] + 1;
                        $stmt = $link->prepare("UPDATE admin_login SET failed_attempts = ?, last_failed_at = NOW() WHERE username = ?");
                        $stmt->bind_param("is", $failed_attempts, $username);
                        $stmt->execute();

                        $_SESSION['failed_attempts'] = $failed_attempts;
                        $_SESSION['last_failed_at'] = time();

                        $error = "Invalid password.";
                    }
                } else {
                    $error = "Username does not exist.";
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FrostBite - Admin Login</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a1d37, #1b3a5f);
            font-family: 'Open Sans', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-logo a {
            font-size: 2rem;
            color: #00ddeb;
            font-weight: 700;
            text-decoration: none;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        .btn-primary {
            background: #00ddeb;
            border: none;
            border-radius: 5px;
            padding: 0.75rem;
            width: 100%;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: #00b7c2;
        }
        .alert {
            border-radius: 5px;
            margin-top: 1rem;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <a href="#">FrostBite Admin</a>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" id="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form id="login-form" method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="mb-3">
                <label for="user_username" class="form-label">Admin Account</label>
                <input type="text" class="form-control" id="user_username" name="user_username" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" name="submit1" class="btn btn-primary">Sign In</button>
            <div class="mt-3 text-center">
                <a href="#" class="text-muted">Forgot Password?</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ẩn thông báo lỗi sau 3 giây
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>