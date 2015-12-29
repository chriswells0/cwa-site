<?php
/*
 * Copyright (c) 2014 Chris Wells (https://chriswells.io)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

use \CWA\DB\DatabaseException;
use \CWA\MVC\Controllers\InvalidArgumentException;

require_once 'BaseDBController.php';

class UserController extends BaseDBController
{
	/* Constructor: */
	public function __construct() {
		$this->pathInURL = \CWA\APP_ROOT . 'users';
		parent::__construct();
		$this->viewInfo['view']['title'] = '{Nickname} :: Users';
		$this->viewInfo['view']['description'] = '{Nickname}';
		$this->viewInfo['view']['canonicalURL'] = PROTOCOL_HOST_PORT . "$this->pathInURL/view/{Nickname}";
	}


	/* Public methods: */

	public function add($properties = null) {
		parent::add($properties);
		$itemRoleIDs = array();
		$roles = $this->db->selectAll('Role', 'ORDER BY Name');
		if (is_null($roles)) {
			throw new DatabaseException('Error loading roles.', 500);
		}
		$this->view->setData(array('Roles' => $roles, 'UserRoleIDs' => $itemRoleIDs));
	}

	public function edit($itemID) {
		parent::edit($itemID);
		$roles = $this->db->selectAll('Role', 'ORDER BY Name');
		if (is_null($roles)) {
			throw new DatabaseException('Error loading roles.', 500);
		}
		$records = $this->db->fetchAll('SELECT RoleID FROM User_Role WHERE UserID = :UserID',
										array('UserID' => $this->view->getData('User')->ID));
		if ($records === false) {
			throw new DatabaseException('Failed to retrieve user role IDs.', 500);
		}
		$itemRoleIDs = array();
		foreach ($records as $record) {
			$itemRoleIDs[] = $record['RoleID'];
		}
		$this->view->setData(array('Roles' => $roles, 'UserRoleIDs' => $itemRoleIDs));
	}

	public function save(array $properties) {
		if (empty($properties) || !is_array($properties)) {
			throw new InvalidArgumentException('You must provide the values to update.', 400);
		}

		if (!empty($properties['SetPassword']) && $properties['SetPassword'] === 'yes') {
			$errorCode = 400;
			$errorMessage = '';
			if ($properties['Password'] !== $properties['ConfirmPassword']) {
				$errorMessage = 'Confirm Password must match Password.';
			} else if (strlen($properties['Password']) < 10) {
				$errorMessage = 'Password cannot be less than 10 characters long.';
			} else if (strlen($properties['Password']) > 100) {
				$errorMessage = 'Password cannot be more than 100 characters long.';
			} else { // Create a User object just to generate the password hash. -- cwells
				$user = new User($properties);
				if (!$user->setPassword($properties['Password'])) {
					$errorCode = 500;
					$errorMessage = 'Failed to set the password. Please try again.';
				}
				$properties = $user->toArray();
			}

			if (!empty($errorMessage)) {
				if (empty($properties[User::getPrimaryKeyName()])) {
					$this->add($properties);
				} else {
					$this->edit($properties);
				}
				$this->view->setStatus($errorMessage, $errorCode);
				return;
			}
		}
		// These are not actual properties on the User object. -- cwells
		unset($properties['SetPassword']);
		unset($properties['Password']);
		unset($properties['ConfirmPassword']);

		parent::save($properties);
	}

}

?>