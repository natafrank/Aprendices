<?php

class VehicleStatusMdl{

	public $idVehicleStatus;
	public $vehicleStatus;
	public $Fuel;
	public $Km;
	
	public function insert($idVehicleStatus, $vehicleStatus, $Fuel, $Km){
		$this->idVehicleStatus = $idVehicleStatus;
		$this->vehicleStatus = $vehicleStatus;
		$this->Fuel = $Fuel;
		$this->Km = Km;
		
		return TRUE;
	}
	public function select($idVehicleStatus){
		return TRUE;
	}
	public function delete($idVehicleStatus){
		return TRUE;
	}
	
	public function update($idVehicleStatus, $vehicleStatus, $Fuel, $Km){
		$this->idVehicleStatus = $idVehicleStatus;
		$this->vehicleStatus = $vehicleStatus;
		$this->Fuel = $Fuel;
		$this->Km = Km;
		
		return TRUE;
	}

}

?>
