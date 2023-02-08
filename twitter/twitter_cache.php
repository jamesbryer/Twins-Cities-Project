<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tweet Cache</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
    <?php
    include 'conf.php';
    //returns max value of primary key for cached tweets table (cache_id)
    function database_search_max($host, $port, $username, $password, $dbname) {
        $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
        try {
            $connection = new PDO($dsn, $username, $password);
            $stmt = $connection->prepare("SELECT MAX(cache_id) AS max_id FROM cached_tweets");
            $stmt->execute();
            $rtn = $stmt->fetch(PDO::FETCH_ASSOC);
            $max_id = $rtn['max_id'];
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
        return $max_id;
    }
    //returns min value of primary key for cached tweets table (cache_id)
    function database_search_min($host, $port, $username, $password, $dbname) {
        $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
        try {
            $connection = new PDO($dsn, $username, $password);
            $stmt = $connection->prepare("SELECT MIN(cache_id) AS max_id FROM cached_tweets");
            $stmt->execute();
            $rtn = $stmt->fetch(PDO::FETCH_ASSOC);
            $max_id = $rtn['max_id'];
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
        return $max_id;
    }
    //pass in a range of cache_ids and will return a table displaying data within that range from database
    function database_retrieve_cache($host, $port, $username, $password, $dbname, $min_value, $max_value) {
        $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
        try {
            $connection = new PDO($dsn, $username, $password);
            $sql = "SELECT * FROM cached_tweets WHERE cache_id BETWEEN :min_value AND :max_value";
            $stmt = $connection->prepare($sql);
            $stmt->execute(['min_value' => $min_value, 'max_value' => $max_value]);

            echo "<table class='w3-table w3-striped w3-hoverable'><tr><td>Cache ID</td><td>Tweeted by</td><td>Tweet</td><td>Cached at</td></tr>";
            foreach ($stmt as $row) {
                echo '<tr>';
                echo '<td>' . $row['cache_id'] . '</td>';
                echo '<td>@' . $row['author_name'] . '</td>';
                echo '<td>' . $row['tweet_text'] . '</td>';
                echo '<td>' . $row['pulled_at'] . '</td></tr>';
            }
            echo '</table>';
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
    }
    //calculate number of pages required to display data within the specified range. 
    $max = database_search_max(HOST, PORT, USERNAME, PASSWORD, DBNAME);
    $min = database_search_min(HOST, PORT, USERNAME, PASSWORD, DBNAME);
    $num_of_pages = ceil(($max - $min) / 10);
    //based on max, min and chosen page in dropdown will calculate the cache_id range needed to display.
    if (isset($_GET['page'])) {
        $selected_page = $_GET['page'];
        if ($selected_page > 1) {
            $min_value = ($min - 10) + ($selected_page * 10);
        } else {
            $min_value = $min;
        }
    } else {
        $min_value = $min;
    }
    $max_value = $min_value + 9;
    ?>

    <h1>Cached Tweets: displays all Tweets pulled by API on <a href='twin_cities_twitter.php'>Twin Cities Twitter</a></h1>
    <h2>Filter by: </h2>
    <form method="get" action="<?php print $_SERVER['PHP_SELF']; ?>">
        <select name="page" id="page">
            <?php
            //created options in dropdown selection for each page, calculated above.
            $page = 1;
            while ($page <= $num_of_pages) {
                echo '<option value="' . $page . '">Page ' . $page . '</option>';
                $page++;
            }
            ?>
        </select>
        <script>
            document.getElementById('page').value = "<?php if (isset($_GET['page'])){echo $_GET['page'];}else{echo '1';} ?>";
        </script>
        <input type="submit">
    </form>

    <?php
    //call function to display table of chosen data.
    database_retrieve_cache(HOST, PORT, USERNAME, PASSWORD, DBNAME, $min_value, $max_value);
    ?>
</body>
</html>