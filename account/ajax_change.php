<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Change password controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

// exit if POST is empty
if (empty($_POST)) exit;

$session = Session::getInstance();
$session->startSession();
Database::obtain()->connect();

// make sure all values are received
if (isset($_POST["uid"]) && isset($_POST["old_password"])
&& isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
	$user = User::searchBy(array("uid" => $_POST["uid"]));
	if ($user) {
		// check the password is confirmed
		if ($user->getPwd() == generatePWD(trim($_POST["old_password"]))) {
			if ($_POST["new_password"] == $_POST["confirm_password"]) {
				$user->updateUser(array("pwd" => generatePWD(trim($_POST["new_password"]))));
				exit("OK");
			} else {
				exit('<div id="error_notification">The passwords you entered do not match.</div>');
			}
		} else {
			exit('<div id="error_notification">The password you entered was invalid.</div>');
		}
	} else {
		exit('<div id="error_notification">This doesn\'t seem to be a valid request...</div>');
	}
} else {
	exit('<div id="error_notification">The information you entered was invalid.</div>');
}

Database::obtain()->close();
