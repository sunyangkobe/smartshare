<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This file contains common functions and instantiations
 *
 * @author Kobe Sun
 *
 */

# Construct the instances
$db = Database::obtain(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$session = Session::getInstance(SESSION_NAME);
$cookie = Cookie::getInstance(COOKIE_EXPIRE, COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTPONLY);

/**
 * One level MD5 encryption is not enough as hacker has created library to
 * collect existing pairs. However, 5 level encryption can make sure the
 * security for now.
 *
 * @param $raw	String type of raw data
 * @return $generated	String type of encryped data
 */
function generatePWD($raw) {
	$generated = $raw;
	$i = 0;
	while ($i < 6) {
		$generated = md5($generated);
		$i++;
	}
	return $generated;
}


/**
 * First fetch the user object from current session, if user could not be
 * found, retrieve the cookie data and search for it in the database. If
 * found, synchronize the user object with session. Otherwise, delete cookie
 * data as it is very possible that they are manual created by hackers.
 *
 * @return $user	User object if user can be found, Null otherwise.
 */
function retrieveUser() {
	$session = Session::getInstance();
	$cookie = Cookie::getInstance();
	$session->startSession();
	
	if (isset($session->user)) {
		return $session->user;
	} elseif ($cookie->check("username") && $cookie->check("pwd")) {
		$user = User::searchBy(array(
			"username" => $cookie->value("username"),
			"pwd" => $cookie->value("pwd")
		));
		if ($user) {
			$session->user = $user;
			return $session->user;
		} else {
			# This could be the case that some one hack the cookie, delete it
			$cookie->delete("username");
			$cookie->delete("pwd");
		}
	}
}


function movePage($num,$url,$sec=0){
	static $http = array (
	100 => "HTTP/1.1 100 Continue",
	101 => "HTTP/1.1 101 Switching Protocols",
	200 => "HTTP/1.1 200 OK",
	201 => "HTTP/1.1 201 Created",
	202 => "HTTP/1.1 202 Accepted",
	203 => "HTTP/1.1 203 Non-Authoritative Information",
	204 => "HTTP/1.1 204 No Content",
	205 => "HTTP/1.1 205 Reset Content",
	206 => "HTTP/1.1 206 Partial Content",
	300 => "HTTP/1.1 300 Multiple Choices",
	301 => "HTTP/1.1 301 Moved Permanently",
	302 => "HTTP/1.1 302 Found",
	303 => "HTTP/1.1 303 See Other",
	304 => "HTTP/1.1 304 Not Modified",
	305 => "HTTP/1.1 305 Use Proxy",
	307 => "HTTP/1.1 307 Temporary Redirect",
	400 => "HTTP/1.1 400 Bad Request",
	401 => "HTTP/1.1 401 Unauthorized",
	402 => "HTTP/1.1 402 Payment Required",
	403 => "HTTP/1.1 403 Forbidden",
	404 => "HTTP/1.1 404 Not Found",
	405 => "HTTP/1.1 405 Method Not Allowed",
	406 => "HTTP/1.1 406 Not Acceptable",
	407 => "HTTP/1.1 407 Proxy Authentication Required",
	408 => "HTTP/1.1 408 Request Time-out",
	409 => "HTTP/1.1 409 Conflict",
	410 => "HTTP/1.1 410 Gone",
	411 => "HTTP/1.1 411 Length Required",
	412 => "HTTP/1.1 412 Precondition Failed",
	413 => "HTTP/1.1 413 Request Entity Too Large",
	414 => "HTTP/1.1 414 Request-URI Too Large",
	415 => "HTTP/1.1 415 Unsupported Media Type",
	416 => "HTTP/1.1 416 Requested range not satisfiable",
	417 => "HTTP/1.1 417 Expectation Failed",
	500 => "HTTP/1.1 500 Internal Server Error",
	501 => "HTTP/1.1 501 Not Implemented",
	502 => "HTTP/1.1 502 Bad Gateway",
	503 => "HTTP/1.1 503 Service Unavailable",
	504 => "HTTP/1.1 504 Gateway Time-out"
	);
	if ($sec == 0) {
		header($http[$num]);
		header ("Location: $url");
	} else {
		header("refresh: $sec; URL=$url");
	}
}

function curPageURL() {
	if ($_SERVER["SERVER_PORT"] == "80") {
		$pageURL = 'http://'.$_SERVER["SERVER_NAME"];
	} elseif ($_SERVER["SERVER_PORT"] == "443") {
		$pageURL = 'https://'.$_SERVER["SERVER_NAME"];
	} else {
	    $pageURL = "http://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];	
	}
	$pageURL .= $_SERVER["REQUEST_URI"];
	return $pageURL;
}

?>
