<?php
session_start();
require_once('../../../config/db.php');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'No case ID provided']);
    exit;
}

$caseId = intval($_GET['id']);

$sql = "SELECT c.*, 
        u1.full_name as created_by_name,
        u2.full_name as updated_by_name
        FROM cases c
        LEFT JOIN users u1 ON c.created_by = u1.id
        LEFT JOIN users u2 ON c.updated_by = u2.id
        WHERE c.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $caseId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'case' => $row
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Case not found'
    ]);
}

$stmt->close();
$conn->close();
