<?php
/*
 * 2011 Aug 05
 * CSC309 - Carpool Reputation System
 *
 * Harro!
 * Friends View
 *
 * @author Min Woo Lee, Kobe Sun
 */

$user = retrieveUser();
if (is_null($user)) {
	?>
	<script type="text/javascript">
		window.setInterval("run();", 1000);
	</script>
<?php
	echo "You need to login as in order to proceed to this page... Redirecting in <span id=sec>3</span> secs...";
	movePage(301, "index.php", 3);
} 
else{
	$user = retrieveUser();
	//automatic friend addition done here
	$now = date("Y-m-d H:i:s");
	$sql = "SELECT DISTINCT users.*
		FROM trip_users
		LEFT JOIN users
		ON trip_users.uid = users.uid
		LEFT JOIN trips
		ON trip_users.trip_id = trips.trip_id
		WHERE trip_users.uid <> " . $user->getUid() . "
			AND user_role <> 'candidate'
			AND start_date < '$now'
			AND trip_users.trip_id IN (SELECT trip_id 
							FROM trip_users
							WHERE uid = " . $user->getUid() . ")";
	$allfriends = Database::obtain()->fetch_array($sql);
	foreach ($allfriends as $friend) {
		$fid = $friend["uid"];
		$sql1 = "SELECT * 
			FROM `friends` 
			WHERE uid = $fid 
				AND fid = " . $user->getUid();
		$sql2 = "SELECT * 
			FROM `friends` 
			WHERE fid = $fid 
				AND uid = " . $user->getUid();
		if (!Database::obtain()->query_first($sql1)
			&& !Database::obtain()->query_first($sql2)) {
			Database::obtain()->insert("friends", array(
				"uid" => $user->getUid(),
				"fid" => $fid,
				"status" => 1,
			));
		}							
	}
	$numfriends = count($allfriends);
	?>
		
	<fieldset style="width: 98%;">
		<legend>
			<b> <?php 
			echo $user->getUsername();
			echo " - Your Friend List";?> </b>
		</legend>
	
		<br />
		
		<?php 
		if ($numfriends < 1) {
		?> 
			<strong>You currently have no friend. Join at least 1 trip to make friends!</strong>
			<br />
		<?php 
		} else {
		?>
			<table border="0" width="550px" align="center">
				<tr>
					<th colspan="3" align="center">Friends List</th>
				</tr>
			<?php 
				foreach ($allfriends as $friend) {?>
					<tr>
						<td width="100px">
						<img alt="user_image" id="user_img"	src="
						<?php
							echo !file_exists("upload/$friend[pic]") || strlen("$friend[pic]") < 1
								? "images/unknown.png"
								: "upload/$friend[pic]";
						?>">
						</td>
						<td width="200px"> <?php echo ("$friend[username]");?> </td>
						<td>
							<form>	
								<input type="button" value="View Profile" 
									onclick="window.location.href='index.php?action=friends_view&uid=<?php echo $friend[uid] ?>'">
								<input type="button" value="View His/Her Trips" 
									onclick="window.location.href='index.php?action=personal_trip&uid=<?php echo $friend[uid] ?>'">
							</form>
						</td>
					</tr>
					<?php 
					if (array_search($friend, $allfriends) < $numfriends - 1) {
						?>
						<tr><td colspan="3" align="center"><hr/></td></tr>
						<?php 
					}
				}
				?>
			</table>
		<?php
		}
		?>
	<br />	
	</fieldset>
	<?php
}?>