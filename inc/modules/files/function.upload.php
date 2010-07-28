<?php
/**
 * Author : Jan Germann
 * Datum : 26.04.2010
 * Modul : files
 * Beschreibung : Dateien Hochladen in UPLOAD_DIR
*/

function upload()
{
	global $msg, $mysql, $log;
	if(!isset($_POST['submit']))
	{
		$tpl = dirname(__FILE__)."/template/upload.form.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);
			$template = str_replace("%name%", $_POST['name'], $template);
			$template = str_replace("%description%", $_POST['description'], $template);
		return $data = $template;
	}
	else
	{
		if($_FILES['file']['error'] == 4 )
			$msg->error("Es wurde keine Datei hochgeladen.");
		if(empty($_POST['name']))
			$msg->error("Es wurde kein Name angegeben.");
			
		if($msg->error)
		{
			unset($_POST['submit']);
			return upload();
		}
		else
		{
			$safe_filename = preg_replace(array("/\s+/", "/[^-\.\w]+/"),
				 array("_", ""),
				 trim($_FILES['file']['name']));
			
			if(is_uploaded_file($_FILES['file']['tmp_name']) &&
				!@move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_DIR.$safe_filename))
			{
				$msg->error("Fehler beim Dateiupload.");
				unset($_POST['submit']);
				return upload();
			}			
			$mysql->insert("files", array(
				NULL,
				mysql_real_escape_string($_POST['name']),
				mysql_real_escape_string($_POST['description']),
				$safe_filename));
				
			$msg->success("Upload erfolgreich.");
			$log->add("Dateiupload", "<file>".$safe_filename."</file><name>".mysql_real_escape_string($_POST['name'])."</name><description>".mysql_real_escape_string($_POST['description'])."</description>");
		}
	}
}
?>