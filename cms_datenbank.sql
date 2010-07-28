-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 08. Mai 2010 um 13:10
-- Server Version: 5.0.51
-- PHP-Version: 5.2.6-1+lenny8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `cms`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_files`
--

CREATE TABLE IF NOT EXISTS `cms_files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text,
  `file` text NOT NULL COMMENT 'URL zum File',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `cms_files`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_images`
--

CREATE TABLE IF NOT EXISTS `cms_images` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text,
  `file` text NOT NULL,
  `file_t` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_log`
--

CREATE TABLE IF NOT EXISTS `cms_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(11) NOT NULL COMMENT 'Referenziert user.id',
  `time` int(10) default '0' COMMENT 'Derzeitige Unixzeit',
  `action` text NOT NULL COMMENT 'Beschreibung der Aktion',
  `comment` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=348 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_menu`
--

CREATE TABLE IF NOT EXISTS `cms_menu` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'Menutext',
  `tooltip` text,
  `type` int(11) NOT NULL COMMENT '0=Nicht zugeordnet / 1=Lokal / 2=Extern',
  `target` text NOT NULL COMMENT 'Zahl für ID wenn lokal. URL wenn Extern',
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_pages`
--

CREATE TABLE IF NOT EXISTS `cms_pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL COMMENT 'Seitentitel',
  `content` text NOT NULL COMMENT 'Seiteninhalt',
  `firstpage` int(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_rel_pf`
--

CREATE TABLE IF NOT EXISTS `cms_rel_pf` (
  `page` int(10) unsigned NOT NULL COMMENT 'Referenziert pages.id',
  `file` int(10) unsigned NOT NULL COMMENT 'Referenziert files.id',
  PRIMARY KEY  (`page`,`file`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cms_rel_pf`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_rightlevel`
--

CREATE TABLE IF NOT EXISTS `cms_rightlevel` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL COMMENT 'Name des Rechtelevel',
  `description` text COMMENT 'Optionale Beschreibung der Rechte',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cms_rightlevel`
--

INSERT INTO `cms_rightlevel` (`id`, `title`, `description`) VALUES
(3, 'Administrator', 'Der Administrator hat vollen Zugriff auf alle Funktionen. Dazu zählt auch das Recht neue Nutzer anzulegen und das Betrachten des Aktionslogfiles.'),
(1, 'Normaler Nutzer', 'Die Rechte dieser Benutzergruppe erstrecken sich nur über das erstellen von Seiten und Menüpunkten. '),
(2, 'Gehobener Nutzer', 'Wie der Normale Nutzer nur mit dem Recht Dateien hoch zu laden');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_settings`
--

CREATE TABLE IF NOT EXISTS `cms_settings` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mail` varchar(255) NOT NULL default 'no@mail..com' COMMENT 'Kontaktmailadresse',
  `mode` tinyint(1) default '1',
  `websitetitel` varchar(255) default NULL COMMENT 'Titel der Seite',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `cms_settings`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_user`
--

CREATE TABLE IF NOT EXISTS `cms_user` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `cms_user`
--

INSERT INTO `cms_user` (`id`, `vorname`, `nachname`, `loginname`, `password`, `email`, `rightlevel`, `mode`, `lastip`, `lastlogin`) VALUES
(1, '', '', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', 3, 1, '192.168.0.20', 1273265814);