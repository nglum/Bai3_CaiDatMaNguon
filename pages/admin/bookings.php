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
    <title>Quản Lý Hóa Đơn - Admin</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>🎬 Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="/pages/admin/dashboard.php" class="nav-item">Dashboard</a>
                <a href="/pages/admin/movies.php" class="nav-item">Quản Lý Phim</a>
                <a href="/pages/admin/showtimes.php" class="nav-item">Quản Lý Suất Chiếu</a>
                <a href="/pages/admin/bookings.php" class="nav-item active">Quản Lý Hóa Đơn</a>
                <a href="/pages/admin/logout.php" class="nav-item logout">Đăng Xuất</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <h1>Quản Lý Hóa Đơn</h1>
            </header>

            <div class="container">
                <div class="filters">
                    <select id="statusFilter" class="filter-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="Chờ thanh toán">Chờ thanh toán</option>
                        <option value="Đã thanh toán">Đã thanh toán</option>
                        <option value="Đã hủy">Đã hủy</option>
                    </select>
                </div>

                <table class="data-table" id="bookingsTable">
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Đặt</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <!-- Danh sách hóa đơn sẽ được load -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Chi Tiết Hóa Đơn -->
    <div id="bookingDetailModal" class="modal hidden">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2>Chi Tiết Hóa Đơn #<span id="detailBookingId"></span></h2>
                <button class="modal-close" onclick="closeBookingDetailModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-section">
                    <h3>Thông Tin Khách Hàng</h3>
                    <p><strong>Tên:</strong> <span id="detailCustomerName"></span></p>
                    <p><strong>SĐT:</strong> <span id="detailPhone"></span></p>
                    <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                </div>

                <div class="detail-section">
                    <h3>Chi Tiết Vé</h3>
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Phim</th>
                                <th>Phòng</th>
                                <th>Ngày - Giờ</th>
                                <th>Ghế</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody id="detailTickets">
                            <!-- Chi tiết vé sẽ được load -->
                        </tbody>
                    </table>
                </div>

                <div class="detail-section">
                    <h3>Tổng Cộng: <span id="detailTotal"></span></h3>
                </div>

                <div class="detail-section">
                    <label for="newStatus">Cập Nhật Trạng Thái:</label>
                    <select id="newStatus" class="filter-select">
                        <option value="Chờ thanh toán">Chờ thanh toán</option>
                        <option value="Đã thanh toán">Đã thanh toán</option>
                        <option value="Đã hủy">Đã hủy</option>
                    </select>
                    <button class="btn btn-primary" onclick="updateBookingStatus()" style="margin-top: 10px;">Cập Nhật</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/admin-bookings.js"></script>
</body>
</html>