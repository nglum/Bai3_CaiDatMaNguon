<?php
/**
 * API: Quản lý Thể loại (Genres)
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        getGenres();
        break;
    case 'create':
        createGenre();
        break;
    case 'update':
        updateGenre();
        break;
    case 'delete':
        deleteGenre();
        break;
    default:
        echo json_encode(['error' => 'Action not found']);
}

/**
 * Lấy danh sách thể loại
 */
function getGenres() {
    global $conn;
    
    $sql = "SELECT * FROM genres ORDER BY name";
    $result = $conn->query($sql);
    $genres = [];
    
    while ($row = $result->fetch_assoc()) {
        $genres[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $genres]);
}

/**
 * Tạo thể loại mới (Admin)
 */
function createGenre() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $conn->real_escape_string($data['name'] ?? '');
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Genre name is required']);
        return;
    }
    
    $sql = "INSERT INTO genres (name) VALUES ('$name')";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Genre created', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

/**
 * Cập nhật thể loại (Admin)
 */
function updateGenre() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id'] ?? 0);
    $name = $conn->real_escape_string($data['name'] ?? '');
    
    if ($id <= 0 || empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Invalid genre ID or name']);
        return;
    }
    
    $sql = "UPDATE genres SET name = '$name' WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Genre updated']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

/**
 * Xóa thể loại (Admin)
 */
function deleteGenre() {
    global $conn;
    
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid genre ID']);
        return;
    }
    
    $sql = "DELETE FROM genres WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Genre deleted']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>