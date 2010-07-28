<?php
/*
* Author : Patrick Lampe
* Datum : 24.04.2010
* Modul : menu
* Beschreibung : Dieses Modul verwaltet das Menü
*/

global $user, $content;
if($user->rightlevel >= 1)
{
	$modulename = "menu";
	$moduletitle = "Menüeditor";
	$content->addModule($modulename, $moduletitle);
	if($_GET['a'] == $modulename)
	{
		$content->pagetitle = $moduletitle;
		$path = dirname(__FILE__) . "/";
		require_once($path."menu.php");
	}
}