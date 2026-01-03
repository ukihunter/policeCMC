<?php
session_start();
require_once('../../../config/db.php');
require_once('../../../config/activity_logger.php');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get form data
$case_number = trim($_POST['case_number'] ?? '');
$previous_date = !empty($_POST['previous_date']) ? $_POST['previous_date'] : null;
$information_book = trim($_POST['information_book'] ?? '');
$register_number = trim($_POST['register_number'] ?? '');
$date_produce_b_report = !empty($_POST['date_produce_b_report']) ? $_POST['date_produce_b_report'] : null;
$date_produce_plant = !empty($_POST['date_produce_plant']) ? $_POST['date_produce_plant'] : null;
$opens = trim($_POST['opens'] ?? '');
// Handle ENUM fields - convert empty strings to NULL
$attorney_general_advice = (!empty($_POST['attorney_general_advice']) && $_POST['attorney_general_advice'] !== '') ? $_POST['attorney_general_advice'] : null;

// Handle production registers (arrays) - store as plain text with line breaks
$production_registers = $_POST['production_registers'] ?? [];
$production_register_lines = [];
for ($i = 0; $i < count($production_registers); $i++) {
    if (!empty($production_registers[$i])) {
        $production_register_lines[] = trim($production_registers[$i]);
    }
}
$production_register_number = implode("\n", $production_register_lines);

$date_handover_court = !empty($_POST['date_handover_court']) ? $_POST['date_handover_court'] : null;
// Handle ENUM fields - convert empty strings to NULL
$receival_memorandum = (!empty($_POST['receival_memorandum']) && $_POST['receival_memorandum'] !== '') ? $_POST['receival_memorandum'] : null;
$analyst_report = (!empty($_POST['analyst_report']) && $_POST['analyst_report'] !== '') ? $_POST['analyst_report'] : null;

// Handle suspects (arrays)
$suspect_names = $_POST['suspect_names'] ?? [];
$suspect_addresses = $_POST['suspect_addresses'] ?? [];
$suspect_ic_numbers = $_POST['suspect_ic_numbers'] ?? [];
$suspects_data = [];
for ($i = 0; $i < count($suspect_names); $i++) {
    if (!empty($suspect_names[$i])) {
        $suspects_data[] = [
            'name' => $suspect_names[$i],
            'address' => $suspect_addresses[$i] ?? '',
            'ic' => $suspect_ic_numbers[$i] ?? ''
        ];
    }
}
$suspect_data = json_encode($suspects_data);

// Handle witnesses (arrays)
$witness_names = $_POST['witness_names'] ?? [];
$witness_addresses = $_POST['witness_addresses'] ?? [];
$witness_ic_numbers = $_POST['witness_ic_numbers'] ?? [];
$witnesses_data = [];
for ($i = 0; $i < count($witness_names); $i++) {
    if (!empty($witness_names[$i])) {
        $witnesses_data[] = [
            'name' => $witness_names[$i],
            'address' => $witness_addresses[$i] ?? '',
            'ic' => $witness_ic_numbers[$i] ?? ''
        ];
    }
}
$witness_data = json_encode($witnesses_data);

$progress = trim($_POST['progress'] ?? '');
$results = trim($_POST['results'] ?? '');
$case_status = !empty($_POST['case_status']) ? $_POST['case_status'] : 'Ongoing';
$next_date = null; // Always null
$created_by = $_SESSION['user_id'];

// Validate required fields
if (empty($case_number) || empty($previous_date) || empty($information_book) || empty($register_number)) {
    echo json_encode(['success' => false, 'message' => 'Case number, previous date, information book, and register number are required']);
    exit;
}

// Check if case number already exists
$check_sql = "SELECT id FROM cases WHERE case_number = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $case_number);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Case number already exists']);
    exit;
}

// Prepare SQL statement
$sql = "INSERT INTO cases (
    case_number, 
    previous_date, 
    information_book, 
    register_number, 
    date_produce_b_report, 
    date_produce_plant, 
    opens, 
    attorney_general_advice, 
    production_register_number, 
    date_handover_court, 
    receival_memorandum, 
    analyst_report, 
    suspect_data, 
    witness_data, 
    progress, 
    results, 
    case_status,
    next_date, 
    created_by,
    updated_by
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param(
    "ssssssssssssssssssii",
    $case_number,
    $previous_date,
    $information_book,
    $register_number,
    $date_produce_b_report,
    $date_produce_plant,
    $opens,
    $attorney_general_advice,
    $production_register_number,
    $date_handover_court,
    $receival_memorandum,
    $analyst_report,
    $suspect_data,
    $witness_data,
    $progress,
    $results,
    $case_status,
    $next_date,
    $created_by,
    $created_by
);

// Execute the statement
if ($stmt->execute()) {
    $case_id = $stmt->insert_id;

    // Log activity
    logActivity(
        $conn,
        'case_added',
        "Added new case: $case_number",
        $case_id,
        $case_number
    );

    echo json_encode([
        'success' => true,
        'message' => 'Case successfully added with Case Number: ' . $case_number,
        'case_id' => $case_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding case: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
