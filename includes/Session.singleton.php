<?php

/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Use the static method getInstance to get the object.
 *
 * @author Kobe Sun, linblow
 *
 */

class Session
{
	const SESSION_STARTED = TRUE;
	const SESSION_NOT_STARTED = FALSE;

	// The state of the session
	private $sessionState = self::SESSION_NOT_STARTED;

	// THE only instance of the class
	private static $instance;


	private function __construct($session_name=null) {
		$session_name = is_null($session_name) ? session_name() : $session_name;
		session_name($session_name);
	}


	/**
	 *    Returns THE instance of 'Session'.
	 *    The session is automatically initialized if it wasn't.
	 *
	 *    @return    object
	 **/

	public static function getInstance($session_name=null)
	{
		if ( !isset(self::$instance))
		{
			self::$instance = new Session($session_name);
		}
			
		return self::$instance;
	}


	/**
	 *    Starts the session.
	 *
	 *    @return    bool    TRUE if the session has been initialized, else FALSE.
	 **/

	public function startSession()
	{
		if ( $this->sessionState == self::SESSION_NOT_STARTED )
		{
			$this->sessionState = session_start();
		}
			
		return $this->sessionState;
	}


	/**
	 *    Stores datas in the session.
	 *    Example: $instance->foo = 'bar';
	 *
	 *    @param    name    Name of the datas.
	 *    @param    value    Your datas.
	 *    @return    void
	 **/

	public function __set( $name , $value )
	{
		$_SESSION[$name] = serialize($value);
	}


	/**
	 *    Gets datas from the session.
	 *    Example: echo $instance->foo;
	 *
	 *    @param    name    Name of the datas to get.
	 *    @return    mixed    Datas stored in session.
	 **/

	public function __get( $name )
	{
		if ( isset($_SESSION[$name]))
		{
			return unserialize($_SESSION[$name]);
		}
	}

	public function __empty($name)
	{
		return empty($_SESSION[$name]);
	}


	public function __isset( $name )
	{
		return isset($_SESSION[$name]);
	}


	public function __unset( $name )
	{
		unset( $_SESSION[$name] );
	}


	/**
	 *    Destroys the current session.
	 *
	 *    @return    bool    TRUE is session has been deleted, else FALSE.
	 **/

	public function destroy()
	{
		if ( $this->sessionState == self::SESSION_STARTED )
		{
			session_regenerate_id();
			$this->sessionState = !session_destroy();
			session_unset();

			return !$this->sessionState;
		}
			
		return FALSE;
	}
}

?>