<?php 

    #API keys 

    #Twitter
    define ("TWITTER_CONSUMER_KEY", "");
    define ("TWITTER_CONSUMER_SECRET", "");
    define ("TWITTER_ACCESS_TOKEN", "");
    define ("TWITTER_ACCESS_TOKEN_SECRET", "");
    define ("TWT_API_BEARER_TKN", "");
    define ("SEARCH_API_URL_1", "https://api.twitter.com/2/tweets/search/recent?query=");
    define ("SEARCH_API_URL_2", "(-is:retweet)(-is:quote)(-is:reply)(lang:en)&tweet.fields=created_at&expansions=author_id&user.fields=created_at");

    #Google Maps

    define ("GGLE_MAP_API_KEY", "");

    #OpenWeatherData

    define ("OPN_WEATHER_API_KEY", "");

    #Flickr

    define ("FLICKR_API_KEY" , "");
    define ("FLICKR_API_SECRET", "");

    #MySQL Database Connection Info

    define("HOST", "localhost");
    define("PORT","8080");
    define("USERNAME","root");
    define("PASSWORD","1234");
    define("DBNAME","twin_cities")
    
?>
