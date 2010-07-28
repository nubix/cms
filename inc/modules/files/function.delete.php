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
	if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."files"), 0))
	{
		$msg->error("Sie m�ssen erst Dateien hochladen, bevor sie welche l�schen k�nnen.");
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
	$tpl = dirname(__FILE__)."/template/form.delete.choose_file.tpl";
	if(is_file($tpl))
		$form_template = file_get_contents($tpl);
	$tpl = dirname(__FILE__)."/template/form.delete.choose_file.file.tpl";
	if(is_file($tpl))
		$token_template = file_get_contents($tpl);
	
	$q = mysql_query("SELECT * FROM "._PREFIX_."files ORDER BY name");
	
	while($r = mysql_fetch_object($q))
	{	
		// Template ausf�llen -->
		$tmp = str_replace("%id%", $r->id, $token_template);
		$tmp = str_replace("%name%", $r->name, $tmp);
		$files .= $tmp;
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

	foreach($_POST['id'] as $id)
	{
		$q = $mysql->query("SELECT * FROM "._PREFIX_."files WHERE id='".intval($id)."'");
		$o = mysql_fetch_object($q);
		$data .= "<li>".$o->name."</li>";
		$hiddenid .= "<input type=hidden name=id[] value=".$o->id." />";
	}
	// Template ausf�llen -->
	$tpl = dirname(__FILE__)."/template/form.delete.confirm.tpl";
	if(is_file($tpl))
		$tmp = file_get_contents($tpl);
	$tmp = str_replace("%file%", $data, $tmp);	
	$tmp = str_replace("%id%", $hiddenid, $tmp);
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
		$q = $mysql->query("SELECT * FROM "._PREFIX_."files WHERE id='".$i."'");
		$o = mysql_fetch_object($q);
		if(is_file(UPLOAD_DIR.$o->file))
		{
			if(!unlink(UPLOAD_DIR.$o->file))
			{
				$msg->error("Fehler beim L�schen von <i>".$o->name."</i> [<i>".$o->file."</i>]");
				continue;
			}
		} else {
			$msg->note("Datei <i>".$o->name."</i> [<i>".$o->file."</i>] war nicht mehr im Dateisystem vorhanden");
		}	
			
		$mysql->query("DELETE
					   FROM "._PREFIX_."files
					   WHERE id='".$o->id."'");

		$mysql->query("DELETE
					   FROM "._PREFIX_."rel_pf
					   WHERE file='".$o->id."'");
		$log->add("L�sche Datei", "<id>".$o->id."</id><name>".$o->name."</name><file>".$o->file."</file>");
		$msg->success("Datei <i>".$o->name."</i> [<i>".$o->file."</i>] gel�scht");
	}
}
?>