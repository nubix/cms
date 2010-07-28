<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : login
* Beschreibung : Ermöglicht dem Nutzer die Anmeldung
*/

$_SESSION['content'] = showLoginForm();

/**
* Zeigt ein Loginformular
*/
function showLoginForm()
{
	global $user, $msg;
	
	if($user->login_post)
		$msg->error("Bitte geben Sie die richtige Kombination aus Benutzername und Passwort ein.");
	
	$tpl = @file_get_contents(dirname(__FILE__)."/template/form.login.tpl");
	$tpl = str_replace("%loginname%", $_POST['loginname'], $tpl);
	return $tpl;
}