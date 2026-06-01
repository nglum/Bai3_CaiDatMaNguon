<?php
/**
 * Database Configuration
 * Cấu hình kết nối cơ sở dữ liệu MySQL
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Để trống nếu không có mật khẩu
define('DB_NAME', 'cine_ticket_db');
define('DB_CHARSET', 'utf8mb4');

// Tạo kết nối
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Lỗi kết nối database: " . $conn->connect_error);
}

// Thiết lập charset
$conn->set_charset(DB_CHARSET);

// Kiểm tra xem database đã tồn tại chưa, nếu chưa thì tạo từ file database.sql
// (Hoặc có thể tạo thủ công trước)

?>
