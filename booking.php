<?php
	session_start();

	# include_once "functions.php";
	include_once "api.php"
?>

<html>
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">

		<!-- 开发环境版本，包含了有帮助的命令行警告 -->
		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

        <script language="javascript">
            function format_number(number) {
                return "&nbsp;" + parseFloat(Math.round(number * 100) / 100).toFixed(2);
            }

            function on_continent_change() {
            	$continent = document.getElementById("continent").value;
            	console.log($continent);

                var data = new FormData();
                data.append("action", "get_country");
                data.append("continent", $continent);

                fetch("json_api.php", {
                    method:"post"
                    , credentials: "same-origin"
                    , body:data
                }).then(function (resp) {
                    return resp.json();
                }).then(function (result) {
                    if(result.message == '') {
                    	var country = document.getElementById("country");
                    	country.options.length = 0;

                        result.data.forEach(function(item){
                            country.options[country.options.length] = new Option(item['country'], item['country']);
                        });

                    	var city = document.getElementById("city");
                    	city.options.length = 0;
                    } else {
                        alert(result.message);
                    }
                });
            }

            function on_country_change() {
            	$country = document.getElementById("country").value;
            	console.log($country);

                var data = new FormData();
                data.append("action", "get_city");
                data.append("country", $country);

                fetch("json_api.php", {
                    method:"post"
                    , credentials: "same-origin"
                    , body:data
                }).then(function (resp) {
                    return resp.json();
                }).then(function (result) {
                    if(result.message == '') {
                    	var city = document.getElementById("city");
                    	city.options.length = 0;

                        result.data.forEach(function(item){
                            city.options[city.options.length] = new Option(item['city'], item['city']);
                        });
                    } else {
                        alert(result.message);
                    }
                });
            }

            function get_country_list() {
            	$currency = document.getElementById("currency").value;
            	$language = document.getElementById("language").value;

                var data = new FormData();
                data.append("action", "get_country_list");
                data.append("currency", $currency);
                data.append("language", $language);

                fetch("json_api.php", {
                    method:"post"
                    , credentials: "same-origin"
                    , body:data
                }).then(function (resp) {
                    return resp.json();
                }).then(function (result) {
                    if(result.message == '') {
                    	var country_s = document.getElementById("country_s");
                    	country_s.options.length = 0;

                        result.data.forEach(function(item){
                            country_s.options[country_s.options.length] = new Option(item['country'], item['country']);
                        });
                    } else {
                        alert(result.message);
                    }
                });
            }


            function get_city_list() {
                var data = new FormData(document.getElementById("form2"));
                data.append("action", "get_city_list");

                fetch("json_api.php", {
                    method:"post"
                    , credentials: "same-origin"
                    , body:data
                }).then(function (resp) {
                    return resp.json();
                }).then(function (result) {
                    if(result.message == '') {
						var table = document.getElementById("city_list_body");
                        while(table.rows.length > 0) {
                            table.deleteRow(0);
                        }

                        result.data.forEach(function(item){
                        	var row = table.insertRow();
                        	row.insertCell().innerHTML = item.city;
                        	row.insertCell().innerHTML = item.tourists;
                        	row.insertCell().innerHTML = item.population;
                        	row.insertCell().innerHTML = item.country;
                        	row.insertCell().innerHTML = item.continent;

                        	console.log(item);
                        });
                    } else {
                        alert(result.message);
                    }
                });

            }

        </script>

		<title>Dream Travel</title>
    </head>
	<body>
		<div class="text-center">
		    <?php echo "<h1>Dream Travel</h1>"; ?>
		</div>

		<div class="container">
			<div class="row alert alert-info">
			    <h5>Book directly</h5>
			</div>
			<div class="row">
                <form class="col-md-12" id="form1">
					<div class="form-group row">
						<label for="continent" class="col-1 col-form-label">Continent</label> 
						<div class="col-3">
                            <select class="custom-select" id="continent" onchange="on_continent_change();">
                            	<?php 
                            		foreach(getContinent() as $continent) {
                            			echo "<option value='$continent[continent]'>$continent[continent]</option>";
                            		}
                            	?>
                            </select>
						</div>
						<label for="country" class="col-1 col-form-label">Country</label> 
						<div class="col-3">
                            <select class="custom-select" id="country" onchange="on_country_change();">
                            	<?php 
#                            		foreach(getCountry() as $country) {
#                            			echo "<option value='$country[country]'>$country[country]</option>";
#                            		}
                            	?>
                            </select>
						</div>
						<label for="city" class="col-1 col-form-label">City</label> 
						<div class="col-3">
                            <select class="custom-select" id="city">
                            	<?php 
#                            		foreach(getCity() as $city) {
#                            			echo "<option value='$city[city]'>$city[city]</option>";
#                            		}
                            	?>
                            </select>
						</div>
					</div>
					<div class="for-group row">
						<div class="col-4">
							<input type="button" class="btn btn-primary" value="Book Now!">
						</div>
					</div>
                </form>
	        </div>

			<div class="row alert alert-info">
			    <h5>Or book by filter</h5>
			</div>
			<div class="row">
                <form class="col-md-12" id="form2">
					<div class="form-group row">
						<label for="currency" class="col-1 col-form-label">Currency</label> 
						<div class="col-3">
                            <select class="custom-select" id="currency" onchange="get_country_list();">
                            	<option value="">[--None--]</option>
                            	<?php 
                            		foreach(getCurrency() as $row) {
                            			echo "<option value='$row[currency]'>$row[currency]</option>";
                            		}
                            	?>
                            </select>
						</div>
						<label for="language" class="col-1 col-form-label">Language</label> 
						<div class="col-3">
                            <select class="custom-select" id="language" onchange="get_country_list();">
                            	<option value="">[--None--]</option>
                            	<?php 
                            		foreach(getLanguage() as $row) {
                            			echo "<option value='$row[language]'>$row[language]</option>";
                            		}
                            	?>
                            </select>
						</div>
						<label for="country_s" class="col-1 col-form-label">Countries</label> 
						<div class="col-3">
                            <select class="custom-select" id="country_s" name="country_s[]" multiple onchange="get_city_list();">
                            </select>
						</div>
					</div>
					<div class="form-group row">
						<label for="city_type" class="col-1 col-form-label">Type</label> 
						<div class="col-3">
                            <select class="custom-select" id="city_type" name="city_type" onchange="get_city_list();">
                            	<option value="">[--None--]</option>
                                <option value="Inland_City">InlandCity</option>
                                <option value="Coastal_City">CoastalCity</option>
                            </select>
						</div>
						<label for="sort" class="col-1 col-form-label">Sort By</label> 
						<div class="col-3">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="sort" value="tdesc" checked onchange="get_city_list();">
								<label class="form-check-label" for="popular1">Most popular</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="sort" value="tasc" onchange="get_city_list();">
								<label class="form-check-label" for="popular2">Least popular</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="sort" value="pdesc" onchange="get_city_list();">
								<label class="form-check-label" for="popular2">Most population</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="sort" value="pasc" onchange="get_city_list();">
								<label class="form-check-label" for="popular2">Least population</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<table id="city_list" class="table table-striped">
							<thead>
								<td>City</td>
								<td>Number of tourists</td>
								<td>Population</td>
								<td>Country</td>
								<td>Continent</td>
							</thead>
							<tbody id="city_list_body">
							</tbody>
						</table>
					</div>
					<div class="form-group row">
						<div class="col-4">
							<input type="button" class="btn btn-primary" value="Book Now!">
						</div>
					</div>					
                </form>
	        </div>
		</div>
    </body>
</html>
