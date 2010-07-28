<?php
/**
* Author : Jan Germann
* Datum : 02.05.2010
* Modul : show
* Beschreibung : Zeigt Seiteninhalte aus der Datenbank an.
* TODO angehängte Dateien anzeigen
*/

$_SESSION['content'] = showHandle();

/**
* Zeigt die Startseite an wenn keine id übergeben wird
*/
function showHandle()
{
	global $mysql;
	if(empty($_GET['id']))
	{
		$qFirstPage = $mysql->query("SELECT * FROM "._PREFIX_."pages WHERE firstpage=1");
		$oFirstPage = @mysql_fetch_object($qFirstPage);
		if($oFirstPage)
			return show($oFirstPage->id);
	}
	return show($_GET['id']);
}

/**
* Ließt den Seiteninhalt der übergebenen ID aus der Datenbank aus
*/
function show($id)
{
	global $mysql, $content;
	
	$id = intval($id);
	
	$qPage = $mysql->query("SELECT * FROM "._PREFIX_."pages WHERE id='".$id."'");
	$oPage = mysql_fetch_object($qPage);
	$content->pagetitle = $oPage->title;
	$pageContent = stripslashes(base64_decode($oPage->content));
	
	if( 0 < mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."rel_pf WHERE page='".$id."'"),0))
	{
		$qFile = $mysql->query("SELECT "._PREFIX_."files.*
						FROM "._PREFIX_."files, "._PREFIX_."rel_pf
						WHERE
							"._PREFIX_."rel_pf.page = ".$id."
						AND
							"._PREFIX_."files.id = "._PREFIX_."rel_pf.file
						ORDER BY
							"._PREFIX_."files.name");

		$token = @file_get_contents(dirname(__FILE__)."/template/attachment.token.tpl");
		$tpl = @file_get_contents(dirname(__FILE__)."/template/attachment.tpl");

		while($o = mysql_fetch_object($qFile))
		{
			$data .= str_replace(array("%link%", "%title%", "%description%", "%filesize%"),
								 array(UPLOAD_DIR.$o->file, $o->name, $o->description, formatBytes(filesize(UPLOAD_DIR.$o->file))), $token);
		}
	}
	$returnVal = $pageContent.str_replace("%attachment%", $data, $tpl);
	
	
	return $returnVal;
}

/**
* Formatiert die Dateigrößen in Menschenlesbares Format
*/
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
   
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
   
    $bytes /= pow(1024, $pow); 
   
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}