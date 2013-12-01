<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Rate Trip Users Controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

Database::obtain()->connect();

$rater_id = (integer) $_POST["rater_id"];
$trip_id = (integer) $_POST["trip_id"];

$rates = $_POST;
unset($rates["rater_id"]);
unset($rates["trip_id"]);
unset($rates["rate"]);

// gather information and send the email to the target user
$link = str_replace("/trip/rateTrip.php",
		"/index.php?action=view_trip&trip_id=".$_POST["trip_id"], 
		curPageURL());
$targets = array_keys($rates);
for ($i = 0; $i < count($rates); $i++) {
	$target = $targets[$i];	
	TripUser::rate($target, $rater_id, $trip_id, (integer) $rates[$target]);

	$target_user = User::searchBy(array("uid" => $target));
	$username = "";
	$email = "";
	if ($target_user instanceof User) {
		$username = $target_user->getUsername();
		$email = $target_user->getEmail();
	}
	$message = "Hey, " . $username . "\n" .
		retrieveUser()->getUsername() . file_get_contents("../email/rate") . "\n" .
		$link . "\n\n" .
		"(Some email client users may need to copy and " . 
		"paste the link into your web browser).";
	mail($email, "You Received Rate Score at The SmartShare", $message, MAILHEADER);
}

Database::obtain()->close();

movePage(301, $_POST["page_url"]);
?>