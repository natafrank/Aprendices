<?php

	class VehicleBrandMdl
	{
		public $id;
		public $brand;

		public function insert($brand)
		{
			$this -> brand = $brand;

			return TRUE;
		}

		public function delete($id)
		{
			$this -> id = $id;

			/*Eliminamos la marca de vehículo de la base de datos y retornamos TRUE*/
			return TRUE;

			/*Si hay un error al momento de realizar la eliminación retornamos FALSE*/
			//return FALSE;
		}

		public function show($id)
		{
			$this -> id = $id;

			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se asignan los demás atributos.
			$this -> brand = "marca_prueba";

			//Si la consulta fue éxitosa retornamos TRUE
			return TRUE;

			//sino FALSE
			//return FALSE;
		}

		public function update()
		{
			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se podrán modificar los demás atributos.
			$this -> brand = "marca_modificada";
		}
	}

?>