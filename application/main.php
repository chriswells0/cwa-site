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

require_once 'config/config.php';
require_once 'WebApp.php';

$app = new WebApp();
$app->setControllers(array(
	'__GLOBAL__' => array('authorizedRoles' => array('add' => array('ADMIN'),
													 'admin' => array('ADMIN'),
													 'delete' => array('ADMIN'),
													 'edit' => array('ADMIN'),
													 'save' => array('ADMIN'))),
	'account' => array('class' => 'AccountController',
						'authorizedRoles' => array('logout' => array())),
	'admin' => array('class' => 'AdminController',
					 'authorizedRoles' => array('__ALL__' => array('ADMIN', 'DEV'),
												'index' => array('QA'),
												'qa' => array('QA'))),
	'error' => array('class' => 'ErrorController'),
	'roles' => array('class' => 'RoleController',
					'authorizedRoles' => array('__ALL__' => array('ADMIN'))),
	'site' => array('class' => 'SiteController'),
	'users' => array('class' => 'UserController',
					'authorizedRoles' => array('__ALL__' => array('ADMIN')))
));

$app->main();
?>