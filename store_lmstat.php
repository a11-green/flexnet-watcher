<?php
$servername = "localhost";
$username = "lmstat-watcher";
$password = "1111";
$dbname = "lmstat_watcher";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Execute lmstat command and capture output
// $output = shell_exec('lmstat -a');
$output = shell_exec('cat lmstat_o.txt');


// Prepare and bind
$stmt = $conn->prepare("INSERT INTO license_usage (output) VALUES (?)");
$stmt->bind_param("s", $output);

// Execute the statement
$stmt->execute();

echo "Data stored successfully";

// Close connection
$stmt->close();
$conn->close();
?>

