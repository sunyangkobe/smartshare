<?php
/*
 * 2011 Jul 29
 * CSC309 - Carpool Reputation System
 *
 * Harro!
 * This takes care of admin view!
 *
 * @author Min Woo Lee
 */

?>
	<?php
	$user = retrieveUser();
	if (is_null($user) || !$user->isAdmin()) { //checks if user is valid and admin
		?>
	<script type="text/javascript">
			window.setInterval("run();", 1000);
		</script>
	<?php
	echo "You need to login as admin account in order to proceed to this page... Redirecting in <span id=sec>3</span> secs...";
	movePage(301, "index.php", 3);
	} else{

		//Retrieve data 	
		date_default_timezone_set('America/Toronto');
		
		$today = getdate();
		$mon = $today["mon"];
		$mday = $today["mday"];
		$year = $today["year"];	

		// last 7 days & gas prices
		
		$day1  = date("y-m-d", mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
		$day2  = date("y-m-d", mktime(0, 0, 0, date("m")  , date("d")-5, date("Y")));
		$day3  = date("y-m-d", mktime(0, 0, 0, date("m")  , date("d")-4, date("Y")));
		$day4  = date("y-m-d", mktime(0, 0, 0, date("m")  , date("d")-3, date("Y")));
		$day5  = date("y-m-d", mktime(0, 0, 0, date("m")  , date("d")-2, date("Y")));
		$day6  = date("y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
		$day7  = date("y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));		

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$day1'"));
		$day1p = $array["gprice"];
		
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$day2'"));
		$day2p = $array["gprice"];
		
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$day3'"));
		$day3p = $array["gprice"];
		
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$day4'"));
		$day4p = $array["gprice"];
		
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$day5'"));
		$day5p = $array["gprice"];
		
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$day6'"));
		$day6p = $array["gprice"];
		
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$day7'"));
		$day7p = $array["gprice"];
		
		if (Database::obtain()->fetch(Database::obtain()->query("SELECT *FROM `gasprice` where date = '$year-$mon-$mday'"))){
			$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$year-$mon-$mday'"));
			$todaygas = $array["gprice"];
		}
		else $todaygas = '0';
		
		// # of user
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(uid) AS '# of Users registered' FROM `users` WHERE 1"));
		$numusers = $array["# of Users registered"];

		// # of trips made
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS '# of Trips made' FROM `trips` WHERE 1"));
		$totaltrips = $array["# of Trips made"];

		// # avg ages of users
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT AVG(age) AS 'Average Age of users' FROM `users` WHERE 1"));
		$aveage = $array["Average Age of users"];

		// # Total Distance Traveled
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT SUM(distance) AS 'Total Distance' FROM `trips` WHERE 1"));
		$totaldistance = $array["Total Distance"];

		// # Average Distance per Trip
		$avgdis = $totaldistance / $totaltrips;

		// # Average Price per KM
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT SUM(price) AS 'Total Price' FROM `trips` WHERE 1"));
		$totalprice = $array["Total Price"];
		$avgprice = $totalprice / $totaldistance;
		
		// # Average emission 
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT AVG(emission) AS 'avgemission' FROM cars, trips WHERE cars.car_id = trips.car_id"));
		$avgemission = $array["avgemission"];

		// # Car Classes
		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='1'"));
		$economy = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='2'"));
		$compact = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='3'"));
		$intermediate = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='4'"));
		$standard = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='5'"));
		$full  = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='6'"));
		$premium = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='7'"));
		$luxury = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='8'"));
		$minivan = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='9'"));
		$isuv = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='10'"));
		$ssuv = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='11'"));
		$lsuv  = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='12'"));
		$pickup = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='13'"));
		$lpickup = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='14'"));
		$cvan = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='15'"));
		$van12 = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='16'"));
		$van15 = $array["carclass"];

		$array = Database::obtain()->fetch(Database::obtain()->query("SELECT COUNT(trip_id) AS 'carclass' FROM `trips` WHERE car_id='17'"));
		$convertible = $array["carclass"];
		?>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
				
				  // GOOGLE CHART (Pie)
				  
				<?php
					if ($economy) echo("economy = $economy;");
						else echo("economy = 0;");
					if ($compact)echo("compact = $compact;");
						else echo("compact = 0;");
					if ($intermediate)echo("intermediate = $intermediate;");
						else echo("intermediate = 0;");
					if ($standard)echo("standard = $standard;");
						else echo("standard = 0;");
					if ($full)echo("full = $full;");
						else echo("full = 0;");
					if ($premium)echo("premium = $premium;");
						else echo("premium = 0;");
					if ($luxury)echo("luxury = $luxury;");
						else echo("luxury = 0;");
					if ($minivan)echo("minivan = $minivan;");
						else echo("minivan = 0;");
					if ($isuv)echo("isuv = $isuv;");
						else echo("isuv = 0;");
					if ($ssuv)echo("ssuv = $ssuv;");
						else echo("ssuv = 0;");
					if ($lsuv)echo("lsuv = $lsuv;");
						else echo("lsuv = 0;");
					if ($pickup)echo("pickup = $pickup;");
						else echo("pickup = 0;");
					if ($lpickup)echo("lpickup = $lpickup;");
						else echo("lpickup = 0;");
					if ($cvan)echo("cvan = $cvan;");
						else echo("cvan = 0;");
					if ($van12)echo("van12 = $van12;");
						else echo("van12 = 0;");
					if ($van15)echo("van15 = $van15;");
						else echo("van15 = 0;");
					if ($convertible)echo("convertible = $convertible;");
						else echo("convertible = 0;");
				?>		  
				  
				  // Load the Visualization API and the piechart package.
				  google.load('visualization', '1.0', {'packages':['corechart']});
				  
				  // Set a callback to run when the Google Visualization API is loaded.
				  google.setOnLoadCallback(drawChart);
				  
				  // Callback that creates and populates a data table, 
				  // instantiates the pie chart, passes in the data and
				  // draws it.
				  function drawChart() {

				  // Create the data table.
				  var data = new google.visualization.DataTable();
				  data.addColumn('string', 'Topping');
				  data.addColumn('number', 'Slices');
				  data.addRows([				  
					['Economy', economy],
					['Compact', compact],					
					['Intermediate', intermediate], 
					['Standard', standard],
					['Full Size', full],
					['Premium', premium],
					['Luxury', luxury],
					['Minivan', minivan],
					['Intermediate SUV', isuv],
					['Standard SUV', ssuv],
					['Large SUV', lsuv],
					['Pickup Truck', pickup],
					['Large Pickup', lpickup],
					['Cargo Van', cvan],
					['12 Seat Van', van12],
					['15 Seat Van', van15],
					['Convertible', convertible]
				  ]);

				
				  // Set chart options
				  var options = {'title':'Car Class used among Trips',
								 'width':400,
								 'height':300};

				  // Instantiate and draw our chart, passing in some options.
				  var chart = new google.visualization.PieChart(document.getElementById('chart1'));
				  chart.draw(data, options);
				}
			</script>
						
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
				// GOOGLE CHART (Line)
				
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'Date');
					data.addColumn('number', 'Gas Price ($)');
					data.addRows(7);
					<?php 
						echo("data.setValue(0, 0, '$day1');");
						echo("data.setValue(1, 0, '$day2');"); 
						echo("data.setValue(2, 0, '$day3');"); 
						echo("data.setValue(3, 0, '$day4');"); 
						echo("data.setValue(4, 0, '$day5');"); 
						echo("data.setValue(5, 0, '$day6');"); 
						echo("data.setValue(6, 0, '$day7');");
						
						echo("data.setValue(0, 1, $day1p);");
						echo("data.setValue(1, 1, $day2p);"); 
						echo("data.setValue(2, 1, $day3p);"); 
						echo("data.setValue(3, 1, $day4p);"); 
						echo("data.setValue(4, 1, $day5p);"); 
						echo("data.setValue(5, 1, $day6p);"); 
						echo("data.setValue(6, 1, $day7p);"); 
					?>

				var chart = new google.visualization.LineChart(document.getElementById('chart2'));
				chart.draw(data, {width: 600, height: 240, title: 'Gas Price Changes'});
			}
			</script>

			
	<fieldset style="width: 98%;">
	<legend>
		<b> <?php echo "Admin View Main - Welcome, Admin ";
		echo $user->getUsername();
		echo "!"?> </b>
	</legend>
			
	<hr />
	
	<table border="10" width="98%" align="center" bgcolor="#EEFFDF"
		style='table-layout: fixed'>
	
		<tr>
			<td align= "center"> <strong><a href='index.php?action=adminview' title='Home' class='current'> <span>Admin View Main</span></a> </strong> </td>
			<td align= "center"> <strong><a href='index.php?action=adminview_user' title='Home' class='current'> <span>User Administration</span></a> </strong> </td>
			<td align= "center"> <strong><a href='index.php?action=adminview_newsfeed' title='Home' class='current'> <span>News Feed Updates</span></a> </strong> </td>
		</tr>
	</table>
	
	<hr />
	<form name="gasp" id="gasp" action="account/ajax_gasp.php" method="POST">
		
	<table border="0" width="98%" align="center"
		style='table-layout: fixed'>
		<tr>
			<th>Users Info</th>
		</tr>
		<tr>
			<td align="center">
				<p>
					Total Number of Registered Users : <strong><?php echo " $numusers Registered";?>
					</strong>
				</p>
				<p>
					Average Ages of Users : <strong><?php echo round($aveage, 1);?> </strong>
				</p>
			</td>
		</tr>

		<tr>
			<td><hr /></td>
		</tr>

		<tr>
			<th>Trips Info</th>
		</tr>
		<tr>
			<td align="center">
				<p>
					Total Number of Trips that have made : <strong><?php echo "$totaltrips Trips";?>
					</strong>
				</p>
				<p>
					Total Distance Traveled : <strong><?php echo "$totaldistance km";?>
					</strong>
				</p>
				<p>
					Average Distance per Trip : <strong><?php echo round($avgdis, 2);
					echo " km per trip";?> </strong>
				</p>
				<p>
					Average Price per 1 km : <strong><?php
					echo round($avgprice, 2);
					echo " CAD per 1 km";?> </strong>
				</p>
			</td>
		</tr>

		<tr>
			<td><hr /></td>
		</tr>

		<tr>
			<th>Cars Info</th>
		</tr>
		<tr>
			<td align="center">
				<div id="chart1"></div>
			</td>
		</tr>
		<tr>
			<td align="center">				
				<p>
					Average emissions of the cars : <strong><?php echo "$avgemission ppm";?>
					</strong>
				</p>
			</td>
		</tr>
		
		<tr>
			<td><hr /></td>
		</tr>
		
		<tr>
			<th>Gas Price Info</th>
		</tr>
		<tr>
			<td align="center">
				<div id="chart1"></div>
			</td>
		</tr>
		<tr>
			<td align="center">
				<div id="chart2"></div>
			</td>
		</tr>		
		<tr>
			<td align="center">			
					<p>
					Today Gas Price : <strong><?php echo "($year-$mon-$mday) \$ $todaygas";?>
					</strong>
				</p>
			</td>
		</tr>
			
		<tr>
			<td align="center"><label for="price">New Gas Price: </label>
			<input name="price" type="text" id="price" style="width: 180px" />	
			<input id="gasp" name="gasp" type="submit" value="Submit" /></td>				
		</tr>
	</table>
	</form>

</fieldset>

	<?php
	}?>
