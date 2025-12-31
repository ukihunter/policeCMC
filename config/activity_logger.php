<?php
// Activity Logger Helper Functions
// This file contains functions to log all system activities

function logActivity($conn, $activity_type, $description = '', $case_id = null, $case_number = null)
{
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['full_name'] ?? 'Unknown User';

    // Get IP address
    $ip_address = getClientIP();

    // Get user agent
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $sql = "INSERT INTO activity_logs (user_id, user_name, activity_type, case_id, case_number, description, ip_address, user_agent, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssisss", $user_id, $user_name, $activity_type, $case_id, $case_number, $description, $ip_address, $user_agent);

    return $stmt->execute();
}

function getClientIP()
{
    $ip = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }

    return $ip;
}

function getBrowserInfo($user_agent)
{
    $browser = 'Unknown';

    if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
        $browser = 'Internet Explorer';
    } elseif (strpos($user_agent, 'Firefox') !== false) {
        $browser = 'Firefox';
    } elseif (strpos($user_agent, 'Chrome') !== false) {
        $browser = 'Chrome';
    } elseif (strpos($user_agent, 'Safari') !== false) {
        $browser = 'Safari';
    } elseif (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
        $browser = 'Opera';
    } elseif (strpos($user_agent, 'Edge') !== false) {
        $browser = 'Edge';
    }

    return $browser;
}

function getActivityIcon($activity_type)
{
    $icons = [
        'case_added' => 'fa-plus-circle',
        'case_edited' => 'fa-edit',
        'case_printed' => 'fa-print',
        'case_deleted' => 'fa-trash',
        'user_added' => 'fa-user-plus',
        'user_edited' => 'fa-user-edit',
        'user_deleted' => 'fa-user-times',
        'password_changed' => 'fa-key',
        'login' => 'fa-sign-in-alt',
        'logout' => 'fa-sign-out-alt'
    ];

    return $icons[$activity_type] ?? 'fa-circle';
}

function getActivityColor($activity_type)
{
    $colors = [
        'case_added' => '#28a745',
        'case_edited' => '#ffc107',
        'case_printed' => '#17a2b8',
        'case_deleted' => '#dc3545',
        'user_added' => '#28a745',
        'user_edited' => '#ffc107',
        'user_deleted' => '#dc3545',
        'password_changed' => '#6f42c1',
        'login' => '#007bff',
        'logout' => '#6c757d'
    ];

    return $colors[$activity_type] ?? '#6c757d';
}

function getActivityLabel($activity_type)
{
    $labels = [
        'case_added' => 'Case Added',
        'case_edited' => 'Case Edited',
        'case_printed' => 'Case Printed',
        'case_deleted' => 'Case Deleted',
        'user_added' => 'User Added',
        'user_edited' => 'User Edited',
        'user_deleted' => 'User Deleted',
        'password_changed' => 'Password Changed',
        'login' => 'Login',
        'logout' => 'Logout'
    ];

    return $labels[$activity_type] ?? ucfirst(str_replace('_', ' ', $activity_type));
}
