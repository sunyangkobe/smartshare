<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Create trip Controller
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

// normalize and concatenate the string, which will be used to send to google map api
$orig = normalize_str($trip_data["home"]);
$dest = "";
for ($i = 0; $i < count($destinations) - 1; $i ++) {
	$orig .=  "|" . normalize_str($destinations[$i]);
	$dest .= normalize_str($destinations[$i]) . "|";
}
$dest .= normalize_str($destinations[$i]);

// receive the data from google map api and calculate the distance
$g_res = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$orig."&destinations=".$dest."&sensor=false"));
$distance = 0;
for ($i = 0; $i < count($destinations); $i ++) {
	$distance += $g_res->rows[$i]->elements[$i]->distance->value;
}

$trip_data["distance"] = $distance / 1000;
$trip_id = Trip::createTrip($trip_data);

Database::obtain()->close();

if ($trip_id > 0) {
	movePage(301, "../index.php?action=view_trip&trip_id=".$trip_id);
}

?>