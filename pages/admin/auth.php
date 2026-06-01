<?php
/**
 * Auth handler for admin login
 */
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Hardcoded credentials (in production, use database)
if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['admin_id'] = 1;
    $_SESSION['admin_user'] = 'admin';
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
}
?>