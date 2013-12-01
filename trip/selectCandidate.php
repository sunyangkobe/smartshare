<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Select Candidates Controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

Database::obtain()->connect();
TripUser::selectPassengers((integer) $_POST["trip_id"], $_POST["candidate"]);

$trip_info = Trip::getTrip($_POST["trip_id"]);
$trip_info->syncTripUser();

$link = str_replace("/trip/selectCandidate.php",
		"/index.php?action=view_trip&trip_id=".$_POST["trip_id"], 
		curPageURL());
foreach ($_POST["candidate"] as $c) {
	$user = User::searchBy(array("uid" => $c));
	$message = "Hey, " . $user->getUsername() . "\n" .
		file_get_contents("../email/select_passenger") . "\n" .
		$link . "\n\n" .
		"(Some email client users may need to copy and " . 
		"paste the link into your web browser).";
	mail($user->getEmail(), "You are selected at The SmartShare", $message, MAILHEADER);
}

Database::obtain()->close();

movePage(301, $_POST["page_url"]);
?>