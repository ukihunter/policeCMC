<?php
session_start();
require_once('../../../config/db.php');

header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$current_user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();

if ($current_user['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit;
}

try {
    // Get database credentials from config
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'police_cms';

    // Save to project's db/backups folder (web-accessible for download)
    // This works on any PC regardless of username or Apache service account
    $project_root = dirname(dirname(dirname(dirname(__FILE__))));
    $backup_folder = $project_root . '\\db\\backups';

    // Create backup folder if it doesn't exist
    if (!is_dir($backup_folder)) {
        if (!mkdir($backup_folder, 0777, true)) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create backup folder at: ' . $backup_folder
            ]);
            exit;
        }
    }

    // Generate backup filename with timestamp
    $backup_file = $backup_folder . '\\police_cms_backup_' . date('Y-m-d_H-i-s') . '.sql';

    // Use mysqldump to create backup
    $mysqldump_path = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

    if (!file_exists($mysqldump_path)) {
        echo json_encode([
            'success' => false,
            'message' => 'mysqldump.exe not found at: ' . $mysqldump_path
        ]);
        exit;
    }

    // Build mysqldump command with proper escaping
    // Note: For XAMPP default, password is empty so we use --password= (with equals but no value)
    $command = '"' . $mysqldump_path . '" --user=' . $db_user . ' --password= --host=' . $db_host . ' ' . $db_name . ' --result-file="' . $backup_file . '"';

    // Execute backup command
    exec($command . ' 2>&1', $output, $return_var);

    // Check if backup file was created and has content
    if (!file_exists($backup_file)) {
        echo json_encode([
            'success' => false,
            'message' => 'Backup file was not created. Error: ' . implode('\n', $output),
            'command' => $command
        ]);
        exit;
    }

    if (filesize($backup_file) < 100) {
        echo json_encode([
            'success' => false,
            'message' => 'Backup file is empty or too small. Error: ' . implode('\n', $output)
        ]);
        unlink($backup_file); // Delete empty file
        exit;
    }

    // Get file size for display
    $file_size = filesize($backup_file);
    $file_size_mb = round($file_size / 1024 / 1024, 2);

    // Create download path relative to web root
    $download_path = '/police/db/backups/' . basename($backup_file);

    // Log the backup activity
    require_once('../../../config/activity_logger.php');
    $details = "Desktop backup created: " . basename($backup_file) . " (" . $file_size_mb . " MB)";
    logActivity($conn, 'database_backup', $details);

    echo json_encode([
        'success' => true,
        'message' => 'Database backup created successfully',
        'filename' => basename($backup_file),
        'path' => $backup_folder,
        'download_url' => $download_path,
        'size' => $file_size_mb . ' MB'
    ]);
} catch (Exception $e) {
    error_log("Backup error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error creating backup: ' . $e->getMessage()
    ]);
}

$conn->close();
