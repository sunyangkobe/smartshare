<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Remove Passenger Controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

Database::obtain()->connect();

// make sure all values do exist
if (!isset($_POST["trip_id"]) || !isset($_POST["page_url"])) {
	exit();
} else {
	foreach ($_POST as $k=>$v) {
		if (preg_match("/^remove(\d+)$/", $k, $matches)) {
			$uid = $matches[1];
			$user = User::getUser($uid);
			TripUser::removePassenger($_POST["trip_id"], $uid);
			// prepare and send email
			$link = str_replace("/trip/removePassenger.php",
				"/index.php?action=view_trip&trip_id=".$_POST["trip_id"], 
				curPageURL());
			$message = "Hey, " . $user->getUsername() . "\n" .
				file_get_contents("../email/remove_passenger") . "\n" .
				$link . "\n\n" .
				"(Some email client users may need to copy and " . 
				"paste the link into your web browser).";

			mail($user->getEmail(), "You were demoted at The SmartShare", $message, MAILHEADER);
		}
	}
}

Database::obtain()->close();
movePage(301, $_POST["page_url"]);

?>