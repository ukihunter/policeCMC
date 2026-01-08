<?php
session_start();
require_once('../../../config/db.php');
require_once('../../../config/activity_logger.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if ID is provided
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Case ID is required']);
    exit;
}

$case_id = (int)$_POST['id'];
$user_id = $_SESSION['user_id'];

try {
    // Start transaction
    $conn->begin_transaction();

    // First, get case details for logging
    $stmt = $conn->prepare("SELECT case_number, information_book, register_number FROM cases WHERE id = ?");
    $stmt->bind_param("i", $case_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Case not found']);
        exit;
    }

    $case = $result->fetch_assoc();
    $stmt->close();

    // Log the deletion activity BEFORE deleting (while case still exists)
    try {
        $details = "Deleted case: {$case['case_number']} (Info Book: {$case['information_book']}, Register: {$case['register_number']})";
        logActivity($conn, 'case_deleted', $details, null, $case['case_number']);
    } catch (Exception $log_error) {
        // Log activity error but continue with deletion
        error_log("Error logging delete activity: " . $log_error->getMessage());
    }

    // Delete related next date history records first (foreign key constraint)
    $stmt = $conn->prepare("DELETE FROM next_date_history WHERE case_id = ?");
    $stmt->bind_param("i", $case_id);
    $stmt->execute();
    $stmt->close();

    // Delete the case
    $stmt = $conn->prepare("DELETE FROM cases WHERE id = ?");
    $stmt->bind_param("i", $case_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to delete case']);
        exit;
    }

    $stmt->close();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Case deleted successfully',
        'case_number' => $case['case_number']
    ]);
} catch (Exception $e) {
    // Rollback on error
    if ($conn->connect_errno === 0) {
        $conn->rollback();
    }
    error_log("Error deleting case: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
}

if ($conn && !$conn->connect_errno) {
    $conn->close();
}
