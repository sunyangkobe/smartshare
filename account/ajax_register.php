<?php

/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Account registration controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

if (empty($_POST)) exit;

$session = Session::getInstance();
$session->startSession();

Database::obtain()->connect();
$user = new User(array (
		"username" => trim($_POST["username"]),
		"email" => trim($_POST["email"]),
		"pwd" => generatePWD(trim($_POST["password"])),
		"activated" => rand(10000, 9999999999),
));

$errMsg = checkForm();
if($errMsg == "" && ($user->addUser() != -1)) {
	$link = str_replace("/account/ajax_register.php",
		"/index.php?action=activate&uid=".$user->getUid()."&activate=".$user->getActivated(), 
		curPageURL());

	$message = "Hey, " . $user->getUsername() . "\n" .
		file_get_contents("../email/activate") . "\n" .
		$link . "\n\n" .
		"(Some email client users may need to copy and " . 
		"paste the link into your web browser).";

	mail($user->getEmail(), "Registration at The SmartShare", $message, MAILHEADER);
	exit($user->getEmail());
} else {
	exit('<div id="error_notification">' . $errMsg . '</div><br />');
}

Database::obtain()->close();

?>
