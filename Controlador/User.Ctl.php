<?php
	include("Controlador/StandardCtl.php");
	
	class UsuarioCtl extends StandardCtl{
		private $modelo;

		public function ejecutar(){
			
			require_once("Modelo/UsuarioMdl.php");
			$this->modelo = new UsuarioMdl();			
			
			switch($_GET['act']){
				
				case "alta" : 
					if(empty($_POST)){
						require_once(Vista/AltaUsuario.php);
					}
					else{
						$nombre = $this->limpiaTexto($_POST['nombre']);
						$login  = $this->limpiaTexto($_POST['login']);
						$pass   = $this->limpiaTexto($_POST['pass']);
						$tipo   = $this->limpiaTexto($_POST['tipo']); //cambiar por int 
							/* tipo = 1 -> Administrador
							 * tipo = 2 -> Usuario */

						$resultado = $this->modelo->alta($nombre,$login,$pass,$tipo);

						if($resultado){
							require_once("Vista/MuestraUsuario.php");
						}
						else{
							require_once("Vista/ErrorUsuario.php");
						}
					}
			
			} /* fin switch */

		} /* fin ejecutar */


	}

?>
