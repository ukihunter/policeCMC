<?php
session_start();
require_once('../../../config/db.php');
require_once('../../../config/activity_logger.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$case_id = $data['case_id'] ?? null;
$case_number = $data['case_number'] ?? '';
$print_type = $data['print_type'] ?? 'single'; // single or bulk

if (empty($case_number)) {
    echo json_encode(['success' => false, 'message' => 'Case number required']);
    exit;
}

$description = $print_type === 'bulk' ?
    "Printed multiple cases including: $case_number" :
    "Printed case: $case_number";

// Log activity
$logged = logActivity(
    $conn,
    'case_printed',
    $description,
    $case_id,
    $case_number
);

if ($logged) {
    echo json_encode(['success' => true, 'message' => 'Print activity logged']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to log activity']);
}

$conn->close();
