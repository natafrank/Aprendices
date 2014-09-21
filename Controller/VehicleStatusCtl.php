<?php

include("Controller/StandardCtl.php");

class VehicleStatusCtl extends StandardCtl{
	private $model;
	private $rows = array( 1=>array('vehicleStatus'=>'Buenas Condiciones','Fuel'=>50.5,'Km'=>105.2),
			       2=>array('vehicleStatus'=>'Malas Condiciones','Fuel'=>10.7,'Km'=>347.87),
			       3=>array('vehicleStatus'=>'Buenas Condiciones','Fuel'=>33.8,'Km'=>431.44) );

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
					$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);
					$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
					$Fuel = $this->cleanFloat($_POST['Fuel']);
					$Km = $this->cleanFloat($_POST['Km']);

					$resul = $this->model->insert($idVehicleStatus,$vehicleStatus,$Fuel,$Km);

					if($result){
						require_once("View/ShowInserVehicleStatus.php");
					}
					else{
						$error = "Error al insertar el nuevo registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'update':
				if(empty($_POST)){
					require_once("View/UpdateVehicleStatus.php");
				}
				else{
					$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);
					$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
					$Fuel = $this->cleanFloat($_POST['Fuel']);
					$Km = $this->cleanFloat($_POST['Km']);

					$result = $this->model->update($idVehicleStatus,$vehicleStatus,$Fuel,$Km);

					if($result){
						require_once("View/ShowUpdateVehicleStatus.php");
					}
					else{
						$error = "Error al actualizar el registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/SelectVehicleStatus.php");
				}
				else{
					if(!isset($_POST['idVehicleStatus'])){
						$error = 'No se ha especificado el ID del registro que se va a mostrar';
						require_once("View/Error.php");	
					}
					else{
						if(($idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->select($idEvent);
		
							if(array_key_exists($idVehicleStatus,$this->rows)){
								var_dump($this->rows[$idVehicleStatus]);
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
					require_once("View/DeleteVehicleStatus.php");
				}
				else{
					if(!isset($_POST['idVehicleStatus'])){
						$error = 'No se ha especificado el ID del registro que se va a eliminar';
						require_once("View/Error.php");	
					}
					else{
						if(($idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->delete($idEvent);
		
							if(array_key_exists($idVehicleStatus,$this->rows)){
								unset($this->rows[$idVehicleStatus]);
								require_once("View/ShowDeleteVehicleStatus.php");
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
