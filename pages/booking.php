<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Vé - Cine Cinema</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/booking.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">🎬 Cine Cinema</div>
            <ul class="nav-links">
                <li><a href="/index.php">Trang Chủ</a></li>
                <li><a href="/pages/booking.php" class="active">Đặt Vé</a></li>
                <li><a href="/pages/admin/login.php">Admin</a></li>
            </ul>
        </div>
    </nav>

    <div class="booking-container">
        <div class="container">
            <h1>Đặt Vé Xem Phim</h1>
            
            <div class="booking-steps">
                <div class="step active" id="step1">
                    <span class="step-number">1</span>
                    <span class="step-name">Chọn Phim</span>
                </div>
                <div class="step" id="step2">
                    <span class="step-number">2</span>
                    <span class="step-name">Chọn Suất & Ghế</span>
                </div>
                <div class="step" id="step3">
                    <span class="step-number">3</span>
                    <span class="step-name">Thông Tin Khách</span>
                </div>
                <div class="step" id="step4">
                    <span class="step-number">4</span>
                    <span class="step-name">Xác Nhận</span>
                </div>
            </div>

            <!-- BƯỚC 1: CHỌN PHIM -->
            <div class="booking-step" id="bookingStep1">
                <h2>Chọn Phim</h2>
                <div class="movies-list" id="moviesListBooking">
                    <!-- Danh sách phim sẽ được load từ API -->
                </div>
            </div>

            <!-- BƯỚC 2: CHỌN SUẤT CHIẾU & GHẾ -->
            <div class="booking-step hidden" id="bookingStep2">
                <h2>Chọn Suất Chiếu & Ghế Ngồi</h2>
                
                <div class="step2-content">
                    <div class="showtimes-section">
                        <h3>Chọn Ngày Chiếu</h3>
                        <div class="date-picker" id="datePickerBooking">
                            <!-- Danh sách ngày sẽ được generate -->
                        </div>

                        <h3>Chọn Suất Chiếu</h3>
                        <div class="showtimes-list" id="showtimesList">
                            <!-- Danh sách suất chiếu sẽ được load -->
                        </div>
                    </div>

                    <div class="seats-section">
                        <h3>Chọn Ghế Ngồi</h3>
                        <div class="screen">PHÒNG CHIẾU</div>
                        <div class="seats-grid" id="seatsGrid">
                            <!-- Sơ đồ ghế sẽ được generate -->
                        </div>
                        <div class="seat-legend">
                            <div class="legend-item">
                                <div class="seat-preview available"></div>
                                <span>Ghế trống</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-preview selected"></div>
                                <span>Ghế đã chọn</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-preview booked"></div>
                                <span>Ghế đã đặt</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BƯỚC 3: THÔNG TIN KHÁCH HÀNG -->
            <div class="booking-step hidden" id="bookingStep3">
                <h2>Thông Tin Khách Hàng</h2>
                <form id="customerForm" class="customer-form">
                    <div class="form-group">
                        <label for="customerName">Tên Khách Hàng *</label>
                        <input type="text" id="customerName" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">Số Điện Thoại *</label>
                        <input type="tel" id="customerPhone" name="phone" placeholder="0901234567" required>
                    </div>
                    <div class="form-group">
                        <label for="customerEmail">Email *</label>
                        <input type="email" id="customerEmail" name="email" required>
                    </div>
                </form>
            </div>

            <!-- BƯỚC 4: XÁC NHẬN -->
            <div class="booking-step hidden" id="bookingStep4">
                <h2>Xác Nhận Đơn Hàng</h2>
                <div class="booking-summary" id="bookingSummary">
                    <!-- Tóm tắt đơn hàng sẽ được hiển thị -->
                </div>
            </div>

            <!-- Nút điều hướng -->
            <div class="booking-buttons">
                <button class="btn btn-secondary" id="btnPrevious" style="display:none;">← Quay Lại</button>
                <button class="btn btn-primary" id="btnNext">Tiếp Tục →</button>
                <button class="btn btn-success" id="btnConfirm" style="display:none;">Xác Nhận Đặt Vé</button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2026 Cine Cinema. All rights reserved.</p>
    </footer>

    <script src="/assets/js/booking.js"></script>
</body>
</html>