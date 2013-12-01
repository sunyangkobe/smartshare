<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * User Data Model
 *
 * @author Kobe Sun, Europa Shang
 *
 */

class User {

	private $uid = -1;
	private $pwd = "";
	private $username = "";
	private $email = "";
	private $lname = "";
	private $fname = "";
	private $age = -1;
	private $description = "";
	private $telephone = "";
	private $car_id = -1;
	private $pic = "";
	private $activated = 0;
	private $is_admin = -1;

	public function __construct($param) {
		$this->buildAttrs($param);
	}

	public function buildAttrs($param) {
		if (isset($param["uid"])) {
			$this->uid = $param["uid"];
		}
		if (isset($param["pwd"])) {
			$this->pwd = $param["pwd"];
		}
		if (isset($param["username"])) {
			$this->username = $param["username"];
		}
		if (isset($param["email"])) {
			$this->email = $param["email"];
		}
		if (isset($param["lname"])) {
			$this->lname = $param["lname"];
		}
		if (isset($param["fname"])) {
			$this->fname = $param["fname"];
		}
		if (isset($param["age"])) {
			$this->age = $param["age"];
		}
		if (isset($param["description"])) {
			$this->description = $param["description"];
		}
		if (isset($param["telephone"])) {
			$this->telephone = $param["telephone"];
		}
		if (isset($param["car_id"])) {
			$this->car_id = $param["car_id"];
		}
		if (isset($param["pic"])) {
			$this->pic = $param["pic"];
		}
		if (isset($param["activated"])) {
			$this->activated = $param["activated"];
		}
		return true;
	}

	
	/**
	 * 
	 * Pass in some criteria to search for a user, return the user instance
	 * @param array $criteria
	 * @param string $operator
	 */
	public static function searchBy($criteria, $operator="=") {
		// Get user information
		$db = Database::obtain();
		$user_query = "SELECT * FROM `users` WHERE ";

		foreach ($criteria as $k => $v) {
			if(strtolower($v)=='null') $user_query.= "`$k` = NULL";
			elseif(strtolower($v)=='now()') $user_query.= "`$k` = NOW()";
			else $user_query.= "`$k`='".$db->escape($v)."'";
			$user_query .= " AND ";
		}
		$user_query = rtrim($user_query, " AND ");

		$user = $db->query_first($user_query);
		return $user ? new User($user) : false;
	}

	
	/**
	 * 
	 * Add this user to database
	 */
	public function addUser() {
		$userData = array (
			"username" => $this->username,
			"email" => $this->email,
			"pwd" => $this->pwd,
			"activated" => $this->activated
		);
		if (Database::obtain()->insert("users", $userData)) {
			$ret_user = User::searchBy($userData);
			if ($ret_user) $this->uid = $ret_user->getUid();
		}
		return $this->uid;
	}

	
	/**
	 * 
	 * update this user in the database
	 * @param array $new_attrs
	 */
	public function updateUser($new_attrs) {
		return Database::obtain()->update("users", $new_attrs, "uid=$this->uid");
	}

	/**
	 * 
	 * Change the activate value in the database
	 */
	public function activate() {
		$this->updateUser(array("activated" => 1));
	}
	

	/**
	 * 
	 * Check whether the user is activated ...
	 */
	public function isActivated() {
		return $this->activated == 1;
	}

	
	/**
	 * 
	 * Check whether the user is administrator ...
	 */
	public function isAdmin() {
		if ($this->is_admin == -1) {
			$db = Database::obtain();
			$admin_query = "SELECT * FROM `admin` WHERE `uid`='".$db->escape($this->uid)."'";
			$this->is_admin = Database::obtain()->query_first($admin_query) ? 1 : 0;
		}
		return $this->is_admin;
	}
	
	
	/**
	 * 
	 * Check whether the user exists in an users array
	 * @param array $userArr
	 */
	public function userExists(array $userArr) {
		foreach ($userArr as $user) {
			if ($user->getUid() == $this->uid) {
				return true;
			}
		}
		return false;
	}

	public function getUid() { return $this->uid; }
	public function getPwd() { return $this->pwd; }
	public function getUsername() { return $this->username; }
	public function getEmail() { return $this->email; }
	public function getLname() { return $this->lname; }
	public function getFname() { return $this->fname; }
	public function getAge() { return $this->age; }
	public function getDescription() { return $this->description; }
	public function getTelephone() { return $this->telephone; }
	public function getCarId() { return $this->car_id; }
	public function getActivated() { return $this->activated; }
	public function getPic() { return $this->pic; }
	public function setUid($x) { $this->uid = $x; }
	public function setPwd($x) { $this->pwd = $x; }
	public function setUsername($x) { $this->username = $x; }
	public function setEmail($x) { $this->email = $x; }
	public function setLname($x) { $this->lname = $x; }
	public function setFname($x) { $this->fname = $x; }
	public function setAge($x) { $this->age = $x; }
	public function setDescription($x) { $this->description = $x; }
	public function setTelephone($x) { $this->telephone = $x; }
	public function setCarId($x) { $this->car_id = $x; }
	public function setPic($x) { $this->pic = $x; }
	public function setActivated($x) { $this->activated = $x; }

	public static function getUser($uid) {
		return User::searchBy(array("uid" => $uid));
	}
}

?>