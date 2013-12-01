<?php
/*
 * 2011 Aug 05
 * CSC309 - Carpool Reputation System
 *
 * Harro!
 * Friends View
 *
 * @author Min Woo Lee
 */

$user = retrieveUser();
if (is_null($user) || !isset($_REQUEST["uid"])) {
	?>
	<script type="text/javascript">
		window.setInterval("run();", 1000);
	</script>
<?php
	echo "You need to login as in order to proceed to this page... Redirecting in <span id=sec>3</span> secs...";
	movePage(301, "index.php", 3);
} 
else{
	$friendid = trim($_REQUEST["uid"]);
	$sql = "SELECT * 
		FROM `users` 
		WHERE `uid`=" . $friendid;
	$friendinfo = Database::obtain()->query_first($sql);
	?>
			
	<fieldset style="width: 98%;">
		<legend>
			<b> <?php 
			echo ("User \"$friendinfo[username]\" Profile");?> </b>
		</legend>
		<div id="form_container">
	
		
		<table border="0" width="98%" align="center">
			<tr>
				<td rowspan = "2"> 
						<img alt="user_image" id="user_img"	src="
							<?php
								if (!file_exists("upload/$friendinfo[pic]") || strlen("$friendinfo[pic]") < 1) echo ("images/unknown.png");
								else echo ("upload/$friendinfo[pic]");
							?>">
				</td>
				<td> User ID: <strong><?php echo("$friendinfo[username]");?></strong> </td>
				<td> Name: <strong><?php echo("$friendinfo[fname] $friendinfo[lname]");?></strong> </td>
				<td> Age: <strong><?php echo("$friendinfo[age]");?></strong> </td>
			</tr>
			<tr>
				<td colspan ="2"> E-Mail: <strong><?php echo("$friendinfo[email]");?></strong> </td>
				<td> Phone: <strong><?php echo("$friendinfo[telephone]");?></strong> </td>
			</tr>
			
			<tr>
				<td colspan="4"> <hr/> </td>
			</tr>
			
			<tr>
				<td colspan ="4" align="center"> Driver Score: <h2><?php echo("$friendinfo[driver_rate_score]");?></h2> </td>
			</tr>
			<tr>
				<td colspan ="4"  align="center"> Passenger Score: <h2><?php echo("$friendinfo[passenger_rate_score]");?></h2>  </td>
			</tr>
			
			<tr>
				<td colspan="4"> <hr/> </td>
			</tr>
			
			<tr>
				<td colspan ="4" align="center"> <h4><?php echo("$friendinfo[description]");?></h4>  </td>
			</tr>
		</table>

		</div>		
	</fieldset>
<?php 
} 
?>
