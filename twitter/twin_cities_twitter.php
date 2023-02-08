<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twin Cities Twitter</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script async src="https://platform.twitter.com/widgets.js"></script>
</head>

<body>
    <h1 class="w3-container">Twin Cities Twitter</h1>
    <h2><a href="twitter_cache.php" target="_blank">Click here to view cached Tweets!</a></h2>

    <?php

    include 'conf.php';

    function api_call($api_url, $api_bearer_token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_bearer_token
            )
        ));

        $data = curl_exec($curl);
        $data_json = json_decode($data);
        curl_close($curl);

        return $data_json;
    }

    function send_tweet($tweet_text, $comment_author)
    {
        // include config and twitter api wrappe
        require_once('conf.php');
        require_once('TwitterAPIExchange.php');

        // settings for twitter api connection
        $settings = array(
            'oauth_access_token' => TWITTER_ACCESS_TOKEN,
            'oauth_access_token_secret' => TWITTER_ACCESS_TOKEN_SECRET,
            'consumer_key' => TWITTER_CONSUMER_KEY,
            'consumer_secret' => TWITTER_CONSUMER_SECRET
        );

        // twitter api endpoint
        $url = 'https://api.twitter.com/1.1/statuses/update.json';

        // twitter api endpoint request type
        $requestMethod = 'POST';

        // twitter api endpoint data
        $apiData = array(
            'status' => 'Comment from ' . $comment_author . ': ' . $tweet_text,
        );

        // create new twitter for api communication
        $twitter = new TwitterAPIExchange($settings);

        // make our api call to twiiter
        $twitter->buildOauth($url, $requestMethod);
        $twitter->setPostfields($apiData);
        $response = $twitter->performRequest(true, array(CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0));
    }

    function database_send_to_comments($host, $port, $username, $password, $dbname, $name, $comment, $tweet_id, $author_id)
    {
        $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
        try {
            $connection = new PDO($dsn, $username, $password);
            $sql = "INSERT INTO twitter_comments(comment_text, comment_author, tweet_id, author_id) VALUES(:comment_text, :comment_author, :tweet_id, :author_id)";
            $stmt = $connection->prepare($sql);
            $stmt->execute(['comment_text' => $comment, 'comment_author' => $name, 'tweet_id' => $tweet_id, 'author_id' => $author_id]);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
    }

    function database_send_to_cache($host, $port, $username, $password, $dbname, $tweet_id, $author_id, $tweet_text, $author_name)
    {
        $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
        try {
            $connection = new PDO($dsn, $username, $password);
            $stmt = $connection->prepare('SELECT * FROM cached_tweets WHERE tweet_id=?');
            $stmt->bindParam(1, $tweet_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                $sql2 = "INSERT INTO cached_tweets(tweet_id, author_id, author_name, tweet_text) VALUES(:tweet_id, :author_id, :author_name, :tweet_text)";
                $stmt2 = $connection->prepare($sql2);
                $stmt2->execute(['tweet_id' => $tweet_id, 'author_id' => $author_id, 'author_name' => $author_name, 'tweet_text' => $tweet_text]);
            }
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
        }
    }

    function comment_box($total_tweets_returned, $tweet_id, $author_id)
    {
        if (isset($_POST['submit' . $total_tweets_returned])) {
            echo 'Thank you for your comment! Your comment was Tweeted from the <a href="https://www.twitter.com/TwinCitiesTWT" target="_blank">@TwinCitiesTWT</a> Twitter account.';
            send_tweet($_POST['comment' . $total_tweets_returned], $_POST['name' . $total_tweets_returned]);
            database_send_to_comments(HOST, PORT, USERNAME, PASSWORD, DBNAME, $_POST['name' . $total_tweets_returned], $_POST['comment' . $total_tweets_returned], $tweet_id, $author_id);
        }
        echo '<form action="twin_cities_twitter.php" method="post">
                <p>Name: <input type="text" name="name' . $total_tweets_returned . '" value="" required></p>
                <p>Comment: <input type="text" name="comment' . $total_tweets_returned . '" value="" required></p>
                <input type="submit" name="submit' . $total_tweets_returned . '" value="Submit">
              </form>';
    }

    $queries = array(
        '"liverpool"',
        '"dublin"',
        '%23liverpool',
        '%23dublin',
        'Anfield%20Stadium',
        '"Liverpool%20Cathedral"',
        '"Trinity%20College"'
    );
    $returned_tweets = array();
    $total_tweets_returned = 0;

    foreach ($queries as $query) {
        $search_api_url = SEARCH_API_URL_1 . $query . SEARCH_API_URL_2;
        $tweets_json = api_call($search_api_url, TWT_API_BEARER_TKN);
        $tweets = $tweets_json->data;
        $users = $tweets_json->includes->users;
        $tweet_count = 0;
        foreach ($tweets as $tweet) {
            if ($tweet_count < 2 and !in_array($tweet->id, $returned_tweets)) {
                echo '<p>Tweet from: @<a href="https://www.twitter.com/' . $users[$tweet_count]->username . '" target="_blank">' . $users[$tweet_count]->username . '</a></p>';
                echo '<p>Tweet: ' . $tweet->text . '</p>';
                echo '<p>Tweeted at: ' . $tweet->created_at . '</p>';
                $total_tweets_returned++;
                comment_box($total_tweets_returned, $tweet->id, $tweet->author_id);
                database_send_to_cache(HOST, PORT, USERNAME, PASSWORD, DBNAME, $tweet->id, $tweet->author_id, $tweet->text, $users[$tweet_count]->username);
                echo '<hr>';
                $tweet_count++;
                array_push($returned_tweets, $tweet->id);
            }
        }
    }
    ?>
</body>

</html>