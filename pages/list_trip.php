<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * List Trip View
 *
 * @author Kobe Sun
 *
 */

?>

<link rel="stylesheet" media="screen" href="css/table-sorter.css" />
<script type="text/javascript" src="js/table-sorter.js"></script>
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

<fieldset style="width: 98%;">
	<legend>
		<b>List Trip</b>
	</legend>
	<div id="form_container">
		<div id="XhrDump">
		<?php
			$now = date("Y-m-d H:i:s"); //current date
			Session::getInstance()->criteria = "`start_date` >= '$now' AND user_role='driver'";
			Session::getInstance()->destination = "";
			echo Trip::genHtmlTableStr(Session::getInstance()->criteria);
		?>
		</div>
	</div>
</fieldset>
