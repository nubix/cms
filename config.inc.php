<?php
/**
* Systemeinstellungen
*/
define("INCLUDE_DIR",	"inc/");
define("MODULE_DIR",	"modules/");
define("UPLOAD_DIR",	"uploads/");
define("IMAGE_DIR",	"images/");
define("TEMPLATE", 	"standard");

/**
* Cookieeinstellungen
*/
define("COOKIE_UID",	"uid");
define("COOKIE_PASS",	"cookiepass");
$config['cookie']['expire']	= 15*60;

/**
* Kontaktangaben
*/
define("EMAIL_SUBJECT",	"");
define("EMAIL_ADMIN",	"");
define("EMAIL_CONTACT", "");
define("_TIME_",		time());

/**
* Datenbank
*/
define("_PREFIX_",		"cms_");
$config['sql']['host']	= "localhost";
$config['sql']['user']	= "root";
$config['sql']['pass']	= "";
$config['sql']['db'] 	= "";