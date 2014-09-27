<?php
	include("Controller/StandardCtl.php");
	
	class DamageDetailCtl extends StandardCtl{
		private $model;
		private $rows = array( 1=>array('idChecklist'=>1,'idVehiclePart'=>3,'idDamage'=>2),
							   2=>array('idChecklist'=>2,'idVehiclePart'=>1,'idDamage'=>3),
							   3=>array('idChecklist'=>3,'idVehiclePart'=>2,'idDamage'=>1) );  
							   //Estos datos serán obtenidos de la base de datos

		public function run(){
			
			require_once("Model/DamageDetailMdl.php");
			$this->model = new DamageDetailMdl();			
			
			switch($_GET['act']){
					
				case "insert" :
					if(empty($_POST)){
						require_once("View/InsertDamageDetail.php");
					}
					else{
						$idDamageDetail = $this->cleanInt($_POST['idDamageDetail']);  // Para este dato se creara un Trigger en la BD
						$idChecklist = $this->cleanInt($_POST['idChecklist']);   	// Validacion de llave foranea
						$idVehiclePart = $this->cleanInt($_POST['idVehiclePart']); // Validacion de llave foranea
						$idDamage = $this->cleanInt($_POST['idDamage']); 			// Validacion de llave foranea
						
						$result = $this->model->insert($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage);

						if($result){
							require_once("View/ShowInsertDamageDetail.php");
						}
						else{
							$error = "Error al insertar el nuevo registro"; 
							require_once("View/Error.php");
						}
					}
					break;
				
				case "update" : 
					if(empty($_POST)){
						require_once("View/UpdateDamageDetail.php");
					}
					else{
						//Id del registro que se va a editar
						if(!isset($_POST['idDamageDetail'])){
							$error = 'No se especifico el ID del registro a modificar';
							require_once("View/Error.php");	
						}
						else{
							$idDamageDetail = $this->cleanInt($_POST['idDamageDetail']);

							//Validar que exista el registro
							if(array_key_exists($idDamageDetail,$this->rows)){
							
								//Validar que datos fueron ingresados para modificacion
								$idChecklist = NULL;
								if(isset($_POST['idChecklist'])){
									$idChecklist  = $this->cleanInt($_POST['idChecklist']);
								}
								$idVehiclePart = NULL;
								if(isset($_POST['idVehiclePart'])){
									$idVehiclePart  = $this->cleanInt($_POST['idVehiclePart']);
								}
								$idDamage = NULL;
								if(isset($_POST['idDamage'])){
									$idDamage  = $this->cleanInt($_POST['idDamage']);
								}

								$result = $this->model->update($this->rows[$idDamageDetail],$idChecklist,$idVehiclePart,$idDamage);

								if($result){
									require_once("View/ShowUpdateDamageDetail.php");
								}
								else{
									$error = 'Error al actualisar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idDamageDetail;
								require_once("View/Error.php");	
							}
						}
					}
					break;
					
				case "select" :
						
						//En esta parte irá la conexión a la base de datos para traer los registros
						
						//Validar que existan registros
						if(!empty($this->rows)){
							require_once("View/SelectDamageDetail.php");
						}
						else{
							$error = 'Error al traer los registros';
							require_once("View/Error.php");
						}
					break;
					
				case "delete" :
					if(empty($_POST)){
						require_once("View/DeleteDamageDetail.php");
					}
					else{
						//Id del registro que se va a eliminar
						if(!isset($_POST['idDamageDetail'])){
							$error = 'No se ha especificado el ID del registro a eliminar';
							require_once("View/Error.php");	
						}
						else{
							$idDamageDetail = $this->cleanInt($_POST['idDamageDetail']);

							//Validar que exista el registro
							if(array_key_exists($idDamageDetail,$this->rows)){

								$result = $this->model->delete($this->rows,$idDamageDetail);

								if($result){
									require_once("View/ShowDeleteDamageDetail.php");
								}
								else{
									$error = 'Error al eliminar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idDamageDetail;
								require_once("View/Error.php");	
							}
						}
					}
					break;
			
			} /* fin switch */

		} /* fin run */


	}

?>
