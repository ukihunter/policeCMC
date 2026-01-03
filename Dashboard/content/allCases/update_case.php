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

$user_id = $_SESSION["user_id"];

// Get POST data
$case_id = $_POST['case_id'] ?? null;
$case_number = $_POST['case_number'] ?? '';
$previous_date = $_POST['previous_date'] ?? '';
$information_book = $_POST['information_book'] ?? '';
$register_number = $_POST['register_number'] ?? '';
// Handle date fields - convert empty strings to NULL
$date_produce_b_report = (!empty($_POST['date_produce_b_report']) && $_POST['date_produce_b_report'] !== '') ? $_POST['date_produce_b_report'] : null;
$date_produce_plant = (!empty($_POST['date_produce_plant']) && $_POST['date_produce_plant'] !== '') ? $_POST['date_produce_plant'] : null;
$date_handover_court = (!empty($_POST['date_handover_court']) && $_POST['date_handover_court'] !== '') ? $_POST['date_handover_court'] : null;
$next_date = (!empty($_POST['next_date']) && $_POST['next_date'] !== '') ? $_POST['next_date'] : null;
$next_date_notes = $_POST['next_date_notes'] ?? '';
$opens = $_POST['opens'] ?? '';
// Handle ENUM fields - convert empty strings to NULL
$attorney_general_advice = (!empty($_POST['attorney_general_advice']) && $_POST['attorney_general_advice'] !== '') ? $_POST['attorney_general_advice'] : null;
$receival_memorandum = (!empty($_POST['receival_memorandum']) && $_POST['receival_memorandum'] !== '') ? $_POST['receival_memorandum'] : null;
$analyst_report = (!empty($_POST['analyst_report']) && $_POST['analyst_report'] !== '') ? $_POST['analyst_report'] : null;
$production_register_number = $_POST['production_register_number'] ?? '';
$progress = $_POST['progress'] ?? '';
$results = $_POST['results'] ?? '';
$case_status = !empty($_POST['case_status']) ? $_POST['case_status'] : 'Ongoing';

// Get suspects and witnesses arrays
$suspects = $_POST['suspects'] ?? [];
$witnesses = $_POST['witnesses'] ?? [];

// Clean and validate suspects data
$suspect_data = [];
foreach ($suspects as $suspect) {
    if (!empty($suspect['name']) || !empty($suspect['ic']) || !empty($suspect['address'])) {
        $suspect_data[] = [
            'name' => trim($suspect['name'] ?? ''),
            'ic' => trim($suspect['ic'] ?? ''),
            'address' => trim($suspect['address'] ?? '')
        ];
    }
}

// Clean and validate witnesses data
$witness_data = [];
foreach ($witnesses as $witness) {
    if (!empty($witness['name']) || !empty($witness['ic']) || !empty($witness['address'])) {
        $witness_data[] = [
            'name' => trim($witness['name'] ?? ''),
            'ic' => trim($witness['ic'] ?? ''),
            'address' => trim($witness['address'] ?? '')
        ];
    }
}

// Convert to JSON
$suspect_json = json_encode($suspect_data);
$witness_json = json_encode($witness_data);

// Validate required fields
if (empty($case_id) || empty($case_number) || empty($previous_date) || empty($information_book) || empty($register_number)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Convert empty strings to NULL for optional date fields
$date_produce_b_report = !empty($date_produce_b_report) ? $date_produce_b_report : null;
$date_produce_plant = !empty($date_produce_plant) ? $date_produce_plant : null;
$date_handover_court = !empty($date_handover_court) ? $date_handover_court : null;
$next_date = !empty($next_date) ? $next_date : null;

// Convert empty strings to NULL for enum fields
$attorney_general_advice = !empty($attorney_general_advice) ? $attorney_general_advice : null;
$receival_memorandum = !empty($receival_memorandum) ? $receival_memorandum : null;
$analyst_report = !empty($analyst_report) ? $analyst_report : null;

try {
    // Start transaction
    $conn->begin_transaction();

    // Get the current next_date before updating
    $check_sql = "SELECT next_date FROM cases WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $case_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $current_case = $result->fetch_assoc();
    $old_next_date = $current_case['next_date'] ?? null;
    $check_stmt->close();

    // Update the case
    $sql = "UPDATE cases SET 
            case_number = ?,
            previous_date = ?,
            information_book = ?,
            register_number = ?,
            date_produce_b_report = ?,
            date_produce_plant = ?,
            date_handover_court = ?,
            next_date = ?,
            opens = ?,
            attorney_general_advice = ?,
            receival_memorandum = ?,
            analyst_report = ?,
            production_register_number = ?,
            progress = ?,
            results = ?,
            case_status = ?,
            suspect_data = ?,
            witness_data = ?,
            updated_by = ?,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssssssii",
        $case_number,
        $previous_date,
        $information_book,
        $register_number,
        $date_produce_b_report,
        $date_produce_plant,
        $date_handover_court,
        $next_date,
        $opens,
        $attorney_general_advice,
        $receival_memorandum,
        $analyst_report,
        $production_register_number,
        $progress,
        $results,
        $case_status,
        $suspect_json,
        $witness_json,
        $user_id,
        $case_id
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to update case: " . $stmt->error);
    }
    $stmt->close();

    // If next_date was changed and is not null, add to history
    if ($next_date !== null && $next_date !== $old_next_date) {
        $history_sql = "INSERT INTO next_date_history (case_id, next_date, notes, created_by) VALUES (?, ?, ?, ?)";
        $history_stmt = $conn->prepare($history_sql);
        $history_stmt->bind_param("issi", $case_id, $next_date, $next_date_notes, $user_id);

        if (!$history_stmt->execute()) {
            throw new Exception("Failed to save next date history: " . $history_stmt->error);
        }
        $history_stmt->close();
    }

    // Commit transaction
    $conn->commit();

    // Return the updated case data for UI update
    $updated_case = [
        'id' => $case_id,
        'case_number' => $case_number,
        'previous_date' => $previous_date,
        'information_book' => $information_book,
        'register_number' => $register_number,
        'date_produce_b_report' => $date_produce_b_report,
        'date_produce_plant' => $date_produce_plant,
        'date_handover_court' => $date_handover_court,
        'next_date' => $next_date,
        'opens' => $opens,
        'attorney_general_advice' => $attorney_general_advice,
        'receival_memorandum' => $receival_memorandum,
        'analyst_report' => $analyst_report,
        'production_register_number' => $production_register_number,
        'progress' => $progress,
        'results' => $results,
        'case_status' => $case_status,
        'suspect_data' => $suspect_json,
        'witness_data' => $witness_json
    ];

    // Log activity
    logActivity(
        $conn,
        'case_edited',
        "Edited case: $case_number",
        $case_id,
        $case_number
    );

    echo json_encode([
        'success' => true,
        'message' => 'Case updated successfully',
        'next_date_changed' => ($next_date !== $old_next_date),
        'case' => $updated_case
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
