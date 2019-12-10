<?php
    # $result = array(); 
    # echo json_encode($result);
    require 'vendor/autoload.php';  

    function getConnection() {
        $sparql = new EasyRdf_Sparql_Client('http://localhost:3030/cities/sparql');
        // $sparql = new EasyRdf_Sparql_Client('http://localhost:3030/dreamtravel/sparql');
        return $sparql;
    }

    function formatResult($result) {
        $rows = array();
        foreach($result as $r) {
            $row = array();
            foreach($r as $k => $v) {
                if($v instanceof EasyRdf_Literal) {
                    $text = $v->getValue();
                } else {
                    $text = str_replace("http://www.DreamTravel.com/", "", $v->dumpValue('text'));
                }
                $row[$k] = $text;
            }

            array_push($rows, $row);
        }

        return $rows;
    }

    function getContinent() {
        $sparql = getConnection();
        $result = $sparql->query('
            PREFIX  : <http://www.DreamTravel.com/>
            SELECT ?continent
            WHERE {
                ?continent <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> :Continent
            }
            ORDER BY ?continent
        ');

        return formatResult($result);
    }

    function getCountry($continent = "") {
        $sparql = getConnection();
        $result = $sparql->query("
            PREFIX  : <http://www.DreamTravel.com/>
            SELECT *
            WHERE {
                ?country :isCountryOf :$continent
            }
            ORDER BY ?country
        ");

        return formatResult($result);
    }

    function getCity($country = "") {
        $sparql = getConnection();
        if($country == null || $country == '') {
            $result = $sparql->query('
                PREFIX  : <http://www.DreamTravel.com/>
                SELECT DISTINCT ?city
                WHERE {
                    ?city :isCityOf|:isCapitalCityOf ?country
                }
                ORDER BY ?city
            ');
        } else {
            $result = $sparql->query("
                PREFIX  : <http://www.DreamTravel.com/>
                SELECT DISTINCT ?city
                WHERE {
                    ?city :isCityOf|:isCapitalCityOf :$country
                }
                ORDER BY ?city
            ");
        }

        return formatResult($result);
    }

    function getCurrency() {
        $sparql = getConnection();
        $result = $sparql->query('
            PREFIX  : <http://www.DreamTravel.com/>
            SELECT DISTINCT ?currency
            WHERE {
                ?s :Trade_in ?currency
            }
            ORDER BY ?currency
        ');

        return formatResult($result);
    }

    function getLanguage() {
        $sparql = getConnection();
        $result = $sparql->query('
            PREFIX  : <http://www.DreamTravel.com/>
            SELECT DISTINCT ?language
            WHERE {
                ?s :use ?language
            }
            ORDER BY ?language
        ');

        return formatResult($result);
    }

    function getCountryList($currency, $language) {
        $sparql = getConnection();

        $condition = "";
        if($currency != "") {
            $condition = $condition."?country :Trade_in :$currency.";
        }

        if($language != "") {
            $condition = $condition."?country :use :$language.";
        }

        if($condition == "") {
            return getCountry();
        }

        $result = $sparql->query("
            PREFIX  : <http://www.DreamTravel.com/>
            SELECT DISTINCT ?country
            WHERE {
                $condition
            }
            ORDER BY ?country
        ");

        return formatResult($result);
    }

    function getCityList($country_list, $type, $sort) {
        $sparql = getConnection();

        $condition = "
                ?city :Number_of_tourists ?tourists.
                ?city :isCityOf ?country.
                ?city :Population ?population.
                ?country :isCountryOf ?continent.
            ";
        if($type != "") {
            $condition = $condition."?city ?p :$type.";
        }

        $country_where = join(", :", $country_list);
        if($country_where != "") {
            $condition = $condition."\nFILTER(?country IN(:$country_where))";
        }

        if($sort == "tdesc") {
            $order = "ORDER BY DESC(xsd:nonNegativeInteger(?tourists))";
        } else if($sort == "tasc") {
            $order = "ORDER BY ASC(xsd:nonNegativeInteger(?tourists))";
        } else if($sort == "pdesc") {
            $order = "ORDER BY DESC(xsd:nonNegativeInteger(?population))";
        } else if($sort == "pasc") {
            $order = "ORDER BY ASC(xsd:nonNegativeInteger(?population))";
        }

        $result = $sparql->query("
            PREFIX  : <http://www.DreamTravel.com/>
            SELECT *
            WHERE {
                $condition
            }
            $order
        ");

        return formatResult($result);
    }

?>