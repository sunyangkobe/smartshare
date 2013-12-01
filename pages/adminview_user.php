<?php
/*
 * 2011 Aug 01
 * CSC309 - Carpool Reputation System
 *
 * Harro!
 * adminview edition #1
 *
 * @author Min Woo Lee
 */

?>
	<?php
	$user = retrieveUser();
	if (is_null($user) || !$user->isAdmin()) {
		?>
	<script type="text/javascript">
			window.setInterval("run();", 1000);
		</script>
	<?php
	echo "You need to login as admin account in order to proceed to this page... Redirecting in <span id=sec>3</span> secs...";
	movePage(301, "index.php", 3);
	} 
	else{
			
		$adminlist_start = Database::obtain()->query("SELECT users.uid, username, fname, lname, email, admin_id, activated FROM users, admin WHERE users.uid = admin.uid");	
		$userlist_start = Database::obtain()->query("SELECT users.uid, username, fname, lname, email, 0 as admin_id, activated FROM users where uid <> ALL(SELECT users.uid AS uid FROM users, admin WHERE users.uid = admin.uid)");				

		?>
			
<fieldset style="width: 98%;">
	<legend>
		<b> <?php echo "User Administration - Welcome, Admin ";
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
			<option value="promote">Promote to Admin</option>
			<option value="activate">Activate the Account</option>
			<option value="deactivate">Deactivate the Account</option>
		</select>
		<br />
		<label for="promote">UID: </label>
		<input name="desc" type="text" id="desc" style="width: 180px" /> 
		<br />
		<input id="promote" name="promote" type="submit" value="Submit" />
	</form>
	
	<br />	
	
	<table border="1" width="98%" align="center">
		<tr>
			<th colspan="6" align="center">Users Info</th>
		</tr>
		<tr>
			<th>UID</th>
			<th>User ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Admin?</th>
			<th>Activated?</th>
		</tr>		
	<?php 
		
		while ($userlist = Database::obtain()->fetch($adminlist_start)){
			echo("<tr align=\"center\">
					<td> $userlist[uid] </td>
					<td> $userlist[username] </td>
					<td> $userlist[fname] $userlist[lname] </td>
					<td> $userlist[email] </td>
					<td> YES </td>");
			if ("$userlist[activated]"==1) echo("<td> YES </td>");
			else echo("<td> - </td>");
			
			echo ("</tr>");
				
			}
			
		while ($userlist = Database::obtain()->fetch($userlist_start)){		
		
			echo("<tr align=\"center\">
					<td> $userlist[uid] </td>
					<td> $userlist[username] </td>
					<td> $userlist[fname] $userlist[lname] </td>
					<td> $userlist[email] </td>
					<td> - </td>");
			if ("$userlist[activated]"==1) echo("<td> YES </td>");
			else echo("<td> - </td>");
			
			echo ("</tr>");
		}
	?>			
	</table>
	<br />
	
	</div>
</fieldset>

	<?php
	}?>