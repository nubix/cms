<?php
/**
* Author : Jan Germann
* Datum : 25.04.2010
* Modul : log 
* Beschreibung : Dieses Modul erlaubt die betrachtung des Logs
*/

global $user, $content;
if($user->rightlevel == 3)
{
	$modulename = "log";
	$moduletitle = "Aktionschronik";
	$content->addModule($modulename, $moduletitle);
	if($_GET['a'] == $modulename)
	{
		$content->pagetitle = $moduletitle;
		$path = dirname(__FILE__) . "/";
		require_once($path."exec.php");
	}
}