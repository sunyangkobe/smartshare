<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This is the configuration file that contains all values used in the system
 * and MYSQL.
 *
 * @author Kobe Sun
 *
 */

# MYSQL connection params
define("DB_HOST", "localhost");	// We are using remote db
define("DB_USER", "csc309");
define("DB_PASS", "CSCC09");
define("DB_NAME", "csc309");

# COOKIE params
// Some common values for COOKIE_EXPIRE are:
// ONEDAY = 86400;
// ONEWEEK = 604800;
// ONEMONTH = 2592000;
// HALFYEAR = 15811200;
// ONEYEAR = 31536000;
// LIFETIME = -1
define("COOKIE_EXPIRE", 31536000);
define("COOKIE_PATH", "/");
define("COOKIE_DOMAIN", $_SERVER['HTTP_HOST']);
define("COOKIE_SECURE", FALSE);
define("COOKIE_HTTPONLY", FALSE);

# SESSION params
define("SESSION_NAME", "SMARTSHARESID");

# Mail params
define("MAILADDR", "appdev01.cheetah@gmail.com");
define("MAILHEADER", "From:" . MAILADDR . "\r\n" .
	"Reply-To:" . MAILADDR . "\r\n" .
    "X-Mailer: PHP/" . phpversion());
?>
