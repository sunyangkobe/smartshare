<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Functional Div View on the view trip page
 *
 * @author Kobe Sun
 *
 */


if ($loggedInPerson) {
	if ($driver->getUid() == $loggedInPerson->getUid()) {
		?>
		<div class="viewsection" style="padding: 20px;">
			<table>
				<tr>
					<td width="300px" align="center">
						<form name="modify_form" id="modify_form"
							action="index.php?action=modify_trip" method="POST">
							<input name="trip_id" type="hidden" id="trip_id"
								value="<?php echo $trip->getTrip_id(); ?>" /> 
							<input type="submit"
								id="modify" name="modify" value="Modify the Trip" />
						</form>
					</td>
					<td width="300px" align="center">
						<form name="cancel_form" id="cancel_form"
							action="trip/cancelTrip.php" method="POST" 
							onsubmit="return alert('Your trip has been successfully cancelled.');">
							<input name="trip_id" type="hidden" id="trip_id"
								value="<?php echo $trip->getTrip_id(); ?>" />
							<input type="submit"
								id="cancel" name="cancel" value="Cancel the Trip" 
								onclick="return confirm('Are you sure to cancel this trip?')"/>
						</form>
					</td>
		
				</tr>
			</table>
		</div>
		<?php
	} elseif ($loggedInPerson->userExists($trip->getPassengers())
		|| $loggedInPerson->userExists($candidates)) {
		?>
		<div class="viewsection" style="padding: 20px;">
			<form name="quit_form" id="quit_form" action="trip/quitTrip.php"
				method="POST" onsubmit="return alert('You successfully quit from the trip.');">
				<input name="trip_id" type="hidden" id="trip_id"
					value="<?php echo $trip->getTrip_id(); ?>" /> 
				<input id="page_url" name="page_url" type="hidden" 
					value="<?php echo $_SERVER["REQUEST_URI"]?>">
				<center>
					<input type="submit" id="quit" name="quit"
						value="Quit the Trip" />
				</center>
			</form>
		</div>
		<?php
	} elseif ($trip->getAvailSeats() > 0) {
		?>
		<div class="viewsection" style="padding: 20px;">
			<form name="join_form" id="join_form" action="trip/joinTrip.php"
				method="POST" onsubmit="return alert('You are now in the candidate list.');">
				<input name="trip_id" type="hidden" id="trip_id"
					value="<?php echo $trip->getTrip_id(); ?>" /> 
				<input id="page_url" name="page_url" type="hidden" 
					value="<?php echo $_SERVER["REQUEST_URI"]?>">
				<center>
					<input type="submit" id="join" name="join"
						value="Want to Join the Trip" />
				</center>
			</form>
		</div>
		<?php
	} else {
		?>
		<div class="viewsection" style="padding: 20px;">
			<center><i>There is no available seats.</i></center>
		</div>
		<?php 
	}

} else {
	?>
	<div class="viewsection" style="padding: 20px;">
		<center><i>You need to login to view this functional section.</i></center>
	</div>
	<?php
}
?>

