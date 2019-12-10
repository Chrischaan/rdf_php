<?php
    include_once "api.php";
    $result = array();

    $result["message"] = "";

    $action = $_POST["action"];

    if($action == "get_country") { // When continent change, get country belongs to selected continent.
        $continent = $_POST["continent"];
        $result["data"] = getCountry($continent);
    } else if($action == "get_city") { // When country change, get city belongs to selected country.
        $country = $_POST["country"];
        $result["data"] = getCity($country);
    } else if($action == "get_country_list") {
        $currency = $_POST["currency"];
        $language = $_POST["language"];
        $result["data"] = getCountryList($currency, $language);
    } else if($action == "get_city_list") {
        $result["data"] = getCityList(isset($_POST["country_s"]) ? $_POST["country_s"] : []
            , $_POST["city_type"], $_POST["sort"]);
    }

    echo json_encode($result);
?>