<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        'total' => 0,
        'pending' => 0,
        'ongoing' => 0,
        'closed' => 0
    ]);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

try {
    // Get total cases
    $totalQuery = "SELECT COUNT(*) as total FROM cases";
    $totalResult = $conn->query($totalQuery);
    $total = 0;
    if ($totalResult) {
        $totalRow = $totalResult->fetch_assoc();
        $total = $totalRow['total'];
    }

    // Get pending cases (by status)
    $pendingQuery = "SELECT COUNT(*) as pending FROM cases WHERE case_status = 'Pending'";
    $pendingResult = $conn->query($pendingQuery);
    $pending = 0;
    if ($pendingResult) {
        $pendingRow = $pendingResult->fetch_assoc();
        $pending = $pendingRow['pending'];
    }

    // Get ongoing cases (by status)
    $ongoingQuery = "SELECT COUNT(*) as ongoing FROM cases WHERE case_status = 'Ongoing'";
    $ongoingResult = $conn->query($ongoingQuery);
    $ongoing = 0;
    if ($ongoingResult) {
        $ongoingRow = $ongoingResult->fetch_assoc();
        $ongoing = $ongoingRow['ongoing'];
    }

    // Get closed cases (by status)
    $closedQuery = "SELECT COUNT(*) as closed FROM cases WHERE case_status = 'Closed'";
    $closedResult = $conn->query($closedQuery);
    $closed = 0;
    if ($closedResult) {
        $closedRow = $closedResult->fetch_assoc();
        $closed = $closedRow['closed'];
    }

    echo json_encode([
        'total' => (int)$total,
        'pending' => (int)$pending,
        'ongoing' => (int)$ongoing,
        'closed' => (int)$closed
    ]);
} catch (Exception $e) {
    echo json_encode([
        'total' => 0,
        'pending' => 0,
        'ongoing' => 0,
        'closed' => 0,
        'error' => $e->getMessage()
    ]);
}

$conn->close();
