<?php
	class EventRegistryMdl
	{
		
		private $idEventRegistry;
		private $idVehicle;
		private $idUser;
		private $idEvent;
		private $Date;
		private $Reason;

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


		public function insert($idEventRegistry, $idVehicle, $idUser, $idEvent, $Date, $Reason)
		{
			//Escapamos las variables.
			$this->idEventRegistry = $this->db_driver->escape($idEventRegistry);
			$this->idVehicle	   = $this->db_driver->escape($idVehicle);
			$this->idUser 		   = $this->db_driver->escape($idUser);
			$this->idEvent 		   = $this->db_driver->escape($idEvent);
			$this->Date 		   = $this->db_driver->escape($Date);
			$this->Reason 		   = $this->db_driver->escape($Reason);
			
			//Query a ejecutar.
			$query = "INSERT INTO EventRegistry VALUES(".$this -> idEventRegistry.", "
												 	.$this -> idVehicle.", "
												 	.$this -> idUser.", "
												 	.$this -> idEvent.", "
												 	.$this -> Date.", "
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
		
		public function delete($idEventRegistry)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this->idEventRegistry = $this->db_driver->escape($idEventRegistry);

			//Query a ejecutar
			$query = "DELETE FROM EventRegistry WHERE idEventRegistry=".$this->idEventRegistry.";";

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
		
		public function update($idEventRegistry, $idVehicle, $idUser, $idEvent, $Reason)
		{
			//Escapamos las variables.
			$this->idEventRegistry = $this->db_driver->escape($idEventRegistry);
			$this->idVehicle	   = $this->db_driver->escape($idVehicle);
			$this->idUser 		   = $this->db_driver->escape($idUser);
			$this->idEvent 		   = $this->db_driver->escape($idEvent);
			$this->Date 		   = $this->db_driver->escape($Date);
			$this->Reason 		   = $this->db_driver->escape($Reason);
			
			//Query que realizará la modificación.
			$query = "UPDATE EventRegistry SET idVehicle=".$this -> idVehicle." "
										."idUser=".$this -> idUser." "
										."idEvent=".$this -> idEvent." "
										."Reason=".$this -> Reason.
					  " WHERE idEventRegistry=".$this -> idEventRegistry.";";

		  	//Ejecutamos el query.
		  	$result = $this->db_driver->execute($query);

		  	return $result;
		}
		
		public function select($idEventRegistry)
		{
			//Escapamos la variable.
			$this->idEventRegistry = $this->db_driver->escape($idEventRegistry);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM EventRegistry WHERE idEventRegistry=".$this->idEventRegistry.";";

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
			$query = "SELECT * FROM VEventRegistry WHERE ".$filter.";";

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
		 * Funcion para obtener los eventos.
		 *
		 * Obtiene todos los registros de la tabla VehicleStatus.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getEvents($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM Event WHERE ".$condition.";";

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
		public function getIdEventRegistry()
		{
			return $this -> idEventRegistry;
		}

		public function getIdVehicle()
		{
			return $this -> idVehicle;
		}
		
		public function getIdUser()
		{
			return $this -> idUser;
		}

		public function getIdEvent()
		{
			return $this -> idEvent;
		}

		public function getDate()
		{
			return $this -> Date;
		}

		public function getReason()
		{
			return $this -> Reason;
		}

	}
?>
