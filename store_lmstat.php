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

// トランザクションを開始
$conn->begin_transaction();

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

try{
    foreach($licenses as $key => $data){
        // 前回の観測データを取得
        $sql = "SELECT usage_count FROM license_usage WHERE feature = ? ORDER BY timestamp DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $stmt->bind_result($prev_usage_count);
        $stmt->fetch();
        $stmt->close();
        // 今回のデータが前回のものと違った場合のみデータベースに挿入
        if ($data != $prev_usage_count){
            $stmt = $conn->prepare("INSERT INTO license_usage (feature, usage_count) VALUES (?, ?)");
            $stmt->bind_param("si", $key, $data);
            $stmt->execute();
            // Close connection
            $stmt->close();
            
            echo "Data stored successfully\n";
        }
        else {
            // 最新のレコードの ID を取得
            $sql = "SET @latest_id = (SELECT id FROM license_usage WHERE feature = ? ORDER BY timestamp DESC LIMIT 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $stmt->close();

            // 最新のレコードの timestamp を現在の時刻に更新
            $sql = "UPDATE license_usage SET timestamp = NOW() WHERE id = @latest_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->close();

            // コミット
            $conn->commit();
            
            echo "Data is same as latest; update only timestamp. \n";
        }
    }
} catch (Exception $e) {
    // エラー発生時にロールバック
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();





?>

