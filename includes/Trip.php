<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Trip Data Model
 *
 * @author Kobe Sun, Europa Shang
 *
 */

class Trip {

	private $trip_id;

	private $name;
	private $description;
	private $seats;
	private $price;
	private $frequency;
	private $distance;

	private $start_date;
	private $end_date;

	private $home;
	private $routes = array();

	private $car;
	private $driver;
	private $passengers = array();
	private $candidates = array();


	public function __construct($tid = null) {
		if (!is_null($tid)) {
			$this->trip_id = $tid;
		}
	}

	/**
	 * Update the fields of the instance from database
	 */
	public function sync() {

		$db = Database::obtain();

		// Get trip information
		$trip_query = "SELECT *
			FROM trips 
			WHERE trip_id = $this->trip_id";
		$trip = $db->query_first($trip_query);

		$this->name = $trip["name"];

		$this->description = $trip["description"];
		$this->seats = $trip["seats"];
		$this->price = $trip["price"];
		$this->frequency = $trip["frequency"];

		$this->start_date = $trip["start_date"];
		$this->end_date = $trip["end_date"];

		// Get routes information
		$destination_query = "SELECT name
			FROM destinations 
			WHERE trip_id = $this->trip_id 
			ORDER BY route_order";
		$route_results = $db->fetch_array($destination_query);
		$this->home = $trip["home"];
		for ($i = 0; $i < count($route_results); $i++) {
			$this->routes[] = $route_results[$i]["name"];
		}

		$this->syncTripUser();
	}

	public function syncTripUser() {

		$db = Database::obtain();

		$trip_user_query = "SELECT *
			FROM trip_users 
			WHERE trip_id = $this->trip_id";
		$trip_user_result = $db->fetch_array($trip_user_query);
		$this->passengers = array();
		$this->candidates = array();
		for ($i = 0; $i < count($trip_user_result); $i++) {

			$role = $trip_user_result[$i]["user_role"];

			$user = User::searchBy(array("uid" => $trip_user_result[$i]["uid"]));

			if ($role == TripRole::DRIVER) {
				$this->driver = $user;
			} else if ($role == TripRole::PASSENGER) {
				$this->passengers[] = $user;
			} else {
				$this->candidates[] = $user;
			}
		}
	}


	/**
	 * Return true if the trip has been cancelled.
	 */
	public function is_cancelled() {
		return is_null($this->start_date);
	}

	public function getTrip_id() { return $this->trip_id; }
	public function getName() { return $this->name; }
	public function getDescription() { return $this->description; }
	public function getSeats() { return $this->seats; }
	public function getPrice() { return $this->price; }
	public function getFrequency() { return $this->frequency; }
	public function getStart_date() { return $this->start_date; }
	public function getEnd_date() { return $this->end_date; }
	public function getHome() { return $this->home; }
	public function getRoutes() { return $this->routes; }
	public function getCar() { return $this->car; }
	public function getDriver() { return $this->driver; }
	public function getPassengers() { return $this->passengers; }
	public function getCandidates() { return $this->candidates; }
	public function getDistance() { return $this->distance; }

	public function setTrip_id($x) { $this->trip_id = $x; }
	public function setName($x) { $this->name = $x; }
	public function setDescription($x) { $this->description = $x; }
	public function setSeats($x) { $this->seats = $x; }
	public function setPrice($x) { $this->price = $x; }
	public function setFrequency($x) { $this->frequency = $x; }
	public function setStart_date($x) { $this->start_date = $x; }
	public function setEnd_date($x) { $this->end_date = $x; }
	public function setHome($x) { $this->home = $x; }
	public function setRoutes($x) { $this->routes = $x; }
	public function setCar($x) { $this->car = $x; }
	public function setDriver($x) { $this->driver = $x; }
	public function setPassengers($x) { $this->passengers = $x; }
	public function setCandidates($x) { $this->candidates = $x; }
	public function setDistance($x) { $this->distance = $x; }

	
	public function getAvailSeats() {
		$sql = "SELECT (SELECT seats 
				FROM trips 
				WHERE trips.trip_id=" . $this->trip_id . ")
			 - (SELECT count( * ) 
			 	FROM trip_users 
			 	WHERE trip_users.trip_id=" . $this->trip_id . "
			 		AND user_role='passenger') AS availSeats";
		$res = Database::obtain()->query_first($sql);
		return $res["availSeats"];
	}

	/*
	 * Fetch Trip information from database
	 */
	public static function getTrip($trip_id) {

		$trip = new Trip();
		$trip->trip_id = $trip_id;

		$trip_query = "SELECT * FROM trips WHERE trip_id = $trip_id";
		$trip_info = Database::obtain()->query_first($trip_query);

		if (empty($trip_info)) {
			return false;
		}

		$trip->name = $trip_info["name"];
		$trip->description = $trip_info["description"];
		$trip->start_date = $trip_info["start_date"];
		$trip->end_date = $trip_info["end_date"];
		$trip->seats = $trip_info["seats"];
		$trip->frequency = $trip_info["frequency"];
		$trip->distance = $trip_info["distance"];
		$trip->price = $trip_info["price"];

		$trip->home = $trip_info["home"];
		$trip->car = CarClass::getCar($trip_info["car_id"]);

		$routes = Destination::getDestinations($trip_id);
		foreach ($routes as $route) {
			$trip->routes[] = $route["name"];
		}

		$trip_users = TripUser::getTripUsers($trip_id);
		foreach ($trip_users as $info) {
			$trip_user = new TripUser($info);
			if ($info["user_role"] == TripRole::DRIVER) {
				$trip->driver = $trip_user;
			} else if ($info["user_role"] == TripRole::PASSENGER) {
				$trip->passengers[] = $trip_user;
			} else if ($info["user_role"] == TripRole::CANDIDATE) {
				$trip->candidates[] = $trip_user;
			}
		}

		return $trip;
	}

	/** Create Trip */

	/**
	 * Return the trip_id of this trip if the trip is created successfully,
	 * otherwise false.
	 */
	private static function create($data) {

		if (! isset($data["name"]) ||
		! isset($data["start_date"]) ||		// string: YYYY-MM-DD
		! isset($data["home"]) ||
		! isset($data["seats"]) ||
		! isset($data["car_id"])) {
				
			return false;
		}

		return Database::obtain()->insert("trips", $data);
	}


	/** Update Trip */
	private static function update($trip_id, $data) {

		if (! isset($data["name"]) ||
		! isset($data["start_date"]) ||		// string: YYYY-MM-DD
		! isset($data["home"]) ||
		! isset($data["seats"]) ||
		! isset($data["car_id"])) {
				
			return false;
		}

		return Database::obtain()->update("trips", $data, "trip_id=".$trip_id);
	}


	/** Remove Trip */
	public static function removeTrip($trip_id) {
		$query = "DELETE FROM `trips` WHERE trip_id=" . $trip_id;
		return Database::obtain()->query($query);
	}


	/**
	 * Create a trip.
	 *
	 * NOTE: $data should contains fields from `trips` table in the database,
	 * and values for "driver", and "routes" only.
	 */
	public static function createTrip($data) {

		$uid = $data["driver"];
		if (! isset($uid)) {
			return false;
		}
		unset($data["driver"]);

		$routes = $data["routes"];
		if (! isset($routes) || ! is_array($routes) || count($routes) == 0) {
			return false;
		}
		unset($data["routes"]);

		if ($data["end_date"] == "") {
			unset($data["end_date"]);
		}

		// Create trip
		$trip_id = Trip::create($data);

		if (! $trip_id) {
			return false;
		}

		// Create trip users
		TripUser::createDriver($trip_id, $uid);

		// Create destinations
		Destination::insertRoutes($trip_id, $routes);

		return $trip_id;
	}


	public static function modifyTrip($trip_id, $data) {
		$uid = $data["driver"];
		if (! isset($uid)) {
			return false;
		}
		unset($data["driver"]);

		$routes = $data["routes"];
		if (! isset($routes) || ! is_array($routes) || count($routes) == 0) {
			return false;
		}
		unset($data["routes"]);

		if ($data["end_date"] == "") {
			unset($data["end_date"]);
		}

		// Update trip
		Trip::update($trip_id, $data);

		if (! $trip_id) {
			return false;
		}

		// Update destinations
		Destination::removeRoutes($trip_id);
		Destination::insertRoutes($trip_id, $routes);
	}

	
	/**
	 * 
	 * Paging table that many views will get and share
	 * @param string $whereClause
	 * @param string $destination
	 * @param int $startingFromRecord
	 * @param int $rowsPerPage
	 * @param string $orderBy
	 * @param boolean $general
	 */
	public static function genHtmlTableStr($whereClause='', $destination='', 
		$startingFromRecord=0, $rowsPerPage=5, $orderBy='start_date ASC', $general=true) {
		
		$startingFromRecord = (int) $startingFromRecord;
		$rowsPerPage = (int) $rowsPerPage;

		$sql = "SELECT count(*) as count
			FROM (SELECT DISTINCT trip_id
					FROM destinations
					WHERE name LIKE '%$destination%'
				) AS t1
			LEFT JOIN trips
			ON t1.trip_id=trips.trip_id 
			LEFT JOIN trip_users
			ON trips.trip_id=trip_users.trip_id 
			LEFT JOIN users
			ON trip_users.uid=users.uid
			WHERE
				$whereClause";
		$countArr = Database::obtain()->query_first($sql);
		$totalRows = $countArr['count'];

		if ($orderBy == '') $orderBy='start_date ASC';

		$sql = "SELECT *, (SELECT seats 
							FROM trips 
							WHERE t1.trip_id=trips.trip_id) 
						- (SELECT count( * ) 
							FROM trip_users 
							WHERE t1.trip_id=trip_users.trip_id 
							AND user_role='passenger') AS availSeats
			FROM (SELECT DISTINCT trip_id
					FROM destinations
					WHERE name LIKE '%$destination%'
				) AS t1
			LEFT JOIN trips
			ON t1.trip_id=trips.trip_id 
			LEFT JOIN trip_users
			ON trips.trip_id=trip_users.trip_id 
			LEFT JOIN users
			ON trip_users.uid=users.uid
			WHERE
				$whereClause
			ORDER BY 
				$orderBy
			LIMIT 
				$startingFromRecord,$rowsPerPage";
		$arr = Database::obtain()->fetch_array($sql);

		$str = '
			<table class="DefaultTable" style="width:645px;">
			' . TableSorter::returnMetaStr($startingFromRecord, $rowsPerPage
				, $totalRows, count($arr), $orderBy) . '
			<tr style="background:#FFF;"><td colspan="10" style="height:5px;">&nbsp;</td></tr>
			<tr id="GeoHead" class="DefaultTableHeader">
				<th width="70px" id="Name" title="' . (($orderBy == 'name ASC') ? 'name DESC' : 'name ASC') . '">Name</th>' .
				(($general) 
					? '<th id="Driver" title="' . (($orderBy == 'username ASC') ? 'username DESC' : 'username ASC') . '">Driver</th>'
					: '<th id="Role" title="' . (($orderBy == 'user_role ASC') ? 'user_role DESC' : 'user_role ASC') . '">Role</th>') . '
				<th width="30px" id="Start Date" title="' . (($orderBy == 'start_date ASC') ? 'start_date DESC' : 'start_date ASC') . '">Start Date</th>
				<th id="Price" title="' . (($orderBy == 'price ASC') ? 'price DESC' : 'price ASC') . '">Price</th>
				<th width="20px" id="Ava. Seats" title="' . (($orderBy == 'availSeats ASC') ? 'availSeats DESC' : 'availSeats ASC') . '">Ava. Seats</th>
				<th id="home" title="' . (($orderBy == 'home ASC') ? 'home DESC' : 'home ASC') . '">Routes</th>
			</tr>
		';
		$x = 1;
		foreach($arr as $i)
		{
			$sql = "SELECT name
				FROM destinations
				WHERE trip_id=" . $i["trip_id"] . "
				ORDER BY route_order";
			$resArr = Database::obtain()->fetch_array($sql);
			$res = "";
			for ($j = 0; $j < count($resArr); $j++) {
				$res .= "<br />";
				$res .= $j + 2 . ". " . $resArr[$j]["name"];
			}
			$class = ($x%2) ? '' : 'zebra';
			$str .= 
				'<tr class="' . $class . '">
					<td><a href="index.php?action=view_trip&trip_id=' . $i["trip_id"] . '">' . $i['name'] . '</a></td>' .
					(($general) 
						? '<td><a href="index.php?action=friends_view&uid=' . $i["uid"] . '">' . $i['username'] . '</a></td>'
						: '<td>' . $i['user_role'] . '</td>') . '
					<td>' . $i['start_date'] . '</td>
					<td>' . $i['price'] . '</td>
					<td>' . $i['availSeats'] . '</td>
					<td>' . '1. ' . $i['home'] . $res . '</td>
				</tr>';
			$x++;
		}

		$str .= '<tr style="background:#FFF;"><td colspan="10" style="height:5px;">&nbsp;</td></tr>';
		$str .= $meta . '</table>';
		
		return $str;
	}
	
}
?>