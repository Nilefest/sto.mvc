<?php
/*
    Для работы с данными сессии
*/

class Session {
	public $data = array();
			
  	public function __construct() {
		if(!session_id()) session_start();
		$this->data = &$_SESSION;
	}
}
?>
