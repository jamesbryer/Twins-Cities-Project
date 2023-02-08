<!DOCTYPE html lang="en">
<head>
    <title>Twin Cities Weather</title>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
    body {
      margin-left:15px;
      margin-top:15px;
      margin-top:10px;
      font-size:16px;
    }
    h1 {
       font-size: 40px;
       color: #000000;
       font-weight: 650;
    }
    h3 {
       font-size: 25px;
       color: #000000;
       font-weight: 600;
    }

   
    </style>
</head>

<body>
    
<h1 class="container p-2">Twin Cities Weather</h1>

<div class="container p-3">
<form method="POST" >
        <select id="site" name="site" class="btn" >
            <option value="" disabled selected>Select a City...</option>
            <option value="Liverpool">Liverpool</option>
            <li class="divider"></li>
            <option value="Dublin">Dublin</option>
            <li class="divider"></li>
            <option value="Bristol">Bristol</option>
            <li class="divider"></li>
            <option value="ViewAll">View All</option>
        </select>

        <input type="submit" class="btn btn-primary" name="submit" value="Select">
    </form>
</div>

<?php 

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('auto_detect_line_endings', true);


function current_weather($d) {
    
    $xml = file_get_contents($d);
    $city_xml = simplexml_load_string($xml) or die("Error: Cannot create object");

    echo '<h3 class="container">Weather in '.$city_xml->city['name'].' on '.date_format(date_create($city_xml->lastupdate['value']), "l jS F Y") .' at '.date_format(date_create($city_xml->lastupdate['value']), "H:i").'</h2>';
    echo '<table class="table table-bordered container p-3 my-3 border btn-primary">';
    
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

    echo '<h3 class="container">Five day forecast for '.$city_xml->location->name.'</h3>';
    echo '<table class="table table-bordered container p-3 my-3 border btn-primary">';

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

if (isset($_POST['submit'])){
    if (isset($_POST['site'])){
        $site = $_POST['site'];
        
    }    

    switch($site){
        case "Liverpool":
            $current_weather_api = "https://api.openweathermap.org/data/2.5/weather?q=" . $citys[0] . "&APPID=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
            current_weather($current_weather_api);

            $forcast_weather_api = "https://api.openweathermap.org/data/2.5/forecast?q=" . $citys[0] . "&appid=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
            five_day_forcast($forcast_weather_api);
            echo '</div>';
            break;
        case "Dublin":
            $current_weather_api = "https://api.openweathermap.org/data/2.5/weather?q=" . $citys[1] . "&APPID=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
            current_weather($current_weather_api);

            $forcast_weather_api = "https://api.openweathermap.org/data/2.5/forecast?q=" . $citys[1] . "&appid=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
            five_day_forcast($forcast_weather_api);
            echo '</div>';
            break;
        case "Bristol":
            $current_weather_api = "https://api.openweathermap.org/data/2.5/weather?q=" . $citys[2] . "&APPID=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
            current_weather($current_weather_api);
    
            $forcast_weather_api = "https://api.openweathermap.org/data/2.5/forecast?q=" . $citys[2] . "&appid=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
            five_day_forcast($forcast_weather_api);
            echo '</div>';
            break;
        case "ViewAll":
                $current_weather_api = "https://api.openweathermap.org/data/2.5/weather?q=" . $citys[0] . "&APPID=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
                current_weather($current_weather_api);
        
                $forcast_weather_api = "https://api.openweathermap.org/data/2.5/forecast?q=" . $citys[0] . "&appid=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
                five_day_forcast($forcast_weather_api);
                echo '</div>';

                $current_weather_api = "https://api.openweathermap.org/data/2.5/weather?q=" . $citys[1] . "&APPID=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
                current_weather($current_weather_api);
        
                $forcast_weather_api = "https://api.openweathermap.org/data/2.5/forecast?q=" . $citys[1] . "&appid=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
                five_day_forcast($forcast_weather_api);
                echo '</div>';

                $current_weather_api = "https://api.openweathermap.org/data/2.5/weather?q=" . $citys[2] . "&APPID=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
                current_weather($current_weather_api);
        
                $forcast_weather_api = "https://api.openweathermap.org/data/2.5/forecast?q=" . $citys[2] . "&appid=565e980f6212df1ad19dfe535174a56f&mode=xml&units=metric";
                five_day_forcast($forcast_weather_api);
                echo '</div>';
                break;  
        default: 
            echo "Please select location!";
            break;
    }
}    

?>

</body>
