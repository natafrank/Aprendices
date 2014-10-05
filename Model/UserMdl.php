<?php
	class UserMdl
	{
		private $id_user;
		private $name;
		private $login;
		private $pass;
		private $email;
		private $tel;		
		private $id_user_type;

		//CONEXIÓN A LA BASE DE DATOS
		/*************************************************************/
		public $db_driver;

		function __construct()
		{
			//Importamos la capa de la base de datos.
			require("Model/Database Motor/DatabaseLayer.php");

			//Creamos la conexión.
			$this -> db_driver = DatabaseLayer::getConnection("MySqlProvider");
		}
		/*************************************************************/

		public function insert($id_user, $name, $login, $pass, $email, $tel, $id_user_type)
		{
			//Escapamos las variables.
			$this -> id_user      = $this -> db_driver -> escape($id_user);
			$this -> name         = $this -> db_driver -> escape($name);
			$this -> login        = $this -> db_driver -> escape($login);
			$this -> pass         = $this -> db_driver -> escape($pass);
			$this -> email        = $this -> db_driver -> escape($email);
			$this -> tel          = $this -> db_driver -> escape($tel);
			$this -> id_user_type = $this -> db_driver -> escape($id_user_type);

			//Query a ejecutar.
			$query = "INSERT INTO User VALUES(".$this -> id_user.
				", '".$this -> name.
				"', '".$this -> login.
				"', '".$this -> pass.
				"', '".$this -> email.
				"', '".$this -> tel.
				"', ".$this -> id_user_type.");";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se insertaron los datos correctamente.
			//Retornará falso en caso de no poder insertar.
			return $this -> db_driver -> execute($query);
		}

		public function delete($id_user)
		{
			//Escapamos el id.
			$this -> id_user = $this -> db_driver -> escape($id_user);

			//Query a ejecutar.
			$query = "DELETE FROM User WHERE idUser=".$this -> id_user.";";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se eliminó el registro correctamente.
			//Retornará falso en caso de no poder eliminar.
			return $this -> db_driver -> execute($query);
		}

		public function select($id_user)
		{
			//Escapamos el id.
			$this -> id_user = $this -> db_driver -> escape($id_user);

			//Query a ejecutar
			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM User WHERE idUser=".$this -> id_user.";";

			//Ejecutamos el query y recogemos el resultado.
			$result = $this -> db_driver -> execute($query);

			//Si el resultado no es null, procesamos la información.
			if($result != null)
			{
				//Si el resultado contiene información retornamos el resultado.
				return $result;
			}
			else
			{
				//Si el resultado es null, retornamos FALSE.
				return FALSE;
			}	
		}

		public function update($id_user, $name, $login, $pass, $email, $tel, $id_user_type)
		{
			//Escapamos las variables.
			$this -> id_user      = $this -> db_driver -> escape($id_user);
			$this -> name         = $this -> db_driver -> escape($name);
			$this -> login        = $this -> db_driver -> escape($login);
			$this -> pass         = $this -> db_driver -> escape($pass);
			$this -> email        = $this -> db_driver -> escape($email);
			$this -> tel          = $this -> db_driver -> escape($tel);
			$this -> id_user_type = $this -> db_driver -> escape($id_user_type);

			//Query a ejecutar.
			$query = "UPDATE User SET User='".$this -> name.
				"', Login='".$this -> login.
				"', Password='".$this -> pass.
				"', Email='".$this -> email.
				"', Tel='".$this -> tel.
				"', idUserType=".$this -> id_user_type.";";

			//Ejecutamos el query y retornamos el resultado.
			//Retornará verdadero si se modificó el registro correctamente.
			//Retornará falso en caso de no poder modificar.
			return $this -> db_driver -> execute($query);
		}

		/******** GETTERS PARA ACCEDER A LA INFORMACIÓN PRIVADA DE LA CLASE **********/
		public function getIdUser()
		{
			return $this -> id_user;
		}

		public function getName()
		{
			return $this -> name;
		}

		public function getLogin()
		{
			return $this -> login;
		}

		public function getPass()
		{
			return $this -> pass;
		}

		public function getEmail()
		{
			return $this -> email;
		}

		public function getTel()
		{
			return $this -> tel;
		}

		public function getIdUserType()
		{
			return $this -> id_user_type;
		}
	}
?>
