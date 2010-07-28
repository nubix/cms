<?php
/**
* Author : Patrick Lampe
* Datum : 24.04.2010
* Beschreibung : 	Diese Datei stellt ein Formular zur Verfügung in dem der Benutzer entscheiden kann,
*					ob er die Menüstruktur Erstellen/Löschen/Bearbeiten will
*/

	$path = dirname(__FILE__) . "/";
	require_once($path."function.create.php");
	require_once($path."function.edit.php");
	require_once($path."function.delete.php");
	require_once($path."function.sort.php");

	$_SESSION['content'] = '	
	<div id=modulemenu><a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=create">Erstellen</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=edit">Editieren</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=delete">Löschen</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=sort">Sortieren</a></div>';

	switch($_GET['exec'])
	{
		case "create":
				$_SESSION['content'] .= create();
				break;
		case "edit":
				$_SESSION['content'] .= edit();
				break;
		case "delete":
				$_SESSION['content'] .= delete();
				break;
		case "sort":
				$_SESSION['content'] .= sortMenu();
				break;
	}
?>