<?php
/**
* Author : Jan Germann
* Datum : 26.04.2010
* Modul : images
* Beschreibung : Bilder Hochladen in IMAGE_DIR
*/

function upload()
{
	global $msg, $mysql, $log, $user;
	if(!isset($_POST['submit']))
	{
		$data = @file_get_contents(dirname(__FILE__)."/template/upload.form.tpl");
		$data = str_replace(array("%name%","%description%"),
							array($_POST['name'], $_POST['description']),
							$data);
		return $data;
	}
	else
	{
		/**
		* Wenn überprüfen ob ein Bild hochgeladen wurde,
		* falls es der Fall ist, überprüfen ob die Dateiendung
		* Erlaubt ist.
		* jpg, jpeg, gif
		*/
		
		if($_FILES['file']['error'] == 4 )
		{
			$msg->error("Es wurde kein Bild hochgeladen.");
		} else {
			$ext = pathinfo($_FILES['file']['name']);
			$ext = mb_convert_case($ext['extension'], MB_CASE_LOWER);
			if(array_search($ext, array('jpg', 'jpeg', 'gif')) === FALSE)
				$msg->error("Die Dateiendung ".$ext." deutet auf ein nicht webtaugliches Format hin. Die Datei wurde abgelehnt.");
		}
		/**
		* Überprüfen ob überhaupt ein Name angegeben wurde und wenn,
		* dass dieser nicht schon vorhanden ist
		*/
		if(empty($_POST['name']))
		{
			$msg->error("Es wurde kein Name angegeben.");
		} else {
			$cThisName = $mysql->query("SELECT count(*) FROM "._PREFIX_."images WHERE name = '".mysql_real_escape_string($_POST['name'])."'");
			if(mysql_result($cThisName, 0))
				$msg->error("Es existiert bereits ein Bild mit diesem Namen.");
		}
		
		
		if($msg->error)
		{
			unset($_POST['submit']);
			return upload();
		}
		else
		{
			/**
			* Zufälligen Dateinamen generieren
			* Der Dateiname ist nicht wichtig, deshalb wird er "zufällig" generiert
			*/
			$extension = pathinfo($_FILES['file']['name']);
			$safe_filename_raw = md5($_FILES['file']['name'].$user->password._TIME_);
			$safe_filename = $safe_filename_raw .".". mb_convert_case($extension['extension'], MB_CASE_LOWER);
			$safe_filename_thumb = $safe_filename_raw ."_t.jpg";
			
			if(is_uploaded_file($_FILES['file']['tmp_name']) &&
				!@move_uploaded_file($_FILES['file']['tmp_name'], IMAGE_DIR.$safe_filename))
			{
				$msg->error("Fehler beim Bildupload.");
				unset($_POST['submit']);
				return upload();
			}
			
			/**
			* Thumbnail erstellen
			*/
			createThumbnail(IMAGE_DIR.$safe_filename, IMAGE_DIR.$safe_filename_thumb);
			
			$mysql->insert("images", array(
				NULL,
				mysql_real_escape_string($_POST['name']),
				mysql_real_escape_string($_POST['description']),
				$safe_filename,
				$safe_filename_thumb));
				
			$msg->success("Upload erfolgreich.");
			$log->add("Bildupload", "<file>".$safe_filename."</file><name>".mysql_real_escape_string($_POST['name'])."</name><description>".mysql_real_escape_string($_POST['description'])."</description>");
		}
	}
}
?>