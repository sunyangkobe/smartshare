<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Account login controller, realized by using ajax
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

if(empty($_POST)) exit;

Database::obtain()->connect();

// make sure the user exists
$user = checkUsername(trim($_POST["username"]))
? User::searchBy(array(
			"username" => trim($_POST["username"]),
			"pwd" => generatePWD(trim($_POST["password"]))))
: FALSE;

Database::obtain()->close();

if ($user) {
	if ($user->getActivated() != 1) {
		echo '<div id="error_notification">Your account is not activated.</div>';
	} else {
		$session = Session::getInstance();
		$session->startSession();
		$session->user = $user;
		if (isset($_POST["autologin"]) && $_POST["autologin"] == "YES") {
			# Set up cookie for the user
			$cookie = Cookie::getInstance();
			$cookie->set("username", $user->getUsername());
			$cookie->set("pwd", $user->getPwd());
		}
		exit("OK");
	}
} else {
	echo '<div id="error_notification">The submitted login info is incorrect.</div>';
}
?>
