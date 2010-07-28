<?php
/**
* Author : Jan Germann
* Datum : 26.04.2010
* Modul : files
* Beschreibung : Dieses Modul dient zum Upload und Löschen von Files
*/
 
 	$path = dirname(__FILE__) . "/";
	require_once($path."function.upload.php");
	require_once($path."function.edit.php");
	require_once($path."function.delete.php");
	require_once($path."function.imagefunctions.php");
 
	$_SESSION['content'] = '
	<div id=modulemenu>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=upload">Hochladen</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=edit">Editieren</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=delete">Löschen</a></div>';
	
	switch($_GET['exec'])
	{
		case "upload":
				$_SESSION['content'] .= upload();
				break;
		case "edit":
				$_SESSION['content'] .= edit();
				break;
		case "delete":
				$_SESSION['content'] .= delete();
				break;
	}
?>