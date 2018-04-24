<?php

namespace API\Model;

/**
 * Shared interface for interactions. 
 */
interface UserInterface {

	/**
	 * Load a user.
	 * @param int $address_id 
	 */
	static function getUSer($user_id);

	/**
	 * Create a user. 
	 */
	static function createUser($userData);

	/**
	 * Update a user. 
	 */
	static function updateUser($user_data);

	/**
	 * Delete a user. 
	 */
	static function deleteUser($user_id);
}
