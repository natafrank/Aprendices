<?php

include("Controller/StandardCtl.php");

class EventRegistryCtl extends StandardCtl{
	private $model;

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
					$idEventRegistry = $this->cleanText($_POST['idEventRegistry']);
					$idVehicle = $this->cleanText($_POST['idVehicle']);
					$idUser = $this->cleanText($_POST['idUser']);
					$idEvent = $this->cleanText($_POST['idEvent']);
					$Date= $this->cleanText($_POST['Date']);
					$Motiv = $this->cleanText($_POST['Motiv']);

					$resul = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Motiv);

					if($result){
						require_once("View/InserEventRegistry.php");
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
					$idEventRegistry = $this->cleanText($_POST['idEventRegistry']);
					$idVehicle = $this->cleanText($_POST['idVehicle']);
					$idUser = $this->cleanText($_POST['idUser']);
					$idEvent = $this->cleanText($_POST['idEvent']);
					$Date= $this->cleanText($_POST['Date']);
					$Motiv = $this->cleanText($_POST['Motiv']);

					$resul = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Motiv);

					if($result){
						require_once("View/UpdateEventRegistry.php");
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
					$idEventRegistry = $this->cleanText($_POST['idEventRegistry']);

					$result = $this->model->select($idEventRegistry);

					if($result){
						require_once("View/SelectEventRegistry.php");
					}
					else{
						$error = "Error al mostrar el registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteEventRegistry.php");
				}
				else{
					$idEventRegistry = $this->cleanText($_POST['idEventRegistry']);

					$result = $this->model->delete($idEventRegistry);

					if($result){
						require_once("View/DeleteEventRegistry.php");
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
