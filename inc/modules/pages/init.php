<?php
/*
* Author : Jan Germann
* Datum : 24.04.2010
* Modul : pages
* Beschreibung : Verwaltung der Seiten (Inhalt) der Website
*/

global $user, $content;
if($user->rightlevel >= 1)
{
	$modulename = "pages";
	$moduletitle = "Seiten";
	$content->addModule($modulename, $moduletitle);
	if($_GET['a'] == $modulename)
	{
		$content->pagetitle = $moduletitle;
		$path = dirname(__FILE__) . "/";
		require_once($path."exec.php");
	}
}