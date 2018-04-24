<?php

namespace API\Model;

use API\Model\UserDisplayInterface;

require_once '../application/models/UserDisplayInterface.php';

class UserDisplayDefault implements UserDisplayInterface {

	/**
	 * Display a user.
	 */
	public static function display($user) {
		echo json_encode($user);
	}

	/**
	 * Is this method of display available?
	 * @return boolean
	 */
	public static function isAvailable($user) {
		return TRUE;
	}

}
