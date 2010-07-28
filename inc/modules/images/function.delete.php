<?php
/**
 * Author : Jan Germann
 * Datum : 27.04.2010
 * Modul : files
 * Beschreibung : L�schen von Files
*/

function delete()
{
	global $log, $msg, $mysql;
	
	//Keine Dateien vorhanden? Dann Abbrechen mit Fehler!
	if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."images"), 0))
	{
		$msg->error("Sie m�ssen erst Bilder hochladen, bevor sie welche l�schen k�nnen.");
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
 * Datei zum L�schen ausw�hlen
*/
function delete_choose()
{
	//Zeigt eine Liste der vorhandenen Dateien zum L�schen an.
	$form_template = @file_get_contents(dirname(__FILE__)."/template/form.delete.choose_file.tpl");
	$token_template = @file_get_contents(dirname(__FILE__)."/template/form.delete.choose_file.file.tpl");
	
	$q = mysql_query("SELECT * FROM "._PREFIX_."images ORDER BY name");
	
	while($r = mysql_fetch_object($q))
	{	
		// Template ausf�llen -->
		$files .= str_replace(array("%id%", "%name%", "%thumburl%", "%fullurl%"),
							array($r->id, $r->name, IMAGE_DIR.$r->file_t, IMAGE_DIR.$r->file),
							$token_template);
		// <-- Template ausf�llen
	}

	return str_replace("%file%", $files, $form_template);
}


/**
 * Best�tigung des L�schens
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
	// Template ausf�llen -->
	$tmp = @file_get_contents(dirname(__FILE__)."/template/form.delete.confirm.tpl");
	$tmp = str_replace(array("%file%", "%id%"),
						array($data, $hiddenid),
						$tmp);	
	// <-- Template ausf�llen
	
	return $tmp;
}



/**
 * Eigentliches L�schen
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
				$msg->error("Fehler beim L�schen von <i>".$o->name."</i> [<i>".$o->file."</i>]");
				continue;
			}
		} else {
			$msg->note("Das Bild <i>".$o->name."</i> [<i>".$o->file."</i>] war nicht mehr im Dateisystem vorhanden");
		}	
			
		$mysql->query("DELETE
					   FROM "._PREFIX_."images
					   WHERE id='".$o->id."'");

		$log->add("L�sche Bild", "<id>".$o->id."</id><name>".$o->name."</name><file>".$o->file."</file>");
		$msg->success("Bild <i>".$o->name."</i> [<i>".$o->file."</i>] gel�scht");
	}
}
?>