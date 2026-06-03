<?php
session_start();
// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) { 
    header("Location: auth/login.php"); 
    exit; 
}

// 2. Nhúng file kết nối và giao diện
require_once 'config/db.php';
include 'includes/header.php'; 

// 3. Xử lý Tìm kiếm và Lọc
$search = $_GET['search'] ?? '';
$type_filter = $_GET['type'] ?? '';

// Lấy khách hàng và tên loại
$sql = "SELECT c.*, t.type_name FROM customers c 
        JOIN customer_types t ON c.customer_type_id = t.id 
        WHERE c.full_name LIKE ?";
$params = ["%$search%"];

if ($type_filter) {
    $sql .= " AND c.customer_type_id = ?";
    $params[] = $type_filter;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$customers = $stmt->fetchAll();
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-people-fill me-2"></i>Quản lý khách hàng</h2>
        <a href="add_customer.php" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Thêm khách hàng
        </a>
    </div>

    <div class="card mb-4 shadow-sm border-0 bg-light">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm theo tên khách hàng..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="type" class="form-select">
                        <option value="">-- Tất cả phân loại --</option>
                        <option value="1" <?php if($type_filter == '1') echo 'selected'; ?>>VIP</option>
                        <option value="2" <?php if($type_filter == '2') echo 'selected'; ?>>Tiềm năng</option>
                        <option value="3" <?php if($type_filter == '3') echo 'selected'; ?>>Khách cũ</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark w-100">Tìm kiếm & Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm bg-white rounded border">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Họ và tên</th>
                    <th>Điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Phân loại</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($customers) > 0): ?>
                    <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($c['full_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($c['phone']); ?></td>
                        <td><?php echo htmlspecialchars($c['address']); ?></td>
                        <td>
                            <?php 
                                $badge_color = 'bg-secondary';
                                // Chuyển màu theo ID phân loại
                                if($c['customer_type_id'] == 1) $badge_color = 'bg-danger'; 
                                if($c['customer_type_id'] == 2) $badge_color = 'bg-warning text-dark';
                            ?>
                            <span class="badge <?php echo $badge_color; ?>">
                                <?php echo $c['type_name']; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="add_interaction.php?customer_id=<?php echo $c['id']; ?>" 
                                   class="btn btn-sm btn-outline-success" title="Ghi nhận chăm sóc">
                                    <i class="bi bi-chat-left-dots-fill"></i>
                                </a>

                                <a href="view_customer.php?id=<?php echo $c['id']; ?>" 
                                   class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                <a href="edit_customer.php?id=<?php echo $c['id']; ?>" 
                                   class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                <?php if($_SESSION['role_id'] == 1): ?>
                                    <a href="delete_customer.php?id=<?php echo $c['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger" title="Xóa"
                                       onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Không tìm thấy dữ liệu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>