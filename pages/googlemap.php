<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Google Map JS Controller on the view trip page
 *
 * @author Kobe Sun
 *
 */

?>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
window.addEvent('domready', function() {
	var directionsService = new google.maps.DirectionsService();
	var directionsDisplay = new google.maps.DirectionsRenderer();
	var toronto = new google.maps.LatLng(43.652527, -79.381961);
	var myOptions = {
		zoom : 6,
		mapTypeId : google.maps.MapTypeId.ROADMAP,
		center : toronto
	};
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	directionsDisplay.setMap(map);
	
	var start = "<?php echo $trip->getHome()?>";
	var end = "<?php echo end($trip->getRoutes()) ?>";
	var waypts = [];
	<?php echo 'var routes = new Array(\'' . implode('\',\'', $trip->getRoutes()) . '\');' ?>
	for ( var i = 0; i < routes.length - 1; i++) {
		waypts.push({
			location : routes[i],
			stopover : true
		});
	}

	var request = {
		origin : start,
		destination : end,
		waypoints : waypts,
		optimizeWaypoints : true,
		travelMode : google.maps.DirectionsTravelMode.DRIVING
	};
	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}
	});
});
</script>