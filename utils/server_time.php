<?php
// filepath: /c:/laragon/www/agricult/utils/server_time.php
// Define constants
define('REAL_TO_SERVER_RATIO', 7); // 1 real day = 7 server days
define('DAYS_IN_MONTH', 30);
define('DAYS_IN_SEASON', 90);
// Set locale to ensure date parsing works correctly
setlocale(LC_TIME, 'fr_FR.UTF-8');

// Define weather coefficients and temperature ranges for each season
$weatherCoefficients = [
    'Hiver' => ['sun' => 2, 'rain' => 3, 'snow' => 5, 'fog' => 2, 'minTemp' => -10, 'maxTemp' => 5],
    'Printemps' => ['sun' => 6, 'rain' => 3, 'snow' => 0, 'fog' => 1, 'minTemp' => 5, 'maxTemp' => 15],
    'Eté' => ['sun' => 8, 'rain' => 2, 'snow' => 0, 'fog' => 0, 'minTemp' => 15, 'maxTemp' => 30],
    'Automne' => ['sun' => 4, 'rain' => 4, 'snow' => 1, 'fog' => 3, 'minTemp' => 0, 'maxTemp' => 10]
];

function getServerTime() {
    global $weatherCoefficients;

    // Get the current real time
    $realTime = time();

    // Define the base date
    $baseDateStr = "01-01-2025 01:01:01";
    $baseDate = DateTime::createFromFormat('d-m-Y H:i:s', $baseDateStr);
    if ($baseDate === false) {
        die('Error: Invalid base date');
    }

    // Calculate the number of real seconds that have passed since the base date
    $baseTimestamp = $baseDate->getTimestamp();
    $realSecondsPassed = $realTime - $baseTimestamp;

    // Calculate the number of server seconds
    $serverSeconds = $realSecondsPassed * REAL_TO_SERVER_RATIO;

    // Add server seconds to the base date
    $baseDate->modify("+$serverSeconds seconds");
    $serverTime = $baseDate->getTimestamp();

    // Check if serverTime is correctly calculated
    if ($serverTime === false) {
        die('Error: Invalid server time calculation');
    }

    // Calculate server date components
    $serverYear = date('Y', $serverTime);
    $serverMonth = date('m', $serverTime);
    $serverDay = date('d', $serverTime);
    $serverHour = date('H', $serverTime);
    $serverMinute = date('i', $serverTime);

    // Determine the current season
    $serverDayOfYear = date('z', $serverTime) + 1; // Day of the year (1-365)
    $seasonNumber = ceil($serverDayOfYear / DAYS_IN_SEASON);

    $seasons = ['Hiver', 'Printemps', 'Eté', 'Automne'];
    $currentSeason = $seasons[($seasonNumber - 1) % 4];

    // Determine if it is day or night
    $isDay = generateDayNight($currentSeason, $serverHour);

    // Determine the weather condition and temperature
    $weather = determineWeather($currentSeason, $isDay);
    $temperature = generateTemperature($currentSeason, $weather, $isDay);

    return [
        'serverTime' => date('d-m-Y H:i', $serverTime),
        'serverYear' => $serverYear,
        'serverMonth' => $serverMonth,
        'serverDay' => $serverDay,
        'serverHour' => $serverHour,
        'serverMinute' => $serverMinute,
        'currentSeason' => $currentSeason,
        'weather' => $weather,
        'temperature' => $temperature,
        'isDay' => $isDay
    ];
}

function determineWeather($season, $isDay) {
    global $weatherCoefficients;

    $coefficients = $weatherCoefficients[$season];
    $totalCoeff = $coefficients['sun'] + $coefficients['rain'] + $coefficients['snow'] + $coefficients['fog'];
    $weather = rand(1, $totalCoeff);

    if ($isDay) {
        if ($weather <= $coefficients['sun']) {
            return 'sun';
        } elseif ($weather <= $coefficients['sun'] + $coefficients['rain']) {
            return 'rain';
        } elseif ($weather <= $coefficients['sun'] + $coefficients['rain'] + $coefficients['fog']) {
            return 'fog';
        } else {
            return 'snow';
        }
    } else {
        if ($weather <= $coefficients['rain']) {
            return 'rain';
        } elseif ($weather <= $coefficients['rain'] + $coefficients['fog']) {
            return 'fog';
        } else {
            return 'snow';
        }
    }
}

function generateTemperature($season, $weather, $isDay) {
    global $weatherCoefficients;

    $coefficients = $weatherCoefficients[$season];
    $baseTemp = rand($coefficients['minTemp'], $coefficients['maxTemp']);

    // Adjust temperature based on weather condition
    switch ($weather) {
        case 'sun':
            $baseTemp += 5;
            break;
        case 'rain':
            $baseTemp -= 5;
            break;
        case 'fog':
            $baseTemp -= 2;
            break;
        case 'snow':
            $baseTemp = $baseTemp; // No change for snow
            break;
    }

    // Adjust temperature based on time of day
    if ($isDay) {
        $baseTemp += 3; // Daytime adjustment
    } else {
        $baseTemp -= 3; // Nighttime adjustment
    }

    return $baseTemp;
}

function generateDayNight($season, $hour) {
    switch ($season) {
        case 'Hiver': // Winter
            return ($hour >= 8 && $hour < 17);
        case 'Printemps': // Spring
            return ($hour >= 6.5 && $hour < 20);
        case 'Eté': // Summer
            return ($hour >= 5.5 && $hour < 21.5);
        case 'Automne': // Autumn
            return ($hour >= 7 && $hour < 18.5);
        default:
            return false;
    }
}