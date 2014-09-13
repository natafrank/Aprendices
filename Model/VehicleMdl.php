<?php

	class VehicleMdl
	{
		public $vin;             
		public $brand;        
		public $vehicle_model;         
		public $color;

		public function insert($vin, $brand, $vehicle_model, $color)
		{
			$this -> vin    = $vin;
			$this -> brand  = $brand;
			$this -> vehicle_model = $vehicle_model;
			$this -> color  = $color;

			return TRUE;
		}        
	}

?>
