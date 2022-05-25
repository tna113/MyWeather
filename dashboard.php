<?php
//supress warnings & errors
error_reporting(0);

//a function to get JSON data from a given url
function getJSONFromURL($url) {
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

//outputs the possible location data
function outputPossibleLocations($json) {
    $output = '';
    foreach ($json as $value) {
        $output .= '<button>' . $value['name'] . ', ' . $value['state'] . '</button>';
    }
    return $output;
}

//api key for OpenWeather API
$apiKey = '8b764506552125710ce8a32479ddc5f4';

//if there is user input inside city search box, do this
if (isset($_POST['city'])) {
    //get city variable from user
    $city = $_POST['city'];
    //implementing OpenWeather API
    $url = 'http://api.openweathermap.org/geo/1.0/direct?q=' . $city . '&limit=5&appid=' . $apiKey;
    $json = getJSONFromURL($url);

    //output the data
    echo outputPossibleLocations($json);
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
        function validate() {
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
                <!-- <div class="weather">
                    <h2 id="temp">32°F</h2>
                    <h3 id="location">rochester, ny</h3>
                    <p>humidity: <span id="humidity">30%</span></p>
                </div>
                <div class="options">
                    <button>daily</button>
                    <button>weekly</button>
                    <button>custom date</button>
                </div> -->
            </div>

        </div>
    </div>
</body>
</html>