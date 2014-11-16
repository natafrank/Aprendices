<?php

	class UserTypeMdl
	{
		private $id_user_type;
		private $user_type;

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

		public function insert($id_user_type, $user_type)
		{
			//Escampamos las variables
			$this -> id_user_type = $this -> db_driver -> escape($id_user_type);
			$this -> user_type    = $this -> db_driver -> escape($user_type);

			//Query a ejecutar
			$query = "INSERT INTO UserType VALUES(".$this -> id_user_type.
					", '".$this -> user_type."');";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se insertaron los datos correctamente.
			//Retornará falso en caso de no poder insertar.
			return $this -> db_driver -> execute($query);
		}

		public function delete($id_user_type)
		{
			//Escapamos la variable
			$this -> id_user_type = $this -> db_driver -> escape($id_user_type);

			//Query a ejecutar
			$query = "DELETE FROM UserType WHERE idUserType=".$this -> id_user_type.";";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se eliminó el registro correctamente.
			//Retornará falso en caso de no poder eliminar.
			return $this -> db_driver -> execute($query);
		}

		public function select($id_user_type)
		{
			//Escapamos la variable
			$this -> id_user_type = $this -> db_driver -> escape($id_user_type);

			//Query a ejecutar
			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM UserType WHERE idUserType=".$this -> id_user_type.";";

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

		public function update($id_user_type, $user_type)
		{
			//Escapamos las variables
			$this -> id_user_type = $this -> db_driver -> escape($id_user_type);
			$this -> user_type    = $this -> db_driver -> escape($user_type);

			//Query a ejecutar
			$query = "UPDATE UserType SET UserType='".$this -> user_type."'
					 WHERE idUserType=".$this -> id_user_type.";";

		 	//Ejecutamos el query y retornamos el resultado.
			//Retornará verdadero si se modificó el registro correctamente.
			//Retornará falso en caso de no poder modificar.
			return $this -> db_driver -> execute($query);
		}

				/**
		 * Funcion de Listado.
		 *
		 * Obtiene todos los registros de la tabla.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getList($filter)
		{
			//Query a ejecutar
			$query = "SELECT * FROM UserType WHERE ".$filter.";";

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

		/******** GETTERS PARA ACCEDER A LA INFORMACIÓN PRIVADA DE LA CLASE **********/
		public function getIdUserType()
		{
			return $this -> id_user_type;
		}

		public function getUserType()
		{
			return $this -> user_type;
		}
	}

?>