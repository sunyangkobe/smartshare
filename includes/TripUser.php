<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * TripUser Data Model
 *
 * @author Europa Shang
 *
 */

class TripUser {

	private $trip_id;
	private $uid;
	private $role;
	private $rates;

	public function __construct($params) {

		if (isset($params["trip_id"])) {
			$this->trip_id = $params["trip_id"];
		}

		if (isset($params["uid"])) {
			$this->uid = $params["uid"];
		}

		if (isset($params["user_role"])) {
			$this->role = $params["user_role"];
		}
	}

	public function sync() {

		$db = Database::obtain();

		$trip_user_query = "SELECT *
			FROM trip_users 
			WHERE trip_id = $this->trip_id 
			AND uid = $this->uid";
		$trip_user = $db->query_first($trip_user_query);
		$this->role = $trip_user["user_role"];

		return $trip_user;
	}


	public static function getMyTrips($uid) {

		$db = Database::obtain();

		$trip_user_query = "SELECT *
			FROM trip_users 
			WHERE uid = $uid";
		$trip_user_result = $db->fetch_array($trip_user_query);

		$trips = array();
		foreach ($trip_user_result as $trip_user) {
			if (! isset($trips[$trip_user]["user_role"])) {
				$trips[$trip_user] = array();
			}
			$trip_id = $trips[$trip_user]["trip_id"];
			$trips[$trip_user][] = new Trip($trip_id);
		}
		return $trips;
	}

	private function fetchRates() {

		if (is_null($this->rates)) {

			$db = Database::obtain();
			$rate_query = "SELECT target_id, score
				FROM rate 
				WHERE rater_id = $this->uid  
				AND trip_id = $this->trip_id";
			$rate_result = $db->fetch_array($rate_query);
			foreach ($rate_result as $val) {
				$this->rates[$val["target_id"]] = $val["score"];
			}
		}

		return $this->rates;
	}


	public function getTrip_id() { return $this->trip_id; }
	public function getUid() { return $this->uid; }
	public function getRole() { return $this->role; }
	public function getRates() { return $this->rates; }

	public function setTrip_id($x) { $this->trip_id = $x; }
	public function setUid($x) { $this->uid = $x; }
	public function setRole($x) { $this->role = $x; }
	public function setRates($x) { $this->rates = $x; }



	/* Rate */

	/**
	 * Return the score of the user in the given trip, or false
	 * if there is no records for this user in the trip.
	 */
	public static function getScore($trip_id, $uid) {

		$query = "SELECT score FROM rate WHERE trip_id = $trip_id AND target_id = $uid";
		$rates = Database::obtain()->fetch_array($query);

		if (count($rates) == 0) {
			return false;
		}

		$total = 0;
		for ($i = 0; $i < count($rates); $i++) {
			$total = $total + $rates[$i]["score"];
		}

		return $total / count($rates);
	}


	/*
	 * Return the information of the Rate of given trip_id, rater_uid, target_uid
	 * or false if the rate doesn't exist.
	 */
	private static function getRate($target_uid, $rater_uid, $trip_id) {

		$query = "SELECT * FROM rate WHERE trip_id = $trip_id AND target_id = $target_uid AND rater_id = $rater_uid";
		return Database::obtain()->query_first($query);
	}


	/**
	 * Return true if the Rate created sucessfully and false if not.
	 */
	private static function createRate($target_uid, $rater_uid, $trip_id, $score) {

		if (TripUser::getRate($target_uid, $rater_uid, $trip_id)) {
			return false;
		}

		$rate_data = array("trip_id" => $trip_id, "target_id" => $target_uid, "rater_id" => $rater_uid, "score" => $score);
		$result = Database::obtain()->insert("rate", $rate_data);

		if (is_bool($result)) {
			return $result;
		} else {
			return true;
		}
	}


	/**
	 * Return true if Rate is created succesfully and false if not.
	 */
	public static function rate($target_uid, $rater_uid, $trip_id, $score) {
//		print("$target_uid, $rater_uid, $trip_id, $score");

		// Return false if the score is not valid
		if (! is_integer($score) || $score < 1 || $score > 10) {
			return false;
		}
			
		$target = TripUser::getTripUser($trip_id, $target_uid);
		$rater = TripUser::getTripUser($trip_id, $rater_uid);

		// Return false if any of them is not on the trip
		if (! $rater_uid || ! $rater) {
			return false;
		}

		// Return false if any of them is a Candidate
		if ($target["user_role"] == TripRole::CANDIDATE || $rater["user_role"] == TripRole::CANDIDATE) {
			return false;
		}

		return TripUser::createRate($target_uid, $rater_uid, $trip_id, $score);
	}


	/**
	 * Return true if the rater has not rated the target user on the trip yet.
	 */
	public static function canRate($target_uid, $rater_uid, $trip_id) {

		return ! TripUser::getRate($target_uid, $rater_uid, $trip_id);
	}



	/* Create a TripUser */

	/**
	 * Return false if the trip user of same trip_id and uid already exists.
	 *
	 * Return the trip_user_id of the trip_user if the TripUser created
	 * successfully and false if not.
	 */
	private static function create($trip_id, $uid, $role) {

		if (TripUser::getTripUser($trip_id, $uid)) {
			return false;
		}

		$trip_user_data = array("trip_id" => $trip_id, "uid" => $uid, "user_role" => $role);
		return Database::obtain()->insert("trip_users", $trip_user_data);
	}


	/*
	 * Return the information of the TripUser of given trip_id, uid and role,
	 * or false if the user doesn't exist.
	 */
	private static function getTripUser($trip_id, $uid, $role=null) {

		$query = "SELECT * FROM trip_users WHERE trip_id = $trip_id AND uid = $uid";
		if (! is_null($role)) {
			$query += " AND user_role = $role";
		}

		return Database::obtain()->query_first($query);
	}


	/*
	 * Create a TripUser as driver.
	 */
	public static function createDriver($trip_id, $uid) {
		return TripUser::create($trip_id, $uid, TripRole::DRIVER);
	}


	/*
	 * Create a TripUser as candidate.
	 */
	public static function createCandidate($trip_id, $uid) {
		return TripUser::create($trip_id, $uid, TripRole::CANDIDATE);
	}

	/*
	 * Remove a TripUser from candidate list or passenger list.
	 */
	public static function removeInTrip($trip_id, $uid) {
		$query = "DELETE FROM `trip_users` WHERE trip_id=" . $trip_id 
			. " AND uid=" . $uid;
		return Database::obtain()->query($query);
	}


	/**
	 * Update the role of given trip_user
	 */
	private static function update($trip_user_id, $role) {

		$data = array("user_role" => $role);
		return Database::obtain()->update("trip_users", $data, "trip_user_id = $trip_user_id");
	}


	/*
	 * Update the role of given user as passenger
	 */
	private static function selectPassenger($trip_id, $uid) {

		$trip_user = TripUser::getTripUser($trip_id, $uid, $role);
		if (! $trip_user || $trip_user["user_role"] != TripRole::CANDIDATE) {
			return false;
		}
		return TripUser::update($trip_user["trip_user_id"], TripRole::PASSENGER);
	}

	/**
	 * Select an array of users as passengers.
	 */
	public static function selectPassengers($trip_id, $users) {
		foreach ($users as $uid) {
			TripUser::selectPassenger($trip_id, $uid);
		}
	}


	/*
	 * Return array of trip users info for given trip.
	 */
	public static function getTripUsers($trip_id) {

		$query = "SELECT * FROM trip_users WHERE trip_id = $trip_id";
		return Database::obtain()->fetch_array($query);
	}


	/*
	 * Return true if the person is a driver or a passenger of the trip.
	 */
	public static function inTrip($trip_id, $uid) {

		$candidate = TripRole::CANDIDATE;
		$query = "SELECT * FROM trip_users WHERE trip_id = $trip_id AND uid = $uid AND user_role != '$candidate'";
		return Database::obtain()->query_first($query);
	}

	/*
	 * Update the role of given user from passenger to candidate
	 */
	public static function removePassenger($trip_id, $uid) {
		
		$trip_user = TripUser::getTripUser($trip_id, $uid, $role);
		if (! $trip_user || $trip_user["user_role"] != TripRole::PASSENGER) {
			return false;
		}
		
		$today = new DateTime("now");
		
		$trip = Trip::getTrip($trip_id);
		$start_date = new DateTime($trip->getStart_date());
		
		if ($today > $start_date) {
			return false;
		}
		
		return TripUser::update($trip_user["trip_user_id"], TripRole::CANDIDATE);
	}
}
?>