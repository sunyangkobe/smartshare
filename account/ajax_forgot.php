<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Forgot password controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

if (empty($_POST)) exit;

$session = Session::getInstance();
$session->startSession();
Database::obtain()->connect();

// check whether the username exists
if (checkUsername($_POST["username"])) {

	$user = User::searchBy(array("username" => trim($_POST["username"])));
	if ($user) {
		// generate a random password
		$new_pwd = rand(10000, 99999999);
		$user->updateUser(array("pwd" => generatePWD($new_pwd)));
		
		$link = str_replace("/account/ajax_forgot.php",
			"/index.php?action=change_pwd&uid=".$user->getUid(), 
			curPageURL());
		$message = "Hey, " . $user->getUsername() . "\n" .
			file_get_contents("../email/forgot_pwd") . $new_pwd .
			file_get_contents("../email/change_pwd") . $link . "\n\n" .
			"(Some email client users may need to copy and " . 
			"paste the link into your web browser).";
		mail($user->getEmail(), "Account Update at SmartShare", $message, MAILHEADER);
		echo $user->getEmail();
	} else {
		echo '<div id="error_notification">The username doesn\'t exist.</div>';
	}
} else {
	echo '<div id="error_notification">The username was invalid.</div>';
}

Database::obtain()->close();