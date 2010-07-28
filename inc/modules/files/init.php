<?php
/**
* Author : Jan Germann
* Datum : 26.04.2010
* Modul : files
* Beschreibung : Dieses Modul dient zum Upload und Löschen von Files
*/

global $user, $content;
if($user->rightlevel >= 2)
{
	$modulename = "files";
	$moduletitle = "Dateien";
	$content->addModule($modulename, $moduletitle);
	if($_GET['a'] == $modulename)
	{
		$content->pagetitle = $moduletitle;
		$path = dirname(__FILE__) . "/";
		require_once($path."exec.php");
	}
}