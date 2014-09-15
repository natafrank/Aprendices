<?php
	class ChecklistMdl{
		public $idChecklist;
		public $idVehicle;
		public $idVehicleStatus;
		public $Date;
		public $InOut;		

		public function insert($idChecklist,$idVehicle,$idVehicleStatus,$Date,$InOut){
			$this->idChecklist = $idChecklist;
			$this->idVehicle = $idVehicle;
			$this->idVehicleStatus= $idVehicleStatus;
			$this->Date = $Date;
			$this->InOut = $InOut;
	
			return TRUE;		
		} /* fin alta*/
		
		public function delete(&$rows,$idChecklist){
			//Eliminar el registro
			unset($rows[$idChecklist]);
			
			return TRUE;			
		}
		
		public function update(&$rows,$idVehicle,$idVehicleStatus,$Date,$InOut){
			//Editar el registro
			if(isset($idVehicle)){
				$rows['idVehicle'] = $idVehicle;
			}
			if(isset($idVehicleStatus)){
				$rows['idVehicleStatus'] = $idVehicleStatus;
			}
			if(isset($Date)){
				$rows['Date'] = $Date;
			}
			if(isset($InOut)){
				$rows['InOut'] = $InOut;
			}
			
			return TRUE;
		}
		
		public function select(){
			//Poner filtros para la seleccion de los registros que se mostrarán		
		}

	}
?>
