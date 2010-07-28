<?php

class classCookie {

	function addcookie($name, $value, $expire=0){
		global $config;
		if ($expire == 0) {
			$expire = _TIME_ + $config['cookie']['expire'];
		} else {
			$expire = _TIME_ + $expire;
		}
		setcookie($name, $value, $expire);
	}

	function delcookie($name){
		global $config;
		setcookie($name, $value, (_TIME_ - 3600));
	}

	function refreshcookie($name, $expire=0){
		global $config;
		if ($expire == 0) {
			$expire = _TIME_ + $config['cookie']['expire'];
		} else {
			$expire = _TIME_ + $expire;
		}
		setcookie($name, $_COOKIE[$name], $expire);
	}
}

?>