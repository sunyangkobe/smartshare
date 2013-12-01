<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This index page will play a role of wrapper and the only portal to access the
 * website. Page jumping is done by using URL GET tokens
 *
 * @author Teresa, Kobe Sun
 *
 */

include_once("includes.php");

$session = Session::getInstance();
$session->startSession();
$cookie = Cookie::getInstance();

Database::obtain()->connect();
# This will ensure that cookie will be set before the header is sent
ob_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Smart Share - Online Carpooling Reputation System</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />

<script src="js/mootools-core.js" type="text/javascript"></script>
<script src="js/mootools-more.js" type="text/javascript"></script>
<script src="js/app.js" type="text/javascript"></script>

</head>
<body>
	<div id="maincontainer">

		<div id="topsection">
			<?php
			include_once("banner.php");
			?>	 
		</div>

		<div id="navi">
		<?php
		include_once("navi.php");
		?>
		</div>

		<div id="contentwrapper">

			<div id="contentcolumn">
				<div class="innertube">
				<?php
				include_once("slides.php");

				if (!isset($_GET["action"])) {
					include_once("pages/home.php");
				} else {
					$filename = "pages/" . $_GET["action"] . ".php";
					if (file_exists($filename))
					include_once($filename);
					else
					include_once("pages/error.php");
				}
				?>
				
				</div>
			</div>

			<div id="rightcolumn">
				<div class="innertube">
				<?php
				include_once("sidebar.php");
				?>
				</div>
			</div>

		</div>

		<div id="footer">
		<?php
		include_once("footer.php");
		?>
		</div>

	</div>

</body>
</html>

		<?php
		ob_end_flush();
		Database::obtain()->close();
		?>