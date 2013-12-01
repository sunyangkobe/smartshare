<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Change Password View
 *
 * @author Kobe Sun
 *
 */


?>

<script
	src="js/change_pwd.js" type="text/javascript"></script>

<fieldset style="width: 98%; height: 470px">
	<legend>
		<b>Change my password</b>
	</legend>
	<div id="form_container">
	<?php
	if (isset($_GET["uid"])) {
		?>
		<form name="changeform" id="changeform"
			action="account/ajax_change.php" method="POST">
			<table>
				<tr>
					<td colspan="2">
						<div id="change_response">
							<!-- spanner -->
						</div>
					</td>
				</tr>

				<tr>
					<td><label for="old_password">Old Password:<br /> </label></td>
					<td><input name="old_password" type="password" id="old_password"
						style="width: 180px" />
					</td>
				</tr>

				<tr>
					<td><label for="new_password">New Password:<br /> </label></td>
					<td><input name="new_password" type="password" id="new_password"
						style="width: 180px" onkeypress="PasswordChanged(this)" /> <span
						style="vertical-align: bottom;" id="PasswordStrength"></span>
					</td>
				</tr>

				<tr>
					<td><label for="confirm_password">Confirm Password:<br /> </label>
					</td>
					<td><input name="confirm_password" type="password"
						id="confirm_password" style="width: 180px" />
					</td>
				</tr>

				<tr height="40px">
					<td align="right"><input id="confirm" name="confirm" type="submit"
						value="Confirm" /></td>
					<td align="center"><input type="button" id="cancel" name="cancel"
						value="Cancel" onclick="window.location='../index.php'" />
						<div id="ajax_loading_change">
							<img align="middle" src="images/spinner.gif" />&nbsp;Processing...<br />
						</div>
					</td>
				</tr>
			</table>

			<input name="uid" type="hidden" id="uid"
				value="<?php echo $_GET["uid"]?>" />
		</form>
		<?php
	} else {
		?>
		<h2>Oops...</h2>
		<p>This doesn't seem to be a valid request... Please contact the
			webmaster.</p>
			<?php
	}

	?>
	</div>
</fieldset>
