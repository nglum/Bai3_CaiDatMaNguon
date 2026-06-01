<?php
/**
 * Helper Functions
 * Các hàm hỗ trợ chung cho hệ thống
 */

/**
 * Format giá tiền VND
 */
function formatPrice($price) {
    return number_format($price, 0, '.', ',') . ' VND';
}

/**
 * Format ngày giờ
 */
function formatDateTime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

/**
 * Format ngày
 */
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

/**
 * Format giờ
 */
function formatTime($time) {
    return date('H:i', strtotime($time));
}

/**
 * Kiểm tra xem một chuỗi là email hợp lệ
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Kiểm tra xem một chuỗi là số điện thoại hợp lệ (Việt Nam)
 */
function isValidPhone($phone) {
    // Số điện thoại Việt Nam bắt đầu với 0 và có 10 chữ số
    return preg_match('/^0\d{9}$/', $phone);
}

/**
 * Tạo mã ghế tự động (A1, A2, ..., B1, B2, ...)
 * $rows: số hàng, $cols: số cột
 */
function generateSeatMap($rows = 10, $cols = 10) {
    $seats = [];
    $letters = array_slice(range('A', 'Z'), 0, $rows);
    
    foreach ($letters as $row) {
        for ($col = 1; $col <= $cols; $col++) {
            $seats[] = $row . $col;
        }
    }
    
    return $seats;
}

/**
 * Chuyển đổi mảng ghế 1D thành 2D để hiển thị (Phòng chiếu)
 */
function generateSeatGrid($allSeats, $bookedSeats, $rows = 10, $cols = 10) {
    $grid = [];
    $letters = array_slice(range('A', 'Z'), 0, $rows);
    $seatIndex = 0;
    
    foreach ($letters as $row) {
        $rowSeats = [];
        for ($col = 1; $col <= $cols; $col++) {
            $seatNum = $row . $col;
            $isBooked = in_array($seatNum, $bookedSeats);
            
            $rowSeats[] = [
                'number' => $seatNum,
                'available' => !$isBooked,
                'booked' => $isBooked
            ];
        }
        $grid[] = $rowSeats;
    }
    
    return $grid;
}

/**
 * Lấy ngày hôm nay
 */
function getToday() {
    return date('Y-m-d');
}

/**
 * Lấy danh sách phòng chiếu
 */
function getRoomNames() {
    return [
        'Phòng 01',
        'Phòng 02',
        'Phòng 03',
        'Phòng 04',
        'Phòng 05'
    ];
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect page
 */
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

?>