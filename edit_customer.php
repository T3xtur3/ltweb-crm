<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit; }
require_once 'config/db.php';
include 'includes/header.php';

// Lấy ID từ thanh địa chỉ
$id = $_GET['id'] ?? null;
if (!$id) { header("Location: customers.php"); exit; }

// Truy vấn lấy dữ liệu khách hàng hiện tại
$stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) { die("Không tìm thấy khách hàng!"); }

// Xử lý khi nhấn nút cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $type = $_POST['customer_type_id'];

    $update = $pdo->prepare("UPDATE customers SET full_name=?, email=?, phone=?, address=?, customer_type_id=? WHERE id=?");
    $update->execute([$name, $email, $phone, $address, $type, $id]);
    
    echo "<script>alert('Cập nhật thành công!'); window.location.href='customers.php';</script>";
}
?>

<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="customers.php" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Chỉnh sửa thông tin</h2>
    </div>

    <div class="card shadow-sm border-0 p-4 col-md-8 col-lg-6">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Họ và tên</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($c['full_name']); ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($c['email']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($c['phone']); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Địa chỉ</label>
                <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($c['address']); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Phân loại</label>
                <select name="customer_type_id" class="form-select">
                    <option value="1" <?php echo ($c['customer_type_id'] == 1) ? 'selected' : ''; ?>>VIP</option>
                    <option value="2" <?php echo ($c['customer_type_id'] == 2) ? 'selected' : ''; ?>>Tiềm năng</option>
                    <option value="3" <?php echo ($c['customer_type_id'] == 3) ? 'selected' : ''; ?>>Khách cũ</option>
                </select>
            </div>
            <div class="d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                <a href="customers.php" class="btn btn-light px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>