<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Activate View
 *
 * @author Kobe Sun
 *
 */

include_once("slides.php");
?>

<script type="text/javascript">
	window.setInterval("run();", 1000);
</script>

<fieldset style="width: 98%; height: 470px;">
	<legend>
		<b>Activation</b>
	</legend>
	<div id="form_container">


<?php
function isValidAccess() {
	if (!isset($_GET["uid"])) {
		return false;
	} elseif (!isset($_GET["activate"])) {
		return false;
	} 
	
	$user = User::searchBy(array(
		"uid" => $_GET["uid"], 
		"activated" => $_GET["activate"])
	);
	
	return $user;
}

$user = isValidAccess();
if ($user) { //user has valid activation
	$user->activate();
	$session->user = $user;
?>
		<h2>Thank you!</h2>
		<p>Your account has been activated.</p>
<?php 
} else {
?>
		<h2>Sorry...</h2>
		<p>This doesn't seem to be a valid activation process...<br />
		   Please contact the webmaster...</p>
<?php
}
movePage(301, "index.php", 5);
?>
		<p>Redirecting to the index page in <span id=sec>5</span> secs...</p>
	</div>
</fieldset>