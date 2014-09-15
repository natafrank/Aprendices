<?php
	class VehiclePartMdl{
		public $idVehiclePart;
		public $VehiclePart;		

		public function insert($idVehiclePart,$VehiclePart){
			$this->idVehiclePart = $idVehiclePart;
			$this->VehiclePart = $VehiclePart;
	
			return TRUE;		
		} /* fin alta*/
		
		public function delete(&$rows,$idVehiclePart){
			//Eliminar el registro
			unset($rows[$idVehiclePart]);
			
			return TRUE;			
		}
		
		public function update(&$rows,$VehiclePart){
			//Editar el registro
			if(isset($VehiclePart)){
				$rows['VehiclePart'] = $VehiclePart;
			}
			
			return TRUE;
		}
		
		public function select(){
			//Poner filtros para la seleccion de los registros que se mostrarán		
		}

	}
?>
