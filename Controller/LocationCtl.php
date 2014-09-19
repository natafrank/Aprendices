<?php

include("Controller/StandardCtl.php");

class LocationCtl extends StandardCtl{
	private $model;

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
					$idLocation = $this->cleanText($_POST['idLocation']);
					$location = $this->cleanText($_POST['location']);
					$idMasterLocation = $this->cleanText($_POST['idMasterLocation']);

					$resul = $this->model->insert($idLocation,$location,$idMasterLocation);

					if($result){
						require_once("View/InserLocation.php");
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
					$idLocation = $this->cleanText($_POST['idLocation']);
					$location = $this->cleanText($_POST['location']);
					$idMasterLocation = $this->cleanText($_POST['idMasterLocation']);

					$resul = $this->model->insert($idLocation,$location,$idMasterLocation);

					if($result){
						require_once("View/UpdateLocation.php");
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
					$idLocation = $this->cleanText($_POST['idLocation']);

					$result = $this->model->select($idLocation);

					if($result){
						require_once("View/SelectLocation.php");
					}
					else{
						$error = "Error al mostrar el registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteLocation.php");
				}
				else{
					$idLocation = $this->cleanText($_POST['idLocation']);

					$result = $this->model->delete($idLocation);

					if($result){
						require_once("View/DeleteLocation.php");
					}
					else{
						$error = "Error al eliminar el registro";
						require_once("View/Error.php");
					}
				}
				break;
		}
	}
}

?>
