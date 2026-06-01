<?php
/**
 * API: Quản lý Suất chiếu (Showtimes)
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($action) {
    case 'list':
        getShowtimes();
        break;
    case 'by-movie':
        getShowtimesByMovie();
        break;
    case 'by-date':
        getShowtimesByDate();
        break;
    case 'create':
        createShowtime();
        break;
    case 'update':
        updateShowtime();
        break;
    case 'delete':
        deleteShowtime();
        break;
    default:
        echo json_encode(['error' => 'Action not found']);
}

/**
 * Lấy danh sách suất chiếu
 */
function getShowtimes() {
    global $conn;
    
    $sql = "SELECT s.*, m.title, m.duration FROM showtimes s 
            JOIN movies m ON s.movie_id = m.id 
            ORDER BY s.show_date, s.start_time";
    
    $result = $conn->query($sql);
    $showtimes = [];
    
    while ($row = $result->fetch_assoc()) {
        $showtimes[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $showtimes]);
}

/**
 * Lấy suất chiếu theo phim và ngày
 */
function getShowtimesByMovie() {
    global $conn;
    
    $movie_id = intval($_GET['movie_id'] ?? 0);
    $show_date = $conn->real_escape_string($_GET['show_date'] ?? '');
    
    if ($movie_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid movie ID']);
        return;
    }
    
    $sql = "SELECT s.*, m.title FROM showtimes s 
            JOIN movies m ON s.movie_id = m.id 
            WHERE s.movie_id = $movie_id";
    
    if (!empty($show_date)) {
        $sql .= " AND s.show_date = '$show_date'";
    }
    
    $sql .= " ORDER BY s.start_time";
    
    $result = $conn->query($sql);
    $showtimes = [];
    
    while ($row = $result->fetch_assoc()) {
        $showtimes[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $showtimes]);
}

/**
 * Lấy suất chiếu theo ngày
 */
function getShowtimesByDate() {
    global $conn;
    
    $show_date = $conn->real_escape_string($_GET['show_date'] ?? '');
    
    if (empty($show_date)) {
        echo json_encode(['success' => false, 'error' => 'Show date required']);
        return;
    }
    
    $sql = "SELECT s.*, m.title FROM showtimes s 
            JOIN movies m ON s.movie_id = m.id 
            WHERE s.show_date = '$show_date' 
            ORDER BY s.start_time";
    
    $result = $conn->query($sql);
    $showtimes = [];
    
    while ($row = $result->fetch_assoc()) {
        $showtimes[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $showtimes]);
}

/**
 * Tạo suất chiếu mới (Admin) - Kiểm tra trùng lịch
 */
function createShowtime() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $movie_id = intval($data['movie_id'] ?? 0);
    $room_name = $conn->real_escape_string($data['room_name'] ?? '');
    $show_date = $conn->real_escape_string($data['show_date'] ?? '');
    $start_time = $conn->real_escape_string($data['start_time'] ?? '');
    $ticket_price = floatval($data['ticket_price'] ?? 0);
    
    if ($movie_id <= 0 || empty($room_name) || empty($show_date) || empty($start_time) || $ticket_price <= 0) {
        echo json_encode(['success' => false, 'error' => 'Missing or invalid fields']);
        return;
    }
    
    // Kiểm tra trùng lịch
    $checkSql = "SELECT id FROM showtimes 
                WHERE room_name = '$room_name' 
                AND show_date = '$show_date' 
                AND start_time = '$start_time'";
    
    $result = $conn->query($checkSql);
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Phòng chiếu đã có phim khác vào khung giờ này']);
        return;
    }
    
    $sql = "INSERT INTO showtimes (movie_id, room_name, show_date, start_time, ticket_price) 
            VALUES ($movie_id, '$room_name', '$show_date', '$start_time', $ticket_price)";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Showtime created', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

/**
 * Cập nhật suất chiếu (Admin)
 */
function updateShowtime() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid showtime ID']);
        return;
    }
    
    $ticket_price = floatval($data['ticket_price'] ?? 0);
    
    if ($ticket_price <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid ticket price']);
        return;
    }
    
    $sql = "UPDATE showtimes SET ticket_price = $ticket_price WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Showtime updated']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

/**
 * Xóa suất chiếu (Admin)
 */
function deleteShowtime() {
    global $conn;
    
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid showtime ID']);
        return;
    }
    
    $sql = "DELETE FROM showtimes WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Showtime deleted']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>