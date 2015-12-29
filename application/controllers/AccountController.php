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

require_once 'BaseController.php';
require_once 'models/User.php';

class AccountController extends BaseController
{
	/* Constructor: */
	public function __construct() {
		parent::__construct();
		$this->viewInfo['login']['title'] = 'Log In';
	}


	/* Public methods: */

	public function login(array $params = null) {
		if ($this->app->getCurrentUser()->isLoggedIn()) {
			$this->app->redirectToHome();
		}

		$this->loadView('login');
		if (!empty($params)) { // Data was received, so this is a login attempt. -- cwells
			if (empty($params['Nickname']) || empty($params['Password'])) {
				$this->view->setStatus('You must provide a username and a password.', 400);
			} else {
				$db = $this->app->getDatabase();
				$user = $db->select('User', $params['Nickname']);
				if (is_null($user)) {
					// Perform the password verification even when the user is not found in order to make timing attacks more difficult. -- cwells
					$user = new User();
					$user->verifyPassword($params['Password']);
					$this->view->setStatus('Login failure. Please try again.', 401);
					$this->logger->warn('Failed to retrieve User with primary key = ' . $params['Nickname'] . '.');
				} else if ($user->verifyPassword($params['Password']) === false) {
					$this->view->setStatus('Login failure. Please try again.', 401);
					$this->logger->warn('Invalid password provided for account: ' . $params['Nickname']);
				} else {
					$user->LastLogin = date(\CWA\DB\DATETIME_PHP_TO_DB);
					$userProperties = $user->toArray();
					$db->update('User', $userProperties);
					$this->app->setCurrentUser($user);

					if (!empty($_GET['returnURL']) && strncmp('/', $_GET['returnURL'], 1) === 0) {
						$this->app->redirect($_GET['returnURL']);
					} else {
						$this->app->redirectToHome();
					}
				}
			}
		}
	}

	public function logout() {
		$this->app->recreateSession();
		$this->app->redirectToHome();
	}

}

?>