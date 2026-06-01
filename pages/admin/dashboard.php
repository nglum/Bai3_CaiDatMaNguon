<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/functions.php';

// Kiểm tra đăng nhập
session_start();
if (!isset($_SESSION['admin_id'])) {
    redirect('/pages/admin/login.php');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cine Cinema</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>🎬 Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="/pages/admin/dashboard.php" class="nav-item active">Dashboard</a>
                <a href="/pages/admin/movies.php" class="nav-item">Quản Lý Phim</a>
                <a href="/pages/admin/showtimes.php" class="nav-item">Quản Lý Suất Chiếu</a>
                <a href="/pages/admin/bookings.php" class="nav-item">Quản Lý Hóa Đơn</a>
                <a href="/pages/admin/logout.php" class="nav-item logout">Đăng Xuất</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <span class="user-info">Xin chào, Admin</span>
            </header>

            <div class="container">
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-icon">🎬</div>
                        <div class="stat-info">
                            <h3>Tổng Phim</h3>
                            <p id="totalMovies">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📅</div>
                        <div class="stat-info">
                            <h3>Suất Chiếu</h3>
                            <p id="totalShowtimes">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🎫</div>
                        <div class="stat-info">
                            <h3>Hóa Đơn</h3>
                            <p id="totalBookings">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">💰</div>
                        <div class="stat-info">
                            <h3>Doanh Thu</h3>
                            <p id="totalRevenue">0 VND</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="/assets/js/admin.js"></script>
</body>
</html>