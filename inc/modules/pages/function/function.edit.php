<?php
	function edit()
	{
		global $log, $msg, $mysql;
		
		//Keine Seiten vorhanden? Dann Abbrechen mit Fehler!
		if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."pages"), 0))
		{
			$msg->error("Es gibt noch keine Seiten.");
			return;
		}
		
		
		if(!isset($_POST['id']))
		{	
			//Shows select field with list of pages
			$q = mysql_query("SELECT * FROM "._PREFIX_."pages ORDER BY title");
			$pageToken = file_get_contents(dirname(__FILE__)."/../template/form.edit.choose_page.page.tpl");
			$pageForm = file_get_contents(dirname(__FILE__)."/../template/form.edit.choose_page.tpl");
			while($r = mysql_fetch_object($q))
				$data .= str_replace(array("%id%", "%title%"),
									 array($r->id, $r->title),
									$pageToken);
			$returnVal = str_replace("%page%", $data, $pageForm);
			return $returnVal;
		}
		elseif(!empty($_POST['id']) && empty($_POST['title']))
		{
			//Shows Form filled with content from previously selected page
			$q = mysql_query("SELECT * FROM "._PREFIX_."pages WHERE id='".intval($_POST['id'])."'");
			$q = mysql_fetch_object($q);
			
			
			//Alles auswählen was mit der Seite verknüpft ist
			$qFiles = $mysql->query("SELECT * FROM "._PREFIX_."files ORDER BY "._PREFIX_."files.name");
			$qFilesPages = $mysql->query("SELECT file FROM "._PREFIX_."rel_pf WHERE page='".intval($_POST['id'])."'");
			$assignedFile = array();
			while($oFP = mysql_fetch_object($qFilesPages))
				array_push($assignedFile, $oFP->file);
			while($oFiles = mysql_fetch_object($qFiles))
			{
				if(!(array_search($oFiles->id, $assignedFile) === FALSE))
					$selected = " selected";
				else
					$selected = "";
				$files .= "<option value=".$oFiles->id.$selected.">".$oFiles->name."</option>";
			}
			
			$qFirstPage = $mysql->query("SELECT * FROM "._PREFIX_."pages WHERE firstpage=1");
			$oFirstPage = @mysql_fetch_object($qFirstPage);
			if($oFirstPage)
				$curFirstPage = 'Die momentane Startseite ist: '.$oFirstPage->title;
			/**
			* Die Bilderliste auslesen und erstellen
			*/
			$qImages = $mysql->query("SELECT * FROM "._PREFIX_."images");
			$imageTpl = @file_get_contents(dirname(__FILE__)."/../template/editor.images.tpl");
			while($o = mysql_fetch_object($qImages))
				$images .= str_replace(array(
											"%name%",
											"%fullurl%",
											"%thumburl%"),
										array(
											$o->name,
											IMAGE_DIR.$o->file,
											IMAGE_DIR.$o->file_t),
										$imageTpl);
			
			
			// Template ausfüllen -->
			$template = @file_get_contents(dirname(__FILE__)."/../template/editor.tpl");				
			$template = str_replace(array("%cur_firstpage%",
										"%firstpage_selected%",
										"%id%",
										"%title%",
										"%content%",
										"%file%",
										"%images%"),
									array($curFirstPage,
										($q->id==$oFirstPage->id)?'checked':'',
										$q->id,
										$q->title,
										stripslashes(base64_decode($q->content)),
										$files,
										$images),
									$template);
			// <-- Template ausfüllen
			
			return $template;
		}
		elseif(!empty($_POST['id']) && !empty($_POST['title']) && !empty($_POST['content']))
		{
			//Updates the page
			$id = intval($_POST['id']);
			$title = mysql_real_escape_string($_POST['title']);
			$content = base64_encode($_POST['content']);
			$firstpage = $_POST['firstpage']?intval($_POST['firstpage']):'NULL';
			
			@mysql_query("UPDATE "._PREFIX_."pages
					  SET  title='".$title."', content='".$content."', firstpage='".$firstpage."'
					  WHERE id='".$id."'");
			
			$mysql->query("DELETE FROM "._PREFIX_."rel_pf WHERE page='".$id."'");
			if(sizeof($_POST['files']) && $_POST['files'][0])
			{
				$files = $_POST['files'];
				/**
				* Array_walk ersetz die fileIDs im array "$files" durch (pageId, fileId) für den Mysqlquery
				*/			
				array_walk($files, create_function('&$i', '$i = "('.$id.', ".intval($i).")";'));
				$mysql->query("INSERT INTO "._PREFIX_."rel_pf (page, file) VALUES ".implode(", ", $files));
			}

			//Logfile -->
			$o = mysql_fetch_object(@mysql_query("SELECT title FROM "._PREFIX_."pages WHERE id='".$id."'"));
			$log->add("Bearbeite Seite ", "<title_old>".$o->title."</title_old><title_new>".$title."</title_new><content>".$content."</content>");
			//<-- Logfile
			
			$msg->success("Die Seite <b>".$title."</b> wurde erfolgreich editiert.");
		}
		else
		{
			$msg->error("Sie haben leider keinen Inhalt auf der Seite.");
		}
	
	}
?>