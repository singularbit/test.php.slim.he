<?php

/**
 * Controller
 *
 * Controls Endpoints
 *
 * Endpoints...
 * GET: http://test.php.slim.he/getUser/1
 * PUT: http://test.php.slim.he/createUser
 * POST: http://test.php.slim.he/updateUser
 * DELETE: http://test.php.slim.he/deleteUser/1
 *
 * PUT and POST can be tested via:
 * PUT:  http://test.php.slim.he/userGUI
 * POST:  http://test.php.slim.he/userGUI/1
 *
 * Use Postman:
 *
 * DELETE: Select the DELETE method and add the endpoint with an id
 *
 * PUT: Select the POST method, add the endpoint and add the following form-data:
 * 	_METHOD = PUT
 * all other fields...
 *
 * POST: Select the POST method and add the endpoint with an id
 * all other fields... including user_id
 *
 * DATABASE: Don't forget to change the
 *
 */

namespace API\Controller;

use API\Model\UserDefault;
use API\Model\UserDisplayDefault;

require_once '../application/models/UserDefault.php';
require_once '../application/models/UserDisplayDefault.php';

class UserController
{

	/**
	 * Endpoint GET
	 *
	 * @param type $request
	 * @param type $response
	 * @param type $args
	 */
	public function getUser($request, $response, $args)
	{
		$user_id = $request->getAttribute('id'); // GET Data

		echo '<h2>Loaded user ' . $user_id . ' from database</h2>';
		$userData = UserDefault::getUser($user_id);

		echo $userData;
	}

	/**
	 * Endpoint PUT
	 *
	 * @param type $request
	 * @param type $response
	 * @param type $args
	 */
	public function createUser($request, $response, $args)
	{
		$userData = $request->getParsedBody();
		$newUser = UserDefault::createUser($userData);

		echo 'success! User ' . $newUser . ' created.';
	}

	/**
	 * Endpoint POST
	 *
	 * @param type $request
	 * @param type $response
	 * @param type $args
	 */
	public function updateUser($request, $response, $args)
	{
		$userData = $request->getParsedBody();
		$result = UserDefault::updateUser($userData);

		echo 'success! User ' . $userData['user_id'] . ' updated.';
	}

	/**
	 * Endpoint DELETE
	 *
	 * @param type $request
	 * @param type $response
	 * @param type $args
	 */
	public function deleteUser($request, $response, $args)
	{
		$user_id = $request->getAttribute('id');
		$result = UserDefault::deleteUser($user_id);

		echo 'deleteUser';
	}

	/**
	 * Test form
	 *
	 * @param type $request
	 * @param type $response
	 * @param type $args
	 */
	public function userGUI($request, $response, $args)
	{
		$user = [];
		$user_id = $request->getAttribute('id');
		if ($user_id > 0) {
			$user = UserDefault::getUser($user_id);
		}
		include_once __DIR__ . '/../views/UserDetails.php';
	}

}
