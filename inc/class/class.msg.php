<?php
/**
 * Author : Jan Germann
 * Datum : 26.04.2010
 * Beschreibung : Message Klasse. Zum standardisierten ausgeben von Nachrichten an den User
*/
 
class classMessage {
	public  $note 		= 0; //Anzahl der Anmerkungen
	public  $error 		= 0; //Anzahl der Fehler
	public	$success	= 0; //Anzahl der Erfolge
	private $note_msg	= 0;
	private $error_msg	= 0;
	private	$success_msg= 0;
	
	private $tpl_dir	= "" ;
	
	public function __construct()
	{
		$this->tpl_dir = dirname($_SERVER['SCRIPT_FILENAME'])."/template/".TEMPLATE."/";
		
		$this->note_msg 	= array();
		$this->error_msg 	= array();
		$this->success_msg	= array();
	}
	
	public function note($text) 
	{
		array_push($this->note_msg, $text);
		return ++$this->note;
	}
	
	public function get_note()
	{
		if(!$this->note)
			return;
		
		$tpl = $this->tpl_dir."msg.note.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);

		foreach($this->note_msg as $e)
			$data .=  str_replace("%message%", $e, $template);	

		return $data;
	}	
	
	public function error($text) 
	{
		array_push($this->error_msg, $text);
		return ++$this->error;
	}
	
	public function get_error()
	{
		if(!$this->error)
			return;

		$tpl = $this->tpl_dir."msg.error.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);

		foreach($this->error_msg as $e)
			$data .= str_replace("%message%", $e, $template);	

		return $data;
	}

	public function success($text) 
	{
		array_push($this->success_msg, $text);
		return ++$this->success;
	}
	
	public function get_success()
	{
		if(!$this->success)
			return;

		$tpl = $this->tpl_dir."msg.success.tpl";
		if(is_file($tpl))
			$template = file_get_contents($tpl);

		foreach($this->success_msg as $e)
			$data .=  str_replace("%message%", $e, $template);	

		return $data;
	}
 	
}
?>