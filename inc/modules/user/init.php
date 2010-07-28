<?php
/*
* Author : Lorenz Materne
* Datum : 24.04.2010
* Modul : user
* Beschreibung : Dieses Modul verwaltet die Benutzer
*/

global $user, $content;
if($user->rightlevel == 3)
{
	$modulename = "user";
	$moduletitle = "Nutzerverwaltung";
	$content->addModule($modulename, $moduletitle);
	if($_GET['a'] == $modulename)
	{
		$content->pagetitle = $moduletitle;
		$path = dirname(__FILE__) . "/";
		require_once($path."nutzer.php");
	}
}