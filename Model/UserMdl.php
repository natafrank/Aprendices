<?php
	class UserMdl{
		public $name;
		public $login;
		public $pass;
		public $type;		

		public function insert($name,$login,$pass,$type){
			$this->name = $name;
			$this->login = $login;
			$this->pass = $pass;
			$this->type = $type;
	
			return TRUE;		
		} /* fin alta*/

	}
?>
