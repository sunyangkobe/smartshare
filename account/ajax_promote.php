<?php

/*
 * 2011 Jul 31
 * CSC309 - Carpool Reputation System
 *
 * Gas price changer
 * not sure where to add this page, so i just add it into account
 *
 * @author MWL
 *
 */

include_once("../includes.php");

if (empty($_POST)) exit;

Database::obtain()->connect();

date_default_timezone_set('America/Toronto');
		
$today = getdate();
$mon = $today["mon"];
$mday = $today["mday"];
$year = $today["year"];	
$H = $today["hours"];
$i = $today["minutes"];
$s = $today["seconds"];

$commandtype = trim($_POST["command"]);
$commanddesc = trim($_POST["desc"]);
$commandtxt = trim($_POST["comment"]);

if ($commandtype == "promote") Database::obtain()->query("INSERT INTO `csc309`.`admin` (`admin_id`, `uid`) VALUES (NULL, '$commanddesc')");
if ($commandtype == "activate") Database::obtain()->query("UPDATE `csc309`.`users` SET `activated` = '1' WHERE `users`.`uid`= $commanddesc");
if ($commandtype == "deactivate") Database::obtain()->query("UPDATE `csc309`.`users` SET `activated` = '0' WHERE `users`.`uid`= $commanddesc");
if ($commandtype == "new") Database::obtain()->query("INSERT INTO `csc309`.`newsfeed` (`nid`, `date`, `comment`) VALUES (NULL, '$year-$mon-$mday $H:$i:$s', '$commandtxt')");
if ($commandtype == "update") Database::obtain()->query("UPDATE `csc309`.`newsfeed` SET `comment` = '$commandtxt' WHERE `newsfeed`.`nid` =$commanddesc");
if ($commandtype == "delete") Database::obtain()->query("DELETE FROM newsfeed WHERE nid='$commanddesc'");

	
Database::obtain()->close();

var_dump($_POST);

if (($commandtype == "promote") || ($commandtype == "activate") || ($commandtype == "deactivate")){
	header("Location: ../index.php?action=adminview_user");
	exit;}
	
if (($commandtype == "new") || ($commandtype == "update") || ($commandtype == "delete")){
	header("Location: ../index.php?action=adminview_newsfeed");
	exit;}

?>
