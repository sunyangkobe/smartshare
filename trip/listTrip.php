<?
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * List trip Controller
 *
 * @author Kobe Sun
 *
 */

include_once("../includes.php");

$session = Session::getInstance();
$session->startSession();

Database::obtain()->connect();
// The criteria was stored in the session, so retrieve it here
echo Trip::genHtmlTableStr($session->criteria, $session->destination,
	$_GET['start'], $_GET['rows'], $_GET['orderBy']);
Database::obtain()->close();
?>