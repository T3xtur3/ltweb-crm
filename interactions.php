<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit; }
require_once 'config/db.php';
include 'includes/header.php';

// Truy vấn lấy lịch sử tương tác kèm tên khách và tên nhân viên
$stmt = $pdo->query("SELECT i.*, c.full_name as customer_name, u.full_name as staff_name 
                     FROM interactions i 
                     JOIN customers c ON i.customer_id = c.id 
                     JOIN users u ON i.staff_id = u.id 
                     ORDER BY interaction_date DESC");
$logs = $stmt->fetchAll();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h2 class="fw-bold"><i class="bi bi-chat-left-quote me-2"></i>Lịch sử chăm sóc khách hàng</h2>
        <a href="add_interaction.php" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Ghi nhận mới
        </a>
    </div>

    <div class="row">
        <?php if (count($logs) > 0): ?>
            <?php foreach ($logs as $log): ?>
            <div class="col-12 mb-3">
                <div class="card shadow-sm border-0 border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title text-primary fw-bold mb-1">
                                    <?php echo htmlspecialchars($log['interaction_type']); ?>
                                </h5>
                                <p class="text-dark mb-2">
                                    <strong>Khách hàng:</strong> 
                                    <a href="view_customer.php?id=<?php echo $log['customer_id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($log['customer_name']); ?>
                                    </a>
                                </p>
                            </div>
                            <span class="badge bg-light text-muted border">
                                <i class="bi bi-clock me-1"></i><?php echo date('d/m/Y H:i', strtotime($log['interaction_date'])); ?>
                            </span>
                        </div>
                        
                        <div class="bg-light p-3 rounded mb-2">
                            <i class="bi bi-quote text-secondary me-2"></i>
                            <span class="fst-italic"><?php echo htmlspecialchars($log['content']); ?></span>
                        </div>

                        <div class="text-end">
                            <small class="text-muted">
                                <i class="bi bi-person-check me-1"></i>Nhân viên thực hiện: 
                                <strong><?php echo htmlspecialchars($log['staff_name']); ?></strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5 shadow-sm">
                    <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                    Chưa có lịch sử chăm sóc nào được ghi nhận.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>