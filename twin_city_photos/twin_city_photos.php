<head>
    <style>
        .liverpoold {
            border: 3px outset black;
            background-color: darkgrey;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 10px;
            margin-left: 20px;
            margin-right: 20px;
        }
        .dublind {
            border: 3px outset black;
            background-color: darkgrey;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 10px;
            margin-left: 20px;
            margin-right: 20px;

        }
    </style>
</head>
<?php

include_once('./phpFlickr.php');

$api_key = "c56879e3bd574508a9ec0d24944ee156";
$api_secret = "69b8e321ce101998";


$liv_request = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key='.$api_key.'&tags=LiverpoolCity&format=json&nojsoncallback=1&per_page=10';
$liv_response = json_decode(file_get_contents($liv_request));

$liv_photos = $liv_response->photos->photo;

echo ('<h1>Recent Photos of Liverpool</h1>');
echo ('<div class="liverpoold">');
foreach ($liv_photos as $photo) {
    $photos_url = 'https://live.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_m.jpg';
    echo '<img src="'.$photos_url.'">';
};
echo ('</div>');


$dub_request = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key='.$api_key.'&tags=DublinCity&format=json&nojsoncallback=1&per_page=10';
$dub_response = json_decode(file_get_contents($dub_request));

$dub_photos = $dub_response->photos->photo;

echo ('<h1>Recent Photos of Dublin</h1>');
echo ('<div class="dublind">');
foreach ($dub_photos as $photo) {
    $photos_url = 'https://live.staticflickr.com/' . $photo->server . '/' . $photo->id . '_' . $photo->secret . '_m.jpg';
    echo '<img src="' . $photos_url . '">';
};
echo ('</div>');

