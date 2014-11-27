<?php
	class LocationMdl
	{
		
		private $idLocation;
		private $location;
		private $idMasterLocation;

		//CONEXIÓN A LA BASE DE DATOS
		/*************************************************************/
		public $db_driver;

		function __construct()
		{
			//Importamos la capa de la base de datos.
			require("Model/Database Motor/DatabaseLayer.php");

			//Creamos la conexión.
			$this->db_driver = DatabaseLayer::getConnection("MySqlProvider");
		}
		/*************************************************************/


		public function insert($idLocation, $location, $idMasterLocation)
		{
			//Escapamos las variables.
			$this->idLocation 		= $this->db_driver->escape($idLocation);
			$this->location	   		= $this->db_driver->escape($location);
			$this->idMasterLocation = $this->db_driver->escape($idMasterLocation);
			
			//Query a ejecutar.
			$query = "INSERT INTO Location VALUES(".$this -> idLocation.", "
												 	.$this -> location.", "
													.$this -> Reason.");";
	
			//Ejecutamos el query.
			if($this->db_driver->execute($query))
			{
				//Retornamos verdadero si se insertaron los datos correctamente.
				return TRUE;
			}		
			else
			{
				//Retornamos falso en caso de no poder insertar.
				return FALSE;
			}
		} /* fin alta*/
		
		public function delete($idLocation)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this->idLocation = $this->db_driver->escape($idLocation);

			//Query a ejecutar
			$query = "DELETE FROM Location WHERE idLocation=".$this->idLocation.";";

			//Ejecutamos el query
			if($this->db_driver->execute($query))
			{
				//Retornamos verdadero si se insertaron los datos correctamente.
				return TRUE;
			}		
			else
			{
				//Retornamos falso en caso de no poder insertar.
				return FALSE;
			}	
		}
		
		public function update($idLocation, $location, $idMasterLocation)
		{
			//Escapamos las variables.
			$this->idLocation 		= $this->db_driver->escape($idLocation);
			$this->location	   		= $this->db_driver->escape($location);
			$this->idMasterLocation = $this->db_driver->escape($idMasterLocation);
			
			//Query que realizará la modificación.
			$query = "UPDATE Location SET location=".$this -> location." "
										."idMasterLocation=".$this -> idMasterLocation.
					  " WHERE idLocation=".$this -> idLocation.";";

		  	//Ejecutamos el query.
		  	$result = $this->db_driver->execute($query);

		  	return $result;
		}
		
		public function select($idLocation)
		{
			//Escapamos la variable.
			$this->idLocation = $this->db_driver->escape($idLocation);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM Location WHERE idLocation=".$this->idLocation.";";

			//Ejecutamos el query y recogemos el resultado.
			$result = $this->db_driver->execute($query);

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
			$query = "SELECT * FROM Location WHERE ".$filter.";";

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

		/**
		 * Funcion para obtener los IDs de MasterUbicación.
		 *
		 * Obtiene todos los registros de la tabla VehicleStatus.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getIdMasterLocations($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM Location WHERE ".$condition.";";

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
		public function getIdLocation()
		{
			return $this -> idLocation;
		}

		public function getLocation()
		{
			return $this -> location;
		}
		
		public function getIdMasterLocation()
		{
			return $this -> idMasterLocation;
		}

	}
?>
