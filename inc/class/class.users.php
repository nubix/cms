<?php

class classUser {
	public $id			= 0;
	public $vorname		= "";
	public $nachname	= "";
	public $name		= 0;
	public $password 	= "";
	public $email		= "";
	public $rightlevel 	= 0;
	public $mode 		= 0;
	public $ip 			= "";
	public $lastogin	= 0;

	public $logged		= 0;
	
	public $login_post		= 0;
	public $error_pass		= 0;
	public $error_user		= 0;
	public $error_active	= 0;

	function __construct(){
		global $mysql, $cookie;
		// IP Adresse in die Klasse speichern
		$this->ip = $_SERVER['REMOTE_ADDR'];

		// Prüfen ob ein Login per Cookie möglich ist
		if (isset($_COOKIE[COOKIE_UID]) && isset($_COOKIE[COOKIE_PASS])) {
			$result = $mysql->query("SELECT * FROM "._PREFIX_."user WHERE id=".intval($_COOKIE[COOKIE_UID]));
			if ($result) {
				$row = $mysql->fetch($result, 1);	
				if ($row['mode'] == true) {
					if ($row['password'] == $_COOKIE[COOKIE_PASS]) {
						$this->loginuser(intval($_COOKIE[COOKIE_UID]), 1);
					} else {
						$this->error_pass = 1;
						$cookie->delcookie(COOKIE_UID);
						$cookie->delcookie(COOKIE_PASS);
					}
				} else {
					$this->error_active = 1;
				}
			} else {
				$this->error_user = 1;
				$cookie->delcookie(COOKIE_UID);
				$cookie->delcookie(COOKIE_PASS);
			}
		
		// Prüfen ob der User versucht sich einzuloggen
		} elseif (isset($_POST['username']) && isset($_POST['password'])) {
			$userhash = sha1($_POST['password']);
			$this->login_post = 1;
			$result = $mysql->query("SELECT * FROM "._PREFIX_."user WHERE loginname='".mysql_real_escape_string($_POST['username'])."'");
			
			if ($result) {
				$row = $mysql->fetch($result, 1);
				
				if ($row['mode'] == true) {
					if ($row['password'] == $userhash) {
						$this->loginuser($row['id'], 0);
					} else {
						$this->error_pass = 1;
					}
				} else {
					$this->error_user = 1;
					$cookie->delcookie(COOKIE_UID);
					$cookie->delcookie(COOKIE_BAN);
				}
			} else {
				$this->error_user = 1;
			}
		}
	}

	function loginuser($id, $has_cookie=0){
		global $mysql, $cookie;
		$result = $mysql->query("SELECT * FROM "._PREFIX_."user WHERE id=".intval($id));
		if ($result) {
			$row = $mysql->fetch($result, 1);

			$this->logged = 1;
			
			$this->id			= $id;
			$this->vorname		= $row['vorname'];
			$this->nachname		= $row['nachname'];
			$this->name			= $row['loginname'];
			$this->password 	= $row['password'];
			$this->email		= $row['email'];
			$this->rightlevel 	= $row['rightlevel'];
			$this->mode 		= $row['mode'];
			$this->ip 			= $_SERVER['REMOTE_ADDR'];
			$this->lastogin		= _TIME_; 
			
			if(!$has_cookie) {
				$cookie->addcookie(COOKIE_UID, $this->id);
				$cookie->addcookie(COOKIE_PASS, $this->password);
			} else {
				$cookie->refreshcookie(COOKIE_UID);
				$cookie->refreshcookie(COOKIE_PASS);
			}
			
			$this->setuser("lastip", $this->ip);
			$this->setuser("lastlogin", _TIME_);
		}
	}
	 
	function logout(){
		global $mysql, $cookie, $session;
		if($this->logged){
			
			$this->logged		= 0;
			
			$this->id			= 0;
			$this->vorname		= "";
			$this->nachname		= "";
			$this->name			= 0;
			$this->password 	= "";
			$this->email		= "";
			$this->rightlevel 	= 0;
			$this->mode 		= 0;
			$this->ip 			= "";
			$this->lastogin		= 0;

			$this->login_post		= 0;
			$this->error_pass		= 0;
			$this->error_user		= 0;
			$this->	error_active	= 0;
		
			$cookie->delcookie(COOKIE_UID);
			$cookie->delcookie(COOKIE_PASS);
			
			$session->destroy();
		}
	}
	
	function setuser($setting, $value, $id=0){
		global $mysql;
		$userid = $id;
		$value = mysql_real_escape_string($value);
		if ($id == 0) {
			$userid = $this->id;
		}
		$update = $mysql->query("UPDATE "._PREFIX_."user SET ".$setting."='".$value."' WHERE id=".$userid);
	}
}
?>