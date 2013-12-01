<?php
/*
 * 2011 Jul 20
 * CSC309 - Carpool Reputation System
 *
 * Search Trips View
 *
 * @author Kobe Sun
 *
 */

$now = date("Y-m-d H:i:s"); //current date
$criteria = "user_role='driver' AND start_date >= '"; 
$criteria .= ($_GET["start_date"] == "") ? $now : $_GET["start_date"];
$criteria .= "'";
$des = isset($_GET["destination"]) ? $_GET["destination"] : ""; //set destination
if (isset($_GET["home"])) {
	$criteria .= " AND home LIKE '%" . $_GET["home"] . "%'";
}
Session::getInstance()->criteria = $criteria;
Session::getInstance()->destination = $des;

?>
<link
	href="css/datepicker_dashboard/datepicker_dashboard.css"
	rel="stylesheet">
<script
	src="js/datepicker/Locale.en-US.DatePicker.js" type="text/javascript"></script>
<script
	src="js/datepicker/Picker.js" type="text/javascript"></script>
<script
	src="js/datepicker/Picker.Attach.js" type="text/javascript"></script>
<script
	src="js/datepicker/Picker.Date.js" type="text/javascript"></script>
<link
	rel="stylesheet" media="screen" href="css/table-sorter.css" />
<script
	type="text/javascript" src="js/table-sorter.js"></script>
<script type="text/javascript">
window.addEvent('domready', function() {
	var today = new Date();

	window.start_date = new Picker.Date($('start_date'), {
		timePicker : true,
		format: 'db',
		minDate: today,
		positionOffset : {
			x : 5,
			y : 0
		},
		pickerClass : 'datepicker_dashboard',
		useFadeInOut : !Browser.ie
	});

	sorter = new TableSorter({
		request: 'action', 
		page: 'trip/listTrip.php',
		destination: 'XhrDump', 
		prev: 'PagePrev', 
		next: 'PageNext', 
		head: 'GeoHead',
		rows: 5,
		defaultStartEndWaitEnabled: 1,
		startWait: '',
		endWait: ''
	});
});
</script>

<div id="form_container">
	<form name="search" method="GET" id="search">
		<fieldset style="padding: 10px 20px;">
			<legend>
				<b>Filter Trips</b>
			</legend>
			
			<!--- SEARCH FORM --->

			<table style="float: left;">
				<tr>
					<th><label for="home">Leaving from:</label></th>
					<td><input type="text" id="home" name="home" style="width: 300px" 
						value="<?php echo $_REQUEST["home"]?>" />
					</td>
				</tr>
				<tr>
					<th><label for="destination">Going to:</label></th>
					<td><input type="text" id="destination" name="destination"
						style="width: 300px" value="<?php echo $_REQUEST["destination"]?>" />
					</td>
				</tr>
				<tr>
					<th><label for="start_date">Date after:</label></th>
					<td><input type="text" id="start_date" name="start_date"
						style="width: 180px" value="<?php echo $_REQUEST["start_date"]?>" />
					</td>
				</tr>
			</table>
			<center>
				<input type="submit" id="filter" name="filter" value="Filter Trips"
					style="width: 140px; height: 70px; font-size: 20px;" />
			</center>
			<input type="hidden" id="action" name="action" value="search" />
		</fieldset>
	</form>

	<div id="XhrDump">
	<?php
		echo Trip::genHtmlTableStr($criteria, $des);
	?>
	</div>
</div>
