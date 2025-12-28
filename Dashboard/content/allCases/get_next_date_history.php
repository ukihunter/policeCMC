<?php
session_start();
require_once('../../../config/db.php');

header('Content-Type: application/json');

$case_id = $_GET['case_id'] ?? null;

if (!$case_id) {
    echo json_encode(['success' => false, 'message' => 'Case ID is required']);
    exit;
}

try {
    // Get next date history for the case
    $sql = "SELECT 
            ndh.id,
            ndh.next_date,
            ndh.notes,
            ndh.created_at,
            u.full_name as created_by_name
            FROM next_date_history ndh
            LEFT JOIN users u ON ndh.created_by = u.id
            WHERE ndh.case_id = ?
            ORDER BY ndh.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $case_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode([
        'success' => true,
        'history' => $history,
        'count' => count($history)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
