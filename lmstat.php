<?php
// lmstatコマンドを実行し、出力を取得する
$output = [];
exec('cat lmstat_o.txt', $output);

// 出力をパースしてライセンス情報を抽出する（必要に応じて調整）
$licenses = [];
foreach ($output as $line) {
    if (strpos($line, "Users of") !== false) {
        // ライセンス名を取得
        $license_name = trim(explode(":", $line)[1]);
        $licenses[$license_name] = 0;
    } elseif (strpos($line, "start") !== false) {
        // ライセンス数をカウント
        $licenses[$license_name]++;
    }
}

// JSON形式で返す
header('Content-Type: application/json');
echo json_encode($licenses);
?>
