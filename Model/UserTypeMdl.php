<?php

	class UserTypeMdl
	{
		public $id_user_type;
		public $user_type;

		public function insert($user_type)
		{
			$this -> user_type = $user_type;

			return TRUE;
		}

		public function delete($id_user_type)
		{
			$this -> id_user_type = $id_user_type;

			/*Eliminamos la marca de vehículo de la base de datos y retornamos TRUE*/
			return TRUE;

			/*Si hay un error al momento de realizar la eliminación retornamos FALSE*/
			//return FALSE;
		}

		public function select($id_user_type)
		{
			$this -> id_user_type = $id_user_type;

			//Se accede a la base de datos por medio del id_user_type
			//y en base a esta consulta se asignan los demás atributos.
			$this -> user_type = "tipo_usuario_prueba";

			//Si la consulta fue éxitosa retornamos TRUE
			return TRUE;

			//sino FALSE
			//return FALSE;
		}

		public function update()
		{
			//Se accede a la base de datos por medio del id_user_type
			//y en base a esta consulta se podrán modificar los demás atributos.
			$this -> user_type = "tipo_usuario_modificado";
		}
	}

?>