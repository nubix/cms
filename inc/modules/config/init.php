<?php
/**
* Author : Jan Germann
* Datum : 03.05.2010
* Modul : config
* Beschreibung : Persönliche Einstellungen.
*/

if($user->logged)
	$content->addModule("config", "Eigene Einstellungen");

if($user->rightlevel >= 1)
{
	$modulename = "config";
	$moduletitle = "Eigene Einstellungen";
	if($_GET['a'] == $modulename)
	{
		$content->pagetitle = $moduletitle;
		$path = dirname(__FILE__) . "/";
		require_once($path."exec.php");
	}
}