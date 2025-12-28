<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'cases' => []]);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

try {
    // Get today's cases
    $query = "SELECT id, case_number, information_book, register_number, opens, case_status, created_at 
              FROM cases 
              WHERE DATE(created_at) = CURDATE()
              ORDER BY created_at DESC 
              LIMIT 10";

    $result = $conn->query($query);
    $cases = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cases[] = $row;
        }
    }

    echo json_encode([
        'success' => true,
        'cases' => $cases,
        'count' => count($cases)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'cases' => [],
        'error' => $e->getMessage()
    ]);
}

$conn->close();
