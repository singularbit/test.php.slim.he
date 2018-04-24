<?php

namespace API\Model;

use API\Model\UserInterface;
use API\Model\Database;
use API\Model\UserException;

include_once "../application/models/UserInterface.php";
include_once "../application/models/Database.php";
include_once "../application/models/UserException.php";

/**
 * Physical user. 
 */
abstract class UserAbstract implements UserInterface {

	const USER_TYPE_DEFAULT = 1;
	const USER_ERROR_NOT_FOUND = 404;
	const USER_ERROR_UNKNOWN_SUBCLASS = 400;
	const USER_ERROR_NO_DISPLAY_STRATEGY = 1002;
	const USER_ERROR_IN_DATABASE = 400;
	const USER_SUCCESS = 200;

	// User types.
	static public $valid_user_types = [
		UserAbstract::USER_TYPE_DEFAULT => 'Default'
	];
	// User ID
	public $user_id;
	// User email address
	public $email;
	// User firstname
	public $forename;
	// User lastname
	public $surname;
	// When the record was created and last updated.
	protected $_changed;
	// User type id.
	public $user_type_id;
	private static $_display_strategies = [
		'API\Model\UserDisplayDefault'
	];
	private $_display_strategy;

	/**
	 * Constructor.
	 * @param array $data Optional array of property names and values.
	 */
	function __construct($data = []) {

		$this->_changed = time();

		// Ensure that the User can be populated.
		if (!is_array($data)) {
			trigger_error('Unable to construct user with a ' . get_class($name));
		}

		// If there is at least one value, populate the User with it.
		if (count($data) > 0) {
			foreach ($data as $name => $value) {
				// Special case for protected properties.
				if (in_array($name, [
							'changed',
						])) {
					$name = '_' . $name;
				}
				$this->$name = $value;
			}
		}
	}

	/**
	 * Magic __get.
	 * @param string $name 
	 * @return mixed
	 */
	function __get($name) {

		// Attempt to return a protected property by name.
		$protected_property_name = '_' . $name;
		if (property_exists($this, $protected_property_name)) {
			return $this->$protected_property_name;
		}

		// Unable to access property; trigger error.
		trigger_error('Undefined property via __get: ' . $name);
		return NULL;
	}

	/**
	 * Magic __set.
	 * @param string $name
	 * @param mixed $value 
	 */
	function __set($name, $value) {

		// Unable to access property; trigger error.
		trigger_error('Undefined or unallowed property via __set(): ' . $name);
	}

	/**
	 * Magic __toString.
	 * @return string 
	 */
	function __toString() {
		return $this->display();
	}

	/**
	 * Force extending classes to implement init method. 
	 */
	abstract protected function _init();

	/**
	 * Display an address in HTML.
	 * @return string 
	 */
	function display() {

		if (is_null($this->_display_strategy)) {
			foreach (self::$_display_strategies as $strategy_class_name) {
				if ($strategy_class_name::isAvailable($this)) {
					$this->_display_strategy = $strategy_class_name;
				}
			}
		}
		if (!$this->_display_strategy) {
			throw new UserException('No display strategy found!', self::USER_ERROR_NO_DISPLAY_STRATEGY);
		}
		$display_strategy = $this->_display_strategy;
		return $display_strategy::display($this);
	}

	/**
	 * Determine if a user type is valid.
	 * @param int $user_type_id
	 * @return boolean
	 */
	static public function isValidUserTypeId($user_type_id) {
		return array_key_exists($user_type_id, self::$valid_user_types);
	}

	/**
	 * If valid, set the user type id.
	 * @param int $user_type_id 
	 */
	protected function _setUserTypeId($user_type_id) {
		if (self::isValidUserTypeId($user_type_id)) {
			$this->_user_type_id = $user_type_id;
		}
	}

	/**
	 * Return an instance of the subclass.
	 * @param array $data 
	 * @return User subclass
	 */
	final public static function getInstance($user_type_id, $data = []) {
		if (!self::isValidUserTypeId($user_type_id)) {
			UserException::errorHandler('User type out of range! Record is invalid.', self::USER_ERROR_NOT_FOUND);
		}
		$class_name = 'API\Model\User' . self::$valid_user_types[$user_type_id];
		if (!class_exists($class_name)) {
			UserException::errorHandler('User subclass not found, cannot create.', self::USER_ERROR_UNKNOWN_SUBCLASS);
		}
		return new $class_name($data);
	}

	/**
	 * Load a User.
	 * @param int $user_id 
	 */
	public static function getUser($user_id) {

		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT * FROM users WHERE user_id = ?";

		$stmt = $mysqli->prepare($query) or die($mysqli->error);
		$stmt->bind_param("i", $user_id);
		$stmt->execute();

		$result = $stmt->get_result();
		if ($row = $result->fetch_assoc()) {
			$classData = self::getInstance($row['user_type_id'], $row);
			return $classData;
		}
		UserException::errorHandler('User not found.', self::USER_ERROR_NOT_FOUND);
	}

	/**
	 * Create a User.
	 * @param array $user_data
	 */
	public static function createUser($user_data) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		// Prepare
		$stmt = $mysqli->prepare("INSERT INTO users ("
				. "user_type_id, "
				. "forename, "
				. "surname, "
				. "email, "
				. "changed "
				. ") VALUES (?, ?, ?, ?, ?)") or die($mysqli->error);

		foreach ($user_data as $key => $value) {
			$user_data[$key] = $mysqli->real_escape_string($value);
		}

		$user_data['user_type_id'] = self::USER_TYPE_DEFAULT;
		$user_data['changed'] = time();

		// Bind
		$stmt->bind_param("isssi", $user_data['user_type_id'], $user_data['forename'], $user_data['surname'], $user_data['email'], $user_data['changed']);

		// Execute
		$stmt->execute();

		if ($mysqli->error) {
			UserException::errorHandler('Could not create record', self::USER_ERROR_IN_DATABASE);
		}
		UserException::errorHandler('User created', self::USER_SUCCESS);
	}

	/**
	 * Update a User.
	 * @param array $user_data
	 */
	public static function updateUser($user_data) {

		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		// Prepare
		$stmt = $mysqli->prepare("UPDATE users SET "
				. "user_type_id = ?, "
				. "forename = ?, "
				. "surname = ?, "
				. "email = ?, "
				. "changed = ? "
				. "WHERE user_id = ?") or die($mysqli->error);

		foreach ($user_data as $key => $value) {
			$user_data[$key] = $mysqli->real_escape_string($value);
		}

		$user_data['user_type_id'] = self::USER_TYPE_DEFAULT;
		$user_data['changed'] = time();

		// Bind
		$stmt->bind_param("issssi", $user_data['user_type_id'], $user_data['forename'], $user_data['surname'], $user_data['email'], $user_data['changed'], $user_data['user_id']);

		// Execute
		$stmt->execute();

		if ($mysqli->error) {
			UserException::errorHandler('Could not update record', self::USER_ERROR_IN_DATABASE);
		}
		UserException::errorHandler('User updated', self::USER_SUCCESS);
	}

	/**
	 * Delete a User.
	 * @param int $user_id 
	 */
	public static function deleteUser($user_id) {

		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ?") or die($mysqli->error);
		$stmt->bind_param("i", $user_id);
		$stmt->execute();

		if ($mysqli->error) {
			UserException::errorHandler('Could not delete record', self::USER_ERROR_IN_DATABASE);
		}
		UserException::errorHandler('User deleted', self::USER_SUCCESS);
	}

}
