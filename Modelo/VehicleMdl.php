<?php

	class VehicleMdl
	{
		public $vin;             
		public $marca;        
		public $modelo;         
		public $color;

		public function alta($vin, $marca, $modelo, $color)
		{
			$this -> vin    = $vin;
			$this -> marca  = $marca;
			$this -> modelo = $modelo;
			$this -> color  = $color;

			return TRUE;
		}        
	}

?>