<?php
/**
* Author : Jan Germann
* Datum : 03.05.2010
* Modul : contact
* Beschreibung : Stellt ein Kontaktformular bereit
*/
$_SESSION['content'] = contact();

/**
* Wrapperfunktion
*/
function contact()
{
	if(!$_POST['submit'])
		return showContactForm();
	else
		return validateContactForm();
}

/**
* Zeigt das Formular und erstellt ein simples Captcha
*/
function showContactForm()
{
	global $content;
	// Captcha -->
	$randChar = mt_rand(0, 9);
	if(!isset($_SESSION['captcha_data']))
		$_SESSION['captcha_data'] = mt_rand(1, 3);
	
	while($_SESSION['captcha_data'] != $count)
	{
		$count = 0;
		$randString = array();
		for($i = 0; $i<5; ++$i)
		{
			$x = mt_rand(0, 9);
			if($x == $randChar)
				++$count;
			array_push($randString, $x);
		}
		$randString = implode(" - ", $randString);
	}
	switch($randChar)
	{
		case '0': $randChar='Nullen';
			break;
		case '1': $randChar='Einsen';
			break;
		case '2': $randChar='Zweien';
			break;
		case '3': $randChar='Dreien';
			break;
		case '4': $randChar='Vieren';
			break;
		case '5': $randChar='Fünfen';
			break;
		case '6': $randChar='Sechsen';
			break;
		case '7': $randChar='Siebenen';
			break;
		case '8': $randChar='Achten';
			break;
		case '9': $randChar='Neunen';
			break;
	}
	// <-- Captcha
	$tpl = @file_get_contents(dirname(__FILE__)."/template/form.contact.tpl");
	$tpl = str_replace(array("%name%", "%phone%", "%mail%", "%message%", "%countchar%", "%randomstring%"),
						array($_POST['name'],$_POST['phone'],$_POST['mail'],$_POST['message'], $randChar, $randString),
						$tpl);
	return $tpl;
}

/**
* Wertet das Formular aus, und das Captcha
*/
function validateContactForm()
{
	global $msg;
	if($_POST['cap'] != $_SESSION['captcha_data'])
		$msg->error("Bitte tragen Sie unten die gesuchte Zahl zwischen 1 und 5 ein.");
	if(empty($_POST['name']))
		$msg->note("Bitte tragen Sie Ihren Namen ein.");
	if(empty($_POST['phone']) && empty($_POST['mail']))
		$msg->note("Wir würden Sie auch gerne Kontaktieren. Geben Sie daher Bitte Ihre Telefonnummer oder Ihre Emailadresse an.");
	if(empty($_POST['message']))
		$msg->note("Bitte schreiben Sie uns eine Nachricht.");
	
	unset($_SESSION['captcha_data']);
	
	if($msg->note || $msg->error)
	{
		unset($_POST['submit']);
		return contact();
	}
	
	
	if(sendMail())
		$msg->success("Vielen Dank für Ihre Nachricht. Wir werden uns schnellst möglich darum kümmern.");
	else
	{
		$msg->error("Leider gab es einen Fehler beim Abschicken des Formulars.");
		return showContactForm();
	}
	
}

/**
* Verschickt eine Email an die Adresse in EMAIL_CONTACT wenn dieser gesetzt ist.
* Fehler ist auch wenn zuwenigt Zeit seit dem letzten Versand vergangen ist.
* 1 bei Fehler
* 0 bei Erfolg
*/
function sendMail()
{
	global $msg;
	$mailDelay = 60; //in Sekunden
	if($_SESSION['lastMailTime'] + $mailDelay > _TIME_)
	{
		$msg->error("Bitte warten Sie mindestens ".$mailDelay." Sekunden bevor sie nochmal eine Nachricht verschicken.");
		return 0;
	}
	$_SESSION['lastMailTime'] = _TIME_;

		$message = 
"Diese Nachricht wurde durch das Kontaktformular des modern-IT CMS versandt.
Der Absender hat folgende Daten angegeben:

Name: ".$_POST['name']."
Telefonnummer: ".$_POST['phone']."
Emailadresse: ".$_POST['mail']."
Nachricht:
".$_POST['message'];
		$success = @mail(EMAIL_CONTACT, EMAIL_SUBJECT, str_replace("\n.", "\n..", $message), 'From: '.EMAIL_CONTACT);
		
	return $success;
}