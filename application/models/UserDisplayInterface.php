<?php

namespace API\Model;

/**
 * Specify the methods a strategy must employ for displaying a user.
 */
interface UserDisplayInterface {

	/**
	 * UserDisplay a user.
	 * @return string
	 */
	public static function display($user);

	/**
	 * Is this method of display available?
	 * @return boolean
	 */
	public static function isAvailable($user);
}
