<?php
/**
* Author : <authorname>
* Datum : 24.04.2010
* Modul : <modulname>
* Beschreibung : Kurze Beschreibung des Moduls und dessen wesentlicher Aufgabe/Funktion
*/

global $user, $content;
if($user->rightlevel <= 3)
{
	$modulename = "<modulname>";
	$content->addModule($modulename, "Linktitel");
	if($_GET['a'] == $modulename)
	{
		$path = dirname(__FILE__) . "/";
		require_once($path."exec.php");
	}
}