<?php
$servername = "localhost";
$username = "flexnet_watcher";
$password = "1111";
$dbname = "flexnet_watcher";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "truncate license_usage;";
$stmt = $conn->prepare($sql);
$stmt->execute();