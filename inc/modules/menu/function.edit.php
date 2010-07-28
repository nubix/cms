<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : menu
* Beschreibung : Bearbeiten von einem Menüpunkt
*/

function edit()
{
	global $msg, $mysql;
	//Keine Menüpunkte vorhande? Dann Abbrechen mit Fehler!
	if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."menu"), 0))
	{
		$msg->error("Es sind keine Menüpunkte vorhanden.");
		return;
	}

	if(!isset($_POST['choose']) && !isset($_POST['edit']))
		return chooseMenu();
	elseif(isset($_POST['choose']))
		return showFormEdit();
	elseif(isset($_POST['edit']))
		return updateData();
}

/**
* Zeigt das Auswahlformular an
*/
function chooseMenu()
{
	global $mysql;

	$tpl = dirname(__FILE__)."/template/form.edit.choose.tpl";
	if(is_file($tpl))
		$template = file_get_contents($tpl);
	
	$tpl = dirname(__FILE__)."/template/form.edit.choose.token.tpl";
	if(is_file($tpl))
		$token = file_get_contents($tpl);
	
	$qMenus = $mysql->query("SELECT * FROM "._PREFIX_."menu ORDER BY `order`");
	while($o = mysql_fetch_object($qMenus))
		$item .= str_replace(array("%id%", "%title%"), array($o->id, $o->title), $token);
	
	return str_replace("%menulist%", $item, $template);
}
/**
* Zeigt das Eingabeformular an
*/
function showFormEdit()
{
	global $mysql;
	$id = intval($_POST['id']);
	$oThisItem = mysql_fetch_object(@$mysql->query("SELECT * FROM "._PREFIX_."menu WHERE id='".$id."'"));
	
	$tpl = dirname(__FILE__)."/template/form.edit.tpl";
	if(is_file($tpl))
		$template = file_get_contents($tpl);
	
	$template = str_replace(array("%id%",
								  "%title%",
								  "%tooltip%",
								  "%sel_nz%",
								  "%sel_i%",
								  "%sel_e%",
								  "%sel_c%",
								  "%pagelist%",
								  "%extern%"),
							array($id,
								  $oThisItem->title,
								  $oThisItem->tooltip,
								  (!$oThisItem->type)?'selected':'',
								  (1==$oThisItem->type)?'selected':'',
								  (2==$oThisItem->type)?'selected':'',
								  (3==$oThisItem->type)?'selected':'',
								  getPages((1==$oThisItem->type)?$oThisItem->target:0),
								  (2==$oThisItem->type)?$oThisItem->target:''),
							$template);
	return $template;
}

/**
* Aktualisiert den Menudatensatz
*/
function updateData()
{
	global $msg, $log, $mysql;
	$id = intval($_POST['id']);
	
	if(empty($_POST['title']))
	{
		$msg->error("Bitte füllen Sie die benötigten Felder aus.");
		return showFormEdit();
	}
	
	switch($_POST['type'])
	{
		case 1: $target=mysql_real_escape_string($_POST['intern']);
				break;
		case 2: $target=mysql_real_escape_string($_POST['extern']);
				break;
		default:$target=0;
	}
	
	$o = mysql_fetch_object($mysql->query("SELECT * FROM  "._PREFIX_."menu WHERE id='".$id."'"));
	$mysql->query("UPDATE "._PREFIX_."menu
					SET
						title='".mysql_real_escape_string($_POST['title'])."',
						tooltip='".mysql_real_escape_string($_POST['tooltip'])."',
						type='".mysql_real_escape_string($_POST['type'])."',
						target='".$target."'
					WHERE
						id='".$id."'");

	$log->add("Bearbeite Menüpunkt" ,
				"<title_old>".$o->title."</title_old>
				<title_new>".$_POST['title']."</title_new>
				<tooltip_old>".$o->tooltip."</tooltip_old>
				<tooltip_new>".$_POST['tooltip']."</tooltip_new>
				<type_old>".$o->type."</type_old>
				<type_new>".$_POST['type']."</type_new>
				<target_old>".$o->target."</target_old>
				<target_new>".$_POST['pages']."</target_new>");
	$msg->success("Menüpunkt bearbeitet");
}