<?php

	class VehicleModelMdl
	{
		public $id_vehicle_model;
		public $vehicle_model;
		public $id_vehicle_brand;

		public function insert($vehicle_model, $id_vehicle_brand)
		{
			$this -> vehicle_model    = $vehicle_model;
			$this -> id_vehicle_brand = $id_vehicle_brand;

			return TRUE;
		} 

		public function delete($id_vehicle_model)
		{
			$this -> id_vehicle_model = $id_vehicle_model;

			/*Eliminamos el modelo vehículo de la base de datos y retornamos TRUE*/
			return TRUE;

			/*Si hay un error al momento de realizar la eliminación retornamos FALSE*/
			//return FALSE;
		}

		public function select($id_vehicle_model)
		{
			$this -> id_vehicle_model = $id_vehicle_model;

			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se asignan los demás atributos.
			$this -> vehicle_model    = "modelo_prueba";
			$this -> id_vehicle_brand = "id_marca_prueba";

			//Si la consulta fue éxitosa retornamos TRUE
			return TRUE;

			//sino FALSE
			//return FALSE;
		}

		public function update()
		{
			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se podrán modificar los demás atributos.
			$this -> vehicle_model    = "modelo_modificado";
			$this -> id_vehicle_brand = "id_marca_modificado";
		}
	}

?>