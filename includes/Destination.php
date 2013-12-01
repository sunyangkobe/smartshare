<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Destination Data Model
 *
 * @author Europa Shang
 *
 */

class Destination {
	
	private static $ORDER_CANCELLED = 0;
	
	
	/*
	 * Return the next available order of a new route of given trip
	 */
	private static function getNextOrder($trip_id) {
		
		$query = "SELECT MAX(route_order) AS max_order FROM destinations WHERE trip_id = $trip_id";
		$max_order = Database::obtain()->query_first($query);
		
		if ($max_order) {
			$order = $max_order["max_order"] + 1;
		} else {
			$order = 1;
		}
		
		return $order;
	}
	
	
	/*
	 * Return -1 is the route does not exist on the trip, 0 if the route is 
	 * cancelled, or the route_order if it exists. 
	 */	
	private static function checkRouteExist($trip_id, $route, $order) {
		
		$query = "SELECT * FROM destinations WHERE trip_id = $trip_id AND name = '$route' AND route_order = '$order'";
		$result = Database::obtain()->query_first($query);
		
		if ($result) {
			return $result["route_order"];
		} else {
			return -1;
		}
	}
	

	/*
	 * Return false if the route already exists on the trip. Otherwise, 
	 * create the destination of the trip at given order, or the next available 
	 * order if order is null, and return true.
	 */
	private static function create($trip_id, $name, $order=null) {
		
		if (is_null($order)) {
			$order = Destination::getNextOrder($trip_id);
		}
		
		$exist = Destination::checkRouteExist($trip_id, $name, $order);
		if ($exist != -1) {
			return false;
		}
		
		$destination_data = array("trip_id" => $trip_id, "name" => $name, "route_order" => $order);

		// why return the id? there is no auto_increment column in destinations table...??
		Database::obtain()->insert("destinations", $destination_data);
		return true;
	}
	
	
	/**
	 * Update the route_order of given destination
	 */
	private static function update($trip_id, $name, $order) {
		
		$data = array("route_order" => $order);
		return Database::obtain()->update("destinations", $data, "trip_id = $trip_id AND name = '$name'");
	}
	
	
	/**
	 * Add array of destinations in order, and return the array of 
	 * routes that are not created sucessfully. 
	 */
	public static function insertRoutes($trip_id, $routes) {
		
		$base_order = Destination::getNextOrder($trip_id);
		$failed = array();
		
		for ($i = 0; $i < count($routes); $i++) {
			if (! Destination::create($trip_id, $routes[$i], $base_order + $i)) {
				$failed[] = $routes[$i];
			}
		}
		return $failed;
	}
	
	
	/**
	 * Remove array of destinations in order, and return the array of 
	 * routes that are not created sucessfully. 
	 */
	public static function removeRoutes($trip_id) {
		$q = "DELETE FROM `destinations` WHERE trip_id=" . $trip_id;
		return Database::obtain()->query($q);
	}
	
	
	/*
	 * Add the given route as the next aviable order
	 */
	public static function addDestination($trip_id, $route) {
		return Destination::create($trip_id, $route);
	}
	
	
	/*
	 * Remove the given destination from the trip and update the 
	 * order of all destinations after it
	 */
	public static function removeDestination($trip_id, $route) {
		
		$order = Destination::checkRouteExist($trip_id, $route);
		
		if ($order <= 0) {
			return false;
		}
		
		$routes = Destination::getDestinations($trip_id);
		
		Destination::update($trip_id, $route, Destination::$ORDER_CANCELLED);
		for ($i = $order; $i < count($routes); $i++) {
			Destination::update($trip_id, $routes[$i]["name"], $i);
		}
	}
	

	/*
	 * Return the array of destinations of given trip
	 */
	public static function getDestinations($trip_id) {
		
		$order = Destination::$ORDER_CANCELLED;
		
		$query = "SELECT * FROM destinations WHERE trip_id = $trip_id AND route_order != $order ORDER BY route_order";
		return Database::obtain()->fetch_array($query);
	}
}
?>