<?php
class classLog {

	public function add($action, $comment="")
	{
		global $user, $mysql;
		$values = array('', $user->id, _TIME_, $action, $comment);
		$mysql->insert("log", $values);
	}
}

?>