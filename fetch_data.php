<?php
$servername = "localhost";
$username = "lmstat-watcher";
$password = "1111";
$dbname = "lmstat_watcher";

// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);
// $conn = new \MySQLi($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Query to fetch historical data
$sql = "SELECT timestamp, output FROM license_usage ORDER BY timestamp DESC LIMIT 100";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Output data in JSON format
header('Content-Type: application/json');
echo json_encode($data);


$conn->close();
?>
