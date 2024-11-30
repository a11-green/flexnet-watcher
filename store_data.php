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

// トランザクションを開始
$conn->begin_transaction();

// Execute lmstat command and capture output
// $output = shell_exec('lmstat -a');
// $output = shell_exec('cat lmstat_o.txt');
exec('cat /mnt/c/Users/bk19i/Programs/flexnet-watcher/lmstat_o_server1.txt', $output1);
exec('cat /mnt/c/Users/bk19i/Programs/flexnet-watcher/lmstat_o_server2.txt', $output2);

// 出力をパースしてライセンス情報を抽出する（必要に応じて調整）
function get_license_info($lmstat_output) {
    $licenses = [];
    foreach ($lmstat_output as $line) {
        if (strpos($line, "License server status:") !== false) {
            // ライセンスサーバのホスト名を取得
            if (preg_match('/License server status:\s*\d+@([a-zA-Z0-9.-]+)/', $line, $matches)) {
                $hostname = $matches[1];
            }
        } 
        if (strpos($line, "Users of") !== false) {
            // ライセンス名を取得
            $feature_name = str_replace(":", "", trim(explode(" ",  $line)[2]));
            // Total と Used のライセンス数を抽出
            if (preg_match('/Total of (\d+) licenses issued; *(\d+) licenses in use/', $line, $matches)) {
                $total_licenses = (int)$matches[1];
                $used_licenses = (int)$matches[2];
            } else {
                $total_licenses = 0;
                $used_licenses = 0;
            }

            $licenses[$feature_name] = [
                'total' => $total_licenses,
                'used' => $used_licenses
            ];
        } 
        
    }
    return [
        "hostname" => $hostname, 
        "licenses" => $licenses
    ];
}

$license_info1 = get_license_info($output1);
$license_info2 = get_license_info($output2);

print_r($license_info1);
print_r($license_info2);

// 抽出したライセンス情報をデータベースに記録する
function store_license_info($conn, $license_info) {
    try {
        foreach($license_info["licenses"] as $feature => $licenses){
            $stmt = $conn->prepare("INSERT INTO license_usage (license_server, feature, total_licenses, used_licenses) VALUES (?, ?, ?, ?)");
            $stmt->bind_param(
                "ssii", 
                $license_info["hostname"], 
                $feature, 
                $licenses["total"],
                $licenses["used"]
            );
            $stmt->execute();
            $stmt->close();
            echo "Data stored successfully\n";
        }
    }
    catch (Exception $e) {
        // エラー発生時にロールバック
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

store_license_info($conn, $license_info1);
store_license_info($conn, $license_info2);

$conn->commit();
$conn->close();





?>

