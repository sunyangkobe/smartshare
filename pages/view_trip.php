<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * View Trips
 *
 * @author Kobe Sun
 *
 */

if (!isset($_REQUEST["trip_id"]) || !Trip::getTrip($_REQUEST["trip_id"])) {
	?>
	<script type="text/javascript">
		window.setInterval("run();", 1000);
	</script>
	<?php
	echo "This doesn't seem to be a valid request... Redirecting in <span id=sec>3</span> secs...";
	movePage(301, "index.php", 3);
} else {
	$trip_id = $_REQUEST["trip_id"];
	$trip = Trip::getTrip($trip_id);
	$loggedInPerson = retrieveUser();
	$driver = User::getUser($trip->getDriver()->getUid());
	include_once("pages/googlemap.php");
	?>

<fieldset style="width: 98%;">
	<legend>
		<b><?php echo $trip->getName() ?> </b>
	</legend>
	<div id="form_container">
		<table>
			<tr>
				<th width="110px">Frequency:</th>
				<td width="210px"><?php echo $trip->getFrequency(); ?>
				</td>
				<th width="110px">Price:</th>
				<td width="210px">$<?php echo $trip->getPrice() ?>
				</td>
			</tr>
			<tr>
				<th>Car Class:</th>
				<td><?php echo $trip->getCar() ?>
				</td>
				<th>Seats:</th>
				<td><?php echo $trip->getSeats() ?>
				</td>
			</tr>
			<tr>
				<th>Start Date:</th>
				<td><?php echo $trip->getStart_date() ?>
				</td>
				<th>End Date:</th>
				<td><?php echo $trip->getEnd_date() ?>
				</td>
			</tr>
		</table>

		<?php
		if ($trip->getDescription() != "") {
			?>
		<div class="viewsection">
			<h4>Description:</h4>
			<p style="padding: 0 10px;">
			<?php echo $trip->getDescription() ?>
			</p>
		</div>
		<?php
		}
		?>

		<div class="viewsection">
			<h4 style="margin-bottom: 0px;">Distance: <?php echo $trip->getDistance() ?> KM<br />Route:</h4>
			<table style="margin: 0 0 15px 40px; text-align: left;">
				<tr>
					<th width="100px">Home:</th>
					<td><?php echo $trip->getHome() ?></td>
				</tr>
				<?php
				$routes = $trip->getRoutes();
				for ($i = 0; $i < count($routes) - 1; $i++) {
					?>
				<tr>
					<th>WayStop <?php echo $i + 1?>:</th>
					<td><?php echo $routes[$i]?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<th>Destination:</th>
					<td><?php echo end($routes) ?></td>
				</tr>
			</table>
			<div id="map_canvas" style="width: 625px; height: 300px"></div>
		</div>

		<?php
		include_once("passengerdiv.php");
		
		$start_date = new DateTime($trip->getStart_date());
		$today = new DateTime("now");
		
		if ($today < $start_date) {
			include_once("candidatediv.php");
			include_once("functionaldiv.php");
		} else {
			?>
		<div class="viewsection" style="padding: 20px;">
			<center>
				<i>This is an expired trip.</i>
			</center>
		</div>
		<?php
		}
		?>
	</div>

</fieldset>
		<?php
}
?>

