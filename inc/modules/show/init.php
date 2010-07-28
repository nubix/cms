<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : show
* Beschreibung : Zeigt Seiteninhalte aus der Datenbank an.
*/

/**
* ACHTUNG DIESER MODUL FÜHRT SICH IMMER AUS WENN KEIN ANDERES MODUL GESETZ IST!
*/
if($_GET['a'] == "show" || empty($_GET['a']))
{
	$path = dirname(__FILE__) . "/";
	require_once($path."exec.php");
}