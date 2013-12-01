<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Forgot Password View
 *
 * @author Kobe Sun
 *
 */

?>

<script src="js/forgot.js" type="text/javascript"></script>

<fieldset style="width: 98%; height: 470px">
	<legend>
		<b>Forgot my password</b>
	</legend>
	<div id="form_container">
		<form name="forgotform" id="forgotform"
			action="account/ajax_forgot.php" method="POST">
			<table>
				<tr>
					<td colspan="2">
						<div id="forgot_response">
							<!-- spanner -->
						</div>
					</td>
				</tr>

				<tr height="40px">
					<td><label for="username">Username:<br /> </label></td>
					<td><input name="username" type="text" id="username"
						style="width: 180px" />
					</td>
				</tr>

				<tr>
					<td></td>
					<td><input id="confirm" name="confirm" type="submit"
						value="Confirm" style="float: left" /> <input type="button"
						id="cancel" name="cancel" value="Cancel" style="float: right"
						onclick="window.location='../index.php'" />
						<div id="ajax_loading_forgot">
							<img align="middle" src="images/spinner.gif" />&nbsp;Processing...<br />
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
</fieldset>
