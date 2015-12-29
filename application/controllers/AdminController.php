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

use \CWA\MVC\Controllers\InvalidArgumentException;
use \CWA\Net\HTTP\HttpResponse;

require_once 'BaseController.php';
require_once \CWA\LIB_PATH . 'cwa/mvc/controllers/InvalidArgumentException.php';
require_once \CWA\LIB_PATH . 'cwa/net/http/HttpResponse.php';

class AdminController extends BaseController
{
	/* Constructor: */
	public function __construct() {
		parent::__construct();
		$this->viewInfo['code']['name'] = 'Code Editor';
		$this->viewInfo['code-dir']['title'] = 'Code Editor :: Admin';
		$this->viewInfo['code-file']['title'] = 'Code Editor :: Admin';
		$this->viewInfo['code-file-save']['title'] = 'Code Editor :: Admin';
		$this->viewInfo['db']['name'] = 'DB Administrator';
		$this->viewInfo['db']['title'] = 'Database Administrator :: Admin';
		$this->viewInfo['index']['title'] = 'Site Admin';
		$this->viewInfo['log']['name'] = 'Log Viewer';
		$this->viewInfo['log']['title'] = 'Log Viewer :: Admin';
		$this->viewInfo['qa']['name'] = 'QA Assistant';
		$this->viewInfo['qa']['title'] = 'QA Assistant :: Admin';
	}


	/* Public methods: */

	public function code($params = null) {
		if (!is_array($params)) { // This is a GET for a dir or file. -- cwells
			if (is_null($params)) {
				$params = '';
				$path = '.';
			} else {
				$path = str_ireplace('~', '/', $params);
			}

			if (strpos(realpath($path), realpath('.')) !== 0) {
				throw new InvalidArgumentException('The specified file does not exist within this application.', 400);
			} else if (is_file($path)) {
				$this->loadView('code-file');
				$this->view->setData(array('FileContents' => file_get_contents($path),
											'FilePath' => $path,
											'ReadOnly' => !is_writable($path)));
			} else if (is_dir($path)) {
				$dirs = array();
				$files = array();
				$iterator = new DirectoryIterator($path);
				foreach ($iterator as $file) {
					if ($file->isDot() || !$file->isReadable()) {
						continue;
					} else if ($file->isDir()) {
						$dirs[] = $file->getFilename();
					} else {
						$files[] = $file->getFilename();
					}
				}
				sort($dirs);
				sort($files);
				$this->loadView('code-dir');
				$this->view->setData(array('DirectoryPath' => $path,
											'Dirs' => $dirs,
											'Files' => $files,
											'PathPrefix' => (empty($params) ? 'code/' : "$params~")));
			} else {
				throw new InvalidArgumentException('You must specify a valid file or directory path.', 400);
			}
		} else { // This is a POST with the contents of a file. -- cwells
			if (!isset($params['file-path']) || empty($params['file-path'])) {
				throw new InvalidArgumentException('You must specify a file path.', 400);
			} else if (strpos(realpath($params['file-path']), realpath('.')) !== 0) {
				throw new InvalidArgumentException('The specified file does not exist within this application.', 400);
			} else if (!is_file($params['file-path'])) {
				throw new InvalidArgumentException('You must specify a valid file path.', 400);
			} else {
				if (file_put_contents($params['file-path'], utf8_encode($params['file-contents'])) === false) {
					$this->loadView('code-file');
					$this->view->setStatus('Failed to update the specified file.', 500);
					$this->view->setData(array('FileContents' => $params['file-contents'],
												'FilePath' => $params['file-path']));
				} else {
					$this->loadView('code-file-save');
					$this->view->setData('FilePath', $params['file-path']);
				}
			}
		}
	}

	public function db($params = null) {
		$db = $this->app->getDatabase();

		if (!empty($params) && $params === 'backup') {
			HttpResponse::setContentType('application/octet-stream');
			HttpResponse::setContentDisposition('db-' . DOMAIN . '-' . date('Ymd-Hi') . '.sql.gz');
			passthru($db->getBackupCommand() . ' | gzip --best', $error);
			if (!empty($error)) {
				$this->logger->error("Error backing up database: $error");
			}
			exit(0);
		}

		$this->loadView('db');
		if (!isset($_SESSION['QueryHistory'])) {
			$_SESSION['QueryHistory'] = array();
		}

		$result = $db->fetchAll('SHOW TABLES;', null, PDO::FETCH_NUM);
		if ($result !== false) {
			$tables = array();
			foreach (array_values($result) as $tableArray) {
				foreach ($tableArray as $table) {
					$tables[] = $table;
				}
			}
			$this->view->setData('Tables', $tables);
		}

		if (!empty($params) && isset($params['query'])) {
			$query = trim($params['query']);
			// If it exists, remove the current query from the history before appending it. -- cwells
			$historyIndex = array_search($query, $_SESSION['QueryHistory']);
			if ($historyIndex !== false) {
				array_splice($_SESSION['QueryHistory'], $historyIndex, 1);
			}
			$_SESSION['QueryHistory'][] = $query;

			if (stripos($query, 'SELECT') === 0 || stripos($query, 'SHOW') === 0 || stripos($query, 'DESCRIBE') === 0) {
				$result = $db->fetchAll($query);
			} else {
				$result = $db->execute($query);
			}
			$errorInfo = $db->getErrorInfo();
			if (!is_null($errorInfo) && count($errorInfo) > 2) {
//				$this->view->setStatus($errorInfo[2], 500);
				$this->view->setData('DBError', $errorInfo[2]);
			}
			$this->view->setData(array('Query' => $query,
										'Result' => $result,
										'RowCount' => $db->getRowCount()));
		}
		$this->view->setData('History', $_SESSION['QueryHistory']);
	}

	public function index() {
		// Check for public methods inside this controller:
		$reflectionClass = new ReflectionClass($this);
		$adminMethods = array();
		foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			$methodName = $method->getName();
			if ($methodName === 'index') {
				continue;
			} else if (strpos($methodName, '__') === 0) { // Methods beginning with __ are disallowed. -- cwells
				continue;
			} else if ($method->getNumberOfRequiredParameters() !== 0) {
				continue;
			} else if (!$this->app->userIsAuthorized('admin', $methodName)) {
				continue;
			}

			if (isset($this->viewInfo[$methodName]) && isset($this->viewInfo[$methodName]['name'])) {
				$toolName = $this->viewInfo[$methodName]['name'];
			} else {
				$toolName = $methodName;
			}
			$adminMethods[$toolName] = \CWA\APP_ROOT . "admin/$methodName";
		}
		ksort($adminMethods);

		// Check for controllers with a public admin method:
		$modelAdminURLs = array();
		$appControllers = $this->app->getControllers();
		$iterator = new DirectoryIterator('controllers');
		foreach ($iterator as $file) {
			if ($file->isDot() || $file->isDir() || !$file->isReadable()) {
				continue;
			}

			require_once "controllers/{$file->getFilename()}";
			$className = $file->getBasename('.php');
			$reflectionClass = new ReflectionClass($className);
			if (!$reflectionClass->isInstantiable() || !is_subclass_of($className, 'BaseDBController')
				|| !$reflectionClass->hasMethod('admin') || !$reflectionClass->getMethod('admin')->isPublic()) {
				continue;
			}

			foreach ($appControllers as $controller => $attributes) {
				if (isset($attributes['class']) && $attributes['class'] === $className) {
					if ($this->app->userIsAuthorized($controller, 'admin')) {
						$modelAdminURLs[str_replace('Controller', '', $className)] = \CWA\APP_ROOT . "$controller/admin";
					}
					break;
				}
			}
		}
		ksort($modelAdminURLs);

		$this->loadView('index');
		$this->view->setData(array('AdminMethods' => $adminMethods,
									'ModelAdminURLs' => $modelAdminURLs));
	}

	public function log(array $params = null) {
		$path = sprintf(\CWA\Util\LOG_PATH, \CWA\Util\LOG_NAME);
		if (!empty($params) && isset($params['delete'])) {
			file_put_contents($path, '');
			$this->app->redirect(''); // Convert to a GET to prevent double-submits. -- cwells
		}
		$this->loadView('log');
		$this->view->setData(array('FileContents' => file_get_contents($path),
									'FilePath' => $path,
									'LogLevel' => \CWA\Util\LOG_LEVEL));
	}

	public function qa() {
		$controllers = array();
		$appControllers = $this->app->getControllers();
		$iterator = new DirectoryIterator('controllers');
		foreach ($iterator as $file) {
			if ($file->isDot() || $file->isDir() || !$file->isReadable()) {
				continue;
			}

			require_once "controllers/{$file->getFilename()}";
			$className = $file->getBasename('.php');
			$reflectionClass = new ReflectionClass($className);
			if (!$reflectionClass->isInstantiable()) {
				continue;
			}

			$classURL = null;
			foreach ($appControllers as $controller => $attributes) {
				if (isset($attributes['class']) && $attributes['class'] === $className) {
					$classURL = \CWA\APP_ROOT . $controller;
					break;
				}
			}
			if (is_null($classURL)) {
				continue;
			}

			$controllers[$className] = array();
			$isDBController = is_subclass_of($className, 'BaseDBController');
			$modelName = str_replace('Controller', '', $className);
			$item = null;
			if ($isDBController && file_exists("models/$modelName.php")) {
				// Get a random item for URLs that need its ID/key. -- cwells
				require_once "models/$modelName.php";
				$item = $this->app->getDatabase()->selectRandom($modelName);
			}
			foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				$methodName = $method->getName();
				if (strpos($methodName, '__') === 0) { // Methods beginning with __ are disallowed. -- cwells
					continue;
				} else if ($isDBController && $methodName === 'page' && !$reflectionClass->getMethod('index')->isPublic()) {
					continue;
				}

				$methodInfo = array('name' => $methodName);
				if ($methodName === 'index') {
					$methodInfo['url'] = $classURL;
				} else {
					$methodInfo['url'] = "$classURL/$methodName";
				}

				$params = $method->getParameters();
				$acceptsPageNumber = false;
				$needsItemID = false;
				foreach ($params as $index => $param) {
					$acceptsPageNumber = ($param->getName() === 'pageNumber');
					$needsItemID = (!$param->isOptional() && $param->getName() === 'itemID');
					$matches = array();
					preg_match('/^[^\[]* \[ ([^\]]*)]/', $param->__toString(), $matches);
					$params[$index] = rtrim($matches[1]);
				}
				$methodInfo['parameters'] = $params;
				$methodInfo['roles'] = $this->app->getAuthorizedRoles($controller, $methodName);
				$controllers[$className][$methodInfo['url']] = $methodInfo;

				if ($needsItemID && !is_null($item)) { // Duplicate this URL with an item ID. -- cwells
					$methodInfo['url'] .= '/' . strtolower($item->{$item->getAlternateKeyName()});
					$controllers[$className][$methodInfo['url']] = $methodInfo;
				} else if ($acceptsPageNumber) {
					// Duplicate this URL with a reasonable page number and one that's likely out of bounds. -- cwells
					$methodInfo['url'] .= '/2';
					$controllers[$className][$methodInfo['url']] = $methodInfo;
					$methodInfo['url'] .= '00000'; // The URL already has /2 appended. -- cwells
					$controllers[$className][$methodInfo['url']] = $methodInfo;
				}
			}
			ksort($controllers[$className]);
		}
		ksort($controllers);
		$this->loadView('qa');
		$this->view->setData(array('Controllers' => $controllers));
	}

}

?>