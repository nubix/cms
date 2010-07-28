<?php
function delete()
{
	global $log, $msg, $mysql;
	
	//Keine Seiten vorhanden? Dann Abbrechen mit Fehler!
	if(!mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."pages"), 0))
	{
		$msg->error("Es gibt noch keine Seiten.");
		return;
	}
	
	
	if(!isset($_POST['id']))
		return delete_choose();
	elseif(isset($_POST['id']) && !isset($_POST['sure']))
		return delete_confirm();
	elseif(isset($_POST['id']) && isset($_POST['sure']))
		return delete_do($_POST['id'], $_POST['menuid']);
}

function delete_choose()
{
	//Zeigt eine Liste der vorhandenen Seiten zum Löschen an.
	$tpl = dirname(__FILE__)."/../template/form.delete.choose_page.tpl";
	if(is_file($tpl))
		$form_template = file_get_contents($tpl);
	$tpl = dirname(__FILE__)."/../template/form.delete.choose_page.page.tpl";
	if(is_file($tpl))
		$token_template = file_get_contents($tpl);
	
	$q = mysql_query("SELECT * FROM "._PREFIX_."pages ORDER BY title");
	while($r = mysql_fetch_object($q))
	{	
		$tmp = str_replace("%id%", $r->id, $token_template);
		$tmp = str_replace("%title%", $r->title, $tmp);
		$pages .= $tmp;
	}

	return str_replace("%page%", $pages, $form_template);
}

function delete_confirm()
{
	//Soll überprüfen ob Menüpunkte auf Seiten zeigen die gelöscht werden sollen
	//und stellt sicher, dass der Nutzer wirklich löschen will.
	
	global $msg;
	foreach($_POST['id'] as $id)
	{
		$hiddenid .= "<input type=hidden name=id[] value=".$id." />";
		unset($inner);
		$query = mysql_fetch_array(mysql_query("SELECT count(*) FROM "._PREFIX_."menu WHERE type=1 AND target='".intval($id)."'"));
		if($query[0])
		{
			$pagename = mysql_fetch_object(mysql_query("SELECT title FROM "._PREFIX_."pages WHERE id='".intval($id)."'"));
			$r = mysql_query("SELECT * FROM "._PREFIX_."menu WHERE type='1' AND target='".intval($id)."'");
			while($o = mysql_fetch_object($r))
			{
				$inner .= "
						<li>
							<label for=".$o->id.">	
							<input type=checkbox name=menuid[] id=".$o->id." value=".$o->id." checked />
							".$o->title."
							</label>
						</li>
					";
			}
				
			$data .= "<li>".$pagename->title."<span><ol>".$inner."</ol></span></li>";
		}
	}
		
	if($data)
		$msg->note("Es zeigen noch Menüpunkte auf Seiten die Sie löschen möchten. Wählen Sie die entsprechenden Menüpunkte aus die mitgelöscht werden sollen.");
		
	// Template ausfüllen -->
	$tpl = dirname(__FILE__)."/../template/form.delete.confirm.tpl";
	if(is_file($tpl))
		$tmp = file_get_contents($tpl);
	$tmp = str_replace("%pagelist%", $data, $tmp);	
	$tmp = str_replace("%id%", $hiddenid, $tmp);
	// <-- Template ausfüllen
	
	return $tmp;
}

function delete_do($page_ids, $menu_ids=0)
{
	//Löscht nach der vorherigen Bestätigung alle Seiten
	global $msg, $log, $mysql;

	foreach($page_ids as $id)
	{
		//Logfile -->
		$o = mysql_fetch_object(@mysql_query("SELECT title FROM "._PREFIX_."pages WHERE id='".intval($id)."'"));
		$log->add("Lösche Seite ", "<title>".$o->title."</title>");
		//<-- Logfile 
			
		$mysql->query("DELETE FROM "._PREFIX_."pages WHERE id='".intval($id)."' LIMIT 1;");
		$mysql->query("DELETE FROM "._PREFIX_."rel_pf WHERE page='".intval($id)."'");
	}
	if($menu_ids)
		foreach($menu_ids as $menuid)
		{
			//Logfile -->
			$o = mysql_fetch_object(@mysql_query("SELECT title FROM "._PREFIX_."menu WHERE id='".intval($menuid)."'"));
			$log->add("Lösche Menüpunkt automatisch", "<title>".$o->title."</title>");
			//<-- Logfile
 			while(!menuDown(intval($menuid)));
			@mysql_query("DELETE FROM "._PREFIX_."menu WHERE id='".intval($menuid)."' LIMIT 1;");
		}
	$msg->success("Löschen erfolgreich.");
}

function menuDown($id)
{
	if(!($id = intval($id)))
		return 1;
		
	$o = mysql_fetch_object(mysql_query("SELECT * FROM "._PREFIX_."menu WHERE id='".$id."'"));
	$max = mysql_fetch_object(mysql_query("SELECT MAX(`order`) AS 'max' FROM "._PREFIX_."menu"));
	if(empty($o))
		return 1;
	if($o->order >= $max->max)
		return 1;

	$o = $o->order+1;	
	mysql_query("UPDATE "._PREFIX_."menu SET `order` = ".$o."-1 WHERE `order`='".$o."'");
	mysql_query("UPDATE "._PREFIX_."menu SET `order` = ".$o." WHERE id='".$id."'");
	
	return 0;
}
?>