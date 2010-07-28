<?php
/**
* Author : Jan Germann
* Datum : 03.05.2010
* Modul : contact
* Beschreibung : Stellt ein Kontaktformular bereit
*/
$modulename = "contact";
if($_GET['a'] == $modulename)
{
	$content->pagetitle = "Kontaktformular";
	$path = dirname(__FILE__) . "/";
	require_once($path."exec.php");
}
