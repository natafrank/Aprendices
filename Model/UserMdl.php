<?php
	class UserMdl
	{
		public $id;
		public $name;
		public $login;
		public $pass;
		public $type;		

		public function insert($name,$login,$pass,$type)
		{
			$this -> name  = $name;
			$this -> login = $login;
			$this -> pass  = $pass;
			$this -> type  = $type;
	
			return TRUE;		
		} /* fin alta*/

		public function delete($id)
		{
			$this -> id = $id;

			/*Eliminamos el usuario de la base de datos y retornamos TRUE*/
			return TRUE;

			/*Si hay un error al momento de realizar la eliminación retornamos FALSE*/
			//return FALSE;
		}

		public function show($id)
		{
			$this -> id = $id;

			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se asignan los demás atributos.
			$this -> name  = "name_prueba";
			$this -> login = "login_prueba";
			$this -> pass  = "pass_prueba";
			$this -> type  = "type_prueba";

			//Si la consulta fue éxitosa retornamos TRUE
			return TRUE;

			//sino FALSE
			//return FALSE;
		}

		public function update()
		{
			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se podrán modificar los demás atributos.
			$this -> name  = "name_modificado";
			$this -> login = "login_modificado";
			$this -> pass  = "pass_modificado";
			$this -> type  = "type_modificado";
		}
	}
?>
