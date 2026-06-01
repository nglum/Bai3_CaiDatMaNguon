<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cine Cinema - Đặt Vé Phim Trực Tuyến</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">🎬 Cine Cinema</div>
            <ul class="nav-links">
                <li><a href="/index.php">Trang Chủ</a></li>
                <li><a href="/pages/booking.php">Đặt Vé</a></li>
                <li><a href="/pages/admin/login.php">Admin</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <h1>Chào Mừng Đến Cine Cinema</h1>
            <p>Trải nghiệm xem phim tuyệt vời với công nghệ chiếu phim 4K</p>
            <a href="/pages/booking.php" class="btn btn-primary">Đặt Vé Ngay</a>
        </div>
    </section>

    <section class="movies-section">
        <div class="container">
            <h2>Phim Đang Chiếu</h2>
            
            <div class="filters">
                <input type="text" id="searchInput" placeholder="Tìm kiếm phim..." class="search-box">
                <select id="genreFilter" class="filter-select">
                    <option value="">Tất cả thể loại</option>
                </select>
                <input type="date" id="dateFilter" class="filter-select">
            </div>

            <div class="movies-grid" id="moviesGrid">
                <!-- Phim sẽ được load từ API -->
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2026 Cine Cinema. All rights reserved.</p>
    </footer>

    <script src="/assets/js/main.js"></script>
</body>
</html>