<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Join the trip Controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

Database::obtain()->connect();

// gather info and send email to the driver
TripUser::createCandidate((integer) $_POST["trip_id"], (integer) retrieveUser()->getUid());
$trip_info = Trip::getTrip($_POST["trip_id"]);
$trip_info->syncTripUser();

$link = str_replace("/trip/joinTrip.php",
		"/index.php?action=view_trip&trip_id=".$_POST["trip_id"], 
		curPageURL());
$message = "Hey, " . $trip_info->getDriver()->getUsername() . "\n" .
		retrieveUser()->getUsername() . file_get_contents("../email/join_trip") . "\n" .
		$link . "\n\n" .
		"(Some email client users may need to copy and " . 
		"paste the link into your web browser).";

mail($trip_info->getDriver()->getEmail(), "Someone Joined Your Trip at The SmartShare", $message, MAILHEADER);

Database::obtain()->close();
movePage(301, $_POST["page_url"]);
?>