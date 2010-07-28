<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : menu
* Beschreibung : Sortieren von Menüpunkten
*/

/**
* Delegiert die Aufgaben
*/
function sortMenu()
{
	global $msg, $mysql;
	//Keine Menüpunkte vorhande? Dann Abbrechen mit Fehler!
	if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."menu"), 0))
	{
		$msg->error("Es sind keine Menüpunkte vorhanden.");
		return;
	}
	
	$moved = 1;
	if($_GET['move'] == "up")
		$moved = menuUp($_GET['id']);
	if($_GET['move'] == "down")
		$moved = menuDown($_GET['id']);
	if(!$moved)
		$msg->success("Menüpunkt verschoben");
	return showSortList();
}

/**
* Zeigt die Menüpunkte an mit den Links für Hoch und Runter
*/
function showSortList()
{
	global $mysql;
		
	$tpl = dirname(__FILE__)."/template/form.sort.tpl";
	if(is_file($tpl))
		$template = file_get_contents($tpl);
		
	$tpl = dirname(__FILE__)."/template/form.sort.page.tpl";
	if(is_file($tpl))
		$item = file_get_contents($tpl);
	
	$q = $mysql->query("SELECT * FROM "._PREFIX_."menu ORDER BY `order`");
	while($o = mysql_fetch_object($q))
	{
		$data .= str_replace(array("%title%",
								"%link_down%",
								"%link_up%"),
							array($o->title,
								"?a=".$_GET['a']."&exec=".$_GET['exec']."&id=".$o->id."&move=down",
								"?a=".$_GET['a']."&exec=".$_GET['exec']."&id=".$o->id."&move=up"),
							$item);
	}
	$data = str_replace("%item%",$data, $template);
	return $data;
}

/**
* Verschieb den Menüpunkt mit der ID um einen nach Oben
* 0 bei Erfolg
* 1 bei Fehler
*/
function menuUp($id)
{
	if(!($id = intval($id)))
		return 1;
	$query = mysql_query("SELECT * FROM "._PREFIX_."menu WHERE id='".$id."'");
	$o = mysql_fetch_object($query);
	if(empty($o))
		return 1;
	if($o->order <= 1)
		return 1;

	$o = $o->order-1;	
	mysql_query("UPDATE "._PREFIX_."menu SET `order` = ".$o."+1 WHERE `order`='".$o."'");
	mysql_query("UPDATE "._PREFIX_."menu SET `order` = ".$o." WHERE id='".$id."'");
	
	return 0;	
}

/**
* Verschieb den Menüpunkt mit der ID um einen nach Untern
* 0 bei Erfolg
* 1 bei Fehler
*/
function menuDown($id)
{
	if(!($id = intval($id)))
		return 1;
		
	$o = mysql_fetch_object(mysql_query("SELECT * FROM "._PREFIX_."menu WHERE id='".$id."'"));
	$max = mysql_fetch_object(mysql_query("SELECT MAX(`order`) AS 'max' FROM "._PREFIX_."menu"));
	if(empty($o))
		return 1;
	if($o->order >= $max->max)
		return 1;

	$o = $o->order+1;	
	mysql_query("UPDATE "._PREFIX_."menu SET `order` = ".$o."-1 WHERE `order`='".$o."'");
	mysql_query("UPDATE "._PREFIX_."menu SET `order` = ".$o." WHERE id='".$id."'");
	
	return 0;
}