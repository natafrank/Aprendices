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
					$idMasterLocation = $this->cleanFloat($_POST['idMasterLocation']);

					$resul = $this->model->insert($idLocation,$location,$idMasterLocation);

					if($result){
						require_once("View/InserLocation.php");
					}
					else{
						require_once("View/ErrorInsert.php");
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
					$idMasterLocation = $this->cleanFloat($_POST['idMasterLocation']);

					$resul = $this->model->insert($idLocation,$location,$idMasterLocation);

					if($result){
						require_once("View/UpdateLocation.php");
					}
					else{
						require_once("View/ErrorUpdate.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/DeleteLocation.php");
				}
				else{
					$idLocation = $this->cleanText($_POST['idLocation']);

					$result = $this->model->select($idLocation);

					if($result){
						require_once("View/SelectLocation.php");
					}
					else{
						require_once("View/ErrorSelect.php");
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteLocation.php");
				}
				else{
					$idLocation = $this->cleanText($_POST['idLocation']);
					//validar que exista el registro en la base de datos
					//if(){
						$result = $this->model->delete($idLocation);
						if($result){
							require_once("View/DeleteLocation.php");
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
