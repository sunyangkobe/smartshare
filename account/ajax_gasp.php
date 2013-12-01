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
$price = trim($_POST["price"]);

	$today = getdate();
	$mon = $today["mon"];
	$mday = $today["mday"];
	$year = $today["year"];

if ( isset($_POST["price"]) &&  trim($_POST["price"]) == "" ) {
		$errMsg .= "The price you entered was invalid. <br />";
	}
else{

if (Database::obtain()->fetch(Database::obtain()->query("SELECT *FROM `gasprice` where date = '$year-$mon-$mday'"))){
		Database::obtain()->query("UPDATE `csc309`.`gasprice` SET `gprice` = '$price' WHERE `gasprice`.`date` = '$year-$mon-$mday'");}

else Database::obtain()->query("INSERT INTO `csc309`.`gasprice` (`date` ,`gprice`) VALUES ('$year-$mon-$mday', $price)");
		
}	

Database::obtain()->close();

header("Location: ../index.php?action=adminview");
exit;

?>
