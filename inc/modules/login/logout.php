<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : login
* Beschreibung : Ermöglicht dem Nutzer die Abmeldung
*/


/**
* Meldet den Nutzer an und zeigt eine Erfolgsmeldung
* Es sei denn login_post ist gesetzt was bedeutet der Nutzer
* Hat sich gerade eben Angemeldet. Dann zeige eine erfolgsmeldung
*/
global $user, $msg, $content;

if(!$user->login_post)
{
	$user->logout();
	$msg->success("Sie haben sich abgemeldet.");
	$content->delModule("login");
}
else
{
	$msg->success("Sie haben sich angemeldet.");
	$content->delModule("login");
}
