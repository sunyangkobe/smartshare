<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Logout controller
 * Remove Session info and Cookie, go back to the index page
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

$session = Session::getInstance();
$session->startSession();
$session->destroy();

$cookie = Cookie::getInstance();
$cookie->delete("username");
$cookie->delete("pwd");

movePage(301, $_POST["page_url"]);
?>
