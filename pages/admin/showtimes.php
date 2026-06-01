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
    <title>Quản Lý Suất Chiếu - Admin</title>
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
                <a href="/pages/admin/showtimes.php" class="nav-item active">Quản Lý Suất Chiếu</a>
                <a href="/pages/admin/bookings.php" class="nav-item">Quản Lý Hóa Đơn</a>
                <a href="/pages/admin/logout.php" class="nav-item logout">Đăng Xuất</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <h1>Quản Lý Suất Chiếu</h1>
                <button class="btn btn-primary" id="btnAddShowtime">+ Thêm Suất Chiếu</button>
            </header>

            <div class="container">
                <table class="data-table" id="showtimesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Phim</th>
                            <th>Phòng</th>
                            <th>Ngày Chiếu</th>
                            <th>Giờ Bắt Đầu</th>
                            <th>Giá Vé</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody id="showtimesTableBody">
                        <!-- Danh sách suất chiếu sẽ được load -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Thêm/Sửa Suất Chiếu -->
    <div id="showtimeModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="showtimeModalTitle">Thêm Suất Chiếu</h2>
                <button class="modal-close" onclick="closeShowtimeModal()">&times;</button>
            </div>
            <form id="showtimeForm" class="modal-form">
                <input type="hidden" id="showtimeId">
                <div class="form-group">
                    <label for="showtimeMovie">Phim *</label>
                    <select id="showtimeMovie" required>
                        <option value="">Chọn phim</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="showtimeRoom">Phòng Chiếu *</label>
                    <select id="showtimeRoom" required>
                        <option value="">Chọn phòng</option>
                        <option value="Phòng 01">Phòng 01</option>
                        <option value="Phòng 02">Phòng 02</option>
                        <option value="Phòng 03">Phòng 03</option>
                        <option value="Phòng 04">Phòng 04</option>
                        <option value="Phòng 05">Phòng 05</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="showtimeDate">Ngày Chiếu *</label>
                    <input type="date" id="showtimeDate" required>
                </div>
                <div class="form-group">
                    <label for="showtimeTime">Giờ Bắt Đầu *</label>
                    <input type="time" id="showtimeTime" required>
                </div>
                <div class="form-group">
                    <label for="showtimePrice">Giá Vé *</label>
                    <input type="number" id="showtimePrice" min="1" step="1000" required>
                </div>
                <div class="form-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeShowtimeModal()">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/assets/js/admin-showtimes.js"></script>
</body>
</html>