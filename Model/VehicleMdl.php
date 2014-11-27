<?php

	class VehicleMdl
	{
		private $id_vehicle;
		private $id_user;
		private $vin;                 
		private $id_vehicle_model;         
		private $id_location;
		private $color;

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

		public function insert($id_vehicle, $id_user, $id_location, $id_vehicle_model, $vin, $color)
		{
			//Escapamos las variables.
			$this -> id_vehicle       = $this -> db_driver -> escape($id_vehicle);
			$this -> id_user          = $this -> db_driver -> escape($id_user);
			$this -> id_location      = $this -> db_driver -> escape($id_location);
			$this -> id_vehicle_model = $this -> db_driver -> escape($id_vehicle_model);
			$this -> vin              = $this -> db_driver -> escape($vin);
			$this -> color            = $this -> db_driver -> escape($color);

			//Query a ejecutar.
			$query = "INSERT INTO Vehicle VALUES(".$this -> id_vehicle.
				", ".$this -> id_user.
				", ".$this -> id_location.
				", ".$this -> id_vehicle_model.
				", '".$this -> vin.
				"', '".$this -> color."');";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se insertaron los datos correctamente.
			//Retornará falso en caso de no poder insertar.
			return $this -> db_driver -> execute($query);
		} 

		public function delete($id_vehicle)
		{
			//Escapamos el id.
			$this -> id_vehicle = $this -> db_driver -> escape($id_vehicle);

			//Query a ejecutar.
			$query = "DELETE FROM Vehicle WHERE idVehicle=".$this -> id_vehicle.";";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se eliminó el registro correctamente.
			//Retornará falso en caso de no poder eliminar.
			return $this -> db_driver -> execute($query);
		}

		public function select($id_vehicle)
		{
			//Escapamos el id.
			$this -> id_vehicle = $this -> db_driver -> escape($id_vehicle);

			//Query a ejecutar
			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM Vehicle WHERE idVehicle=".$this -> id_vehicle.";";

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

		public function update($id_vehicle, $id_user, $id_location, $id_vehicle_model, $vin, $color)
		{
			//Escapamos las variables.
			$this -> id_vehicle       = $this -> db_driver -> escape($id_vehicle);
			$this -> id_user          = $this -> db_driver -> escape($id_user);
			$this -> id_location      = $this -> db_driver -> escape($id_location);
			$this -> id_vehicle_model = $this -> db_driver -> escape($id_vehicle_model);
			$this -> vin              = $this -> db_driver -> escape($vin);
			$this -> color            = $this -> db_driver -> escape($color);

			//Query a ejecutar.
			$query = "UPDATE Vehicle SET idUser=".$this-> id_user.
				", idLocation=".$this-> id_location.
				", idVehicleModel=".$this -> id_vehicle_model.
				", vin='".$this -> vin.
				"', color='".$this -> color.
				"' WHERE idVehicle = ".$id_vehicle.";";

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
			$query = "SELECT * FROM VVehicle WHERE ".$filter.";";

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
		 * Funcion para obtener las usuarios.
		 *
		 * Obtiene todos los registros de la tabla User.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getUsers($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM User WHERE ".$condition.";";

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
		 * Funcion para obtener las ubicaciones de vehiculos.
		 *
		 * Obtiene todos los registros de la tabla Location.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getLocations($condition)
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

		/**
		 * Funcion para obtener los modelos de vehiculos.
		 *
		 * Obtiene todos los registros de la tabla VehicleModel.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getVehicleModels($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM VehicleModel WHERE ".$condition.";";

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
		 * Funcion para obtener los estatus de vehículo.
		 *
		 * Obtiene todos los registros de la tabla VehicleStatus.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getVehiclesStatus($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM VehicleStatus WHERE ".$condition.";";

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
		public function getIdVehicle()
		{
			return $this -> id_vehicle;
		}

		public function getIdUser()
		{
			return $this -> id_user;
		}

		public function getIdLocation()
		{
			return $this -> id_location;
		}

		public function getIdVehicleModel()
		{
			return $this -> id_vehicle_model;
		}

		public function getVin()
		{
			return $this -> vin;
		}   

		public function getColor()
		{
			return $this -> color;
		}
	}

?>
