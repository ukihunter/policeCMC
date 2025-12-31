<?php
session_start();
require_once __DIR__ . '/../../../config/db.php';

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

// Get filter parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 5; // Show only 5 activities per page to prevent lag
$offset = ($page - 1) * $per_page;

$type_filter = $_GET['type'] ?? 'all';
$user_filter = $_GET['user'] ?? 'all';
$date_filter = $_GET['date'] ?? '';

// Build query with filters
$where_conditions = [];
$params = [];
$types = '';

if ($type_filter !== 'all') {
    $where_conditions[] = "activity_type = ?";
    $params[] = $type_filter;
    $types .= 's';
}

if ($user_filter !== 'all') {
    $where_conditions[] = "user_id = ?";
    $params[] = intval($user_filter);
    $types .= 'i';
}

if (!empty($date_filter)) {
    $where_conditions[] = "DATE(created_at) = ?";
    $params[] = $date_filter;
    $types .= 's';
}

$where_clause = count($where_conditions) > 0 ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM activity_logs $where_clause";
if (count($params) > 0) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
} else {
    $count_result = $conn->query($count_sql);
}
$total_count = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_count / $per_page);

// Get activities
$sql = "SELECT id, user_id, user_name, activity_type, case_id, case_number, description, ip_address, user_agent, created_at 
        FROM activity_logs 
        $where_clause 
        ORDER BY created_at DESC 
        LIMIT ? OFFSET ?";

$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($sql);
if (count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$activities = [];
while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}

// Get statistics
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN activity_type = 'case_added' THEN 1 ELSE 0 END) as case_added,
    SUM(CASE WHEN activity_type = 'case_edited' THEN 1 ELSE 0 END) as case_edited,
    SUM(CASE WHEN activity_type = 'case_printed' THEN 1 ELSE 0 END) as case_printed,
    SUM(CASE WHEN activity_type = 'case_deleted' THEN 1 ELSE 0 END) as case_deleted,
    SUM(CASE WHEN activity_type = 'user_added' THEN 1 ELSE 0 END) as user_added,
    SUM(CASE WHEN activity_type = 'user_edited' THEN 1 ELSE 0 END) as user_edited,
    SUM(CASE WHEN activity_type = 'user_deleted' THEN 1 ELSE 0 END) as user_deleted
    FROM activity_logs
    $where_clause";

if (count($where_conditions) > 0) {
    $stats_stmt = $conn->prepare($stats_sql);
    // Remove the last two parameters (LIMIT and OFFSET) for stats query
    $stats_params = array_slice($params, 0, -2);
    $stats_types = substr($types, 0, -2);
    if (count($stats_params) > 0) {
        $stats_stmt->bind_param($stats_types, ...$stats_params);
    }
    $stats_stmt->execute();
    $stats_result = $stats_stmt->get_result();
} else {
    $stats_result = $conn->query($stats_sql);
}

$stats = $stats_result->fetch_assoc();

echo json_encode([
    'success' => true,
    'activities' => $activities,
    'stats' => $stats,
    'page' => $page,
    'total_pages' => $total_pages,
    'total_count' => $total_count
]);

$conn->close();
