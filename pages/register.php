<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This register view
 *
 * @author Kobe Sun
 *
 */

?>

<script
	src="js/register.js" type="text/javascript"></script>

<fieldset style="width: 98%; height: 500px;">
	<legend>
		<b>Register</b>
	</legend>

	<div id="form_container">
	<?php
	$user = retrieveUser();
	if ($user) {
		?>
		<script type="text/javascript">
		window.setInterval("run();", 1000);
		</script>
		
		<?php
		echo "Thank you for your registration! Redirecting in <span id=sec>3</span> secs...";
		movePage(301, "index.php", 3);
	} else {
		?>
		<form name="regform" id="regform" action="account/ajax_register.php"
			method="POST">
			<table>
				<tr>
					<td colspan="2">
						<div id="register_response">
							<!-- spanner -->
						</div>
					</td>
				</tr>

				<tr>
					<td><label for="username">Username:<br /> </label></td>
					<td><input name="username" type="text" id="username"
						style="width: 180px" />
					</td>
				</tr>

				<tr>
					<td><label for="password">Password:</label></td>
					<td><input name="password" type="password" id="password"
						style="width: 180px" onkeypress="PasswordChanged(this)" /> <span
						style="vertical-align: bottom;" id="PasswordStrength"></span>
					</td>
				</tr>

				<tr>
					<td><label for="confirm">Confirm Password:</label></td>
					<td><input name="confirm" type="password" id="confirm"
						style="width: 180px" />
					</td>
				</tr>

				<tr>
					<td><label for="email">E-mail:</label></td>
					<td><input name="email" type="text" id="email" style="width: 180px" />
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td></td>
					<td><img id="siimage" align="left" width="55%"
						style="padding-right: 5px; border: 0"
						src="account/securimage_show.php?sid=<?php echo md5(time()) ?>" />

						<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
							codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0"
							width="19" height="19" id="SecurImage_as3" align="middle">
							<param name="allowScriptAccess" value="sameDomain" />
							<param name="allowFullScreen" value="false" />
							<param name="movie"
								value="account/securimage_play.swf?audio=account/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" />
							<param name="quality" value="high" />

							<param name="bgcolor" value="#ffffff" />
							<embed
								src="account/securimage_play.swf?audio=account/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5"
								quality="high" bgcolor="#ffffff" width="19" height="19"
								name="SecurImage_as3" align="middle"
								allowScriptAccess="sameDomain" allowFullScreen="false"
								type="application/x-shockwave-flash"
								pluginspage="http://www.macromedia.com/go/getflashplayer" />
						</object> <br /> <!-- pass a session id to the query string of the script to prevent ie caching -->
						<a tabindex="-1" style="border-style: none" href="#"
						title="Refresh Image"
						onclick="document.getElementById('siimage').src = 'account/securimage_show.php?sid=' + Math.random(); return false"><img
							src="account/images/refresh.gif" alt="Reload Image" border="0"
							onclick="this.blur()" align="bottom" /> </a>
				
				</tr>

				<tr>
					<td><label for="code">Code:</label></td>
					<td><input name="code" type="text" id="code" style="width: 180px" />
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td align="right"><input id="register" name="register"
						type="submit" value="Submit" /></td>
					<td align="center"><input id="reset" name="reset" type="reset"
						value="Reset" />
						<div id="ajax_loading_reg">
							<img align="middle" src="images/spinner.gif" />&nbsp;Processing...<br />
						</div>
					</td>
				</tr>

			</table>
		</form>
		<?php
	}
	?>
	</div>
</fieldset>


