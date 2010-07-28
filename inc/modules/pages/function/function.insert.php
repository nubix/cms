<?php
function insert($title, $content, $firstpage=0, $files=0)
{
	global $msg, $log, $mysql;
	if(!$title || !$content)
		return 1;

	$firstpage = $firstpage?intval($firstpage):'NULL';
	
	$mysql->query("INSERT INTO "._PREFIX_."pages
				  SET
					title='".mysql_real_escape_string($title)."',
					content='".base64_encode($content)."',
					firstpage='".$firstpage."'");
	if($files)
	{
		/**
		* Hole die ID der neu eingetragenen Seite als pageID
		* Array_walk ersetz die fileIDs im array "$files" durch (pageId, fileId) für den Mysqlquery
		*/
		$pageId = mysql_insert_id($mysql->id);
		array_walk($files, create_function('&$i', '$i = "('.$pageId.', ".intval($i).")";'));
		$mysql->query("INSERT INTO "._PREFIX_."rel_pf (page, file) VALUES ".implode(", ", $files), 0);
	}

	$log->add("Erstelle Seite ", "<title>".mysql_real_escape_string($title)."</title><content>".base64_encode($content)."</content>");
	$msg->success("Die Seite ".$title." wurde erfolgreich erstellt.");
}
?>