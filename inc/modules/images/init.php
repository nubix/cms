<?php
/**
* Author : Jan Germann
* Datum : 26.04.2010
* Modul : images
* Beschreibung : Dieses Modul dient zum Upload und Löschen von Bilder.
*				 Gleichzeitig werden Thumbnails für die Bildervorschau im Editor generiert.
*/

global $user, $content;
if($user->rightlevel >= 2)
{
	$modulename = "images";
	$moduletitle = "Bilder";
	$content->addModule($modulename, $moduletitle);
	if($_GET['a'] == $modulename)
	{
		$content->pagetitle = $moduletitle;
		$path = dirname(__FILE__) . "/";
		require_once($path."exec.php");
	}
}