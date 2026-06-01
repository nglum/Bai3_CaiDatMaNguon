<?php
/**
 * API: Quản lý Phim (Movies)
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($action) {
    case 'list':
        getMovies();
        break;
    case 'get':
        getMovieById();
        break;
    case 'create':
        createMovie();
        break;
    case 'update':
        updateMovie();
        break;
    case 'delete':
        deleteMovie();
        break;
    default:
        echo json_encode(['error' => 'Action not found']);
}

/**
 * Lấy danh sách phim với filter
 */
function getMovies() {
    global $conn;
    
    $genre_id = $_GET['genre_id'] ?? '';
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    
    $sql = "SELECT m.*, g.name as genre_name FROM movies m 
            LEFT JOIN genres g ON m.genre_id = g.id WHERE 1=1";
    
    if (!empty($genre_id)) {
        $genre_id = intval($genre_id);
        $sql .= " AND m.genre_id = $genre_id";
    }
    
    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $sql .= " AND m.title LIKE '%$search%'";
    }
    
    if (!empty($status)) {
        $status = $conn->real_escape_string($status);
        $sql .= " AND m.status = '$status'";
    }
    
    $sql .= " ORDER BY m.created_at DESC";
    
    $result = $conn->query($sql);
    $movies = [];
    
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $movies]);
}

/**
 * Lấy chi tiết phim theo ID
 */
function getMovieById() {
    global $conn;
    
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid movie ID']);
        return;
    }
    
    $sql = "SELECT m.*, g.name as genre_name FROM movies m 
            LEFT JOIN genres g ON m.genre_id = g.id WHERE m.id = $id";
    
    $result = $conn->query($sql);
    $movie = $result->fetch_assoc();
    
    if ($movie) {
        echo json_encode(['success' => true, 'data' => $movie]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Movie not found']);
    }
}

/**
 * Tạo phim mới (Admin)
 */
function createMovie() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $title = $conn->real_escape_string($data['title'] ?? '');
    $duration = intval($data['duration'] ?? 0);
    $description = $conn->real_escape_string($data['description'] ?? '');
    $image_url = $conn->real_escape_string($data['image_url'] ?? '');
    $genre_id = intval($data['genre_id'] ?? 0);
    $status = $conn->real_escape_string($data['status'] ?? 'Đang chiếu');
    
    if (empty($title) || $duration <= 0 || $genre_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Missing or invalid fields']);
        return;
    }
    
    $sql = "INSERT INTO movies (title, duration, description, image_url, genre_id, status) 
            VALUES ('$title', $duration, '$description', '$image_url', $genre_id, '$status')";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Movie created', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

/**
 * Cập nhật phim (Admin)
 */
function updateMovie() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid movie ID']);
        return;
    }
    
    $title = $conn->real_escape_string($data['title'] ?? '');
    $duration = intval($data['duration'] ?? 0);
    $description = $conn->real_escape_string($data['description'] ?? '');
    $image_url = $conn->real_escape_string($data['image_url'] ?? '');
    $genre_id = intval($data['genre_id'] ?? 0);
    $status = $conn->real_escape_string($data['status'] ?? '');
    
    $sql = "UPDATE movies SET ";
    $updates = [];
    
    if (!empty($title)) $updates[] = "title = '$title'";
    if ($duration > 0) $updates[] = "duration = $duration";
    if (!empty($description)) $updates[] = "description = '$description'";
    if (!empty($image_url)) $updates[] = "image_url = '$image_url'";
    if ($genre_id > 0) $updates[] = "genre_id = $genre_id";
    if (!empty($status)) $updates[] = "status = '$status'";
    
    if (empty($updates)) {
        echo json_encode(['success' => false, 'error' => 'No fields to update']);
        return;
    }
    
    $sql .= implode(', ', $updates) . " WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Movie updated']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

/**
 * Xóa phim (Admin)
 */
function deleteMovie() {
    global $conn;
    
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid movie ID']);
        return;
    }
    
    $sql = "DELETE FROM movies WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Movie deleted']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>