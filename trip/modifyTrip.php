<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Modify a trip Controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

function normalize_str($str) {
	$str = str_replace(", ", "+", $str);
	$str = str_replace(" ", "+", $str);
	return $str;
}

Database::obtain()->connect();

$destinations = array();
for ($i = 1; $i <= $_POST["counter"]; $i ++) {
	array_push($destinations, $_POST["route".$i]);
}

$trip_data = array(
	"name" => trim($_POST["name"]),
	"description" => trim($_POST["description"]),
	"seats" => (integer) $_POST["seats"],
	"price" => (integer) $_POST["price"],
	"frequency" => trim($_POST["frequency"]),
	"driver" => (integer) $_POST["driver"],
	"start_date" => $_POST["start_date"],
	"end_date" => $_POST["end_date"],
	"home" => trim($_POST["home"]),
	"routes" => $destinations,
	"car_id" => (integer) $_POST["car_id"]
);

// similar to create trip, normalize and concatenate string for google map api
$orig = normalize_str($trip_data["home"]);
$dest = "";
for ($i = 0; $i < count($destinations) - 1; $i ++) {
	$orig .=  "|" . normalize_str($destinations[$i]);
	$dest .= normalize_str($destinations[$i]) . "|";
}
$dest .= normalize_str($destinations[$i]);

// calculate distance
$g_res = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$orig."&destinations=".$dest."&sensor=false"));
$distance = 0;
for ($i = 0; $i < count($destinations); $i ++) {
	$distance += $g_res->rows[$i]->elements[$i]->distance->value;
}

$trip_data["distance"] = $distance / 1000;

Trip::modifyTrip($_POST["trip_id"], $trip_data);

// gather trip information
$trip_info = Trip::getTrip($_POST["trip_id"]);
$trip_info->syncTripUser();

$link = str_replace("/trip/modifyTrip.php",
		"/index.php?action=view_trip&trip_id=".$_POST["trip_id"], 
		curPageURL());

// send email to all passengers
foreach ($trip_info->getPassengers() as $p) {
	$message = "Hey, " . $p->getUsername() . "\n" .
		file_get_contents("../email/modify_trip") . "\n" . 
		$link . "\n\n" .
		"(Some email client users may need to copy and " . 
		"paste the link into your web browser).";
	mail($p->getEmail(), "Trip Info Changes at The SmartShare", $message, MAILHEADER);
}

// send email to all candidates
foreach ($trip_info->getCandidates() as $c) {
	$message = "Hey, " . $c->getUsername() . "\n" .
		file_get_contents("../email/modify_trip") . "\n" . 
		$link . "\n\n" .
		"(Some email client users may need to copy and " . 
		"paste the link into your web browser).";
	mail($c->getEmail(), "Trip Info Changes at The SmartShare", $message, MAILHEADER);
}


Database::obtain()->close();
movePage(301, "../index.php?action=view_trip&trip_id=".$_POST["trip_id"]);

?>