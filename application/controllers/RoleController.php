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

require_once 'BaseDBController.php';

class RoleController extends BaseDBController
{
	/* Constructor: */
	public function __construct() {
		$this->pathInURL = \CWA\APP_ROOT . 'roles';
		parent::__construct();
		$this->viewInfo['view']['title'] = '{Name} :: Roles';
		$this->viewInfo['view']['description'] = '{Name}';
		$this->viewInfo['view']['canonicalURL'] = PROTOCOL_HOST_PORT . "$this->pathInURL/view/{Type}";
	}


	/* Public methods: */

	public function add($properties = null) {
		parent::add($properties);
		$itemUserIDs = array();
		$users = $this->db->selectAll('User', 'ORDER BY FirstName, LastName');
		if (is_null($users)) {
			throw new DatabaseException('Error loading users.', 500);
		}
		$this->view->setData(array('Users' => $users, 'RoleUserIDs' => $itemUserIDs));
	}

	public function edit($itemID) {
		parent::edit($itemID);
		$users = $this->db->selectAll('User', 'ORDER BY FirstName, LastName');
		if (is_null($users)) {
			throw new DatabaseException('Error loading users.', 500);
		}
		$records = $this->db->fetchAll('SELECT UserID FROM User_Role WHERE RoleID = :RoleID',
										array('RoleID' => $this->view->getData('Role')->ID));
		if ($records === false) {
			throw new DatabaseException('Failed to retrieve role user IDs.', 500);
		}
		$itemUserIDs = array();
		foreach ($records as $record) {
			$itemUserIDs[] = $record['UserID'];
		}
		$this->view->setData(array('Users' => $users, 'RoleUserIDs' => $itemUserIDs));
	}

}

?>