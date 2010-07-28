<?php
/**
 * Zeigt ein Formular zum erstellen einer Seite an
*/
function create()
{
	global $msg, $mysql;
	
	if(isset($_POST['content']))
	{
		if(!insert($_POST['title'], $_POST['content'], $_POST['firstpage'], $_POST['files']))
			return;
		$msg->error("Bitte füllen Sie alle Felder aus.");
	}
	
	$q = $mysql->query("SELECT * FROM "._PREFIX_."files");
	while($o = mysql_fetch_object($q))
		$files .= "<option value=".$o->id.">".$o->name."</option>";
	
	$qFirstPage = $mysql->query("SELECT * FROM "._PREFIX_."pages WHERE firstpage=1");
	if($o = @mysql_fetch_object($qFirstPage))
		$curFirstPage = 'Die momentane Startseite ist: '.$o->title;
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
	$template = str_replace(array(
									"%cur_firstpage%",
									"%title%",
									"%content%",
									"%file%",
									"%images%"),
							array(
									$curFirstPage,
									$_POST['title'],
									$_POST['content'],
									$files,
									$images),
							$template);
	// <-- Template ausfüllen
	
	return $template;
}
?>