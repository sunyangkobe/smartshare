<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Leave a trip Controller
 *
 * @author Kobe Sun
 *
 */


include_once("../includes.php");

Database::obtain()->connect();
TripUser::removeInTrip((integer) $_POST["trip_id"], (integer) retrieveUser()->getUid());

// gather information and send the email to the driver
$trip_info = Trip::getTrip($_POST["trip_id"]);
$trip_info->syncTripUser();

$link = str_replace("/trip/quitTrip.php",
		"/index.php?action=view_trip&trip_id=".$_POST["trip_id"], 
		curPageURL());
$message = "Hey, " . $trip_info->getDriver()->getUsername() . "\n" .
		retrieveUser()->getUsername() . file_get_contents("../email/quit_trip") . "\n" .
		$link . "\n\n" .
		"(Some email client users may need to copy and " . 
		"paste the link into your web browser).";

mail($trip_info->getDriver()->getEmail(), "Someone Quited Your Trip at The SmartShare", $message, MAILHEADER);

Database::obtain()->close();
movePage(301, $_POST["page_url"]);
?>