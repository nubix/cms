<?php
/*
 * Author : Jan Germann
 * Datum : 26.04.2010
 * Beschreibung : Biete grundlegende MySQL Funktionen.
 *				  __construct stellt eine MySQL-Verbindung her
 *
 *			Bedarf weiterer Beschreibung hier
 */
 
class classMySQL {
	public $id = 0;
	private $sql_host = "";
	private $sql_user = "";
	private $sql_pass = "";
	private $sql_db = "";

	public $state = 0;
	public $insertid = 0;

	function __construct(){
		global $config;
		$this->sql_host = $config['sql']['host'];
		$this->sql_user = $config['sql']['user'];
		$this->sql_pass = $config['sql']['pass'];
		$this->sql_db = $config['sql']['db'];
		$this->id = mysql_connect($this->sql_host, $this->sql_user, $this->sql_pass);
		if ($this->id) {
			mysql_select_db($this->sql_db);
			$this->state = 1;
		}
	}

	function __destruct(){
		if ($this->id && $this->state) {
			mysql_close($this->id);
			$this->state = 0;
		}
	}

	
	/*
	 * Intelligentes Insert
	*/
	function insert($table, $values, $no_real_escape=0){
		if ($this->state) {
			/*
			 * Feldnamen auslesen
			*/
			$fieldres = $this->query("SELECT * FROM "._PREFIX_.$table." LIMIT 0, 1"); // Qry um 1 Row abzufragen
			$fc = mysql_num_fields($fieldres); // Anzahl der Felder
			/*
			 * Anhand des RESULT und der Anzahl der Felder Feldnamen auslesen und in ein Array speichern
			*/
			for($x = 0; $x < $fc; ++$x) {
				$fieldarray[$x] = "`".mysql_field_name($fieldres, $x)."`";
			}
			mysql_free_result($fieldres); // Freigeben
			/*
			 * Feldnamen liste aus array erstellen
			*/
			$fieldlist = implode(", ", $fieldarray);
			/*
			 * Prüfen ob genügend Werte angegeben sind
			*/
			if (sizeof($values) < $fc) {
				/*
				 * Fehlende Werte mit "" auffüllen
				*/
				for($x = sizeof($values); $x < $fc; ++$x) {
					$values[$x] = "";
				}
			}
			/*
			 * Werte für SQL Vorbereiten
			*/
			for($x = 0; $x < sizeof($values); ++$x) {
				if ($values[$x] != "NULL") {
					if($no_real_escape)
						$values[$x] = "'".$values[$x]."'";
					else
						$values[$x] = "'".mysql_real_escape_string($values[$x])."'";
				}
			}
			/*
			 * Werteliste erstellen
			*/
			$valueslist = implode(", ", $values);
			/*
			 * Werte einfügen
			*/
			//echo "INSERT INTO "._PREFIX_.$table." (".$fieldlist.") VALUES(".$valueslist.")";
			$insert = $this->query("INSERT INTO "._PREFIX_.$table." (".$fieldlist.") VALUES(".$valueslist.")");
			/*
			 * Speichern und Return der Insert-ID
			*/
			$this->insertid = mysql_insert_id($this->id);
			return $this->insertid;
		}
	}	
		
	function query($query){
		if ($this->state) {
			return mysql_query($query, $this->id);
		}
		return 0;
	}

	function fetch($result, $freeresult=0, $numeric_array=0){
		if ($this->state  && $result != false) {
			if($numeric_array)
				$data = mysql_fetch_array($result, MYSQL_NUM);
			else
				$data = mysql_fetch_array($result);
			
			if ($freeresult == 1) {
				mysql_free_result($result);
			}
			return $data;
		} else {
			return 0;
		}
	}
	
	function field_names($table) {
		$feld_resource = $this->query("SELECT * FROM "._PREFIX_.$table." LIMIT 0, 1");
		$fc = mysql_num_fields($feld_resource);
		for($x = 0; $x < $fc; ++$x)
			$fieldarray[] = mysql_field_name($feld_resource, $x);
		mysql_free_result($feld_resource); //Freigeben
		
		return $fieldarray;
	}
	
	function err_formated($error_msg){
		$data = '
		Ein Fehler in der Datenbank wurde festgestellt.
		<hr style=" width: 200px; background: #123123; height: 1px; border: 1px;"/>
		<i>Fehlerbeschreibung</i>
		<div style="border: 1px dashed #BBB; background: #EAEAEA; padding: 5px;">'.$error_msg.'</div>';
		
		return $data;
	}
}

?>