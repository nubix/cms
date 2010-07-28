<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : menu
* Beschreibung : L�schen von Men�punkten
*/

function delete()
{
	global $msg, $mysql;
	//Keine Men�punkte vorhande? Dann Abbrechen mit Fehler!
	if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."menu"), 0))
	{
		$msg->error("Es sind keine Men�punkte vorhanden.");
		return;
	}
	
	if(!isset($_POST['delete']) && !isset($_POST['confirm']))
		return showFormDelete();
	elseif(isset($_POST['delete']))
		return showFormConfirm();
	elseif(isset($_POST['confirm']))
	{
		foreach($_POST['id'] AS $id)
			deleteItem($id);
		$msg->success("Men�punkt wurde gel�scht");
	}	
}

/**
* Zeigt Liste der Men�punkte zur Auswahl von zu l�schenden Men�punkten
*/
function showFormDelete()
{
	global $mysql;

	$tpl = dirname(__FILE__)."/template/form.delete.choose.token.tpl";
	if(is_file($tpl))
		$token = file_get_contents($tpl);
	
	$qMenu = $mysql->query("SELECT * FROM "._PREFIX_."menu ORDER BY title");
	while($o = mysql_fetch_object($qMenu))
		$menulist .= str_replace(array("%id%", "%title%"), array($o->id, $o->title), $token);
		
	$tpl = dirname(__FILE__)."/template/form.delete.choose.tpl";
	if(is_file($tpl))
		$template = file_get_contents($tpl);
	$template = str_replace("%menulist%", $menulist, $template);
	
	return $template;
}

/**
* Zeigt eine Sicherheitsabfrage f�r das L�schen der Men�punkte
*/
function showFormConfirm()
{
	global $mysql, $msg;
	
	//Sicherstellen das auch eine Checkbox ausgew�hlt war
	if(!is_array($_POST['id']))
	{
		$msg->error("Bitte w�hlen Sie <b>mindestens einen</b> Men�punkt der gel�scht werden soll.");
		return showFormDelete();
	}
	
	$tpl = dirname(__FILE__)."/template/form.delete.confirm.token.tpl";
	if(is_file($tpl))
		$token = file_get_contents($tpl);
	
	array_walk($_POST['id'], create_function('&$i', '$i = intval($i);'));
	foreach($_POST['id'] AS $id)
	{
		$o = mysql_fetch_object($mysql->query("SELECT * FROM "._PREFIX_."menu WHERE id='".$id."'"));
		$menulist .= str_replace(array("%id%", "%title%"),array($o->id, $o->title), $token);
	}
	
	$tpl = dirname(__FILE__)."/template/form.delete.confirm.tpl";
	if(is_file($tpl))
		$template = file_get_contents($tpl);
	$template = str_replace("%menulist%", $menulist, $template);
	
	return $template;
}

/**
* L�scht die in $_POST['id'][] vorhandenen Men�punkte mit Id
* 0 bei Erfolg
* 1 bei Fehler
* Ben�tigt menuDown um die Integrit�t der Reihenfolge des Men�s zu behalten
*/
function deleteItem($id)
{
	global $msg, $log, $mysql;
	if(!$id)
		return 1;
	$id = intval($id);
	
	while(!menuDown($id));
	
	$log->add("L�sche Men�punkt", "<id>".intval($id)."</id>");

	$mysql->query("DELETE FROM "._PREFIX_."menu WHERE id='".$id."'");
	
	return 0;
}