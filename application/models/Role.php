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

use \CWA\DB\DatabaseMapping;
use \CWA\MVC\Models\DatabaseRecord;

require_once \CWA\LIB_PATH . 'cwa/mvc/models/DatabaseRecord.php';

class Role extends DatabaseRecord
{
	/* Protected variables: */
	protected static $altKeyName = 'Type';
}

/* Database mappings: instantiated outside the class definition because they're static. */

Role::addDatabaseMapping('Users',
	new DatabaseMapping(DatabaseMapping::ManyToMany, 'ID', 'User_Role.RoleID', array(
		new DatabaseMapping(DatabaseMapping::ManyToMany, 'User_Role.UserID', 'User.ID')
)));
?>