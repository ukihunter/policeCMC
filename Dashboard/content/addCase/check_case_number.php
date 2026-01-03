<?php
session_start();
require_once('../../../config/db.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$case_number = trim($_GET['case_number'] ?? '');

if (empty($case_number)) {
    echo json_encode(['exists' => false]);
    exit;
}

// Check if case number exists
$sql = "SELECT id FROM cases WHERE case_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $case_number);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode(['exists' => $result->num_rows > 0]);

$stmt->close();
$conn->close();
?>
