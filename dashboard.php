<?php
//supress warnings & errors
error_reporting(0);

//a function to get JSON data from a given url
function getJSONFromURL($url) {
    //set up url, append api key 
    $apiKey = '8b764506552125710ce8a32479ddc5f4';
    $url .= $apiKey;

    //set up curl (client uniform resource locator)
    //initiate
    $ch = curl_init();
    //returns response, if false it will print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    //set the url
    curl_setopt($ch, CURLOPT_URL, $url); 
    //execute
    $result = curl_exec($ch);
    //close
    curl_close($ch); 

    $result = file_get_contents($url);
    $json = json_decode($result, true);
    return $json;
}

//a function that gets cities and outputs it
function outputCities() {
    //get city variable from user
    $city = $_POST['city'];

    //prepare the url of API we want to access
    $url = 'http://api.openweathermap.org/geo/1.0/direct?q=' . $city . '&limit=5&appid=';

    //make api call to get JSON data
    $json = getJSONFromURL($url);

    //output data
    //for each city, get the weather details
    foreach ($json as $value) {
        //format longitude and lattitude for api call
        $lat = substr($value['lat'],0,5);
        $lon = substr($value['lon'],0,5);
        
        //get weather data using latitude and longitude coordinates
        $cityWeatherJSON = getWeather($lat,$lon);

        //display weather data
        // $output .= '<p>32deg<br>';
        // $output .= $value['name'] . ', ' . $value['state'] . '<br>';
        // $output .= 'humidity: <br><br>';
    }
    //echo $output;    
}

//a function that gets the weather using latitude and longitude (reqired) of a city
function getWeather($lat,$lon) {
    echo '<br>lat: ' . $lat . ' lon: ' . $lon . '<br>';

    //prepare the url of API we want to access
    $url = 'https://api.openweathermap.org/data/3.0/onecall?lat=' . $lat . '&lon='. $lon .'&exclude=hourly,daily&appid=';
    
    $json = getJSONFromURL($url);
    echo '<br>json:<br>';
    foreach ($json as $value) {
        echo 'timezone: ' . $value['timezone'] . '<br>';
    }
    //return $json;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyWeather | Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script>
        //make sure city input box is filled out
        function validate() {}

        function setCity(city) {
            lat = city.dataset.lat;
            lon = city.dataset.lon;

            //clear previous data
            localStorage.clear();

            //set data
            localStorage.setItem('lon', lon);
            localStorage.setItem('lat',lat);
        }
    </script>
</head>
<body>
    <div class="dashboard">
        <div class="favorites">
            <button>show my favorites</button>
        </div>
        <div class="details">
            <div class="searchContainer">
                <form action="dashboard.php" method="POST" onsubmit="return validate()">
                    <input type="text" class="searchBar" name="city" id="city" placeholder="Search Location By City">
                    <input type="submit" name="" id="" value="Submit">
                </form>
            </div>

            <div id="output">
                <?php
                    if (isset($_POST['city'])) {
                        
                        //output the data
                        outputCities($json);
                    }
                ?>
                
            </div>

        </div>
    </div>
</body>
</html>