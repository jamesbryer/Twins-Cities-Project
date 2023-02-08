<?php

include_once('db_connection.php');

$map_query = "SELECT CityID, CityName, Latitude, Longitude FROM city";

$map_result = mysqli_query($server, $map_query);

$map_data = array();
while ($row = mysqli_fetch_assoc($map_result)) {
    $map_data[] = $row;
}

echo json_encode($map_data);


?>