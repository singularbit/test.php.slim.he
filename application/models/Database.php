<?php

namespace API\Model;

use API\Model\UserException;

include_once "../application/models/UserException.php";

/**
 * MySQLi database.
 */
class Database
{

	private $_connection;
	// Single instance
	private static $_instance;

	/**
	 * Instance of the Database.
	 * @return Database
	 *
	 */
	public static function getInstance()
	{
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->_connection = new \mysqli('localhost', 'root', 'F3rr@r!', 'he');
		// Error handling.
		if (mysqli_connect_error()) {
			UserException::errorHandler('Failed to connect to MySQL: ' . mysqli_connect_error(), 403);
		}
	}

	/**
	 * Empty clone magic method to prevent duplication.
	 */
	private function __clone()
	{

	}

	/**
	 * Get the mysqli connection.
	 */
	public function getConnection()
	{
		return $this->_connection;
	}

}
