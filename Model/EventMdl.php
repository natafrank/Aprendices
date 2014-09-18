<?php

class EventRegistryMdl{

	public $idEvent;
	public $Event;
	
	public function insert($idEvent, $Event){
		$this->idEvent = $idEvent;
		$this->Event = $Event;
		return TRUE;
	}
	public function select(){
		return TRUE;
	}
	public function delete($idEvent){
		return TRUE;
	}
	
	public function update($idEvent,$Event){
		$this->idEvent = $idEvent;
		$this->Event = $Event;
		
		return TRUE;
	}

}

?>
