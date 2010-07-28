<?php
/**
* Installationsroutine
*/
//Tabellen erstellen und notwendigstes Einfügen.

include("config.inc.php");
$returnVal .= mysqlQuerys();

if(!$returnVal)
	$returnVal = '
	<h2>Installation Erfolgreich</h2>
	<div style="background-color:#E8E7E2;padding:4px;margin:4px;border: 1px dashed #5B5B59;">
		Sie können sich nun Anmelden.
		<p>Benutzername: <i>admin</i><br/>
		Passwort: <i>admin</i></p>
		<a href="http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/admin">Zum Anmelden klicken Sie hier</a>
		<p>
	</div>
	<div style="background-color:#E8E7E2;padding:4px;margin:4px;border: 1px dashed #5B5B59;">
		Die direkte URL zum Admininterface, für den späteren Zugriff ist:
		<div style="border: 1px solid #5B5B59;margin: 3px; padding:2px; background: #f0F0F0;">http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/admin</div>
		</p>
	</div>
	<p style="background-color:#FFAAAA; padding:4px; border: 1px dashed #FF0000; font-weight: bold;">Löschen Sie die install.php im Rootverzeichnis.</p>';

echo $returnVal;	

function mysqlQuerys()
{
	global $config;
	$e = mysql_connect($config['sql']['host'], $config['sql']['user'], $config['sql']['pass']);
	
	if(!$e)
		$returnVal .=
		'<div style="background-color:#FFAAAA;padding:4px;margin:4px;border: 1px dashed #FF0000;">
		Es war nicht möglich zu dem SQL-Server eine Verbindung aufzubauen
		</div>';
	
	$e = mysql_select_db($config['sql']['db']);
	if(!$e)
		$returnVal .=
		'<div style="background-color:#FFAAAA;padding:4px;margin:4px;border: 1px dashed #FF0000;">
		Konnte die Datenbank nicht auswählen
		</div>';
	
	if($returnVal)
		return $returnVal;
		
	
	$querys = array(
		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."files` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `name` varchar(255) NOT NULL,
		  `description` text,
		  `file` text NOT NULL COMMENT 'URL zum File',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1",
		
		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."images` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `name` varchar(255) NOT NULL,
		  `description` text,
		  `file` text NOT NULL,
		  `file_t` text NOT NULL COMMENT 'URL Thumbnail',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1",
		
		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."log` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `user` int(11) NOT NULL COMMENT 'Referenziert user.id',
		  `time` int(10) default '0' COMMENT 'Derzeitige Unixzeit',
		  `action` text NOT NULL COMMENT 'Beschreibung der Aktion',
		  `comment` text,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1",

		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."menu` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `title` varchar(255) NOT NULL COMMENT 'Menutext',
		  `tooltip` text,
		  `type` int(11) NOT NULL COMMENT '0=Nicht zugeordnet / 1=Lokal / 2=Extern / 3=Kontaktformular',
		  `target` text NOT NULL COMMENT 'Zahl für ID wenn lokal. URL wenn Extern',
		  `order` int(10) unsigned NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1",

		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."pages` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `title` varchar(255) NOT NULL COMMENT 'Seitentitel',
		  `content` text NOT NULL COMMENT 'Seiteninhalt',
		  `firstpage` int(1) unsigned NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1",

		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."rel_pf` (
		  `page` int(10) unsigned NOT NULL COMMENT 'Referenziert pages.id',
		  `file` int(10) unsigned NOT NULL COMMENT 'Referenziert files.id',
		  PRIMARY KEY  (`page`,`file`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1",

		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."rightlevel` (
		  `id` int(10) unsigned NOT NULL,
		  `title` varchar(100) NOT NULL COMMENT 'Name des Rechtelevel',
		  `description` text COMMENT 'Optionale Beschreibung der Rechte',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1",

		"INSERT INTO `".$_POST['db_prefix']."rightlevel` (`id`, `title`, `description`) VALUES
		(3, 'Administrator', 'Der Administrator hat vollen Zugriff auf alle Funktionen. Dazu zählt auch das Recht neue Nutzer anzulegen und das Betrachten des Aktionslogfiles.'),
		(1, 'Normaler Nutzer', 'Die Rechte dieser Benutzergruppe erstrecken sich nur über das erstellen von Seiten und Menüpunkten. '),
		(2, 'Gehobener Nutzer', 'Wie der Normale Nutzer nur mit dem Recht Dateien hoch zu laden')",

		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."settings` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `mail` varchar(255) NOT NULL default 'no@mail..com' COMMENT 'Kontaktmailadresse',
		  `mode` tinyint(1) default '1',
		  `websitetitel` varchar(255) default NULL COMMENT 'Titel der Seite',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ",

		"CREATE TABLE IF NOT EXISTS `".$_POST['db_prefix']."user` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `vorname` varchar(255) default NULL,
		  `nachname` varchar(255) default NULL,
		  `loginname` varchar(255) NOT NULL,
		  `password` varchar(40) NOT NULL COMMENT 'SHA1 - 40 Zeichen',
		  `email` text,
		  `rightlevel` int(10) unsigned NOT NULL,
		  `mode` tinyint(1) NOT NULL default '1' COMMENT '1 wenn aktiv',
		  `lastip` varchar(15) default NULL,
		  `lastlogin` int(10) unsigned default NULL COMMENT 'Unixtime',
		  PRIMARY KEY  (`id`),
		  UNIQUE KEY `name` (`loginname`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1",

		"INSERT INTO `".$_POST['db_prefix']."user` (`id`, `vorname`, `nachname`, `loginname`, `password`, `email`, `rightlevel`, `mode`, `lastip`, `lastlogin`) VALUES
		(1, '', '', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', 3, 1, '', 0);");
	
	foreach($querys AS $query)
	{
		@mysql_query($query);
		if(mysql_error())
			$returnVal .=
			'<div style="background-color:#E8E7E2;padding:4px;margin:4px;border: 1px dashed #5B5B59;">'
			.mysql_error()
			.'</div>';
	}
	
	return $returnVal;
}