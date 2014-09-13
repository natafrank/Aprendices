<?php
	include("Controlador/StandardCtl.php");
	
	class UserCtl extends StandardCtl{
		private $model;

		public function run(){
			
			require_once("Modelo/UserMdl.php");
			$this->model = new UserMdl();			
			
			switch($_GET['act']){
				
				case "insert" : 
					if(empty($_POST)){
						require_once("Vista/InsertUser.php");
					}
					else{
						$nombre = $this->cleanText($_POST['nombre']);
						$login  = $this->cleanText($_POST['login']);
						$pass   = $this->cleanText($_POST['pass']);
						$tipo   = $this->cleanText($_POST['tipo']); //cambiar por int 
							/* tipo = 1 -> Administrador
							 * tipo = 2 -> Usuario */

						$result = $this->model->insert($nombre,$login,$pass,$tipo);

						if($result){
							require_once("Vista/ShowUser.php");
						}
						else{
							require_once("Vista/UserError.php");
						}
					}
			
			} /* fin switch */

		} /* fin ejecutar */


	}

?>
