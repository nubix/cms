<?php
/**
* Author : Jan Germann
* Datum : 24.04.2010
* Modul : pages
* Beschreibung : Verwaltung der Seiten (Inhalt) der Website
*/
 
	$path = dirname(__FILE__) . "/function/";
	require_once($path."function.create.php");
	require_once($path."function.edit.php");
	require_once($path."function.delete.php");
	require_once($path."function.insert.php");
 
	$_SESSION['content'] = '
	<div id=modulemenu><a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=create">Erstellen</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=edit">Editieren</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=delete">Löschen</a></div>';
	
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
	}
	
	/**
	* Meldung wenn es noch keine Startseite gibt!
	* Prüfung Nach dem ausführen der Funktionen damit die Meldung nicht angezeigt wird
	* Wenn die Seite gerade gesetzt wurde.
	*/
	global $mysql, $msg;
	$qFirstPage = $mysql->query("SELECT * FROM "._PREFIX_."pages WHERE firstpage=1");
	if(!@mysql_fetch_object($qFirstPage))
		$msg->note("Sie haben noch keine Startseite definiert. Wenn jemand Ihre Seite besucht, sieht er nur das Menü.");
	
	
?>