<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This is a wrapper file which is supposed to include all library files used
 * in our system and the configuration file.
 *
 * @author Kobe Sun
 *
 */

# include general configuration
include_once("config.php");	// Config file has to be included at the top

# include MYSQL-PHP Class
include_once("includes/Database.singleton.php");

# include PHP Session Class
include_once("includes/Session.singleton.php");

# include PHP Cookie Class
include_once("includes/Cookie.singleton.php");

# include Mootools Table Sorter Class
include_once("includes/TableSorter.class.php");

# include all classes files
include_once("includes/Trip.php");
include_once("includes/TripRole.php");
include_once("includes/User.php");
include_once("includes/TripUser.php");
include_once("includes/Destination.php");
include_once("includes/CarClass.php");
include_once("account/securimage.php");

# include instantiation step
include_once("common.php");	// This has to be done after importing mysql class

# include form validation file
include_once("account/validate.php");

?>

