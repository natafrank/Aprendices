<?php
	class VehicleStatusMdl
	{
		
		private $idVehicleStatus;
		private $vehicleStatus;
		private $Fuel;
		private $Km;

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


		public function insert($idVehicleStatus, $vehicleStatus, $Fuel, $Km)
		{
			//Escapamos las variables.
			$this->idVehicleStatus 	= $this->db_driver->escape($idVehicleStatus);
			$this->vehicleStatus	= $this->db_driver->escape($vehicleStatus);
			$this->Fuel 			= $this->db_driver->escape($Fuel);
			$this->Km 				= $this->db_driver->escape($Km);
			
			//Query a ejecutar.
			$query = "INSERT INTO VehicleStatus VALUES(".$this -> idVehicleStatus.", '"
												 	.$this -> vehicleStatus."', "
												 	.$this -> Fuel.", "
													.$this -> Km.");";
	
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
		
		public function delete($idVehicleStatus)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this->idVehicleStatus = $this->db_driver->escape($idVehicleStatus);

			//Query a ejecutar
			$query = "DELETE FROM VehicleStatus WHERE idVehicleStatus=".$this->idVehicleStatus.";";

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
		
		public function update($idVehicleStatus, $vehicleStatus, $Fuel, $Km)
		{
			//Escapamos las variables.
			$this->idVehicleStatus 	= $this->db_driver->escape($idVehicleStatus);
			$this->vehicleStatus	= $this->db_driver->escape($vehicleStatus);
			$this->Fuel 			= $this->db_driver->escape($Fuel);
			$this->Km 				= $this->db_driver->escape($Km);
			
			//Query que realizará la modificación.
			$query = "UPDATE VehicleStatus SET VehicleStatus='".$this -> vehicleStatus."' ,"
										."Fuel=".$this -> Fuel.", "
										."Km=".$this -> Km.
					  " WHERE idVehicleStatus=".$this -> idVehicleStatus.";";

		  	//Ejecutamos el query.
		  	$result = $this->db_driver->execute($query);

		  	return $result;
		}
		
		public function select($idVehicleStatus)
		{
			//Escapamos la variable.
			$this->idVehicleStatus = $this->db_driver->escape($idVehicleStatus);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM VehicleStatus WHERE idVehicleStatus=".$this -> idVehicleStatus.";";

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
			$query = "SELECT * FROM VehicleStatus WHERE ".$filter.";";

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
		public function getIdVehicleStatus()
		{
			return $this -> idVehicleStatus;
		}

		public function getVehicleStatus()
		{
			return $this -> vehicleStatus;
		}
		
		public function getFuel()
		{
			return $this -> Fuel;
		}

		public function getKm()
		{
			return $this -> Km;
		}

	}
?>
