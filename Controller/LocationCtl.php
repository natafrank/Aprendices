<?php

include("Controller/StandardCtl.php");

class LocationCtl extends StandardCtl{
	private $model;
	private $rows = array( 1=>array('location'=>'C3','idMasterLocation'=>'1'),
			       2=>array('location'=>'C2','idMasterLocation'=>'2'),
			       3=>array('location'=>'C1','idMasterLocation'=>'3') );

	function __construct(){
		require_once("Model/LocationMdl.php");
		$this->model = new LocationMdl();
	}

	function run(){
		switch($_GET['act']){
			case "insert" :
				if(empty($_POST)){
					require_once("View/InsertLocation.php");
				}
				else{
					$idLocation = $this->cleanInt($_POST['idLocation']);
					$location = $this->cleanText($_POST['location']);
					$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);

					$resul = $this->model->insert($idLocation,$location,$idMasterLocation);

					if($result){
						require_once("View/ShowInserLocation.php");
					}
					else{
						$error = "Error al insertar el nuevo registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'update':
				if(empty($_POST)){
					require_once("View/UpdateLocation.php");
				}
				else{
					$idLocation = $this->cleanInt($_POST['idLocation']);
					$location = $this->cleanText($_POST['location']);
					$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);

					$resul = $this->model->insert($idLocation,$location,$idMasterLocation);

					if($result){
						require_once("View/ShowUpdateLocation.php");
					}
					else{
						$error = "Error al actualizar el registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/SelectLocation.php");
				}
				else{
					if(!isset($_POST['idLocation'])){
						$error = 'No se ha especificado el ID del registro que se va a mostrar';
						require_once("View/Error.php");	
					}
					else{
						if(($idLocation = $this->cleanInt($_POST['idLocation'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->select($idEvent);
		
							if(array_key_exists($idLocation,$this->rows)){
								var_dump($this->rows[$idLocation]);
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
					require_once("View/DeleteLocation.php");
				}
				else{
					if(!isset($_POST['idLocation'])){
						$error = 'No se ha especificado el ID del registro que se va a eliminar';
						require_once("View/Error.php");	
					}
					else{
						if(($idLocation = $this->cleanInt($_POST['idLocation'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->delete($idEvent);
		
							if(array_key_exists($idLocation,$this->rows)){
								unset($this->rows[$idLocation]);
								require_once("View/ShowDeleteLocation.php");
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
