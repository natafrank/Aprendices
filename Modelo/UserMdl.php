<?php
	class UsuarioMdl{
		public $nombre;
		public $login;
		public $pass;
		public $tipo;		

		public function insert($nombre,$login,$pass,$tipo){
			$this->nombre = $nombre;
			$this->login = $login;
			$this->pass = $pass;
			$this->tipo = $tipo;
	
			return TRUE;		
		} /* fin alta*/

	}
?>
