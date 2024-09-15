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
                
                const timeData = data.map(entry => new Date(entry.timestamp).toLocaleTimeString());
                // const valueData = data.map(entry => parseInt(entry.output.split('\n').length, 10)); // Adjust based on your output
                const valueData1 = data.map(entry => entry.feature1); 
                const valueData2 = data.map(entry => entry.feature2); 
                console.log(timeData);
                console.log(valueData1);
                console.log(valueData2);

                var dataPoints1 = timeData.map(function(time, index) {
                    return { x: time, y: valueData1[index] };
                });
                var dataPoints2 = timeData.map(function(time, index) {
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
                                    parser: 'HH:mm:ss',  // 時間の形式を指定
                                    unit: 'second',      // 秒単位で表示
                                    displayFormats: {
                                        second: 'HH:mm:ss'  // HH:mm:ss形式でX軸に表示
                                    }
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
                                    parser: 'HH:mm:ss',  // 時間の形式を指定
                                    unit: 'second',      // 秒単位で表示
                                    displayFormats: {
                                        second: 'HH:mm:ss'  // HH:mm:ss形式でX軸に表示
                                    }
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
