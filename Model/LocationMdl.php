<?php

class LocationMdl{

	public $idLocation;
	public $location;
	public $idMasterLocation;
	
	public function insert($idLocation, $location, $idMasterLocation){
		$this->idLocation = $idLocation;
		$this->location = $location;
		$this->idMasterLocation = $idMasterLocation;
		
		return TRUE;
	}
	public function select($idLocation){
		return TRUE;
	}
	public function delete($idLocation){
		return TRUE;
	}
	
	public function update($idLocation, $location, $idMasterLocation){
		$this->idLocation = $idLocation;
		$this->location = $location;
		$this->idMasterLocation = $idMasterLocation;
		
		return TRUE;
	}

}

?>
