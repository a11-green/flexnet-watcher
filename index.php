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
    <h1>License Usage</h1>
    <h2>Feature 1</h2>
    <canvas id="licenseChart1" width="200" height="100"></canvas>
    <h2>Feature 2</h2>
    <canvas id="licenseChart2" width="200" height="100"></canvas>
    <script>
        // Fetch historical data
        fetch('fetch_data.php')
        // fetch('lmstat.php')
            .then(response => response.json())
            .then(data => {
                const feature1Data = data.feature1;
                console.log(feature1Data);
                const feature2Data = data.feature2;
                const timeData1 = feature1Data.map(entry => new Date(entry.timestamp).toLocaleString());
                const timeData2 = feature2Data.map(entry => new Date(entry.timestamp).toLocaleString());
                // const valueData = data.map(entry => parseInt(entry.output.split('\n').length, 10)); // Adjust based on your output
                const valueData1 = feature1Data.map(entry => entry.usage_count); 
                const valueData2 = feature2Data.map(entry => entry.usage_count); 
                console.log(timeData1);
                console.log(timeData2);
                console.log(valueData1);
                console.log(valueData2);

                var dataPoints1 = timeData1.map(function(time, index) {
                    return { x: time, y: valueData1[index] };
                });
                var dataPoints2 = timeData2.map(function(time, index) {
                    return { x: time, y: valueData2[index] };
                });
                
                const ctx1 = document.getElementById('licenseChart1').getContext('2d');
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        // labels: labels,
                        datasets: [{
                            label: 'License Usage Over Time',
                            data: dataPoints1,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    // parser: 'HH:mm:ss',  // 時間の形式を指定
                                    unit: 'minute',      // 秒単位で表示
                                    // displayFormats: {
                                    //     second: 'HH:mm:ss'  // HH:mm:ss形式でX軸に表示
                                    // }
                                }
                            },
                            y: {
                                suggestedMin: 0,
                                ticks: {
                                    stepSize: 1,
                                }
                            }
                        }
                    }
                });
                const ctx2 = document.getElementById('licenseChart2').getContext('2d');
                new Chart(ctx2, {
                    type: 'line',
                    data: {
                        // labels: labels,
                        datasets: [{
                            label: 'License Usage Over Time',
                            data: dataPoints2,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    // parser: 'HH:mm:ss',  // 時間の形式を指定
                                    unit: 'second',      // 秒単位で表示
                                    // displayFormats: {
                                    //     second: 'HH:mm:ss'  // HH:mm:ss形式でX軸に表示
                                    // }
                                }
                            },
                            y: {
                                suggestedMin: 0,
                                ticks: {
                                    stepSize: 1,
                                }
                            }
                        }
                    }
                });
            });
    </script>
</body>
</html>
