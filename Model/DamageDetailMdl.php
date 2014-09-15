<?php
	class DamageDetailMdl{
		public $idDamageDetail;
		public $idCheklist;
		public $idVehiclePart;
		public $idDamage;	

		public function insert($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage){
			$this->idDamageDetail = $idDamageDetail;
			$this->idChecklist = $idChecklist;
			$this->idVehiclePart = $idVehiclePart;
			$this->idDamage = $idDamage;
	
			return TRUE;		
		} /* fin alta*/
		
		public function delete(&$rows,$idDamageDetail){
			//Eliminar el registro
			unset($rows[$idDamageDetail]);
			
			return TRUE;			
		}
		
		public function update(&$rows,$idChecklist,$idVehiclePart,$idDamage){
			//Editar el registro
			if(isset($idChecklist)){
				$rows['idChecklist'] = $idChecklist;
			}
			if(isset($idVehiclePart)){
				$rows['idVehiclePart'] = $idVehiclePart;
			}
			if(isset($idDamage)){
				$rows['idDamage'] = $idDamage;
			}
			
			return TRUE;
		}
		
		public function select(){
			//Poner filtros para la seleccion de los registros que se mostrarán		
		}

	}
?>
