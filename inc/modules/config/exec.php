<?php
/**
* Author : Jan Germann
* Datum : 03.05.2010
* Modul : config
* Beschreibung : Persönliche Einstellungen.
*/

$_SESSION['content'] = handle();
function handle()
{
	if(!$_POST['submit'])
		return showForm();
	else
		return editData();
}

/**
* Zeigt das Formular mit eigenen Daten an
*/
function showForm()
{
	global $mysql, $user;
	$qUser = $mysql->query("SELECT * FROM "._PREFIX_."user WHERE id='".$user->id."'");
	$o = mysql_fetch_object($qUser);
	
	$tpl = @file_get_contents(dirname(__FILE__)."/template/form.edit.tpl");
	$tpl = str_replace(array(
							//"%loginname%",
							"%vorname%",
							"%nachname%",
							"%email%"),
						array(
							//isset($_POST['loginname'])?$_POST['loginname']:$o->loginname,
							isset($_POST['vorname'])?$_POST['vorname']:$o->vorname,
							isset($_POST['nachname'])?$_POST['nachname']:$o->nachname,
							isset($_POST['email'])?$_POST['email']:$o->email),
						$tpl);
	return $tpl;
}

/**
* Bearbeitet die im POST-Array übergebenen Werte
* Stellt sicher, dass wenn das Passwort geändert wird der
* Nutzer nicht ausgeloggt wird.
*/
function editData()
{
	global $msg, $user;
	if($_POST['password1'] != $_POST['password2'])
	{
		$msg->error("Das Passwort und die Wiederholung stimmen nicht überein.");
		return showForm();
	}
	foreach(array("vorname","nachname","email") AS $key)
		$user->setuser($key, $_POST[$key]);
	
	if(!empty($_POST['password1']))
	{
		$user->setuser("password", sha1($_POST['password1']));
		$user->loginuser($user->id);
	}
	$msg->success("Sie haben Ihre Daten geändert.");
	return showForm();
}