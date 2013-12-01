<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This is a utility class in order to validate multiple fields
 *
 * @author Kobe Sun
 *
 */

function checkEmail($email) {
	// checks for proper syntax
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function checkUsername($username) {
	// checks for proper syntax
	return preg_match("/^[a-z\d_]{5,20}$/i", $username);
}

function checkName($name) {
	// checks for proper syntax
	return ($name == "") || preg_match("/^[a-zA-z]+([ '-][a-zA-Z]+)*$/", $name);
}

function checkTel($tel) {
	// checks for proper syntax
	return ($tel == "") || preg_match("/\(?\d{3}\)?[-\s.]?\d{3}[-\s.]\d{4}/x", $tel);
}

function checkPic($pic) {
	// checks for proper file type
	return (trim($pic["name"] == "")) || ((($pic["type"] == "image/gif")
		|| ($pic["type"] == "image/jpeg")
		|| ($pic["type"] == "image/png"))
		&& ($pic["size"] < 2048000));
}

function checkForm() {
	$errMsg = "";

	if (isset($_POST["username"]) && (strlen($_POST["username"]) < 5 || strlen($_POST["username"]) > 20)) {
		$errMsg .= "The length of the username you entered was invalid. <br />";
	} elseif (isset($_POST["username"]) && !checkUsername(trim($_POST["username"]))) {
		$errMsg .= "The username you entered was invalid. <br />";
	} elseif (isset($_POST["username"])) {
		$user = User::searchBy(array("username" => trim($_POST["username"])));
		if ($user) {
			if (is_null(retrieveUser()) || (retrieveUser()->getUid() != $user->getUid())) {
				$errMsg .= "The username you entered already exists. <br />";
			}
		}
	}

	if (isset($_POST["lname"]) && !checkName(trim($_POST["lname"]))) {
		$errMsg .= "The last name you entered was invalid. <br />";
	}

	if (isset($_POST["fname"]) && !checkName(trim($_POST["fname"]))) {
		$errMsg .= "The first name you entered was invalid. <br />";
	}

	if ( isset($_POST["password"]) &&  trim($_POST["password"]) == "" ) {
		$errMsg .= "The password you entered was invalid. <br />";
	} elseif ( isset($_POST["password"]) && ( trim($_POST["password"]) != trim($_POST["confirm"]) ) ) {
		$errMsg .= "The passwords you entered do not match. <br />";
	}

	if (isset($_POST["email"]) && !checkEmail(trim($_POST["email"]))) {
		$errMsg .= "The email you entered was invalid. <br />";
	} elseif (isset($_POST["email"])) {
		$user = User::searchBy(array("email" => trim($_POST["email"])));
		if ($user) {
			if (is_null(retrieveUser()) || (retrieveUser()->getUid() != $user->getUid())) {
				$errMsg .= "The email you entered already exists. <br />";
			}
		}
	}

	if (isset($_POST["telephone"]) && !checkTel(trim($_POST["telephone"]))) {
		$errMsg .= "The telephone number you entered was invalid. <br />";
	}

	if (isset($_FILES["pic"]) && !checkPic($_FILES["pic"])) {
		$errMsg .= "The image you uploaded was invalid. <br />";
	}

	$img = new Securimage();
	if (isset($_POST["code"]) && !$img->check(trim($_POST["code"]))) {
		$errMsg .= "The code you entered was invalid. <br />";
	}

	return $errMsg;
}

?>