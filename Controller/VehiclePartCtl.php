<?php
	include("Controller/StandardCtl.php");
	
	class VehiclePartCtl extends StandardCtl{
		private $model;
		private $rows = array( 1=>array('VehiclePart'=>'Motor'),2=>array('VehiclePart'=>'Frenos'),3=>array('VehiclePart'=>'Escape') );  //Estos datos serán obtenidos de la base de datos

		public function run(){
			
			require_once("Model/VehiclePartMdl.php");
			$this->model = new VehiclePartMdl();			
			
			switch($_GET['act']){
					
				case "insert" :
					if(empty($_POST)){
						require_once("View/InsertVehiclePart.php");
					}
					else{
						$idVehiclePart = $this->cleanText($_POST['idVehiclePart']);  // Para este dato se creara un Trigger en la BD
						$VehiclePart   = $this->cleanText($_POST['VehiclePart']);
						
						$result = $this->model->insert($idVehiclePart,$VehiclePart);

						if($result){
							require_once("View/ShowInsertVehiclePart.php");
						}
						else{
							$error = "Error al insertar el nuevo registro"; 
							require_once("View/Error.php");
						}
					}
					break;
				
				case "update" : 
					if(empty($_POST)){
						require_once("View/UpdateVehiclePart.php");
					}
					else{
						//Id del registro que se va a editar
						if(!isset($_POST['idVehiclePart'])){
							$error = 'No se especifico el ID del registro a modificar';
							require_once("View/Error.php");	
						}
						else{
							$idVehiclePart = $this->cleanText($_POST['idVehiclePart']);

							//Validar que exista el registro
							if(array_key_exists($idVehiclePart,$this->rows)){
							
								//Validar que datos fueron ingresados para modificacion
								$VehiclePart = NULL;
								if(isset($_POST['VehiclePart'])){
									$VehiclePart  = $this->cleanText($_POST['VehiclePart']);
								}

								$result = $this->model->update($this->rows[$idVehiclePart],$VehiclePart);

								if($result){
									require_once("View/ShowUpdateVehiclePart.php");
								}
								else{
									$error = 'Error al actualisar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idVehiclePart;
								require_once("View/Error.php");	
							}
						}
					}
					break;
					
				case "select" :
						
						//En esta parte irá la conexión a la base de datos para traer los registros
						
						//Validar que existan registros
						if(!empty($this->rows)){
							require_once("View/SelectVehiclePart.php");
						}
						else{
							$error = 'Error al traer los registros';
							require_once("View/Error.php");
						}
					break;
					
				case "delete" :
					if(empty($_POST)){
						require_once("View/DeleteVehiclePart.php");
					}
					else{
						//Id del registro que se va a eliminar
						if(!isset($_POST['idVehiclePart'])){
							$error = 'No se ha especificado el ID del registro a eliminar';
							require_once("View/Error.php");	
						}
						else{
							$idVehiclePart = $this->cleanText($_POST['idVehiclePart']);

							//Validar que exista el registro
							if(array_key_exists($idVehiclePart,$this->rows)){

								$result = $this->model->delete($this->rows,$idVehiclePart);

								if($result){
									require_once("View/ShowDeleteVehiclePart.php");
								}
								else{
									$error = 'Error al eliminar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idVehiclePart;
								require_once("View/Error.php");	
							}
						}
					}
					break;
			
			} /* fin switch */

		} /* fin run */


	}

?>
