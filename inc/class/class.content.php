<?php

class classContent {
	private $modules = "";
	private $moduleCount = 0;
	public $tpl_dir = "";
	public $pagetitle = '';
	
	
	function __construct()
	{
		$this->modules = array();
		$this->tpl_dir = dirname($_SERVER['SCRIPT_FILENAME'])."/template/".TEMPLATE."/";
	}

	function __destruct()
	{
		global $page, $msg, $user;
		$content = $_SESSION['content'];
		unset($_SESSION['content']);
		$content = str_replace("%request_uri%", $_SERVER['REQUEST_URI'], $content);	
		
		
		
		$tpl = $this->tpl_dir."index.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);
			
		//Die Token in der Index.tpl die ersetzwerden sollen. mit werten belegen.
		$token = array("content" => $content,
					   "error" => $msg->get_error(),
					   "note" => $msg->get_note(),
					   "success" => $msg->get_success(),
					   "navigation" => $this->getNavigation());

		if($this->moduleCount)
		{
			$token = array_merge($token, array("modulelist" => $this->getModules()));
		} else {
			$template = str_replace("%modulelist%", '', $template);
		}
		//TODO Hier die module auslesen wenn der nutzer angemeldet ist und ausgeben.
		//	   ansonsten nur die Seitenliste ausgeben und module verstecken.
		//	   Ein anmelden soll über das noch nicht vorhandene Modul "login" möglich sein.
		//	   Danach sollen Die module angezeigt werden.
		//	   Eventuell eine Templateaufteilung in frontend/ und backend/ mit entsprechenden
		//	   Dateien und Token.
		
		//Hier werden die Werte aus dem Array in weiter "verpackt" und für die index.tpl vorbereitet
		$tokenTpl = array();
		foreach($token AS $t => $d)
		{
			if($returnVal = $this->readToken($t))
			{
				$newArray = array($t => str_replace("%".$t."%", $d, $returnVal));
				$tokenTpl = array_merge($tokenTpl, $newArray);
			}
		}

		if(!$msg->error)
			$tokenTpl['error'] = '';
		if(!$msg->note)
			$tokenTpl['note'] = '';
		if(!$msg->success)
			$tokenTpl['success'] = '';
		
		foreach($token AS $t => $d)
			$template = str_replace("%".$t."%", $tokenTpl[$t], $template);
		
		$template = str_replace("%pagetitle%", $this->pagetitle, $template);
		$template = str_replace("%tpl%", "template/".TEMPLATE, $template);	
		echo $template;
	}
	
	
	/**
	* Gibt ein Array mit dem Key $token und der Value $tpl zurück,
	* wenn das template für den Token nicht vorhanden ist 0 zurück.
	*/
	function readToken($token)
	{
		$tpl = $this->tpl_dir . "index.token." . $token . ".tpl";
		if(is_file($tpl))
			return file_get_contents($tpl);
		return 0;
	}
	
	function getModules()
	{
		$tpl = $this->tpl_dir . "index.token.modulelist.token.tpl";
		if(is_file($tpl))
			$tpl = file_get_contents($tpl);
			
		$modules = "";
		foreach($this->modules AS $key => $value)
			$modules .= str_replace(array("%key%","%title%"), array($key, $value), $tpl);
		return $modules;
	}

	function delModule($module)
	{
		unset($this->modules[$module]);
		--$this->moduleCount;
	}

	function addModule($module, $name)
	{
		$this->modules = array_merge($this->modules, array($module => $name));
		++$this->moduleCount;
	}
	
	function getNavigation()
	{
		global $mysql;
		
		$tpl = $this->tpl_dir . "index.token.navigation.token.tpl";
		if(is_file($tpl))
			$tpl = file_get_contents($tpl);
			
		$qNavi = $mysql->query("SELECT * FROM "._PREFIX_."menu ORDER BY `order`");
		while($o = mysql_fetch_object($qNavi))
		{
			$token = array(array("%link%", "%tooltip%", "%title%"));
			if($o->type == 1)
				$token.array_push(array('href="?a=show&id='.$o->target.'"', $o->tooltip, $o->title));
			elseif($o->type == 2)
				$token.array_push(array('href="'.$o->target.'" target="_blank"', $o->tooltip,  $o->title));
			elseif($o->type == 3)
				$token.array_push(array('href="?a=contact"', $o->tooltip, $o->title));
			else
				$token.array_push(array('class="nolink"', $o->tooltip, $o->title));

			$navi .= str_replace($token[0],$token[1], $tpl);
			
			
		}
		return $navi;
	}
}

?>
