<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Profile View
 *
 * @author Kobe Sun
 *
 */

?>

<form name="profileform" id="profileform" method="POST"
	action="index.php?action=profile" enctype="multipart/form-data">
	<table>
		<tr>
			<td><label for="username">Username:</label></td>
			<td><input name="username" type="text" id="username"
				style="width: 200px" value="<?php echo $user->getUsername() ?>" />
				<span><i>5 &#060; length &#060; 20</i></span>
			</td>
		</tr>

		<tr>
			<td><label for="lname">Last Name:</label></td>
			<td><input name="lname" type="text" id="lname" style="width: 200px"
				value="<?php echo $user->getLname()?>" />
			</td>
		</tr>

		<tr>
			<td><label for="fname">First Name:</label></td>
			<td><input name="fname" type="text" id="fname" style="width: 200px"
				value="<?php echo $user->getFname() ?>" />
			</td>
		</tr>

		<tr>
			<td><label for="email">E-mail:</label></td>
			<td><input name="email" type="text" id="email" style="width: 200px"
				value="<?php echo $user->getEmail() ?>" />
			</td>
		</tr>

		<tr>
			<td><label for="age">Age: </label></td>
			<td><select name="age" id="age">
					<option></option>
					<?php
					for ($i = 18; $i <= 70; $i++) {
						if ($user->getAge() == $i) {
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
			<td><label for="description">Description: </label></td>
			<td><textarea rows="4" cols="50" name="description"
					id="description"><?php echo $user->getDescription() ?></textarea>
			</td>
		</tr>

		<tr>
			<td><label for="telephone">Telephone: </label></td>
			<td><input name="telephone" type="text" id="telephone"
				style="width: 200px" value="<?php echo $user->getTelephone() ?>" />
				<span><i>e.g. (416)-988-1111</i></span>
			</td>
		</tr>

		<tr>
			<td><label for="car">Car Class: </label></td>
			<td><select name="car" id="car">
			<?php
			$cars = CarClass::getValues();
			for ($i = 0; $i < count($cars); $i++) {
				if ($i == $user->getCarId()) {
					echo "<option selected='selected'>$cars[$i]</option>";
				} else {
					echo "<option>$cars[$i]</option>";
				}
			}
			?>
			</select>
			</td>
		</tr>

		<tr>
			<td><label for="pic">Picture:</label></td>
			<td><input name="pic" type="file" id="pic" /><span><i>&nbsp;Size &#060; 2M</i></span>
			</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td></td>
			<td><img id="siimage" align="left" width="50%"
				style="padding-right: 5px; border: 0"
				src="account/securimage_show.php?sid=<?php echo md5(time()) ?>" /> <object
					classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
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
			</td>
		</tr>

		<tr>
			<td><label for="code">Code:</label></td>
			<td><input name="code" type="text" id="code" style="width: 200px" />
			</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td></td>
			<td><input id="update" name="update" type="submit" value="Update" style="float:left" />
			<input type="button" id="cancel" name="cancel" value="Cancel" style="float:right"
				onclick="window.location='../index.php'" />
			</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

	</table>
</form>
