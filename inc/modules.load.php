<?php
/*
 * Author : Jan Germann
 * Datum : 24.04.2010
 * Beschreibung : Ließt alle Module ein die eine "init.php" haben.
 */
function load_modules() 
{
	$handle = opendir(INCLUDE_DIR.MODULE_DIR);

	while($dir = readdir($handle))
		if(is_dir(INCLUDE_DIR.MODULE_DIR.$dir))
			if(is_file(INCLUDE_DIR.MODULE_DIR .$dir."/init.php"))
				require_once("inc/modules/".$dir."/init.php");
}

load_modules();

?>