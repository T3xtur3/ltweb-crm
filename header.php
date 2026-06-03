<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>CRM Admin System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7f6; }
        .sidebar { min-height: 100vh; width: 250px; background: #212529; color: white; position: fixed; z-index: 1000; }
        .main-content { margin-left: 250px; width: calc(100% - 250px); min-height: 100vh; display: flex; flex-direction: column; }
        .top-navbar { background: white; padding: 15px 30px; border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        .nav-link { color: #adb5bd; padding: 10px 15px; }
        .nav-link.active { color: white; background: #0d6efd; border-radius: 5px; }
    </style>
</head>
<body class="d-flex">
    <div class="sidebar p-3 d-flex flex-column">
        <h4 class="text-center fw-bold py-3">CRM Admin</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="customers.php" class="nav-link <?php echo $current_page == 'customers.php' ? 'active' : ''; ?>">
                    <i class="bi bi-people me-2"></i> Khách hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="interactions.php" class="nav-link <?php echo $current_page == 'interactions.php' ? 'active' : ''; ?>">
                    <i class="bi bi-chat-dots me-2"></i> Lịch sử chăm sóc
                </a>
            </li>
        </ul>
        <hr>
        <a href="auth/logout.php" class="btn btn-outline-danger w-100 mb-3">Đăng xuất</a>
    </div>

    <div class="main-content">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <span class="text-muted fw-bold">HỆ THỐNG CRM</span>
            <span class="text-dark"><i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['full_name'] ?? 'Admin'; ?></span>
        </div>
        <div class="container-fluid px-4">