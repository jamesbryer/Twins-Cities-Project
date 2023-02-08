<!DOCTYPE html lang="en">
<head>
    <title>Twin Cities Weather</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
    
<h1 class="w3-container">Twin Cities Weather</h1>

<?php 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('auto_detect_line_endings', true);

function current_weather($d) {
    
    $xml = file_get_contents($d);
    $city_xml = simplexml_load_string($xml) or die("Error: Cannot create object");

    echo '<div class="w3-row"><div class="w3-twothird"><h3 class="w3-container">Weather in ' . $city_xml->city['name'] . ' on ' . date_format(date_create($city_xml->lastupdate['value']), "l jS F Y") . ' at ' . date_format(date_create($city_xml->lastupdate['value']), "H:i") . '</h2>';
    echo '<table class="w3-table w3-container w3-teal w3-round-xlarge w3-card">';
    echo '<tr><td>Condition: </td><td>' . ucfirst($city_xml->clouds['name']) . '<img src="http://openweathermap.org/img/wn/' . $city_xml->weather["icon"] .  '@2x.png" alt="weather icon" width="30" height="30"></td></tr>'; 
    echo '<tr><td>Temperature: </td><td>' . $city_xml->temperature['value'] .  '&#176;C</td></tr>'; 
    echo '<tr><td>Wind: </td><td>' . $city_xml->wind->speed['value'] . 'm/s (' . $city_xml->wind->speed['name'] . ') from a ' . $city_xml->wind->direction['name'] . ' direction. </td></tr>'; 
    echo '<tr><td>Humidity: </td><td>' . $city_xml->humidity['value'] . '%</td></tr>'; 
    echo '<tr><td>Pressure: </td><td>' . $city_xml->pressure['value'] . 'hPa</td></tr>'; 
    echo '<tr><td>Sunrise: </td><td>' . substr($city_xml->city->sun['rise'], 11) . '</td></tr>';  
    echo '<tr><td>Sunrise: </td><td>' . substr($city_xml->city->sun['set'], 11) . '</td></tr>'; 
    echo '</table></div></div>';
}

function five_day_forcast($d) {
    
    $xml = file_get_contents($d);
    $city_xml = simplexml_load_string($xml) or die("Error: Cannot create object");

    echo '<div class=""w3-row"><div class="w3-twothird"><h3 class="w3-container">Five day forecast for ' . $city_xml->location->name . '</h3>';
    echo '<table class="w3-table w3-container w3-teal w3-round-xlarge w3-card">';

    foreach ($city_xml->forecast->time as $forecast) {
        if (substr($forecast["from"], 11) == "12:00:00") {
            echo '<tr><td>Weather at Midday on ' . date_format(date_create($forecast['from']), "l") . '</td>';
            echo '<td>Temperature: ' . $forecast->temperature['value'] . '&#176;C</td>';
            echo '<td>Condition: ' . ucfirst($forecast->symbol['name']) . '<img src="http://openweathermap.org/img/wn/' . $forecast->symbol["var"] .  '@2x.png" alt="weather icon" width="30" height="30"></td>';
            echo '<td>Wind Speed: ' . $forecast->windSpeed['mps'] . 'm/s (' . $forecast->windSpeed['name'] . ') from a ' . $forecast->windDirection['name'] . ' direction.</td>';
            echo '</tr>';
        }
        elseif (substr($forecast["from"], 11) == "00:00:00") {
            echo '<tr><td>Weather at Midnight on ' . date_format(date_create($forecast['from']), "l") . '</td>';
            echo '<td>Temperature: ' . $forecast->temperature['value'] . '&#176;C</td>';
            echo '<td>Condition: ' . ucfirst($forecast->symbol['name']) . '<img src="http://openweathermap.org/img/wn/' . $forecast->symbol["var"] .  '@2x.png" alt="weather icon" width="30" height="30"></td>';
            echo '<td>Wind Speed: ' . $forecast->windSpeed['mps'] . 'm/s (' . $forecast->windSpeed['name'] . ') from a ' . $forecast->windDirection['name'] . ' direction.</td>';
            echo '</tr>';;
        }
    }
    echo '</table></div></div>';
}


$citys = array("Liverpool,uk", "Dublin,ie", "Bristol,uk");

foreach ($citys as $city) {
    $current_weather_api = "https://api.openweathermap.org/data/2.5/weather?q=" . $city . "&APPID=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
    current_weather($current_weather_api);

    $forcast_weather_api = "https://api.openweathermap.org/data/2.5/forecast?q=" . $city . "&appid=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
    five_day_forcast($forcast_weather_api);
    echo '</div>';
}

?>

</body>
