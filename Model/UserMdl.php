<?php
	class UserMdl
	{
		public $id_user;
		public $name;
		public $login;
		public $pass;
		public $type;
		public $email;
		public $tel;		

		public function insert($name,$login,$pass,$type,$email,$tel)
		{
			$this -> name  = $name;
			$this -> login = $login;
			$this -> pass  = $pass;
			$this -> type  = $type;
			$this -> email = $email;
			$this -> tel   = $tel;
	
			return TRUE;		
		} /* fin alta*/

		public function delete($id_user)
		{
			$this -> id_user = $id_user;

			/*Eliminamos el usuario de la base de datos y retornamos TRUE*/
			return TRUE;

			/*Si hay un error al momento de realizar la eliminación retornamos FALSE*/
			//return FALSE;
		}

		public function select($id_user)
		{
			$this -> id_user = $id_user;

			//Se accede a la base de datos por medio del id
			//y en base a esta consulta se asignan los demás atributos.
			$this -> name  = "name_prueba";
			$this -> login = "login_prueba";
			$this -> pass  = "pass_prueba";
			$this -> type  = "type_prueba";
			$this -> email = "email_prueba";
			$this -> tel   = "tel_prueba";
			

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
			$this -> email = "email_modificado";
			$this -> tel   = "tel_modificado";
		}
	}
?>
