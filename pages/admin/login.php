<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Admin - Cine Cinema</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>🎬 Cine Cinema Admin</h1>
                <p>Đăng Nhập Trang Quản Trị</p>
            </div>
            
            <form id="loginForm" class="login-form">
                <div class="form-group">
                    <label for="adminUser">Tên Đăng Nhập</label>
                    <input type="text" id="adminUser" name="username" placeholder="admin" required>
                </div>
                <div class="form-group">
                    <label for="adminPass">Mật Khẩu</label>
                    <input type="password" id="adminPass" name="password" placeholder="admin123" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
            </form>

            <div class="login-hint">
                <p><strong>Tài khoản mặc định:</strong></p>
                <p>Username: <code>admin</code></p>
                <p>Password: <code>admin123</code></p>
            </div>
        </div>
    </div>

    <script src="/assets/js/admin-login.js"></script>
</body>
</html>