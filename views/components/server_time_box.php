
<div id="server-time-box">
    <div class="card">
        <div class="card-header">
            <h2>Météo</h2>
        </div>
        <div class="card-body">
            <img id="currentSeasonImage" src="" alt="Current Season">
            <p>Date: <span id="serverTime"></span></p>
            <p>Jour/Nuit: <span id="isDay"></span></p>
            <p>Météo: <span id="weather"></span></p>
            <p>Température: <span id="temperature"></span>°C</p>
        </div>
    </div>
</div>

<script>
    const ROOT = '<?php echo WWW_ROOT; ?>';
    const STORAGE_KEY = 'serverTimeData';
    const EXPIRATION_TIME = 3 * 60 * 60 * 1000; // 3 hours in milliseconds

    function updateServerTime(data) {
        document.getElementById('serverTime').innerText = data.serverTime;
        document.getElementById('weather').innerText = data.weather;
        document.getElementById('temperature').innerText = data.temperature;
        document.getElementById('isDay').innerText = data.isDay ? 'Day' : 'Night';

        const seasonImage = document.getElementById('currentSeasonImage');
        switch (data.currentSeason) {
            case 'Hiver':
                seasonImage.src = ROOT + '/public/image/hiver.png';
                seasonImage.alt = 'Hiver';
                break;
            case 'Printemps':
                seasonImage.src = ROOT + '/public/image/printemps.png';
                seasonImage.alt = 'Printemps';
                break;
            case 'Eté':
                seasonImage.src = ROOT + '/public/image/ete.png';
                seasonImage.alt = 'Eté';
                break;
            case 'Automne':
                seasonImage.src = ROOT + '/public/image/automne.png';
                seasonImage.alt = 'Automne';
                break;
            default:
                seasonImage.src = '';
                seasonImage.alt = 'Unknown Season';
                break;
        }
    }

    function fetchServerTime() {
        fetch('/agricult/times/index')
            .then(response => response.json())
            .then(data => {
                updateServerTime(data);
                saveToLocalStorage(data);
            })
            .catch(error => console.error('Error fetching server time data:', error));
    }

    function saveToLocalStorage(data) {
        const dataWithTimestamp = {
            ...data,
            timestamp: Date.now()
        };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(dataWithTimestamp));
    }

    function loadFromLocalStorage() {
        const storedData = localStorage.getItem(STORAGE_KEY);
        if (storedData) {
            const data = JSON.parse(storedData);
            if (Date.now() - data.timestamp < EXPIRATION_TIME) {
                return data;
            } else {
                localStorage.removeItem(STORAGE_KEY);
            }
        }
        return null;
    }

    function initializeServerTime() {
        const data = loadFromLocalStorage();
        if (data) {
            updateServerTime(data);
        } else {
            fetchServerTime();
        }
    }

    // Initial update on page load
    window.onload = initializeServerTime;

    // Update server time every 3 hours (10800000 milliseconds)
    setInterval(fetchServerTime, 10800000);
</script>