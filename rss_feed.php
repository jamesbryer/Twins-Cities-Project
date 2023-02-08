<?php 

include 'conf.php';

function generate_rss_from_database($host, $port, $username, $password, $dbname) {
    $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
    try {
        $connection = new PDO($dsn, $username, $password);
        $sql = "SELECT * FROM city";
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        echo "<?xml version='1.0' encoding='UTF-8'?>" . PHP_EOL;
        echo "<rss version='2.0' xmlns:twncty='http://192.168.64.2/assignment/twin_city_namespace.html'>" . PHP_EOL;
        echo "<channel>" . PHP_EOL;
        echo "<title>Feed from Twin Cities Application</title>" . PHP_EOL;
        echo "<link>http://192.168.64.2/assignment/app.php</link>" . PHP_EOL;
        echo "<description>This feed displays data held in the MySQL database for the Twin Cities App. Info about each city and point of interest is shown.</description>" . PHP_EOL;
        

        foreach($stmt as $row){
            echo "<item>" . PHP_EOL;
            echo "<title>City</title>" . PHP_EOL;
            echo "<link>http://192.168.64.2/assignment/app.php</link>" . PHP_EOL;
            echo "<description>Details of a given city</description>" . PHP_EOL;
            echo "<twncty:cityname>" . $row['CityName'] . "</twncty:cityname>" . PHP_EOL;
            echo "<twncty:cityid>" . $row['CityID'] . "<twncty:cityid>" . PHP_EOL;
            echo "<twncty:population>" . $row['Population'] . "<twncty:population>" . PHP_EOL;
            echo "<twncty:currency>" . $row['Currency'] . "<twncty:currency>" . PHP_EOL;
            echo "<twncty:woeid>" . $row['WoeID'] . "<twncty:woeid>" . PHP_EOL;
            echo "<twncty:latitude>" . $row['Latitude'] . "<twncty:latitude>" . PHP_EOL;
            echo "<twncty:longitude>" . $row['Longitude'] . "<twncty:longitude>" . PHP_EOL;
            echo "<twncty:country>" . $row['Country'] . "<twncty:country>" . PHP_EOL;
            echo "</item>" . PHP_EOL;

            $city_id = $row['CityID'];

            $sql2 = "SELECT * FROM poi WHERE CityID = '" . $city_id . "'";
            $stmt2 = $connection->prepare($sql2);
            $stmt2->execute();

            foreach($stmt2 as $row2){
                echo "<item>" . PHP_EOL;
                echo "<title>Point of Interest</title>" . PHP_EOL;
                echo "<link>http://192.168.64.2/assignment/app.php</link>" . PHP_EOL;
                echo "<description>Details of a given point of interest</description>" . PHP_EOL;
                echo "<twncty:poiid>" . $row2['PoiID'] . "</twncty:poiid>" . PHP_EOL;
                echo "<twncty:poiname>" . $row2['PoiName'] . "</twncty:poiname>" . PHP_EOL;
                echo "<twncty:openingyear>" . $row2['OpeningYear'] . "</twncty:openingyear>" . PHP_EOL;
                echo "<twncty:description>" . $row2['Desc'] . "</twncty:description>" . PHP_EOL;
                echo "<twncty:address>" . $row2['Address'] . "</twncty:address>" .PHP_EOL;
                echo "<twncty:phonenum>" . $row2['PhoneNum'] . "</twncty:phonenum>" . PHP_EOL;
                echo "<twncty:rating>" . $row2['Rating'] . "</twncty:rating>" . PHP_EOL;
                echo "<twncty:cityid>" . $row2['CityID'] . "</twncty:cityid>" . PHP_EOL;
                echo "</item>" . PHP_EOL;
            }
        }
        echo "</channel>" . PHP_EOL;
        echo "</rss>";
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

generate_rss_from_database(HOST, PORT, USERNAME, PASSWORD, DBNAME);

?>