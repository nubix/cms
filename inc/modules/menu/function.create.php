<?php
/**
* Author : Jan Germann
* Datum : 01.05.2010
* Modul : menu
* Beschreibung : Erstellen von einem Menüpunkt
*/

function create()
{
	if(!$_POST['submit'])
		return showFormCreate();
	elseif($_POST['submit'])
		return insertData();
}

/**
* Zeigt das Eingabeformular an
*/
function showFormCreate()
{
	$editor_tpl = dirname(__FILE__)."/template/form.create.tpl";
	if(is_file($editor_tpl))
		$template = file_get_contents($editor_tpl);
	$template = str_replace(array("%title%",
								  "%tooltip%",
								  "%sel_nz%",
								  "%sel_i%",
								  "%sel_e%",
								  "%sel_c%",
								  "%pagelist%",
								  "%extern%"),
							array($_POST['title'],
								  $_POST['tooltip'],
								  (!$_POST['type'])?'selected':'',
								  (1==$_POST['type'])?'selected':'',
								  (2==$_POST['type'])?'selected':'',
								  (3==$_POST['type'])?'selected':'',
								  getPages($_POST['intern']),
								  $_POST['extern']),
							$template);
	return $template;
}

/**
* Fügt daten in Datenbank ein. Übergabe mit dem POST Array
*/
function insertData()
{
	global $msg, $log, $mysql;
	
	if(empty($_POST['title']))
	{
		$msg->error("Bitte füllen Sie alle Felder aus");
		unset($_POST['submit']);
		return showFormCreate();
	}
	
	switch($_POST['type'])
	{
		case 1: $target=$_POST['intern'];
				break;
		case 2: $target=$_POST['extern'];
				break;
		default:$target=0;
	}

	$menuCount = mysql_fetch_object($mysql->query("SELECT MAX(`order`) AS i FROM "._PREFIX_."menu"));
	$mysql->insert("menu", array('',
								$_POST['title'],
								$_POST['tooltip'],
								$_POST['type'],
								$target,
								$menuCount->i + 1));
	$log->add("Erstelle Menüpunkt",	"<title>".mysql_real_escape_string($_POST['title'])."</title><tooltip>".mysql_real_escape_string($_POST['tooltip'])."</tooltip><type>".mysql_real_escape_string($_POST['type'])."</type><target>".mysql_real_escape_string($target)."</target>");
	$msg->success("Menüpunkt \"".$_POST['title']."\"</i> erstellt");
}

/**
* Gibt eine mit form.create.pagelist.tpl formatierte liste seiten zurück
* $id => vorausgewählte seitenid
*/
function getPages($id=0)
{
	global $mysql;
	$id = intval($id);
	$editor_tpl = dirname(__FILE__)."/template/form.create.pagelist.tpl";
	if(is_file($editor_tpl))
		$template = file_get_contents($editor_tpl);
	$qPages = $mysql->query("SELECT * FROM "._PREFIX_."pages ORDER BY title");
	
	while($oPages = mysql_fetch_object($qPages))
		$pagelist .= str_replace(array("%id%", "%selected%", "%title%"),
								 array($oPages->id, ($id==$oPages->id)?'selected':'', $oPages->title),
								 $template);

	return $pagelist;
}