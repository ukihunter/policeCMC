<?php
header("Content-Type: application/json");

// Database credentials
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "police_cms";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}

// SQL query (NO column specification)
$sql = "SELECT * FROM cases";
$result = $conn->query($sql);

$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Output raw JSON
echo json_encode($data, JSON_PRETTY_PRINT);

// Close connection
$conn->close();
