<?php
define("INIT", 1);

/**
* Wenn das Installscript noch vorhanden ist, ausführung Stoppen
*/
if(is_file("install.php"))
	die("Die 'install.php' ist noch vorhanden. Löschen Sie diese bevor sie weiter machen.");

	
/**
* Auf magic_quotes testen und Rückgängigmachen wenn es an ist.
*/
if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value) {
		return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	}
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}	
	
/**
* Konstanten und Config Laden
*/
require_once("config.inc.php");

/**
* Elementare Dateien Laden 
*/
require_once(INCLUDE_DIR."class/class.mysql.php");
require_once(INCLUDE_DIR."class/class.cookie.php");
require_once(INCLUDE_DIR."class/class.users.php");
require_once(INCLUDE_DIR."class/class.content.php");
require_once(INCLUDE_DIR."class/class.session.php");
require_once(INCLUDE_DIR."class/class.log.php");
require_once(INCLUDE_DIR."class/class.msg.php");

/**
* Klassen initialisieren
* Reihenfolge beachten!!!
*/
$mysql = new classMySQL();
$cookie = new classCookie();
$session = new classSession();
$user = new classUser();
$log = new classLog();

/**
* Klassen für Useroutput
*/ 
$msg = new classMessage();
$content = new classContent();

/**
* Skripte Laden
*/
require_once(INCLUDE_DIR."modules.load.php");

?>
