<?php
session_start();
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../config/activity_logger.php';

header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

// Get user role
$user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied. Admin only.']);
    exit;
}

try {
    // Get the police station from POST data
    $police_station = $_POST['police_station'] ?? '';

    if (empty($police_station)) {
        echo json_encode(['success' => false, 'message' => 'Police station is required']);
        exit;
    }

    // Update or insert the setting
    $sql = "INSERT INTO system_settings (setting_key, setting_value, updated_by) 
            VALUES ('police_station', ?, ?) 
            ON DUPLICATE KEY UPDATE setting_value = ?, updated_by = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $police_station, $user_id, $police_station, $user_id);

    if ($stmt->execute()) {
        // Log the activity
        logActivity(
            $conn,
            $user_id,
            'user_edited',
            null,
            null,
            "Updated system setting: Police Station = $police_station"
        );

        echo json_encode([
            'success' => true,
            'message' => 'Police station updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update police station'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

$conn->close();
