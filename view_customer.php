<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit; }
require_once 'config/db.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: customers.php"); exit; }

// Lấy thông tin khách hàng
$stmt = $pdo->prepare("SELECT c.*, t.type_name FROM customers c 
                       JOIN customer_types t ON c.customer_type_id = t.id 
                       WHERE c.id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();

// Lấy lịch sử chăm sóc của riêng khách này
$stmt_logs = $pdo->prepare("SELECT i.*, u.full_name as staff_name FROM interactions i 
                            JOIN users u ON i.staff_id = u.id 
                            WHERE i.customer_id = ? ORDER BY interaction_date DESC");
$stmt_logs->execute([$id]);
$logs = $stmt_logs->fetchAll();
?>

<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="customers.php" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Hồ sơ khách hàng</h2>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold"><?php echo htmlspecialchars($c['full_name']); ?></h4>
                <span class="badge bg-primary mb-3"><?php echo $c['type_name']; ?></span>
                <hr>
                <div class="text-start">
                    <p><i class="bi bi-envelope me-2"></i> <?php echo htmlspecialchars($c['email']); ?></p>
                    <p><i class="bi bi-telephone me-2"></i> <?php echo htmlspecialchars($c['phone']); ?></p>
                    <p><i class="bi bi-geo-alt me-2"></i> <?php echo htmlspecialchars($c['address']); ?></p>
                </div>
                <a href="edit_customer.php?id=<?php echo $c['id']; ?>" class="btn btn-warning text-white w-100 mt-2">Chỉnh sửa</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Lịch sử chăm sóc</h5>
                <?php if (count($logs) > 0): ?>
                    <div class="timeline">
                        <?php foreach ($logs as $log): ?>
                        <div class="mb-3 p-3 bg-light rounded border-start border-primary border-4">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-primary"><?php echo $log['interaction_type']; ?></span>
                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($log['interaction_date'])); ?></small>
                            </div>
                            <p class="mb-1 mt-1 italic">"<?php echo htmlspecialchars($log['content']); ?>"</p>
                            <small class="text-muted">Nhân viên: <?php echo $log['staff_name']; ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-4">Chưa có lịch sử chăm sóc cho khách hàng này.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>