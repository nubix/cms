<?php
/**
 * Author : Jan Germann
 * Datum : 05.05.2010
 * Modul : images
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
		if(1 > mysql_result(mysql_query("SELECT count(*) FROM "._PREFIX_."images"), 0))
		{
				$msg->error("Es gibt noch keine Bilder die editiert werden können.");
				return;
		}
		
		//Auswahl mit Bilddateien vorbereiten 
		$tpl = @file_get_contents(dirname(__FILE__)."/template/form.edit.choose.token.tpl");
		$q = mysql_query("SELECT * FROM "._PREFIX_."images ORDER BY name");
		while($r = mysql_fetch_object($q))
		{
			$data .= str_replace(array("%id%","%name%", "%fullurl%", "%thumburl%"),
								 array($r->id, $r->name, IMAGE_DIR.$r->file, IMAGE_DIR.$r->file_t)
								 ,$tpl);
		}
		
		// Template ausfüllen -->
		$tpl = @file_get_contents(dirname(__FILE__)."/template/form.edit.choose.tpl");	
		$tpl = str_replace("%files%", $data, $tpl);
		// <-- Template ausfüllen
		return $tpl;
	}
	elseif(isset($_POST['id']) && !isset($_POST['name']))
	{
		$q = mysql_query("SELECT * FROM "._PREFIX_."images WHERE id='".intval($_POST['id'])."'");
		$o = mysql_fetch_object($q);
		
		// Template ausfüllen -->
		$tpl = @file_get_contents(dirname(__FILE__)."/template/form.edit.tpl");
		$tpl = str_replace(array("%id%", "%name%", "%description%", "%file%"),
							array($o->id, $o->name, $o->description, $o->file),
							$tpl);
		// <-- Template ausfüllen
		return $tpl;
	}
	elseif(isset($_POST['id']) && isset($_POST['name']))
	{	
		$id = intval($_POST['id']);
		$name = mysql_real_escape_string($_POST['name']);
		$description = mysql_real_escape_string($_POST['description']);
		
		/**
		* Überprüfen ob überhaupt ein Name angegeben wurde und wenn,
		* dass dieser nicht schon vorhanden ist
		*/
		if(empty($_POST['name']))
		{
			$msg->error("Es wurde kein Name angegeben.");
		} else {
			$cThisName = $mysql->query("SELECT count(*) FROM "._PREFIX_."images WHERE id <>'".$id."' AND name = '".$name."'");
			if(mysql_result($cThisName, 0))
				$msg->error("Es existiert bereits ein anderes Bild mit diesem Namen.");
		}

		/**
		* Wenn überprüfen ob ein Bild hochgeladen wurde,
		* falls es der Fall ist, überprüfen ob die Dateiendung die selbe wie die der vorhandenen Datei ist.
		* Ist dies auch der Fall, Datei updaten
		*/
		if(!$_FILES['file']['error'])
		{
			/**
			* Dateiendung der neuen Datei
			*/
			$ext = pathinfo($_FILES['file']['name']);
			$ext_new = mb_convert_case($ext['extension'], MB_CASE_LOWER);
			
			/**
			* Dateiendung der Datei in der DB
			*/
			$qExtension = $mysql->query("SELECT * FROM "._PREFIX_."images WHERE id = '".$id."'");
			$oExtension = mysql_fetch_object($qExtension);
			$ext_now = end(explode(".", $oExtension->file));
			if($ext_new != $ext_now)
				$msg->error("Die Dateiendung, der neuen Bilddatei [.".$ext_new."] muss die selbe sein, wie die der vorhandenen Bilddatei [.".$ext_now."].");
			else
				update_file($_POST['id'], $_FILES['file']);
		}
		
		/**
		* Wenn ein Fehler aufgetreten ist in den vorherigen Schritt springen.
		*/
		if($msg->error)
		{
			unset($_POST['name']);
			return edit();
		}
		
		
		$mysql->query("UPDATE "._PREFIX_."images
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

	$qPicture = $mysql->query("SELECT * FROM "._PREFIX_."images WHERE id = '".$id."'");
	$oPicture = mysql_fetch_object($qPicture);
	
	if(is_uploaded_file($file['tmp_name']) &&
		!@move_uploaded_file($file['tmp_name'], IMAGE_DIR.$oPicture->file))
	{
		$msg->error("Fehler beim Dateiupload.");
		return;
	}
	
	/**
	* Thumbnail erstellen
	*/
	createThumbnail(IMAGE_DIR.$oPicture->file, IMAGE_DIR.$oPicture->file_t);
	
	
	$msg->success("Upload erfolgreich.");
	$log->add("Dateiupload Update", "<id>".$id."</id><file>".$safe_filename."</file>");

}
?>