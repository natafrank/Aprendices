<?php
	include("Controller/StandardCtl.php");
	
	class DamageCtl extends StandardCtl{
		private $model;
		private $rows = array( 1=>array('Damage'=>'Abolladura'),2=>array('Damage'=>'Roto'),3=>array('Damage'=>'Destrozado') );  
						//Estos datos serán obtenidos de la base de datos

		public function run(){
			
			require_once("Model/DamageMdl.php");
			$this->model = new DamageMdl();			
			
			switch($_GET['act']){
					
				case "insert" :
					if(empty($_POST)){
						require_once("View/InsertDamage.php");
					}
					else{
						$idDamage = $this->cleanInt($_POST['idDamage']);  // Para este dato se creara un Trigger en la BD
						$Damage   = $this->cleanText($_POST['Damage']);
						
						$result = $this->model->insert($idDamage,$Damage);

						if($result){
							require_once("View/ShowInsertDamage.php");
						}
						else{
							$error = "Error al insertar el nuevo registro"; 
							require_once("View/Error.php");
						}
					}
					break;
				
				case "update" : 
					if(empty($_POST)){
						require_once("View/UpdateDamage.php");
					}
					else{
						//Id del registro que se va a editar
						if(!isset($_POST['idDamage'])){
							$error = 'No se especifico el ID del registro a modificar';
							require_once("View/Error.php");	
						}
						else{
							$idDamage = $this->cleanInt($_POST['idDamage']);

							//Validar que exista el registro
							if(array_key_exists($idDamage,$this->rows)){
							
								//Validar que datos fueron ingresados para modificacion
								$Damage = NULL;
								if(isset($_POST['Damage'])){
									$Damage  = $this->cleanText($_POST['Damage']);
								}

								$result = $this->model->update($this->rows[$idDamage],$Damage);

								if($result){
									require_once("View/ShowUpdateDamage.php");
								}
								else{
									$error = 'Error al actualisar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idDamage;
								require_once("View/Error.php");	
							}
						}
					}
					break;
					
				case "select" :
						
						//En esta parte irá la conexión a la base de datos para traer los registros
						
						//Validar que existan registros
						if(!empty($this->rows)){
							require_once("View/SelectDamage.php");
						}
						else{
							$error = 'Error al traer los registros';
							require_once("View/Error.php");
						}
					break;
					
				case "delete" :
					if(empty($_POST)){
						require_once("View/DeleteDamage.php");
					}
					else{
						//Id del registro que se va a eliminar
						if(!isset($_POST['idDamage'])){
							$error = 'No se ha especificado el ID del registro a eliminar';
							require_once("View/Error.php");	
						}
						else{
							$idDamage = $this->cleanInt($_POST['idDamage']);

							//Validar que exista el registro
							if(array_key_exists($idDamage,$this->rows)){

								$result = $this->model->delete($this->rows,$idDamage);

								if($result){
									require_once("View/ShowDeleteDamage.php");
								}
								else{
									$error = 'Error al eliminar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idDamage;
								require_once("View/Error.php");	
							}
						}
					}
					break;
			
			} /* fin switch */

		} /* fin run */


	}

?>
