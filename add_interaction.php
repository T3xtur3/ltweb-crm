<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit; }
require_once 'config/db.php';
include 'includes/header.php';

// 1. Lấy ID khách hàng từ URL 
$customer_id_from_url = $_GET['customer_id'] ?? '';

// 2. Lấy TẤT CẢ khách hàng để đổ vào dropdown
$stmt = $pdo->query("SELECT id, full_name FROM customers ORDER BY full_name ASC");
$all_customers = $stmt->fetchAll();

// 3. Xử lý khi nhấn Lưu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $c_id = $_POST['customer_id'];
    $staff_id = $_SESSION['user_id'];
    $type = $_POST['interaction_type'];
    $content = $_POST['content'];

    if (!empty($c_id)) {
        $insert = $pdo->prepare("INSERT INTO interactions (customer_id, staff_id, interaction_type, content, interaction_date) VALUES (?, ?, ?, ?, NOW())");
        $insert->execute([$c_id, $staff_id, $type, $content]);
        echo "<script>alert('Lưu thành công!'); window.location.href='interactions.php';</script>";
    }
}
?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4">Ghi nhận chăm sóc</h2>
    <div class="card shadow-sm p-4 col-md-6 border-0">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Chọn khách hàng</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">-- Chọn một khách hàng --</option>
                    <?php foreach ($all_customers as $cust): ?>
                        <option value="<?php echo $cust['id']; ?>" <?php echo ($customer_id_from_url == $cust['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cust['full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Hình thức</label>
                <select name="interaction_type" class="form-select">
                    <option>Gọi điện</option>
                    <option>Gửi Email</option>
                    <option>Chat Zalo</option>
                    <option>Tư vấn trực tiếp</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nội dung chi tiết</label>
                <textarea name="content" class="form-control" rows="4" required placeholder="Nhập kết quả tư vấn..."></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-success px-4">Lưu ghi chú</button>
                <a href="interactions.php" class="btn btn-light">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>