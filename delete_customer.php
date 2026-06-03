<?php
session_start();
require_once 'config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        //Transaction 
        $pdo->beginTransaction();

        // 1. Xóa appointments (Lịch hẹn)
        $stmt1 = $pdo->prepare("DELETE FROM appointments WHERE customer_id = ?");
        $stmt1->execute([$id]);

        // 2. Xóa interactions (Lịch sử chăm sóc)
        $stmt2 = $pdo->prepare("DELETE FROM interactions WHERE customer_id = ?");
        $stmt2->execute([$id]);

        // 3. xóa Khách hàng
        $stmt3 = $pdo->prepare("DELETE FROM customers WHERE id = ?");
        $stmt3->execute([$id]);

        // Hoàn tất 
        $pdo->commit();
        
    } catch (Exception $e) {
        // Nếu có lỗi, hủy bỏ mọi thao tác trên
        $pdo->rollBack();
        die("Lỗi khi xóa: " . $e->getMessage());
    }
}

header("Location: customers.php");
exit;