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
            const features = [
                { name: 'feature1', elementId: 'licenseChart1' },
                { name: 'feature2', elementId: 'licenseChart2' }
            ];

            features.forEach(feature => {
                const featureData = data[feature.name];
                const timeData = featureData.map(entry => new Date(entry.timestamp).toLocaleString());
                const valueData = featureData.map(entry => entry.used_licenses);

                const dataPoints = timeData.map((time, index) => ({
                    x: time,
                    y: valueData[index]
                }));

                const ctx = document.getElementById(feature.elementId).getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: `${feature.name} Usage Over Time`,
                            data: dataPoints,
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
                                    unit: 'minute', // X軸を時間単位に設定
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
        })
        .catch(error => console.error('Error fetching data:', error));
</script>
</body>
</html>
