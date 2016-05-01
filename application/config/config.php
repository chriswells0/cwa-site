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

// App-specific constants with no defaults. -- cwells
define('LIB_PATH', '../lib/');
define('SITE_AUTHOR', 'Chris Wells'); // Used in meta tags, copyright notices, etc.
define('SITE_DOMAIN', 'example.com'); // Must be the production domain.
define('SITE_NAME', 'My CWA Site'); // Displayed at the top of every page.
define('SITE_SLOGAN', 'Welcome to my CWA Site!'); // Shown below SITE_NAME.
define('SITE_TITLE', 'My CWA Site'); // Displayed in title bar.
define('CONTACT_ANONYMOUS_FROM', '"Anonymous Visitor" <anonymous@' . SITE_DOMAIN . '>'); // For users that provide no email in the contact form.
define('CONTACT_SENDTO', '"' . SITE_AUTHOR . '" <webmaster@' . SITE_DOMAIN . '>'); // "To" address for the contact form.
/* Define these to enable reCAPTCHA on the contact form. -- cwells
define('RECAPTCHA_PRIVATE_KEY', '');
define('RECAPTCHA_PUBLIC_KEY', '');
*/


// Determine the current domain, which may or may not be the production SITE_DOMAIN. -- cwells
if (!empty($_SERVER['HTTP_HOST'])) {
	define('DOMAIN', $_SERVER['HTTP_HOST']);
} else if (!empty($_SERVER['SERVER_NAME'])) {
	define('DOMAIN', $_SERVER['SERVER_NAME']);
}
define('PROTOCOL_HOST_PORT', 'https://' . DOMAIN);


// Environment-specific constants:
if (DOMAIN === SITE_DOMAIN) { // Production.
	define('ANALYTICS_ID', '');

	// CWA Database connectivity parameters:
	define('CWA\DB\HOST', 'localhost');
	define('CWA\DB\DBNAME', 'cwa_database');
	define('CWA\DB\USERNAME', 'cwa_dbuser');
	define('CWA\DB\PASSWORD', '');
} else { // Non-production: dev/test/QA.
//	define('ANALYTICS_ID', ''); Leave undefined in non-production. -- cwells

	// CWA Database connectivity parameters:
	define('CWA\DB\HOST', 'localhost');
	define('CWA\DB\DBNAME', 'cwa_database');
	define('CWA\DB\USERNAME', 'cwa_dbuser');
	define('CWA\DB\PASSWORD', '9Sd.!i9$Ha,R');

	// Log/display all errors in non-production. -- cwells
	define('CWA\Util\LOG_LEVEL', 'ALL');
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}


// CWA lib constants that may be overridden. -- cwells
/*
define('CWA\APP_ROOT', '/');
define('CWA\LIB_PATH', '../lib/'); // Default when LIB_PATH is not defined.
define('CWA\DB\DATETIME_DB_TO_PHP', 'D., F jS, Y @ g:i A T');
define('CWA\DB\DATETIME_PHP_TO_DB', 'Y-m-d H:i:s');
define('CWA\IO\HASH_ALGORITHM', 'sha1');
define('CWA\IO\STORAGE_PATH', '../storage');

// It's recommended to set these headers in the Apache config.  If you do, then
// set them to null here to prevent them from being duplicated in PHP. -- cwells
define('CWA\MVC\VIEWS\HEADERS\CONTENT_SECURITY_POLICY', "frame-ancestors 'none'");
define('CWA\MVC\VIEWS\HEADERS\X_CONTENT_TYPE_OPTIONS', 'nosniff');
define('CWA\MVC\VIEWS\HEADERS\X_FRAME_OPTIONS', 'DENY');
define('CWA\MVC\VIEWS\HEADERS\X_XSS_PROTECTION', '1; mode=block');
*/


// PHP settings:
date_default_timezone_set('America/New_York');
ini_set('session.cookie_httponly', true);
ini_set('session.cookie_secure', true);

?>