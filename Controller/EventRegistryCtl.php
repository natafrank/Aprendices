<?php

include("Controller/StandardCtl.php");

class EventRegistryCtl extends StandardCtl{
	private $model;
	private $rows = array( 1=>array('idVehicle'=>'1','idUser'=>'1','idEvent'=>'1','Date'=>'01-01-14','Motiv'=>'Se necesitaba el lugar'),
			       2=>array('idVehicle'=>'2','idUser'=>'2','idEvent'=>'2','Date'=>'02-02-14','Motiv'=>'Se necesitaba el lugar'),
			       3=>array('idVehicle'=>'3','idUser'=>'3','idEvent'=>'3','Date'=>'03-03-14','Motiv'=>'Se necesitaba el lugar') );

	function __construct(){
		require_once("Model/EventRegistryMdl.php");
		$this->model = new EventRegistryMdl();
	}

	function run(){
		switch($_GET['act']){
			case "insert" :
				if(empty($_POST)){
					require_once("View/InsertEventRegistry.php");
				}
				else{
					$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);
					$idVehicle = $this->cleanInt($_POST['idVehicle']);
					$idUser = $this->cleanInt($_POST['idUser']);
					$idEvent = $this->cleanInt($_POST['idEvent']);
					$Date= $this->cleanDateTime($_POST['Date']);
					$Motiv = $this->cleanText($_POST['Motiv']);

					$resul = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Motiv);

					if($result){
						require_once("View/ShowInserEventRegistry.php");
					}
					else{
						$error = "Error al insertar el nuevo registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'update':
				if(empty($_POST)){
					require_once("View/UpdateEventRegistry.php");
				}
				else{
					$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);
					$idVehicle = $this->cleanInt($_POST['idVehicle']);
					$idUser = $this->cleanInt($_POST['idUser']);
					$idEvent = $this->cleanInt($_POST['idEvent']);
					$Date= $this->cleanDateTime($_POST['Date']);
					$Motiv = $this->cleanText($_POST['Motiv']);

					$resul = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Motiv);

					if($result){
						require_once("View/ShowUpdateEventRegistry.php");
					}
					else{
						$error = "Error al actualizar el registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/SelectEventRegistry.php");
				}
				else{
					if(!isset($_POST['idEventRegistry'])){
						$error = 'No se ha especificado el ID del registro que se va a mostrar';
						require_once("View/Error.php");	
					}
					else{
						if(($idEventRegistry = $this->cleanInt($_POST['idEventRegistry'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->select($idEvent);
		
							if(array_key_exists($idEventRegistry,$this->rows)){
								var_dump($this->rows[$idEventRegistry]);
							}

							//if($result){
							//	require_once("View/SelectEvent.php");
							//}
							else{
								$error = "No se encuentra el registro";
								require_once("View/Error.php");
							}
						}
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteEventRegistry.php");
				}
				else{
					if(!isset($_POST['idEventRegistry'])){
						$error = 'No se ha especificado el ID del registro que se va a eliminar';
						require_once("View/Error.php");	
					}
					else{
						if(($idEventRegistry = $this->cleanInt($_POST['idEventRegistry'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->delete($idEvent);
		
							if(array_key_exists($idEventRegistry,$this->rows)){
								unset($this->rows[$idEventRegistry]);
								require_once("View/ShowDeleteEventRegistry.php");
							}

							//if($result){
							//	require_once("View/SelectEvent.php");
							//}
							else{
								$error = "No se encuentra el registro";
								require_once("View/Error.php");
							}
						}
					}
				}
				break;
		}
	}
}

?>
