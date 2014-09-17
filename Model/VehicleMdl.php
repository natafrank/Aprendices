<?php

	class VehicleMdl
	{
		public $id;
		public $id_location;
		public $id_vehicle_model;         
		public $vin;                 
		public $color;

		public function insert($vin, $id_location, $id_vehicle_model, $vin, $color)
		{
			$this -> vin              = $vin;
			$this -> id_location      = $id_location;
			$this -> id_vehicle_model = $id_vehicle_model;
			$this -> vin              = $vin;
			$this -> color            = $color;

			return TRUE;
		} 

		public function delete($id)
		{
			$this -> id = $id;

			/*Eliminamos el vehículo de la base de datos y retornamos TRUE*/
			return TRUE;

			/*Si hay un error al momento de realizar la eliminación retornamos FALSE*/
			//return FALSE;
		}

		public function show($id)
		{
			$this -> id = $id;

			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se asignan los demás atributos.
			$this -> vin              = "vin_prueba";
			$this -> id_location      = "id_location_prueba";
			$this -> id_vehicle_model = "id_vehicle_model_prueba";
			$this -> vin              = "vin_prueba";
			$this -> color            = "color_prueba";

			//Si la consulta fue éxitosa retornamos TRUE
			return TRUE;

			//sino FALSE
			//return FALSE;
		}

		public function update()
		{
			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se podrán modificar los demás atributos.
			$this -> vin              = "vin_modificado";
			$this -> id_location      = "id_location_modificado";
			$this -> id_vehicle_model = "id_vehicle_model_modificado";
			$this -> vin              = "vin_modificado";
			$this -> color            = "color_modificado";
		}       
	}

?>
