<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Candidate Div View on the view trip page
 *
 * @author Kobe Sun
 *
 */

?>
<script type="text/javascript">
function checkSeatsNumber(seats) {
	var checkbox = document.getElementById("candidate_form").elements["candidate[]"];
	var select = document.getElementById("candidate_form").elements["select"];

	var selected = 0;
	for ( var i = 0; i < checkbox.length; i++) {
		if (checkbox[i].checked) {
			selected++;
			if (selected == seats) {
				select.disabled = true;
				return;
			}
		}
	}

	if (selected < seats) {
		select.disabled = false;
	}
}
</script>

<?php
$candidates = $trip->getCandidates();

if (count($candidates) != 0) { ?>
<div class="viewsection">
	<form name="candidate_form" id="candidate_form"
		action="trip/selectCandidate.php" method="POST">
		<?php
		if ($loggedInPerson
			&& $loggedInPerson->getUid() == $driver->getUid()) {
		?>
			<h4>Candidates:</h4>
			<div style="padding: 0 30px 10px">
			<?php
			for ($i = 0; $i < count($candidates); $i++) {
				$user = User::getUser($candidates[$i]->getUid());
				?>
				<input type="checkbox" name="candidate[]"
					value="<?php echo $user->getUid() ?>"
					onchange="checkSeatsNumber(<?php echo $trip->getSeats() - count($trip->getPassengers()) ?>);" />
				<?php 
				echo $user->getUsername();
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			}?>
			</div>
			<input name="trip_id" type="hidden" id="trip_id"
				value="<?php echo $trip->getTrip_id(); ?>" />
			<input id="page_url" name="page_url" type="hidden" 
					value="<?php echo $_SERVER["REQUEST_URI"]?>">
			<center><input type="submit" id="select" name="select" value="Add Passengers" /></center>
		<?php
		} else {
			echo "<h4>Candidates: " . count($candidates) . "</h4>";
		}
		?>
	</form>
</div>
<?php
}