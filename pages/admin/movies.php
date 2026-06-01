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
    <title>Quản Lý Phim - Admin</title>
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
                <a href="/pages/admin/movies.php" class="nav-item active">Quản Lý Phim</a>
                <a href="/pages/admin/showtimes.php" class="nav-item">Quản Lý Suất Chiếu</a>
                <a href="/pages/admin/bookings.php" class="nav-item">Quản Lý Hóa Đơn</a>
                <a href="/pages/admin/logout.php" class="nav-item logout">Đăng Xuất</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <h1>Quản Lý Phim</h1>
                <button class="btn btn-primary" id="btnAddMovie">+ Thêm Phim</button>
            </header>

            <div class="container">
                <table class="data-table" id="moviesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Phim</th>
                            <th>Thể Loại</th>
                            <th>Thời Lượng</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody id="moviesTableBody">
                        <!-- Danh sách phim sẽ được load -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Thêm/Sửa Phim -->
    <div id="movieModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="movieModalTitle">Thêm Phim</h2>
                <button class="modal-close" onclick="closeMovieModal()">&times;</button>
            </div>
            <form id="movieForm" class="modal-form">
                <input type="hidden" id="movieId">
                <div class="form-group">
                    <label for="movieTitle">Tên Phim *</label>
                    <input type="text" id="movieTitle" required>
                </div>
                <div class="form-group">
                    <label for="movieGenre">Thể Loại *</label>
                    <select id="movieGenre" required>
                        <option value="">Chọn thể loại</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="movieDuration">Thời Lượng (phút) *</label>
                    <input type="number" id="movieDuration" min="1" required>
                </div>
                <div class="form-group">
                    <label for="movieDescription">Mô Tả</label>
                    <textarea id="movieDescription" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="movieStatus">Trạng Thái *</label>
                    <select id="movieStatus" required>
                        <option value="Đang chiếu">Đang chiếu</option>
                        <option value="Sắp chiếu">Sắp chiếu</option>
                        <option value="Kết thúc">Kết thúc</option>
                    </select>
                </div>
                <div class="form-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeMovieModal()">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/assets/js/admin-movies.js"></script>
</body>
</html>