<?php
/**
 * API: Quản lý Hóa đơn và Vé (Bookings)
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($action) {
    case 'list':
        getBookings();
        break;
    case 'get':
        getBookingById();
        break;
    case 'create':
        createBooking();
        break;
    case 'update-status':
        updateBookingStatus();
        break;
    case 'get-seats':
        getBookedSeats();
        break;
    default:
        echo json_encode(['error' => 'Action not found']);
}

/**
 * Lấy danh sách hóa đơn (Admin)
 */
function getBookings() {
    global $conn;
    
    $status = $_GET['status'] ?? '';
    
    $sql = "SELECT b.* FROM bookings b WHERE 1=1";
    
    if (!empty($status)) {
        $status = $conn->real_escape_string($status);
        $sql .= " AND b.status = '$status'";
    }
    
    $sql .= " ORDER BY b.booking_date DESC";
    
    $result = $conn->query($sql);
    $bookings = [];
    
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $bookings]);
}

/**
 * Lấy chi tiết hóa đơn (Admin)
 */
function getBookingById() {
    global $conn;
    
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid booking ID']);
        return;
    }
    
    // Lấy thông tin hóa đơn
    $sql = "SELECT b.* FROM bookings b WHERE b.id = $id";
    $result = $conn->query($sql);
    $booking = $result->fetch_assoc();
    
    if (!$booking) {
        echo json_encode(['success' => false, 'error' => 'Booking not found']);
        return;
    }
    
    // Lấy chi tiết vé
    $itemsSql = "SELECT bi.*, s.start_time, s.show_date, m.title, s.room_name FROM booking_items bi
                 JOIN showtimes s ON bi.showtime_id = s.id
                 JOIN movies m ON s.movie_id = m.id
                 WHERE bi.booking_id = $id";
    
    $itemsResult = $conn->query($itemsSql);
    $items = [];
    
    while ($row = $itemsResult->fetch_assoc()) {
        $items[] = $row;
    }
    
    $booking['items'] = $items;
    echo json_encode(['success' => true, 'data' => $booking]);
}

/**
 * Tạo hóa đơn mới (Khách hàng)
 */
function createBooking() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $customer_name = $conn->real_escape_string($data['customer_name'] ?? '');
    $phone = $conn->real_escape_string($data['phone'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $total_price = floatval($data['total_price'] ?? 0);
    $items = $data['items'] ?? []; // Array of {showtime_id, seat_number, price}
    
    if (empty($customer_name) || empty($phone) || empty($email) || $total_price <= 0 || empty($items)) {
        echo json_encode(['success' => false, 'error' => 'Missing or invalid fields']);
        return;
    }
    
    // Kiểm tra ghế đã được đặt chưa
    foreach ($items as $item) {
        $showtime_id = intval($item['showtime_id']);
        $seat_number = $conn->real_escape_string($item['seat_number']);
        
        $checkSql = "SELECT id FROM booking_items 
                    WHERE showtime_id = $showtime_id AND seat_number = '$seat_number'";
        $checkResult = $conn->query($checkSql);
        
        if ($checkResult->num_rows > 0) {
            echo json_encode(['success' => false, 'error' => "Ghế $seat_number đã được đặt"]);
            return;
        }
    }
    
    // Bắt đầu transaction
    $conn->begin_transaction();
    
    try {
        // Tạo hóa đơn
        $bookingSql = "INSERT INTO bookings (customer_name, phone, email, total_price, status) 
                      VALUES ('$customer_name', '$phone', '$email', $total_price, 'Chờ thanh toán')";
        
        if (!$conn->query($bookingSql)) {
            throw new Exception($conn->error);
        }
        
        $booking_id = $conn->insert_id;
        
        // Thêm chi tiết vé
        foreach ($items as $item) {
            $showtime_id = intval($item['showtime_id']);
            $seat_number = $conn->real_escape_string($item['seat_number']);
            $price = floatval($item['price']);
            
            $itemSql = "INSERT INTO booking_items (booking_id, showtime_id, seat_number, price) 
                       VALUES ($booking_id, $showtime_id, '$seat_number', $price)";
            
            if (!$conn->query($itemSql)) {
                throw new Exception($conn->error);
            }
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Booking created', 'booking_id' => $booking_id]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

/**
 * Cập nhật trạng thái hóa đơn (Admin)
 */
function updateBookingStatus() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id'] ?? 0);
    $status = $conn->real_escape_string($data['status'] ?? '');
    
    if ($id <= 0 || empty($status)) {
        echo json_encode(['success' => false, 'error' => 'Invalid booking ID or status']);
        return;
    }
    
    $validStatuses = ['Chờ thanh toán', 'Đã thanh toán', 'Đã hủy'];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        return;
    }
    
    $sql = "UPDATE bookings SET status = '$status' WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Booking status updated']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

/**
 * Lấy danh sách ghế đã đặt của một suất chiếu
 */
function getBookedSeats() {
    global $conn;
    
    $showtime_id = intval($_GET['showtime_id'] ?? 0);
    
    if ($showtime_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid showtime ID']);
        return;
    }
    
    $sql = "SELECT seat_number FROM booking_items 
            WHERE showtime_id = $showtime_id";
    
    $result = $conn->query($sql);
    $seats = [];
    
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row['seat_number'];
    }
    
    echo json_encode(['success' => true, 'data' => $seats]);
}
?>