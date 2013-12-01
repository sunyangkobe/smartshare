<?php
/*
 * 2011 Aug 01
 * CSC309 - Carpool Reputation System
 *
 * Harro!
 * adminview edition #2
 *
 * @author Min Woo Lee
 */

?>
	<?php
	$user = retrieveUser(); //user information
	if (is_null($user) || !$user->isAdmin()) { //checks if user is valid and admin
		?>
	<script type="text/javascript">
			window.setInterval("run();", 1000);
		</script>
	<?php //if user is not admin, redirect to home page
	echo "You need to login as admin account in order to proceed to this page... Redirecting in <span id=sec>3</span> secs...";
	movePage(301, "index.php", 3);
	} 
	else{

		$newsfeed_start = Database::obtain()->query("SELECT * FROM newsfeed");	
		
		?>
			
<fieldset style="width: 98%;">
	<legend>
		<b> <?php echo "News Feed - Welcome, Admin ";
		echo $user->getUsername();
		echo "!"?> </b>
	</legend>
	<div align="center" id="form_container">
			
	<hr />
	
	<table border="10" width="98%" align="center" bgcolor="#EEFFDF"
		style='table-layout: fixed'>
	
		<tr>
			<td align= "center"> <strong><a href='index.php?action=adminview' title='Home' class='current'> <span>Admin View Main</span></a> </strong> </td>
			<td align= "center"> <strong><a href='index.php?action=adminview_user' title='Home' class='current'> <span>User Administration</span></a> </strong> </td>
			<td align= "center"> <strong><a href='index.php?action=adminview_newsfeed' title='Home' class='current'> <span>News Feed Updates</span></a> </strong> </td>
		</tr>
	</table>
	
	<hr />
	
	<br />
	
		
	<form name="promote" id="promote" action="account/ajax_promote.php" method="POST">
		<label for="promote">Command: </label>
		<select name="command">
			<option value="new">Create New</option>
			<option value="update">Update</option>
			<option value="delete">Delete</option>
		</select>
		<br />
		<label for="promote">Article ID: </label>
		<input name="desc" type="text" id="desc" style="width: 180px" /> 
		<br />
		<textarea cols="65" rows="5" name="comment"></textarea>
		<br />
		<input id="promote" name="promote" type="submit" value="Submit" />
	</form>
	
	<br />	
	
	<table border="1" width="98%" align="center">
		<tr>
			<th colspan="3" align="center">News Feed Info</th>
		</tr>
		<tr>
			<th>NID</th>
			<th>Date</th>
			<th>Contents</th>
		</tr>			
	
	<?php 
		
		while ($newsfeed = Database::obtain()->fetch($newsfeed_start)){
			echo("<tr align=\"center\">
					<td> $newsfeed[nid] </td>
					<td> $newsfeed[date] </td>
					<td> $newsfeed[comment] </td>
					</tr>");}
		?>
		
	</table>
	<br />
	
	</div>
	
</fieldset>

	<?php
}?>
