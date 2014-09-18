<?php

class EventRegistryMdl{

	public $idEventRegistry;
	public $idVehicle;
	public $idUser;
	public $idEvent;
	public $Date;
	public $Motiv;
	
	public function insert($idEventRegistry, $idVehicle, $idUser,$idEvent,$Date,$Motiv){
		$this->idEventRegistry = $idEventRegistry;
		$this->idVehicle = $idVehicle;
		$this->idUser = $idUser;
		$this->idEvent = $idEvent;
		$this->Date = $Date;
		$this->Motiv = $Motiv;
		
		return TRUE;
	}
	public function select($idEventRegistry){
		return TRUE;
	}
	public function delete($idEventRegistry){
		return TRUE;
	}
	
	public function update($idEventRegistry, $idVehicle, $idUser,$idEvent,$Date,$Motiv){
		$this->idEventRegistry = $idEventRegistry;
		$this->idVehicle = $idVehicle;
		$this->idUser = $idUser;
		$this->idEvent = $idEvent;
		$this->Date = $Date;
		$this->Motiv = $Motiv;
		
		return TRUE;
	}

}

?>
