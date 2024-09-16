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

// Query to fetch historical data
// $sql = "SELECT timestamp, feature1, feature2  FROM license_usage ORDER BY timestamp DESC LIMIT 100";
// $result = $conn->query($sql);

// $data = [];
// if ($result->num_rows > 0) {
//     while ($row = $result-> fetch_assoc()) {
//         $data[] = $row;
//     }
// }

// // Output data in JSON format
// header('Content-Type: application/json');
// echo json_encode($data);


// $conn->close();

// フィーチャー名を取得（POSTリクエストから取得できるように）
$data = array("feature1" => array(), "feature2" => array());
$sql = "SELECT timestamp, usage_count FROM license_usage WHERE feature = ? ORDER BY timestamp ASC LIMIT 100";
foreach($data as $feature => $feature_data){
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $feature);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $data[$feature][] = $row;
    }
    $stmt->close();
}
// print_r($data);
echo json_encode($data);


$conn->close();
?>
