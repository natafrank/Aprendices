<?php

include("Controller/StandardCtl.php");

class VehicleStatusCtl extends StandardCtl{
	private $model;

	function __construct(){
		require_once("Model/VehicleStatusMdl.php");
		$this->model = new VehicleStatusMdl();
	}

	function run(){
		switch($_GET['act']){
			case "insert" :
				if(empty($_POST)){
					require_once("View/InsertVehicleStatus.php");
				}
				else{
					$idVehicleStatus = $this->cleanText($_POST['idVehicleStatus']);
					$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
					$Fuel = $this->cleanFloat($_POST['Fuel']);
					$Km = $this->cleanFloat($_POST['Km']);

					$resul = $this->model->insert($idVehicleStatus,$vehicleStatus,$Fuel,$Km);

					if($result){
						require_once("View/InserVehicleStatus.php");
					}
					else{
						require_once("View/ErrorInsert.php");
					}
				}
				break;
			case 'update':
				if(empty($_POST)){
					require_once("View/UpdateVehicleStatus.php");
				}
				else{
					$idVehicleStatus = $this->cleanText($_POST['idVehicleStatus']);
					$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
					$Fuel = $this->cleanFloat($_POST['Fuel']);
					$Km = $this->cleanFloat($_POST['Km']);

					$result = $this->model->update($idVehicleStatus,$vehicleStatus,$Fuel,$Km);

					if($result){
						require_once("View/UpdateVehicleStatus.php");
					}
					else{
						require_once("View/ErrorUpdate.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/DeleteVehicleStatus.php");
				}
				else{
					$idVehicleStatus = $this->cleanText($_POST['idVehicleStatus']);

					$result = $this->model->select($idVehicleStatus);

					if($result){
						require_once("View/SelectVehicleStatus.php");
					}
					else{
						require_once("View/ErrorSelect.php");
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteVehicleStatus.php");
				}
				else{
					$idVehicleStatus = $this->cleanText($_POST['idVehicleStatus']);
					//validar que exista el registro en la base de datos
					//if(){
						$result = $this->model->delete($idVehicleStatus);
						if($result){
							require_once("View/DeleteVehicleStatus.php");
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
