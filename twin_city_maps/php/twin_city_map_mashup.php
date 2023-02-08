<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Archie Telfer">
    <title>Interactive Maps, Liverpool and Dublin</title> 
    <link rel="stylesheet" href="../leaflet/leaflet.css" />
    <script src="../leaflet/leaflet.js"></script>
    <style>
        #city1_map {
            height: 400px;
            margin-bottom: 25px;
            margin-left: 50px;
            margin-right: 50px; static = yes;}
        #city2_map {
            height: 400px;
            margin-left: 50px;
            margin-right: 50px; }
    </style>

</head>

<body>
    <h1>Map Of Liverpool and Twin</h1>
    <div>
        <?php
            echo '<h2>Liverpool</h2>';
            echo '<div id="city1_map"></div>';
            echo '<h2>Dublin</h2>';
            echo '<div id="city2_map"></div>';
            echo '<script type="text/javascript" src="../JS/twin_cities_map.js"></script>';
        ?>
    </div>
</body>
</html>