<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Time</title>
    <script>
        function updateServerTime(data) {
            document.getElementById('serverTime').innerText = data.serverTime;
            document.getElementById('serverYear').innerText = data.serverYear;
            document.getElementById('serverMonth').innerText = data.serverMonth;
            document.getElementById('serverDay').innerText = data.serverDay;
            document.getElementById('serverHour').innerText = data.serverHour;
            document.getElementById('serverMinute').innerText = data.serverMinute;
            document.getElementById('currentSeason').innerText = data.currentSeason;
            document.getElementById('weather').innerText = data.weather;
            document.getElementById('temperature').innerText = data.temperature + '°C';

            // Save the server time data
            saveServerTime(data);
        }

        function saveServerTime(data) {
            fetch('/agricult/time/saveServerTime', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    console.log('Server time data saved successfully.');
                } else {
                    console.error('Error saving server time data.');
                }
            })
            .catch(error => console.error('Error saving server time data:', error));
        }

        // Initial update on page load
        window.onload = function() {
            const serverTimeData = {
                serverTime: "<?php echo $data['serverTime']; ?>",
                serverYear: "<?php echo $data['serverYear']; ?>",
                serverMonth: "<?php echo $data['serverMonth']; ?>",
                serverDay: "<?php echo $data['serverDay']; ?>",
                serverHour: "<?php echo $data['serverHour']; ?>",
                serverMinute: "<?php echo $data['serverMinute']; ?>",
                currentSeason: "<?php echo $data['currentSeason']; ?>",
                weather: "<?php echo $data['weather']; ?>",
                temperature: "<?php echo $data['temperature']; ?>"
            };
            updateServerTime(serverTimeData);
        };

        // Update server time every 3 hours (10800000 milliseconds)
        setInterval(function() {
            const serverTimeData = {
                serverTime: "<?php echo $data['serverTime']; ?>",
                serverYear: "<?php echo $data['serverYear']; ?>",
                serverMonth: "<?php echo $data['serverMonth']; ?>",
                serverDay: "<?php echo $data['serverDay']; ?>",
                serverHour: "<?php echo $data['serverHour']; ?>",
                serverMinute: "<?php echo $data['serverMinute']; ?>",
                currentSeason: "<?php echo $data['currentSeason']; ?>",
                weather: "<?php echo $data['weather']; ?>",
                temperature: "<?php echo $data['temperature']; ?>"
            };
            updateServerTime(serverTimeData);
        }, 10800000);
    </script>
</head>
<body>
    <h1>Welcome to the Main Page</h1>
    <p>Server Time: <span id="serverTime"></span></p>
    <p>Server Year: <span id="serverYear"></span></p>
    <p>Server Month: <span id="serverMonth"></span></p>
    <p>Server Day: <span id="serverDay"></span></p>
    <p>Server Hour: <span id="serverHour"></span></p>
    <p>Server Minute: <span id="serverMinute"></span></p>
    <p>Current Season: <span id="currentSeason"></span></p>
    <p>Weather: <span id="weather"></span></p>
    <p>Temperature: <span id="temperature"></span>°C</p>
</body>
</html>