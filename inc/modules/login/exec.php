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
	$tpl = @file_get_contents(dirname(__FILE__)."/template/form.login.tpl");
	return $tpl;
}