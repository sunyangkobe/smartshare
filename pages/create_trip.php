<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Create Trip View
 *
 * @author Kobe Sun
 *
 */
?>

<link
	href="css/datepicker_dashboard/datepicker_dashboard.css"
	rel="stylesheet">
	
<script type="text/javascript">
	window.init = 1;
	window.counter = 1;
</script>

<fieldset style="width: 98%;">
	<legend>
		<b>Create Trip</b>
	</legend>
	<div id="form_container">

	<?php
	$user = retrieveUser();
	if (is_null($user)) {
		?>
		<script type="text/javascript">
			window.setInterval("run();", 1000);
		</script>
		<?php
		movePage(301, "index.php", 3);
		echo "You need to login in order to proceed to this page..."
		. "Redirecting in <span id=sec>3</span> secs...";
	} else {
		?>
		<script src="js/datepicker/Locale.en-US.DatePicker.js"
			type="text/javascript"></script>
		<script src="js/datepicker/Picker.js" type="text/javascript"></script>
		<script src="js/datepicker/Picker.Attach.js" type="text/javascript"></script>
		<script src="js/datepicker/Picker.Date.js" type="text/javascript"></script>
		<script type="text/javascript"
			src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script src="js/create_trip.js" type="text/javascript"></script>

		<form name="trip_form" id="trip_form" action="trip/createTrip.php"
			onSubmit="return validateForm()" method="POST">
			<table>
				<tr>
					<td><label for="name">Name:</label></td>
					<td colspan="2"><input name="name" type="text" id="name"
						style="width: 180px" />
					</td>
				</tr>

				<tr>
					<td><label for="description">Description:</label></td>
					<td colspan="2"><textarea name="description" id="description"
							cols="50" rows="4" style="resize: none;"></textarea>
					</td>
				</tr>

				<tr>
					<td><label for="seats">Seats:</label></td>
					<td colspan="2"><select name="seats" id="seats">
					<?php
					for ($i = 1; $i <= 25; $i++) {
						if ($i == 4) {
							echo "<option selected='selected'>$i</option>";
						} else {
							echo "<option>$i</option>";
						}
					}
					?>
					</select>
					</td>
				</tr>

				<tr>
					<td><label for="price">Price:</label></td>
					<td colspan="2">$ <input name="price" type="text" id="price"
						style="width: 40px" />
					</td>
				</tr>
				
				<tr>
					<td><label for="frequency">Frequency:</label></td>
					<td colspan="2"><select id="frequency" name="frequency">
							<option value="Once Only" selected="selected">Once Only</option>
							<option value="Everyday">Everyday</option>
					</select>
					</td>
				</tr>

				<tr>
					<td><label for="start_date">Start Date/Time:</label></td>
					<td colspan="2"><input name="start_date" id="start_date"
						type="text" style="width: 180px" /></td>
				</tr>

				<tr>
					<td><label for="end_date">End Date/Time:</label></td>
					<td colspan="2"><input name="end_date" id="end_date" type="text"
						style="width: 180px" /></td>
				</tr>

				<tr>
					<td><label for="home">Home:</label></td>
					<td colspan="2"><input name="home" type="text" id="home"
						style="width: 300px;" />
					</td>
				</tr>

				<tr>
					<td valign="top"><label for="routes">Destination:</label></td>
					<td width="300px"><div id="destination">
							<input name="route1" id="route1" type="text"
								style="width: 300px;" />
						</div>
					</td>
					<td valign="top">
						<input type="button" onclick="addWayStop()" value="Add" /> 
						<input type="button" id="removeWaystop" onclick="removeWayStop()"
							value="Remove" style="display: none; float: right" />
					</td>

				</tr>

				<tr>
					<td><label for="car">Car:</label></td>
					<td colspan="2"><select id="car_id" name="car_id">
					<?php
					$cars = CarClass::getValues();
					array_shift($cars);
					for ($i = 0; $i < count($cars); $i++) {
						$j = $i + 1;
						if ($i == $user->getCarId() - 1) {
							echo "<option selected='selected' value='$j'>$cars[$i]</option>";
						} else {
							echo "<option value='$j'>$cars[$i]</option>";
						}
					}
					?>
					</select>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td align="center" colspan="2"><input id="create" name="create"
						type="submit" value="Submit" />
					</td>
					<td align="left"><input id="reset" name="reset" type="reset"
						value="Reset" />
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

			</table>

			<input name="driver" type="hidden" id="driver"
				value="<?php echo $user->getUid() ?>" /> <input name="counter"
				type="hidden" id="counter" value="window.counter" />
		</form>
		<?php
	}
	?>
	</div>
</fieldset>
