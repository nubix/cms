<?php

class classSession {
	function __construct() {
		session_start();
	}
	function destroy() {
		session_destroy();
		session_unset();
	}
}

?>