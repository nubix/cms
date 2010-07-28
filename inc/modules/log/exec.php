<?php
/*
 * Author : Jan Germann
 * Datum : 25.04.2010
 * Beschreibung : Ansicht des Aktionslogfiles. Möglichkeit zum löschen des ganzen logs.
 */

	$_SESSION['content'] = '
	<div id=modulemenu>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=">Übersicht</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=clear">Alles löschen</a>
	</div>';
	

	switch($_GET['exec'])
	{
		case "clear":
				$_SESSION['content'] .= clear();
				break;
		default:
				$_SESSION['content'] .= showlog();
	}

	function clear()
	{
		global $user, $mysql, $log, $msg;
		if(!isset($_POST['confirm_pass']))
		{
			$data = '<p>Bitte geben Sie zur Bestätigung ihr Passwort ein:</p>
			<form action='.$_SERVER['REQUEST_URI'].' method=post>
			<input type=password name=confirm_pass />
			<input type=submit value=Löschen />
			</form>';
		} else {
			if(!($user->password == sha1($_POST['confirm_pass'])))
			{
				$msg->error("Ihr Passwort ist Falsch");
				//Confirmpass unset damit formular angezeigt wird.
				unset($_POST['confirm_pass']);
				$data = clear();	
			} else {
				$mysql->query("DELETE FROM "._PREFIX_."log");
				$log->add("Aktionschronik vollständig gelöscht");
				$msg->success("Aktionschronik vollständig gelöscht.");
				$data = showlog();
			}			
		}
		return $data;
	}
	
	function showlog()
	{
		global $mysql;
		
		$tpl = dirname(__FILE__)."/template/action.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);
		
		$q = $mysql->query("SELECT * FROM "._PREFIX_."log ORDER BY time DESC -- LIMIT 0, 50");
		while($o = mysql_fetch_object($q))
		{
			$object = $template;
			$user = $mysql->query("SELECT * FROM "._PREFIX_."user WHERE id='".$o->user."'");
			$user = mysql_fetch_object($user);

			$object = str_replace("%id%", $o->id, $object);			
			$object = str_replace("%time%", date("H:i:s d.m.Y", $o->time), $object);
			$object = str_replace("%name%", $user->loginname, $object);
			$object = str_replace("%action%", $o->action, $object);
			$object = str_replace("%comment%", htmlspecialchars($o->comment), $object);
			
			$data .= $object;
		}
		return $data;
	}
?>