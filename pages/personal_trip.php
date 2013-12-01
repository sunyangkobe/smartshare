<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Personal Trip View
 *
 * @author Kobe Sun
 *
 */

?>

<link rel="stylesheet" media="screen" href="css/table-sorter.css" />
<?php 
$user = retrieveUser();
if (isset($_REQUEST["uid"])) {
	$user = User::searchBy(array("uid" => $_REQUEST["uid"]));
}
?>

<fieldset style="width: 98%;">
			<legend>
				<b><?php echo is_null($user) ? "" : $user->getUsername() . "'s" ?> Trip</b>
			</legend>
			<div id="form_container">

<?php
	if (is_null($user)) {
		?>
		<script type="text/javascript">
			window.setInterval("run();", 1000);
		</script>
		<?php
		movePage(301, "index.php", 3);
		echo "You need to login in order to proceed to this page..."
			. "Redirecting in <span id=sec>3</span> secs...";
	} else {
		?>
		<script
			type="text/javascript" src="js/table-sorter.js"></script>
		<script type="text/javascript">
			window.addEvent('domready',function(){
				sorter = new TableSorter({
					request: 'action', 
					page: 'trip/listTrip.php',
					destination: 'XhrDump', 
					prev: 'PagePrev', 
					next: 'PageNext', 
					head: 'GeoHead',
					rows: 5,
					defaultStartEndWaitEnabled: 1,
					startWait: '',
					endWait: ''
				});
			});
		</script>
		
		<div id="XhrDump">
			<?php
			Session::getInstance()->criteria = "trip_users.uid=" . $user->getUid();
			Session::getInstance()->destination = "";
			echo Trip::genHtmlTableStr(Session::getInstance()->criteria, Session::getInstance()->destination,
				0, 5, 'start_date ASC', false);
			?>
		</div>
<?php 
	}
?>
	</div>
</fieldset>