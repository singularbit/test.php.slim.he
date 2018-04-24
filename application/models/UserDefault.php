<?php

namespace API\Model;

use API\Model\UserAbstract;

include_once '../application/models/UserAbstract.php';

/**
 * Default User. 
 */
class UserDefault extends UserAbstract {

	/**
	 * Initialization. 
	 */
	protected function _init() {
		$this->_setUserTypeId(UserAbstract::USER_TYPE_DEFAULT);
	}

}
