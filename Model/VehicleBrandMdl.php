<?php

	class VehicleBrandMdl
	{
		public $id_vehicle_brand;
		public $vehicle_brand;

		public function insert($vehicle_brand)
		{
			$this -> vehicle_brand = $vehicle_brand;

			return TRUE;
		}

		public function delete($id_vehicle_brand)
		{
			$this -> id_vehicle_brand = $id_vehicle_brand;

			/*Eliminamos la marca de vehículo de la base de datos y retornamos TRUE*/
			return TRUE;

			/*Si hay un error al momento de realizar la eliminación retornamos FALSE*/
			//return FALSE;
		}

		public function select($id_vehicle_brand)
		{
			$this -> id_vehicle_brand = $id_vehicle_brand;

			//Se accede a la base de datos por medio del id_vehicle_brand
			//y en base a esta consulta se asignan los demás atributos.
			$this -> vehicle_brand = "marca_prueba";

			//Si la consulta fue éxitosa retornamos TRUE
			return TRUE;

			//sino FALSE
			//return FALSE;
		}

		public function update()
		{
			//Se accede a la base de datos por medio del id_vehicle_brand
			//y en base a esta consulta se podrán modificar los demás atributos.
			$this -> vehicle_brand = "marca_modificada";
		}
	}

?>