# Core Web Application Site

Core Web Application Site provides a fully-functional example of how to use the [Core Web Application Libraries](https://github.com/chriswells0/cwa-lib) to create a basic website. It gives developers a baseline to launch new sites quickly by addressing many of the details that should be included in every site. It follows industry best practices to make it simpler for developers to do the same.

## Features Included

* A "starter" website with a home page, about page, and contact form.
* Responsive design that works well at multiple screen resolutions on devices of all sizes.
* Search engine (and human!) friendly URLs with a consistent format: /controller/method/parameter
* Ability to return data in a variety of content types such as HTML, JSON, or Atom/RSS by creating new view templates.
* Includes structured data to improve indexing by search engines.
* Error pages match the site design and can be easily customized for all errors or by HTTP status code.
* Site Admin section with multiple tools to facilitate common tasks:
  * Code Editor for minor ad-hoc changes
  * DB Administrator to perform queries and database backups
  * Log Viewer with filtering options
  * QA Assistant to review methods, parameters, and permissions

##### Features From [Core Web Application Libraries](https://github.com/chriswells0/cwa-lib)

* Lightweight and flexible base classes make it easy to master and extend the code.
* Uses the MVC design pattern and other web application best practices.
* Many built-in protections against common web application vulnerabilities/exploits:
  * Primarily uses prepared statements to deter SQL injection attacks.
  * Clickjacking defenses encompass multiple headers as well as JavaScript.
  * Automatic sanitization of simple variables passed to views and easy sanitization of other content to defend against cross-site scripting (XSS).
  * Cross-site request forgery (CSRF) prevention using the synchronizer token pattern for all POST requests.
  * Role-based method access is straightforward to configure and a cinch to validate with the QA Assistant.
  * User passwords are stored strongly hashed and salted.
  * Full session teardown and recreation upon login to inhibit session fixation.
  * Sessions are pinned to the user's IP and user agent string to thwart hijacking.

## Database Setup

After you clone the project, log into your local database and run the following commands to set it up. It's recommended that you replace the username and password in these commands with your own.

```sql
create database `cwa_database`;
use cwa_database;
source database.sql;
create user 'cwa_dbuser'@'localhost' identified by '9Sd.!i9$Ha,R';
grant select, insert, update, delete on cwa_database.* to 'cwa_dbuser'@'localhost';
```

Once you set up the site, instructions for next steps are available on its main page.
