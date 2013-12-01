<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Passenger Div View on the view trip page
 *
 * @author Kobe Sun
 *
 */

$start_date = new DateTime($trip->getStart_date());
$end_date = new DateTime($trip->getEnd_date());
$today = new DateTime("now");
$can_remove = $loggedInPerson 
	&& $loggedInPerson->getUid() == $driver->getUid() 
	&& $start_date > $today;
$can_rate = false;
?>

<div class="viewsection">
	<h4>Driver and Passengers:</h4>
	<?php
	if ($can_remove) {
		?>
		<form name="remove_form" id="remove_form"
			action="trip/removePassenger.php" method="POST">
			<?php
	} else {
		?>
		<form name="rate_form" id="rate_form" action="trip/rateTrip.php"
			method="POST">
		<?php
	}
	?>
			<table>
			<?php
			$all = $trip->getPassengers();
			array_unshift($all, $trip->getDriver());
			for ($i = 0; $i < count($all); $i++) {
				$user = User::getUser($all[$i]->getUid());
				$is_driver = $trip->getDriver()->getUid() == $all[$i]->getUid();
				?>
				<tr>
					<th width="110px"><?php echo $is_driver ? "Driver: " : "Passenger: "?>
					</th>
					<td width="110px"><?php echo $user->getUsername(); ?></td>
					<th width="110px">Current Score:</th>
					<td width="80px"><?php
					$score = TripUser::getScore($trip->getTrip_id(), $user->getUid());
					echo empty($score) ? "none" : $score;
					?>
					</td>
					<?php
					if ($loggedInPerson
						&& TripUser::inTrip($trip_id, $loggedInPerson->getUid())
						&& $user->getUid() != $loggedInPerson->getUid()
						&& TripUser::canRate($user->getUid(), $loggedInPerson->getUid(), $trip->getTrip_id())
						&& $end_date < $today) {
						$can_rate = true;
						?>
						<th width="110px">Score in Trip:</th>
						<td width="80px"><select id="<?php echo $user->getUid() ?>"
							name="<?php echo $user->getUid() ?>">
								<option selected="selected"></option>
								<?php
								for ($rate = 1; $rate <= 10; $rate++) {
									?>
								<option value="<?php echo $rate ?>">
								<?php echo $rate ?>
								</option>
								<?php
								}
								?>
						</select>
						</td>
					<?php
					} elseif ($can_remove && !$is_driver) {
						$button = "remove" . $user->getUid();
						?>
						<td width="80px">
							<input type="submit" name="<?php echo $button; ?>" 
								id="<?php echo $button; ?>" value="Remove Passenger">
						</td>
					<?php
					}
					?>
				</tr>
				<?php
			}
			?>
			</table>
			<?php
			if ($can_rate) {
				$confirm = "Please be aware that in order to reduce"
						. " potential issue, you can rate once only."
						. " Are you sure to continue?"
				?>
				<center>
					<div style="margin: 10px 0">
						<input name="trip_id" type="hidden" id="trip_id"
							value="<?php echo $trip->getTrip_id(); ?>" /> 
						<input name="rater_id" type="hidden" id="rater_id"
							value="<?php echo $loggedInPerson->getUid(); ?>" /> 
						<input name="page_url" type="hidden" id="page_url"
							value="<?php echo $_SERVER["REQUEST_URI"] ?>" /> 
						<input type="submit" id="rate" name="rate" value="Rate People"
							onclick="return confirm('<?php echo $confirm?>');" />
					</div>
				</center>
			<?php
			} elseif ($can_remove && !$is_driver) {
				?>
				<input name="trip_id" type="hidden" id="trip_id"
							value="<?php echo $trip->getTrip_id(); ?>" />
				<input name="page_url" type="hidden" id="page_url"
							value="<?php echo $_SERVER["REQUEST_URI"] ?>" /> 
				<?php
			}
			?>
		</form>

</div>
