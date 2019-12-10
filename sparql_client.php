<?php 
	require 'vendor/autoload.php';  
	require 'api.php'

	# Dump all items	
	$sparql = new EasyRdf_Sparql_Client('http://localhost:3030/cities/sparql');
	$result = $sparql->query('
		SELECT ?s ?p ?o
		WHERE {
			?s ?p ?o
		}
	');

	# print_r($result);
#	foreach($result as $row) {
#	    print_r($row);
#	}
	# $result->dump('html');

	print_r($result->getFields());
	echo "<p>";
	print_r($result->numRows());
	echo "<p>";

	# Dump items about Beijing
	$result = $sparql->query('
		SELECT ?s ?p ?o
		WHERE {
			<http://www.DreamTravel.com/Beijing> ?p ?o
		}
	');

	echo $result->dump();
	echo "<p>";

	# Dump items about joins, and sort by population by number, not by string
	# http://rdf.myexperiment.org/howtosparql?page=ORDER%20BY
	$result = $sparql->query('
		PREFIX  : <http://www.DreamTravel.com/>

		SELECT *
		WHERE {
			?city :Population ?population .
			?city :isCapitalCityOf ?country
		}
		ORDER BY DESC(xsd:nonNegativeInteger(?population))
	');

	echo $result->dump();
	echo "<p>";

	foreach($result as $row) {
		foreach($row as $k => $v) {
			echo $k." ==> ".$v->dumpValue('text');
			echo "<p>";

			if($v instanceof EasyRdf_Literal) {
				echo $v->getValue();
				echo "<p>";
			}
		}
		echo "<p><p><p>";
	}

	$rows = getCountry();
	var_dump($rows);
?>
