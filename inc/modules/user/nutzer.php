<?php
/*
* Autor : Jan Germann
* Datum : 24.04.2010
* Modul : user
* Beschreibung : Die Nutzerverwaltung
*/
 
	$_SESSION['content'] = '
	<div id=modulemenu><a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=create">Erstellen</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=edit">Editieren</a>
	<a href="'.$_SERVER['PHP_SELF'].'?a='.$_GET['a'].'&exec=delete">Löschen</a></div>';


	switch($_GET['exec'])
	{
		case "create":
				$_SESSION['content'] .= insert();
				break;
		case "edit":
				$_SESSION['content'] .= edit();
				break;
		case "delete":
				$_SESSION['content'] .= delete();
				break;
	}
	
	function get_rightlevels($select=0)
	{
		$select = intval($select);
		$query = mysql_query("SELECT * FROM "._PREFIX_."rightlevel ORDER BY id");
		while($o = mysql_fetch_object($query))
		{
			if($o->id == $select)
				$data .= '<option selected value='.$o->id.'>'.$o->title.'</option>';
			else
				$data .= '<option value='.$o->id.'>'.$o->title.'</option>';
			$desc .= '<span style="width: 300px; display:none; position: relative; background: #FFFF99; padding: 3px; border: groove 3px #FF9900; " id='.$o->id.'>'.$o->description.'</span>';
		}
		return array($data,$desc);
	}

	function get_modes($select=0)
	{
		$select = intval($select);
		if($select)
			$first = "selected";
		else
			$second = "selected";
		$data = "<option value=1 $first>Aktiv</option>
				 <option value=0 $second>Inaktiv</option>";
		return $data;
	}
	
	function insert()
	{
		global $mysql, $log, $msg;
		
		//Template ausfüllen -->
		$tpl = dirname(__FILE__)."/template/user.new.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);	
		$rechte = get_rightlevels($_REQUEST['rightlevel']);				
		$template = str_replace("%loginname%", $_REQUEST['loginname'], $template);
		$template = str_replace("%vorname%", $_REQUEST['vorname'], $template);
		$template = str_replace("%nachname%", $_REQUEST['nachname'], $template);
		$template = str_replace("%password%", $_REQUEST['password'], $template);
		$template = str_replace("%email%", $_REQUEST['email'], $template);
		$template = str_replace("%rightlevel%", $rechte[0], $template);
		$template = str_replace("%right_description%", $rechte[1], $template);
		$template = str_replace("%mode%", get_modes($_REQUEST['mode']), $template);
		// <-- Template ausfüllen
	
		if(!isset($_POST['insert']))
		{
			$_SESSION['content'] .= $template;
		} else {
			//Notwendige Felder
			if(empty($_POST["loginname"]))
				$msg->error("Bitte füllen n das Feld Nutzername auszufüllen");
			if(empty($_POST['password']))
				$msg->error("Sie haben vergessen das Feld Passwort auszufüllen");
			if(!is_numeric($_POST['rightlevel']))
				$msg->error("Sie haben vergessen das Feld Rechtelevel auszufüllen");
			if(!isset($_POST['mode']))
				$msg->error("Sie haben vergessen das Feld Aktiv auszufüllen");
			//Optionale Felder, Hinweisausgeben wenn nicht benutzt
			if(empty($_POST['vorname']) || empty($_POST['nachname']))
				$msg->note("Wenn Sie dem Benutzer seinen vollen Namen zuordnen, erleichter es Ihnen später die Administration.");
			if(!$_POST['mode'])
				$msg->note("Beachten Sie bitte, dass der Benutzer auf Inaktiv geschaltet ist.");
				
			if(!$msg->error)
			{
				$count = implode(mysql_fetch_array(mysql_query("SELECT count(*) FROM "._PREFIX_."user WHERE loginname = '".mysql_real_escape_string($_POST['loginname'])."'")));
				if($count == 0)
				{
					$mysql->insert("user",array('',
											  $_POST['vorname'],
											  $_POST['nachname'],
											  $_POST['loginname'],
											  sha1($_POST['password']),
											  $_POST['email'],
											  intval($_POST['rightlevel']),
											  intval($_POST['mode'])));
					$q = mysql_query("SELECT id FROM "._PREFIX_."user WHERE loginname = '".mysql_real_escape_string($_POST['loginname'])."'");
					$o = mysql_fetch_object($q);
					$log->add("Erstelle Nutzer", "<id>".$o->id."</id><loginname>".mysql_real_escape_string($_POST['loginname'])."</loginname><rightlevel>".intval($_POST['rightlevel'])."</rightlevel><mode>".intval($_POST['mode'])."</mode>");
					$msg->success("Der Benutzer mit dem Namen <b>".$_POST['loginname']."</b> wurde angelegt.");
				} else {
					$msg->error("Der Benutzer mit dem Namen <b>".$_POST['loginname']."</b> existiert leider bereits.");
					unset($_POST['insert']);
					$data = insert();
				}
			} else {			
				$data = $error.$template;
			}
		}
		return $data;
	}	

	function edit()
	{
		global $mysql, $log, $msg, $user;	
		if (!isset($_POST['choose']) && !isset($_POST['edit']))
		{		
			$q = $mysql->query("SELECT * FROM "._PREFIX_."user ORDER BY loginname");
			while($o = mysql_fetch_object($q))
			{
				if($o->vorname || $o->nachname)
					$option .= "<option value=".$o->id.">".$o->loginname." - ".$o->vorname." ".$o->nachname."</option>";
				else
					$option .= "<option value=".$o->id.">".$o->loginname."</option>";
			}
		
			//Template ausfüllen -->
			$tpl = dirname(__FILE__)."/template/user.choose.tpl";
			if(is_file($tpl))
				$template = file_get_contents($tpl);
			$data = str_replace("%user%", $option, $template);
			// <-- Template ausfüllen
			
		}
		elseif(isset($_POST['choose']) && !isset($_POST['edit']))
		{
		
			$q = $mysql->query("SELECT * FROM "._PREFIX_."user WHERE id='".intval($_POST['user'])."'");
			$o = mysql_fetch_object($q);
			
			
			//Template ausfüllen -->			
			$tpl = dirname(__FILE__)."/template/user.edit.tpl";
			if(is_file($tpl))
				$template = file_get_contents($tpl);
			$rechte = get_rightlevels($o->rightlevel);
			
			$token = array("id", "loginname", "vorname", "nachname", "email");
			foreach($token as $t)
				$template = str_replace("%".$t."%", $o->{$t}, $template);
			$template = str_replace("%password%", '', $template);
			$template = str_replace("%rightlevel%", $rechte[0], $template);
			$template = str_replace("%right_description%", $rechte[1], $template);
			$template = str_replace("%mode%", get_modes($o->mode), $template);
			// <-- Template ausfüllen
			
			//Hinweis bei nichtvonhandensein von Vorname und Nachname
			if(empty($o->vorname) || empty($o->nachname))
				$msg->note("Wenn Sie dem Benutzer seinen vollen Namen zuordnen, erleichter es Ihnen später die Administration.");
			
			$data .= $template;
		}
		elseif(!isset($_POST['choose']) && isset($_POST['edit']))
		{
			//Verhindern das weniger als 1 Administrator in Datenbank ist
			$rightlevel = intval($_POST['rightlevel']);
			$anzahlAdmins = mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."user WHERE rightlevel='3'"), 0);
			if($anzahlAdmins == 1 && $rightlevel != 3 && intval($_POST['id']) == $user->id) 
			{
				$msg->note("Es ist nurnoch ein Administrator übrig, daher wurde Ihr Rechtelevel <b>nicht geändert</b>. Wenn Sie sich selbst den Administratorstatus nehmen wollen, erstellen Sie vorher mindestens einen anderen Benutzer mit Administratorrechten.");
				$rightlevel = 3;
			}
			
			
			
			if(!$_POST['loginname'])
			{
				$msg->error("Bitte Füllen Sie das Feld Loginname aus");
				
				if(empty($_POST['vorname']) || empty($_POST['nachname']))
					$msg->note("Wenn Sie dem Benutzer seinen vollen Namen zuordnen, erleichter es Ihnen später die Administration.");
				
				//Template ausfüllen -->
				$q = $mysql->query("SELECT * FROM "._PREFIX_."user WHERE id='".intval($_POST['id'])."'");
				$o = mysql_fetch_object($q);

				$tpl = dirname(__FILE__)."/template/user.edit.tpl";
				if(is_file($tpl))
					$template = file_get_contents($tpl);
				$rechte = get_rightlevels($_POST['rightlevel']);
				
				$token = array("id", "loginname", "vorname", "nachname", "email");
				foreach($token as $t)
					$template = str_replace("%".$t."%", $_POST[$t], $template);
				$template = str_replace("%password%", $_POST['password'], $template);
				$template = str_replace("%rightlevel%", $rechte[0], $template);
				$template = str_replace("%right_description%", $rechte[1], $template);
				$template = str_replace("%mode%", get_modes($_POST['mode']), $template);
				// <-- Template ausfüllen
			} else {
				if($_POST['password'])
					$pass = "password = '".sha1($_POST['password'])."',";
				$mysql->query ("UPDATE "._PREFIX_."user
							   SET  vorname='".mysql_real_escape_string($_POST['vorname'])."',
									nachname= '".mysql_real_escape_string($_POST['nachname'])."',
									loginname = '".mysql_real_escape_string($_POST['loginname'])."',
									".$pass."
									email = '".mysql_real_escape_string($_POST['email'])."',
									rightlevel = '".$rightlevel."',
									mode = '".intval($_POST['mode'])."'
								WHERE 
									id = '".intval($_POST['id'])."'
								LIMIT 1");
				$log->add("Bearbeite Nutzerdetails", "<id>".intval($_POST['id'])."</id><loginname>".mysql_real_escape_string($_POST['loginname'])."</loginname><rightlevel>".$rightlevel."</rightlevel><mode>".intval($_POST['mode'])."</mode>");
				$msg->success("Die Benutzerdaten wurde Erfolgreich bearbeitet.");
			}

			$data = $template;
		}
		return $data;
	}
	
	function delete()
	{
		global $user, $mysql, $log, $msg;
				
		//Verhindern das weniger als 1 Nutzer in Datenbank ist
		if(2 > mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."user"), 0))
		{
			$msg->note("Es ist nurnoch ein Benutzer übrig. Sie können nicht alle Nutzer löschen.");
			return;
		}
		
		if(!isset($_POST['confirm_pass']))
		{
			$q = $mysql->query("SELECT * FROM "._PREFIX_."user ORDER BY loginname");
			while($o = mysql_fetch_object($q))
			{
				if($o->vorname || $o->nachname)
					$option .= "<option value=".$o->id.">".$o->loginname." - ".$o->vorname." ".$o->nachname."</option>";
				else
					$option .= "<option value=".$o->id.">".$o->loginname."</option>";
			}
					
			//Template ausfüllen -->
			$tpl = dirname(__FILE__)."/template/user.delete.tpl";
			if(is_file($tpl))
				$template = file_get_contents($tpl);
			$data = str_replace("%user%", $option, $template);
			// <-- Template ausfüllen
			
		} else {
			if(!($user->password == sha1($_POST['confirm_pass'])))
			{
				$msg->error("Ihr Passwort, das Sie eingegeben haben ist leider Falsch.");
				unset($_POST['confirm_pass']);
				$data = delete();				
			} else {
			
				$anzahlAdmins = mysql_result($mysql->query("SELECT count(*) FROM "._PREFIX_."user WHERE rightlevel='3'"), 0);
				if($anzahlAdmins == 1 && intval($_POST['user']) == $user->id) 
				{
					$msg->note("Es ist nurnoch ein Administrator übrig. Wenn Sie sich selbst löschen wollen, erstellen Sie vorher <b>mindestens einen</b> anderen Benutzer mit Administratorrechten.");
					unset($_POST['confirm_pass']);
					return delete();
				}
			
				$o = mysql_fetch_object($mysql->query("SELECT loginname FROM "._PREFIX_."user WHERE id = '".intval($_POST['user'])."'"));
				$mysql->query("DELETE FROM "._PREFIX_."user WHERE id = '".intval($_POST['user'])."'");
				$log->add("Lösche Benutzer", "<id>".intval($_POST['user'])."</id><loginname>".$o->loginname."</loginname>");
				$msg->success("Benutzer ".$o->loginname." gelöscht!");		
				unset($_POST['confirm_pass']);
				$data = delete();
			}			
		}
		return $data;
	}
?>