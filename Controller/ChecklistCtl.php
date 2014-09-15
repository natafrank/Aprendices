<?php
	include("Controller/StandardCtl.php");
	
	class ChecklistCtl extends StandardCtl{
		private $model;
		private $rows = array( 1=>array('idVehicle'=>1,'idVehicleStatus'=>1,'Date'=>'20140911','InOut'=>0),
							   2=>array('idVehicle'=>2,'idVehicleStatus'=>2,'Date'=>'20140912','InOut'=>0),
							   3=>array('idVehicle'=>1,'idVehicleStatus'=>3,'Date'=>'20140914','InOut'=>1) );  
							   //Estos datos serán obtenidos de la base de datos

		public function run(){
			
			require_once("Model/ChecklistMdl.php");
			$this->model = new ChecklistMdl();			
			
			switch($_GET['act']){
					
				case "insert" :
					if(empty($_POST)){
						require_once("View/InsertChecklist.php");
					}
					else{
						$idChecklist 	 = $this->cleanText($_POST['idChecklist']);  // Para este dato se creara un Trigger en la BD
						$idVehicle   	 = $this->cleanText($_POST['idVehicle']);    // Necesita Validacion de llave foranea
						$idVehicleStatus = $this->cleanText($_POST['idVehicleStatus']); // Necesita Validacion de llave foranea
						$Date        	 = $this->cleanText($_POST['Date']);
						$InOut       	 = $this->cleanText($_POST['InOut']);
						
						$result = $this->model->insert($idChecklist,$idVehicle,$idVehicleStatus,$Date,$InOut);

						if($result){
							require_once("View/ShowInsertChecklist.php");
						}
						else{
							$error = "Error al insertar el nuevo registro"; 
							require_once("View/Error.php");
						}
					}
					break;
				
				case "update" : 
					if(empty($_POST)){
						require_once("View/UpdateChecklist.php");
					}
					else{
						//Id del registro que se va a editar
						if(!isset($_POST['idChecklist'])){
							$error = 'No se especifico el ID del registro a modificar';
							require_once("View/Error.php");	
						}
						else{
							$idChecklist = $this->cleanText($_POST['idChecklist']);

							//Validar que exista el registro
							if(array_key_exists($idChecklist,$this->rows)){
								
								//Validar que datos fueron ingresados para modificacion
								$idVehicle = NULL;
								if(isset($_POST['idVehicle'])){
									$idVehicle  = $this->cleanText($_POST['idVehicle']);
								}
								$idVehicleStatus = NULL;
								if(isset($_POST['idVehicleStatus'])){
									$idVehicleStatus  = $this->cleanText($_POST['idVehicleStatus']);
								}
								$Date = NULL;
								if(isset($_POST['Date'])){
									$Date  = $this->cleanText($_POST['Date']);
								}
								$InOut = NULL;
								if(isset($_POST['InOut'])){
									$InOut  = $this->cleanText($_POST['InOut']);
								}

								$result = $this->model->update($this->rows[$idChecklist],$idVehicle,$idVehicleStatus,$Date,$InOut);

								if($result){
									require_once("View/ShowUpdateChecklist.php");
								}
								else{
									$error = 'Error al actualisar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idChecklist;
								require_once("View/Error.php");	
							}
						}
					}
					break;
					
				case "select" :
						
						//En esta parte irá la conexión a la base de datos para traer los registros
						
						//Validar que existan registros
						if(!empty($this->rows)){
							require_once("View/SelectChecklist.php");
						}
						else{
							$error = 'Error al traer los registros';
							require_once("View/Error.php");
						}
					break;
					
				case "delete" :
					if(empty($_POST)){
						require_once("View/DeleteChecklist.php");
					}
					else{
						//Id del registro que se va a eliminar
						if(!isset($_POST['idChecklist'])){
							$error = 'No se ha especificado el ID del registro a eliminar';
							require_once("View/Error.php");	
						}
						else{
							$idChecklist = $this->cleanText($_POST['idChecklist']);

							//Validar que exista el registro
							if(array_key_exists($idChecklist,$this->rows)){

								$result = $this->model->delete($this->rows,$idChecklist);

								if($result){
									require_once("View/ShowDeleteChecklist.php");
								}
								else{
									$error = 'Error al eliminar el registro';
									require_once("View/Error.php");
								}
							}
							else{
								$error = 'No existe el registro con el ID: ' . $idChecklist;
								require_once("View/Error.php");	
							}
						}
					}
					break;
			
			} /* fin switch */

		} /* fin run */


	}

?>
