<?php
/**
 * Author : Jan Germann
 * Datum : 26.04.2010
 * Modul : files
 * Beschreibung : Dateiendaten Editieren
 *				  in 3 Schritten: Datei auswählen, Dateidaten anzeigen und ändern
 *				  und am ende die Dateidaten Updaten
*/

function edit()
{
	global $msg, $log, $mysql;
	if(!isset($_POST['id']))
	{
		//Prüfen ob Dateien zum Editieren in der Datenbank vorhanden sind
		if(1 > mysql_result(mysql_query("SELECT count(*) FROM "._PREFIX_."files"), 0))
		{
				$msg->error("Es gibt noch keine Dateien die editiert werden können.");
				return;
		}
		
		//Selectfeld mit Dateien vorbereiten
		$q = mysql_query("SELECT * FROM "._PREFIX_."files ORDER BY name");
		while($r = mysql_fetch_object($q))
		{
			$data .= "<option value=".$r->id.">".$r->name."</option>";
		}
		
		// Template ausfüllen -->
		$tpl = dirname(__FILE__)."/template/form.edit.choose.tpl";
			$tpl = file_get_contents($tpl);	
		return str_replace("%files%", $data, $tpl);
		// <-- Template ausfüllen
	}
	elseif(isset($_POST['id']) && !isset($_POST['name']))
	{
		$q = mysql_query("SELECT * FROM "._PREFIX_."files WHERE id='".intval($_POST['id'])."'");
		$o = mysql_fetch_object($q);
		
		// Template ausfüllen -->
		$tpl = dirname(__FILE__)."/template/form.edit.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);
		$template = str_replace(array("%id%", "%name%", "%description%", "%file%"),
								array($o->id, $o->name, $o->description, $o->file),
								$template);
		// <-- Template ausfüllen
		return $template;
	}
	elseif(isset($_POST['id']) && isset($_POST['name']))
	{	
		if(empty($_POST['name']))
		{
			$msg->error("Sie haben keinen Namen angegeben.");
			unset($_POST['name']);
			return edit();
		}

		//Wenn kein Fehler beim Dateiupload bekannt ist soll die Datei ersetzt werden.
		if(!$_FILES['file']['error'])
			update_file($_POST['id'], $_FILES['file']);
		
		$name = mysql_real_escape_string($_POST['name']);
		$description = mysql_real_escape_string($_POST['description']);
		$id = intval($_POST['id']);
		
		$mysql->query("UPDATE "._PREFIX_."files
					   SET name = '".$name."',
						   description = '".$description."'
					   WHERE id = '".$id."'
					   LIMIT 1");
				   
		$msg->success("Datei editiert.");
		$log->add("Dateiupdate", "<id>".$id."</id><name>".$name."</name><description>".$description."</description>");
	}
}

function update_file($id, $file)
{
	global $mysql, $msg, $log;
	
	$id = intval($id);
	if(!$id)
		return;
	
	//Dateinamen auf erlaubte Zeichen beschränken
	$safe_filename = preg_replace(array("/\s+/", "/[^-\.\w]+/"),
		 array("_", ""),
		 trim($file['name']));
	
	if(is_uploaded_file($file['tmp_name']) &&
		!@move_uploaded_file($file['tmp_name'], UPLOAD_DIR.$safe_filename))
	{
		$msg->error("Fehler beim Dateiupload.");
		return;
	}			
	$mysql->query("UPDATE "._PREFIX_."files
				   SET file = '".$safe_filename."'
				   WHERE id = '".$id."'
				   LIMIT 1");
			
	$msg->success("Upload erfolgreich.");
	$log->add("Dateiupload Update", "<id>".$id."</id><file>".$safe_filename."</file>");

}
?>