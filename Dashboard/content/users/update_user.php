<?php
session_start();
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../config/activity_logger.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if user is admin
$current_user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();

if ($current_user['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Admin access required.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$user_id = intval($data['user_id'] ?? 0);
$full_name = trim($data['full_name'] ?? '');
$email = trim($data['email'] ?? '');
$position = trim($data['position'] ?? '');
$rank_title = trim($data['rank_title'] ?? '');
$role = $data['role'] ?? 'user';
$status = $data['status'] ?? 'active';

// Validation
if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

if (empty($full_name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Full name and email are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

if (!in_array($role, ['admin', 'user'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

if (!in_array($status, ['active', 'inactive'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

// Check if email already exists for another user
$check_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("si", $email, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already exists']);
    exit;
}

// Prevent user from changing their own status to inactive
if ($user_id == $current_user_id && $status === 'inactive') {
    echo json_encode(['success' => false, 'message' => 'You cannot deactivate your own account']);
    exit;
}

// Update user
$update_sql = "UPDATE users SET full_name = ?, email = ?, position = ?, rank_title = ?, role = ?, status = ?, updated_at = NOW() WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssssssi", $full_name, $email, $position, $rank_title, $role, $status, $user_id);

if ($update_stmt->execute()) {
    // Log activity
    logActivity(
        $conn,
        'user_edited',
        "Updated user: $full_name ($email)"
    );

    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating user']);
}

$conn->close();
