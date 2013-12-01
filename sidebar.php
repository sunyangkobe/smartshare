<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This sidebar contains the login/logout box and news feeds
 *
 * @author Teresa, Kobe Sun
 *
 */
$user = retrieveUser();
if (is_null($user)) {
	?>
<script src="js/login.js" type="text/javascript"></script>
	<?php
}
?>

<fieldset>
	<legend>Login</legend>
	<?php
	if ($user) {
		?>
	<form id="logoutform" name="logoutform" method="POST"
		action="account/logout.inc.php">
		<img alt="user_image" id="user_img"
			src="<?php echo ($user->getPic() == "" || !file_exists("upload/".$user->getPic())) 
					? "images/unknown.png" 
					: "upload/" . Session::getInstance()->user->getPic() ?>" />
		<center>
			<table>
				<tr height="30px" style="vertical-align: top">
					<td colspan="2">
						Welcome back! <?php echo $user->getUsername() ?>
					</td>
				</tr>
				<tr>
					<td>
						<input id="page_url" name="page_url" type="hidden" value="<?php echo $_SERVER["REQUEST_URI"]?>">
						<input id="logout" name="logout" type="submit" value="Log out" />
					</td>
					<td>
						<a href="index.php?action=change_pwd&uid=<?php echo $user->getUid()?>" id="change_pwd">Change my password?</a>
					</td>
				</tr>
			</table>
		</center>
	</form>
	<?php
	} else {
		?>
	<div id="login_response">
		<!-- spanner -->
	</div>
	<form id="loginform" name="loginform" method="POST"
		action="account/ajax_login.php">
		<table>
			<tr>
				<td width="75px">
					<label for="username">Username :</label>
				</td>
				<td colspan="2">
					<input name="username" type="text" id="username" style="width: 170px" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="password">Password :</label>
				</td>
				<td colspan="2">
					<input name="password" type="password" id="password" style="width: 170px" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2">
					<input name="autologin" type="checkbox" id="autologin" value="YES" style="border: none;" />
					<label for="autologin">Keep me logged in</label>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input id="login" name="login" type="submit" value="Log in" />
				</td>
				<td>
					<a href="index.php?action=forgot_pwd" id="forgot">Forgot my password?</a>
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2">
					<div id="ajax_loading_login">
						<img align="middle" src="images/spinner.gif" />&nbsp;Processing...<br />
					</div>
				</td>
			</tr>
		</table>
	</form>
	<?php
	}
	?>
</fieldset>

<?php

		date_default_timezone_set('America/Toronto');
		
		$today = getdate();
		$mon = $today["mon"];
		$mday = $today["mday"];
		$year = $today["year"];
		
		$newsfeed_start = Database::obtain()->query("SELECT * FROM newsfeed ORDER BY nid DESC");		
		
		if (Database::obtain()->fetch(Database::obtain()->query("SELECT *FROM `gasprice` where date = '$year-$mon-$mday'"))){
			$array = Database::obtain()->fetch(Database::obtain()->query("SELECT * FROM `gasprice` WHERE `date` = '$year-$mon-$mday'"));
			$todaygas = $array["gprice"];
		}
		else $todaygas = '0';

		
?>

<fieldset>
	<legend>Daily Gas Price</legend>
	<center><?php echo "($year-$mon-$mday)  $$todaygas /liter";?></center>
</fieldset>

<fieldset>
	<legend>Newsfeed:</legend>
	
	<?php 
		for ($i = 1; $i <= 5; $i++){
			$newsfeed = Database::obtain()->fetch($newsfeed_start);
			echo "<hr /><p>$newsfeed[comment] ($newsfeed[date])</p>";
		}			
		//<p align="right">
		//<a href="#">older posts &gt;&gt;</a>
		//</p>
		?>
	

</fieldset>