<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit; }
require_once 'config/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $type = $_POST['customer_type_id'];

    $stmt = $pdo->prepare("INSERT INTO customers (full_name, email, phone, address, customer_type_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $address, $type]);
    
    echo "<script>alert('Thêm thành công!'); window.location.href='customers.php';</script>";
}
?>

<div class="container-fluid">
    <h2 class="mb-4">Thêm khách hàng mới</h2>
    <div class="card shadow-sm col-md-6 p-4">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Phân loại</label>
                <select name="customer_type_id" class="form-select">
                    <option value="1">VIP</option>
                    <option value="2">Tiềm năng</option>
                    <option value="3">Khách cũ</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lưu khách hàng</button>
            <a href="customers.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>