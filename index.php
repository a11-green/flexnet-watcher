<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Usage</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/combine/npm/moment@2.29.1,npm/chartjs-adapter-moment@1.0.0"></script>

</head>
<body>
    <h1>License Usage 2</h1>
    <h2>License Server 1</h2>
    <canvas id="licenseChart1" width="200" height="100"></canvas>
    <h2>License Server 2</h2>
    <canvas id="licenseChart2" width="200" height="100"></canvas>
    <script>
    // Fetch historical data
    fetch('fetch_data.php')
        .then(response => response.json())
        .then(data => {
            // const features = [
            //     { name: 'feature1', elementId: 'licenseChart1' },
            //     { name: 'feature2', elementId: 'licenseChart2' }
            // ];
            const licenseServers = [
                { name: 'server1', elementId: 'licenseChart1' },
                { name: 'server2', elementId: 'licenseChart2' }
            ];

            licenseServers.forEach(server => {
                const serverData = data[server.name];
                // const featureData = data[feature.name];
                // const featureData = serverData["feature1"];
                // data[server.name]のkeyからfeature名を動的に取得
                const features = Object.keys(data[server.name]);

                // datasetsを生成
                const datasets = Object.keys(data[server.name]).flatMap((feature, index) => {
                    const featureData = data[server.name][feature];
                    const timeData = featureData.map(entry => new Date(entry.timestamp).toLocaleString());
                    const usedData = featureData.map(entry => entry.used_licenses);
                    const totalData = featureData.map(entry => entry.total_licenses);

                    // total_licensesのデータセット（実線）
                    const totalDataset = {
                        label: `${feature} Total Licenses`,
                        data: timeData.map((time, i) => ({
                            x: time,
                            y: totalData[i],
                        })),
                        borderColor: `hsla(${index * 60}, 70%, 50%, 1)`, // 実線の色
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.0, // 折れ線を少し滑らかに
                        fill: false, // 塗りつぶしなし
                    };

                    // used_licensesのデータセット（点線）
                    const usedDataset = {
                        label: `${feature} Used Licenses`,
                        data: timeData.map((time, i) => ({
                            x: time,
                            y: usedData[i],
                        })),
                        borderColor: `hsla(${index * 60}, 70%, 50%, 0.7)`, // 点線の色（少し透明に）
                        // backgroundColor: 'transparent',
                        backgroundColor: `hsla(${index * 60}, 70%, 50%, 0.2)`,
                        borderWidth: 2,
                        borderDash: [5, 5], // 点線のスタイル
                        tension: 0.0, // 折れ線を少し滑らかに
                        fill: true, // 塗りつぶしなし
                    };

                    return [totalDataset, usedDataset];
                });

                // グラフを描画
                const ctx = document.getElementById(server.elementId).getContext('2d');
                new Chart(ctx, {
                    type: 'line', // 折れ線グラフ
                    data: {
                        datasets: datasets, // 動的に生成したdatasetsを使用
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'minute', // X軸を時間単位に設定
                                },
                                title: {
                                    display: true,
                                    text: 'Time',
                                }
                            },
                            y: {
                                beginAtZero: true, // Y軸の開始を0に設定
                                title: {
                                    display: true,
                                    text: 'Licenses',
                                },
                            }
                        },
                        plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false, // 同じ時刻のデータをまとめて表示
                            },
                            legend: {
                                position: 'top', // 凡例を上部に配置
                            },
                        },
                    }
                });

            });
        })
        .catch(error => console.error('Error fetching data:', error));
</script>
</body>
</html>
