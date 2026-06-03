<?php
// 1. Khởi tạo session
session_start();

// 2. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) { 
    header("Location: auth/login.php"); 
    exit; 
}

// 3. Kết nối DB
require_once 'config/db.php';

// 4. Truy vấn thống kê
try {
    $total = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
    $new_this_month = $pdo->query("SELECT COUNT(*) FROM customers WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetchColumn();
    $vips = $pdo->query("SELECT COUNT(*) FROM customers WHERE customer_type_id = 1")->fetchColumn();
} catch (Exception $e) {
    die("Lỗi truy vấn dữ liệu: " . $e->getMessage());
}

// 5. Nhúng Header (Mở đầu trang, Sidebar)
include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h2 class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Bảng điều khiển (Dashboard)</h2>
        <span class="badge bg-light text-dark shadow-sm p-2 border">Hôm nay: <?php echo date('d/m/Y'); ?></span>
    </div>

    <h4 class="mb-4 text-muted">Chào mừng trở lại, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>!</h4>
    
    <div class="row g-4"> <div class="col-md-4">
            <div class="card bg-primary text-white shadow border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title opacity-75 mb-2">Tổng Khách Hàng</h5>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $total; ?></h2>
                        </div>
                        <i class="bi bi-people-fill fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-success text-white shadow border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title opacity-75 mb-2">Khách Mới (Tháng này)</h5>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $new_this_month; ?></h2>
                        </div>
                        <i class="bi bi-person-plus-fill fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title opacity-75 mb-2">Khách VIP</h5>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $vips; ?></h2>
                        </div>
                        <i class="bi bi-star-fill fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <?php 
// 7. Nhúng Footer (Đóng main-content, body, html)
include 'includes/footer.php'; 
?>