<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Sử dụng PHPMailer
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Xử lý xóa tin nhắn
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $link->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Message deleted successfully.";
    } else {
        $error = "Failed to delete message. Please try again.";
    }
    $stmt->close();
}

// Xử lý gửi email phản hồi
if (isset($_POST['reply'])) {
    $id = intval($_POST['message_id']);
    $to_email = mysqli_real_escape_string($link, trim($_POST['to_email']));
    $subject = mysqli_real_escape_string($link, trim($_POST['subject']));
    $reply_message = mysqli_real_escape_string($link, trim($_POST['reply_message']));

    if (empty($subject) || empty($reply_message)) {
        $error = "Please fill in both subject and reply content.";
    } else {
        // Kiểm tra PHPMailer
        if (!file_exists('vendor/autoload.php')) {
            $error = "PHPMailer is not installed. Please run 'composer require phpmailer/phpmailer'.";
        } else {
            $mail = new PHPMailer(true);
            try {
                // Cấu hình SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ndtan2101102@student.ctuet.edu.vn';
                $mail->Password = 'ojlu yzzm teug svzk';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Thiết lập email
                $mail->setFrom('ndtan2101102@student.ctuet.edu.vn', 'Frostbite Admin');
                $mail->addAddress($to_email);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = nl2br(htmlspecialchars($reply_message));
                $mail->AltBody = strip_tags($reply_message);

                $mail->send();

                // Cập nhật trạng thái replied
                $stmt = $link->prepare("UPDATE contact_messages SET replied = 1 WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();

                $success = "Reply sent successfully.";
            } catch (Exception $e) {
                $error = "Failed to send email. Error: {$mail->ErrorInfo}";
            }
        }
    }
}

// Lấy danh sách tin nhắn
$stmt = $link->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include "../theme/header.php"; // Giả định header.php có menu admin
?>

<!-- Main Content -->
<section class="content-section" style="padding: 50px 0;">
    <div class="auto-container">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <h3>Manage Contact Messages</h3>
                    </div>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success col-md-12" role="alert">
                            <center><strong>Success!</strong> <?php echo $success; ?></center>
                        </div>
                    <?php elseif (isset($error)): ?>
                        <div class="alert alert-danger col-md-12" role="alert">
                            <center><strong>Error!</strong> <?php echo $error; ?></center>
                        </div>
                    <?php endif; ?>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Date Sent</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($messages)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No messages found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($messages as $message): ?>
                                        <tr>
                                            <td><?php echo $message['id']; ?></td>
                                            <td><?php echo htmlspecialchars($message['username']); ?></td>
                                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                                            <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                            <td><?php echo $message['created_at']; ?></td>
                                            <td><?php echo $message['replied'] ? '<span class="badge badge-success">Replied</span>' : '<span class="badge badge-warning">Not Replied</span>'; ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#replyModal<?php echo $message['id']; ?>">View & Reply</button>
                                                <a href="?delete=<?php echo $message['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                                            </td>
                                        </tr>

                                        <!-- Reply Modal -->
                                        <div class="modal fade" id="replyModal<?php echo $message['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reply to Message #<?php echo $message['id']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($message['username']); ?></p>
                                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                                                        <p><strong>Subject:</strong> <?php echo htmlspecialchars($message['subject']); ?></p>
                                                        <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                                                        <hr>
                                                        <form method="post" action="">
                                                            <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                                            <input type="hidden" name="to_email" value="<?php echo htmlspecialchars($message['email']); ?>">
                                                            <div class="form-group">
                                                                <label>Reply Subject</label>
                                                                <input type="text" name="subject" class="form-control" value="Re: <?php echo htmlspecialchars($message['subject']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Reply Content</label>
                                                                <textarea name="reply_message" class="form-control" rows="5" required></textarea>
                                                            </div>
                                                            <button type="submit" name="reply" class="theme-btn btn-style-five"><span class="txt">Send Reply</span></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include "../theme/footer.php";
?>

<!-- Scripts -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/popper.min.js"></script>
<script src="../assets/js/jquery-ui.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/jquery.fancybox.js"></script>
<script src="../assets/js/owl.js"></script>
<script src="../assets/js/wow.js"></script>
<script src="../assets/js/validate.js"></script>
<script src="../assets/js/appear.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>