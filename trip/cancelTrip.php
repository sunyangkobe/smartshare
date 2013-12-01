<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Cancel a trip Controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

Database::obtain()->connect();

// generate info message
$trip_info = Trip::getTrip($_POST["trip_id"]);
$trip_info->syncTripUser();

$info = "Driver: " . $trip_info->getDriver()->getUsername() . 
		"\nEmail: " . $trip_info->getDriver()->getEmail() . "\n".
		"Name: " . $trip_info->getName() . 
		"\nPrice: " . $trip_info->getPrice() . "\n".
		"Start Date: " . $trip_info->getStart_date() . 
		"\nEnd Date: " . $trip_info->getEnd_date() . "\n".
		"Routes: \n    * " . $trip_info->getHome() . "\n";
foreach ($trip_info->getRoutes() as $i) {
	$info .= "    * " . $i . "\n";
}

$info .= "Description: \n" . $trip_info->getDescription();

// send the email to all invloved passengers
foreach ($trip_info->getPassengers() as $p) {
	$message = "Hey, " . $p->getUsername() . "\n" .
		file_get_contents("../email/cancel_trip") . "\n" . $info;
	mail($p->getEmail(), "Cancel Trip at The SmartShare", $message, MAILHEADER);
}

// send the email to all involved candidates
foreach ($trip_info->getCandidates() as $c) {
	$message = "Hey, " . $c->getUsername() . "\n" .
		file_get_contents("../email/cancel_trip") . "\n" . $info;
	mail($c->getEmail(), "Cancel Trip at The SmartShare", $message, MAILHEADER);
}

Trip::removeTrip($_POST["trip_id"]);
Database::obtain()->close();
movePage(301, "../index.php");
?>