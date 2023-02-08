//defining a new XMLHttpRequest object to request and receive data from the twin_cities database
let ajax = new XMLHttpRequest();
let request_method = "GET";
let url = "../php/city_query.php";
let asynchronous = true;

let ajax_poi = new XMLHttpRequest();
let poi_request_method = "GET";
let poi_url = "../php/poi_query.php";
let poi_asynchronous = true;

ajax_poi.open(poi_request_method, poi_url, poi_asynchronous);
ajax_poi.send();

//setting and then initiating the request (method, url and synchronous flag)
ajax.open(request_method, url, asynchronous);
ajax.send();



//function that is called each time the ajax readyState property changes (When the data from the query gets returned
ajax.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200) {
        let city_m_data = JSON.parse(this.responseText);

        //creating a new map and initializing values
        let city1_map = L.map('city1_map').setView([city_m_data[0].Latitude, city_m_data[0].Longitude], 12);
        city1_map.scrollWheelZoom.disable();
        city1_map.dragging.disable();

        //adding a tile layer to the map
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiYXJjaGlldGVsZmVyNyIsImEiOiJjbDBtcGpodmwwNGZkM2VxaXNxZHJ1bjNjIn0.LSQdpzFTpBxOk5leYsBWRg'
        }).addTo(city1_map);

        //creating second map
        let city2_map = L.map('city2_map').setView([city_m_data[1].Latitude, city_m_data[1].Longitude], 12);
        city2_map.scrollWheelZoom.disable();
        city2_map.dragging.disable();

        //adding tile layer to the second map
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiYXJjaGlldGVsZmVyNyIsImEiOiJjbDBtcGpodmwwNGZkM2VxaXNxZHJ1bjNjIn0.LSQdpzFTpBxOk5leYsBWRg'
        }).addTo(city2_map);

        ajax_poi.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200) {
                let poi_data = JSON.parse(this.responseText);

                console.log(poi_data);

                for (let i = 0; i < poi_data.length; i++) {
                    let place_name = poi_data[i].PoiName;
                    let poi_lat = poi_data[i].PoiLatitude;
                    let poi_long = poi_data[i].PoiLongitude;
                    let city_id = poi_data[i].CityID;
                    let markers = []

                    if (city_id == 1) {
                        markers[i] = new L.marker([poi_lat, poi_long]).bindTooltip(place_name).addTo(city1_map).on('click', openPage);
                    }
                    else{
                        markers[i] = new L.marker([poi_lat, poi_long]).bindTooltip(place_name).addTo(city2_map).on('click', openPage);;
                    }
                }

                function openPage(e){
                    let event = e.latlng;

                    for (let i= 0; i < poi_data.length; i++) {
                        if (event.lat === parseFloat(poi_data[i].PoiLatitude) && event.lng === parseFloat(poi_data[i].PoiLongitude)) {
                            url = "../POI_HTML/" + poi_data[i].PhoneNum + ".html";
                            window.open(url);
                        }
                    }
                }


            }
        }
    }
}







