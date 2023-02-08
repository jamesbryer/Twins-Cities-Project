<?php

include_once('db_connection.php');

$poi_query_city1 = "SELECT PoiName, CityID, PoiLatitude, PoiLongitude, PhoneNum FROM poi";

$poi_query_result = mysqli_query($server, $poi_query_city1);

$poi_data = array();
while ($row = mysqli_fetch_assoc($poi_query_result)) {
    $poi_data[] = $row;
}

echo json_encode($poi_data);

?>
