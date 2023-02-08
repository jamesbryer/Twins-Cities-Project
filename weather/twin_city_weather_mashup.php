<head>
<style type="text/css">
	#weather_table {
		top: 15px	
	}
	th {
  		background-color: #87CEFA;
 		color: white;
	}
	tr {
		background-color: #8FBC8F;
		color: black;
	}
}
</style>

</head>

<?php


$api_key =  "6d7b46121e884b4b6f88d0e9c0b4e14d";
$city_name1 = "Liverpool,uk";
$api_call1 = "https://api.openweathermap.org/data/2.5/weather?q=".$city_name1."&units=metric&appid=".$api_key;

$city_name2 = "Cologne,de";
$api_call2 = "https://api.openweathermap.org/data/2.5/weather?q=".$city_name2."&units=metric&appid=".$api_key;



$weather_data1 = json_decode(file_get_contents($api_call1), true);
$weather_data2 = json_decode(file_get_contents($api_call2), true);

#List of Current weather attributes for City 1:
$description1 = $weather_data1['weather']['0']['description'];
$temperature1 = $weather_data1['main']['temp'];
$wind_speed1 = $weather_data1['wind']['speed'];
$humidity1 = $weather_data1['main']['humidity'];
$sunrise1 = gmdate("H:i:s", $weather_data1['sys']['sunrise']);
$sunset1 = gmdate("H:i:s", $weather_data1['sys']['sunset']);
$icon_code1 = $weather_data1['weather']['0']['icon'];

#List of Current Attributes for City 2:
$description2 = $weather_data2['weather']['0']['description'];
$temperature2 = $weather_data2['main']['temp'];
$wind_speed2 = $weather_data2['wind']['speed'];
$humidity2 = $weather_data2['main']['humidity'];
$sunrise2 = gmdate("H:i:s", $weather_data2['sys']['sunrise']);
$sunset2 = gmdate("H:i:s", $weather_data2['sys']['sunset']);
$icon_code2 = $weather_data2['weather']['0']['icon'];

echo '<h2>Weather Forecast for Liverpool, United Kingdom</h2>';
echo '<table border=0 align=c>';

    echo '<tr>';
        echo '<th align="left">'."Summary".'</th>';
        echo '<th>'.$description1.'</th>';
        echo '<th><img src="http://openweathermap.org/img/wn/'.$icon_code1.'@2x.png"/></th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Temperature".'</th>';
        echo '<th>'.$temperature1.' celcius</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Wind Speed".'</th>';
        echo '<th>'.$wind_speed1.'m/s</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Humidity".'</th>';
        echo '<th>'.$humidity1.'%</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Sunrise".'</th>';
        echo '<th>'.$sunrise1.'</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Sunset".'</th>';
        echo '<th>'.$sunset1.'</th>';
    echo '</tr>';
echo '</table>';

echo '<h2>Weather Forecast for Cologne, Germany</h2>';
echo '<table id="weather_table" border=0>';

    echo '<tr>';
        echo '<th align="left">'."Summary".'</th>';
        echo '<th>'.$description2.'</th>';
        echo '<th><img src="http://openweathermap.org/img/wn/'.$icon_code2.'@2x.png"/></th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Temperature".'</th>';
        echo '<th>'.$temperature2.' celcius</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Wind Speed".'</th>';
        echo '<th>'.$wind_speed2.' m/s</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Humidity".'</th>';
        echo '<th>'.$humidity2.'%</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Sunrise".'</th>';
        echo '<th>'.$sunrise2.'</th>';
    echo '</tr>';

    echo '<tr>';
        echo '<th align="left">'."Sunset".'</th>';
        echo '<th>'.$sunset2.'</th>';
    echo '</tr>';
echo '</table>';
?>

