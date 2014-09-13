<?php
	include("Controller/StandardCtl.php");
	
	class UserCtl extends StandardCtl{
		private $model;

		public function run(){
			
			require_once("Model/UserMdl.php");
			$this->model = new UserMdl();			
			
			switch($_GET['act']){
				
				case "insert" : 
					if(empty($_POST)){
						require_once("View/InsertUser.php");
					}
					else{
						$name = $this->cleanText($_POST['name']);
						$login  = $this->cleanText($_POST['login']);
						$pass   = $this->cleanText($_POST['pass']);
						$type   = $this->cleanText($_POST['type']); //cambiar por int 
							/* tipo = 1 -> Administrador
							 * tipo = 2 -> Usuario */

						$result = $this->model->insert($name,$login,$pass,$type);

						if($result){
							require_once("View/ShowUser.php");
						}
						else{
							require_once("View/UserError.php");
						}
					}
			
			} /* fin switch */

		} /* fin ejecutar */


	}

?>
