<?php

$username = "root";
$password = "root";
$hostname = "localhost";
$database = "twin_cities";

$server = mysqli_connect($hostname, $username, $password);
$connection = mysqli_select_db($server, $database);

?>

