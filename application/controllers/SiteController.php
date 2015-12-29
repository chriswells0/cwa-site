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

require_once 'BaseController.php';
require_once \CWA\LIB_PATH . 'cwa/mvc/controllers/InvalidArgumentException.php';

class SiteController extends BaseController
{
	/* Constructor: */
	public function __construct() {
		parent::__construct();
		$this->viewInfo['about']['title'] = 'About';
		$this->viewInfo['about']['description'] = 'About me and my site.';
		$this->viewInfo['contact']['title'] = 'Contact Me';
		$this->viewInfo['contact']['description'] = 'Contact ' . SITE_AUTHOR . '.';
		$this->viewInfo['contact-success']['title'] = 'Message Sent';
		$this->viewInfo['credits']['title'] = 'Site Credits';
		$this->viewInfo['credits']['description'] = 'Credits for 3rd-party resources used on ' . SITE_DOMAIN . '.';
		$this->viewInfo['index']['title'] = 'Home Page';
		$this->viewInfo['index']['description'] = 'Home page on my site.';
		$this->viewInfo['map']['title'] = 'Site Map';
		$this->viewInfo['map']['description'] = 'List of all main pages on my site.';
	}


	/* Public methods: */

	public function about() {
		$this->loadView('about');
	}

	public function contact($params = null) {
		if (!isset($params)) { // Initial page/form load.
			$this->loadView('contact');
			// Default values:
			$this->view->setData(array(
				'anonymous' => false,
				'fromName' => '',
				'fromEmail' => '',
				'subject' => '',
				'message' => '',
				'captchaError' => null
			));
		} else if (is_array($params)) { // Process the form data.
			$this->loadView('contact');
			foreach ($params as $key => $value) {
				$params[$key] = trim($value);
			}
			$params['anonymous'] = (!empty($params['anonymous']) && $params['anonymous'] === 'true');
			$params['fromName'] = str_ireplace('"', '', $params['fromName']);
			$params['subject'] = str_ireplace('"', '', $params['subject']);
			if ($this->view->getFormat() !== 'json') $this->view->setData($params); // In case we need to display the data on an error screen.

			if (!$params['anonymous'] && (empty($params['fromName']) || empty($params['fromEmail']))) {
				$this->view->setStatus('If you do not wish to remain anonymous, your name and email address are required.', 400);
			} else if (!$params['anonymous'] && preg_match('/(\r|\n|%0A|%0D)/i', $params['fromName']) === 1) {
				$this->view->setStatus('If you do not wish to remain anonymous, you must provide a valid name.', 400);
			} else if (!$params['anonymous']
						&& (filter_var($params['fromEmail'], FILTER_VALIDATE_EMAIL) === false
						|| checkdnsrr(array_pop(explode('@', $params['fromEmail']))) === false)) {
				$this->view->setStatus('If you do not wish to remain anonymous, you must provide a valid email address.', 400);
			} else if (empty($params['message'])) {
				$this->view->setStatus('Nothing worthwhile to say?', 400);
			} else if (defined('RECAPTCHA_PUBLIC_KEY') && empty($params['g-recaptcha-response'])) {
				$this->view->setStatus('Are you a robot?', 400);
			} else {
				if (defined('RECAPTCHA_PUBLIC_KEY')) {
					$verifyURL = 'https://www.google.com/recaptcha/api/siteverify'
									. '?secret=' . urlencode(stripslashes(RECAPTCHA_PRIVATE_KEY))
									. '&response=' . urlencode(stripslashes($params['g-recaptcha-response']))
									. '&remoteip=' . urlencode(stripslashes($_SERVER['REMOTE_ADDR']));

					// This approach has an SSL issue. -- cwells
					//$response = json_decode(file_get_contents($verifyURL), true);

					$options = array(CURLOPT_URL => $verifyURL,
									CURLOPT_CONNECTTIMEOUT => 5,
									CURLOPT_RETURNTRANSFER => true);
					$ch = curl_init();
					if (curl_setopt_array($ch, $options)) {
						$response = curl_exec($ch);
						if ($response !== false) {
							$response = json_decode($response, true);
						}
					}
					if (curl_errno($ch)) {
						$this->logger->error('cURL error: ' . curl_error($ch));
					}
					curl_close($ch);

					$validCAPTCHA = (!empty($response) && isset($response['success']) && $response['success']);
				} else { // reCAPTCHA is disabled, so fake success. -- cwells
					$validCAPTCHA = true;
				}

				if (!$validCAPTCHA) {
					$this->view->setStatus('Are you sure you\'re human?', 400);
					$this->view->setData('captchaError', $response['error-codes']);
				} else {
					if ($params['anonymous']) {
						$from = CONTACT_ANONYMOUS_FROM;
					} else {
						$from = '"' . $params['fromName'] . '" <' . $params['fromEmail'] . '>';
					}

					if (mail(CONTACT_SENDTO, $params['subject'], $params['message'], "From: $from")) {
						$this->app->redirect('/site/contact/success'); // Redirect to the success page to prevent double posts. -- cwells
					} else {
						$this->view->setStatus('Failed to send your message. Please try again.', 500);
					}
				}
			}
		} else if ($params === 'success') { // Show the success page.
			$this->loadView('contact-success');
			$this->view->setStatus('Your message has been sent. Thanks for contacting me.');
		}
	}

	public function credits() {
		$this->loadView('credits');
	}

	public function index() {
		parent::index();
	}

	public function map() {
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
			foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				$methodName = $method->getName();
				if (strpos($methodName, '__') === 0) { // Methods beginning with __ are disallowed. -- cwells
					continue;
				} else if ($method->getNumberOfRequiredParameters() !== 0) {
					continue;
				} else if (!$this->app->userIsAuthorized($controller, $methodName)) {
					continue;
				}

				if ($methodName === 'index') {
					$controllers[$className][] = $classURL;
				} else {
					$controllers[$className][] = "$classURL/$methodName";
				}
			}
			sort($controllers[$className]);
		}
		ksort($controllers);

		$this->loadView('map');
		$this->view->setData(array('Controllers' => $controllers));
	}

}

?>