<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../config/activity_logger.php';

// Verify admin role
$current_user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();

if ($current_user['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied. Admin only.']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

$user_id = $data['user_id'] ?? null;
$new_password = $data['new_password'] ?? null;

// Validation
if (!$user_id || !$new_password) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

if (strlen($new_password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit;
}

// Get the target user's name for the activity log
$sql = "SELECT full_name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$target_user = $result->fetch_assoc();

if (!$target_user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the password
$sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $hashed_password, $user_id);

if ($stmt->execute()) {
    // Log the activity
    logActivity(
        $conn,
        'password_changed',
        "Admin reset password for user: " . $target_user['full_name']
    );

    echo json_encode([
        'success' => true,
        'message' => 'Password reset successfully for ' . $target_user['full_name']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to reset password'
    ]);
}

$stmt->close();
$conn->close();
