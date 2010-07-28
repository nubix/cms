<?php
/**
 * Author : Jan Germann
 * Datum : 27.04.2010
 * Modul : files
 * Beschreibung : Löschen von Files
*/

function delete()
{
	global $log, $msg, $mysql;
	
	//Keine Dateien vorhanden? Dann Abbrechen mit Fehler!
	if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."images"), 0))
	{
		$msg->error("Sie müssen erst Bilder hochladen, bevor sie welche löschen können.");
		return;
	}
	
	if(!isset($_POST['id']))
		return delete_choose();
	elseif(isset($_POST['id']) && !isset($_POST['sure']))
		return delete_confirm();
	elseif(isset($_POST['id']) && isset($_POST['sure']))
		return delete_do($_POST['id']);
}

/**
 * Datei zum Löschen auswählen
*/
function delete_choose()
{
	//Zeigt eine Liste der vorhandenen Dateien zum Löschen an.
	$form_template = @file_get_contents(dirname(__FILE__)."/template/form.delete.choose_file.tpl");
	$token_template = @file_get_contents(dirname(__FILE__)."/template/form.delete.choose_file.file.tpl");
	
	$q = mysql_query("SELECT * FROM "._PREFIX_."images ORDER BY name");
	
	while($r = mysql_fetch_object($q))
	{	
		// Template ausfüllen -->
		$files .= str_replace(array("%id%", "%name%", "%thumburl%", "%fullurl%"),
							array($r->id, $r->name, IMAGE_DIR.$r->file_t, IMAGE_DIR.$r->file),
							$token_template);
		// <-- Template ausfüllen
	}

	return str_replace("%file%", $files, $form_template);
}


/**
 * Bestätigung des Löschens
*/
function delete_confirm()
{
	global $msg, $mysql;	
	if(!is_array($_POST['id']))
		 return delete_choose();
	foreach($_POST['id'] as $id)
	{
		$q = $mysql->query("SELECT * FROM "._PREFIX_."images WHERE id='".intval($id)."'");
		$o = mysql_fetch_object($q);
		$data .= "<li>".$o->name."</li>";
		$hiddenid .= "<input type=hidden name=id[] value=".$o->id." />";
	}
	// Template ausfüllen -->
	$tmp = @file_get_contents(dirname(__FILE__)."/template/form.delete.confirm.tpl");
	$tmp = str_replace(array("%file%", "%id%"),
						array($data, $hiddenid),
						$tmp);	
	// <-- Template ausfüllen
	
	return $tmp;
}



/**
 * Eigentliches Löschen
*/
function delete_do($id)
{
	global $mysql, $msg, $log;
	foreach($id as $i)
	{
		$i = intval($i);
		$q = $mysql->query("SELECT * FROM "._PREFIX_."images WHERE id='".$i."'");
		$o = mysql_fetch_object($q);
		if(is_file(IMAGE_DIR.$o->file))
		{
			if(!unlink(IMAGE_DIR.$o->file) || !unlink(IMAGE_DIR.$o->file_t))
			{
				$msg->error("Fehler beim Löschen von <i>".$o->name."</i> [<i>".$o->file."</i>]");
				continue;
			}
		} else {
			$msg->note("Das Bild <i>".$o->name."</i> [<i>".$o->file."</i>] war nicht mehr im Dateisystem vorhanden");
		}	
			
		$mysql->query("DELETE
					   FROM "._PREFIX_."images
					   WHERE id='".$o->id."'");

		$log->add("Lösche Bild", "<id>".$o->id."</id><name>".$o->name."</name><file>".$o->file."</file>");
		$msg->success("Bild <i>".$o->name."</i> [<i>".$o->file."</i>] gelöscht");
	}
}
?>