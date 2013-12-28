<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : login
* Beschreibung : Ermöglicht dem Nutzer die Anmeldung
*/

global $user, $content;
/**
* Sonderfall weil das Modullogin nur angezeigt werden soll wenn der
* Nutzer schon eingeloggt ist.
* Dies ist bedingt dadurch das ein Aufruf dieses Moduls den Nutzer direkt Abmeldet!
*/
if($user->logged)
	$content->addModule("login", "Abmelden");

if($_GET['a'] == "login")
{
	$path = dirname(__FILE__) . "/";
	$content->pagetitle = "Login";
	if(!$user->logged)
		require_once($path."login.php");
	else
		require_once($path."logout.php");
}