<?php

class Cookie {

	// THE only instance of the class
	private static $instance;

	private $expiration = 0;
	private $path = "";
	private $domain = "";
	private $secure = FALSE;
	private $httponly = FALSE;

	/**
	 * Class constructor
	 *
	 * @param int $expires Number of seconds until cookie expires
	 * @param mixed $path
	 * @param bool $secure
	 * @param bool $httponly
	 * @access public
	 */
	private function __construct($expires, $path, $domain, $secure, $httponly) {
		if(is_null($expires) || is_null($path) || is_null($domain) || is_null($secure) || is_null($httponly)){
			echo "Cookie initialization error: Null is found";
		}

		$this->expiration = $expires;
		$this->path = $path;
		$this->domain = $domain;
		$this->secure = $secure;
		$this->httponly = $httponly;
	}

	/**
	 *    Returns THE instance of 'Session'.
	 *    The session is automatically initialized if it wasn't.
	 *
	 *    @return    object
	 **/

	public static function getInstance($expires=null, $path=null, $domain=null, $secure=null, $httponly=null)
	{
		if ( !isset(self::$instance))
		{
			self::$instance = new Cookie($expires, $path, $domain, $secure, $httponly);
		}
			
		return self::$instance;
	}

	/**
	 * Deletes a cookie
	 *
	 * @param mixed $name
	 * @return bool
	 * @access public
	 */
	public function delete($name) {
		if (headers_sent() === FALSE) {

			setcookie (
			$name,
                "",
			time() - 3600,
			$this->path,
			$this->domain,
			$this->secure,
			$this->httponly);

			// Unset $_COOKIE value for same page load
			unset($_COOKIE[$name]);

			// Confirm Deletion
			if ($this->check($name) === FALSE) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Creates a cookie
	 *
	 * @param mixed $name
	 * @param mixed $data
	 * @return bool
	 * @access public
	 */
	public function set($name, $data = '') {
		if (headers_sent() === FALSE) {

			setcookie (
			$name,
			serialize($data),
			time() + $this->expiration,
			$this->path,
			$this->domain,
			$this->secure,
			$this->httponly);

			// Set $_COOKIE value for same page load
			$_COOKIE[$name] = serialize($data);

			// Confirm Set
			if ($this->check($name) === TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * Returns the value of a cookie
	 *
	 * @param mixed $name
	 * @return mixed
	 * @access public
	 */
	public function value($name) {
		if ($this->check($name) === TRUE) {
			return unserialize($_COOKIE[$name]);
		}
		return FALSE;
	}

	/**
	 * Check the existance of a cookie
	 *
	 * @param mixed $name
	 * @return bool
	 * @access public
	 */
	public function check($name) {
		return isset($_COOKIE[$name]);
	}

}

?>