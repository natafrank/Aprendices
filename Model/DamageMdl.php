<?php
	class DamageMdl{
		public $idDamage;
		public $Damage;		

		public function insert($idDamage,$Damage){
			$this->idDamage = $idDamage;
			$this->Damage = $Damage;
	
			return TRUE;		
		} /* fin alta*/
		
		public function delete(&$rows,$idDamage){
			//Eliminar el registro
			unset($rows[$idDamage]);
			
			return TRUE;			
		}
		
		public function update(&$rows,$Damage){
			//Editar el registro
			if(isset($Damage)){
				$rows['Damage'] = $Damage;
			}
			
			return TRUE;
		}
		
		public function select(){
			//Poner filtros para la seleccion de los registros que se mostrarán		
		}

	}
?>
