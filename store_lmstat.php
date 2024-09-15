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
// $output = shell_exec('cat lmstat_o.txt');
exec('cat /mnt/c/Users/bk19i/Programs/flexnet-watcher/lmstat_o.txt', $output);

// 出力をパースしてライセンス情報を抽出する（必要に応じて調整）
$licenses = [];
foreach ($output as $line) {
    if (strpos($line, "Users of") !== false) {
        // ライセンス名を取得
        $license_name = str_replace(":", "", trim(explode(" ",  $line)[2]));
        // print_r($license_name);
        $licenses[$license_name] = 0;
    } elseif (strpos($line, "start") !== false) {
        // ライセンス数をカウント
        $licenses[$license_name]++;
    }
}

print_r($licenses);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO license_usage (feature1, feature2) VALUES (?, ?)");
$stmt->bind_param("ii", $licenses["feature1"], $licenses["feature2"]);
$stmt->execute();





echo "Data stored successfully";

// Close connection
$stmt->close();
$conn->close();
?>

