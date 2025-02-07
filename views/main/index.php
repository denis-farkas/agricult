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
            document.getElementById('temperature').innerText = data.temperature;
            document.getElementById('isDay').innerText = data.isDay ? 'Day' : 'Night';
        }

        function fetchServerTime() {
        fetch('/agricult/time/index')
            .then(response => response.json())
            .then(data => {
                updateServerTime(data);
            })
            .catch(error => console.error('Error fetching server time data:', error));
        }

         // Initial update on page load
        window.onload = fetchServerTime;

        // Update server time every 3 hours (10800000 milliseconds)
        setInterval(fetchServerTime, 10800000);
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
    <p>Day: <span id="isDay"></span></p>
    <p>Weather: <span id="weather"></span></p>
    <p>Temperature: <span id="temperature"></span>°C</p>
</body>
</html>