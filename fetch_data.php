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

// fetch
$license_server_name = "server1";
$data = array(
    "server1" => array("feature1" => array(), "feature2" => array()),
    "server2" => array("feature1" => array(), "feature2" => array())
);
$sql = "SELECT timestamp, total_licenses, used_licenses FROM license_usage WHERE license_server = ? AND feature = ? ORDER BY timestamp ASC LIMIT 100";
foreach($data as $server_name => $features){
    foreach($features as $feature_name => $feature_data){
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $server_name, $feature_name);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $data[$server_name][$feature_name][] = $row;
        }
        $stmt->close();
    }  
}

echo json_encode($data);
// echo json_encode($data["feature1"]);
// echo json_encode($data["feature2"]);


$conn->close();
?>
