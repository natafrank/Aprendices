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
					$idUser = $this->cleanFloat($_POST['idUser']);
					$idEvent = $this->cleanFloat($_POST['idEvent']);
					$Date= $this->cleanFloat($_POST['Date']);
					$Motiv = $this->cleanFloat($_POST['Motiv']);

					$resul = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Motiv);

					if($result){
						require_once("View/InserEventRegistry.php");
					}
					else{
						require_once("View/ErrorInsert.php");
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
					$idUser = $this->cleanFloat($_POST['idUser']);
					$idEvent = $this->cleanFloat($_POST['idEvent']);
					$Date= $this->cleanFloat($_POST['Date']);
					$Motiv = $this->cleanFloat($_POST['Motiv']);

					$resul = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Motiv);

					if($result){
						require_once("View/UpdateEventRegistry.php");
					}
					else{
						require_once("View/ErrorUpdate.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/DeleteEventRegistry.php");
				}
				else{
					$idEventRegistry = $this->cleanText($_POST['idEventRegistry']);

					$result = $this->model->select($idEventRegistry);

					if($result){
						require_once("View/SelectEventRegistry.php");
					}
					else{
						require_once("View/ErrorSelect.php");
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteEventRegistry.php");
				}
				else{
					$idEventRegistry = $this->cleanText($_POST['idEventRegistry']);
					//validar que exista el registro en la base de datos
					//if(){
						$result = $this->model->delete($idEventRegistry);
						if($result){
							require_once("View/DeleteEventRegistry.php");
						}
						else{
							require_once("View/ErrorDelete.php");
						}
					//}
					//else{
					//	require_once("View/ErrorDelete");
					//}
				}
				break;
		}
	}
}

?>
