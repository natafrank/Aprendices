<?php

	class VehicleModelMdl
	{
		private $id_vehicle_model;
		private $vehicle_model;
		private $id_vehicle_brand;

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

		public function insert($id_vehicle_model, $vehicle_model, $id_vehicle_brand)
		{
			//Escapamos las variables
			$this -> id_vehicle_model = $this -> db_driver -> escape($id_vehicle_model);
			$this -> vehicle_model    = $this -> db_driver -> escape($vehicle_model);
			$this -> id_vehicle_brand = $this -> db_driver -> escape($id_vehicle_brand);

			//Query a ejecutar
			$query = "INSERT INTO VehicleModel VALUES(".$this -> id_vehicle_model.", '"
					  .$this -> vehicle_model."', ".$this -> id_vehicle_brand.");";
			
			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se insertaron los datos correctamente.
			//Retornará falso en caso de no poder insertar.
			return $this -> db_driver -> execute($query);
		} 

		public function delete($id_vehicle_model)
		{
			//Escapamos el id
			$this -> id_vehicle_model = $this -> db_driver -> escape($id_vehicle_model);

			//Query a ejecutar
			$query = "DELETE FROM VehicleModel WHERE idVehicleModel=".$this -> id_vehicle_model.";";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se eliminó el registro correctamente.
			//Retornará falso en caso de no poder eliminar.
			return $this -> db_driver -> execute($query);
		}

		public function select($id_vehicle_model)
		{
			//Escapamos la variable.
			$this -> id_vehicle_model = $this -> db_driver -> escape($id_vehicle_model);

			//Query a ejecutar.
			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM VehicleModel WHERE idVehicleModel=".$this -> id_vehicle_model.";";

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

		public function update($id_vehicle_model, $vehicle_model, $id_vehicle_brand)
		{
			//Escapamos las variables
			$this -> id_vehicle_model = $this -> db_driver -> escape($id_vehicle_model);
			$this -> vehicle_model    = $this -> db_driver -> escape($vehicle_model);
			$this -> id_vehicle_brand = $this -> db_driver -> escape($id_vehicle_brand);
		
			//Query a ejecutar
			$query = "UPDATE VehicleModel SET Model='".$this -> vehicle_model
					."', idVehicleBrand=".$this -> id_vehicle_brand
					." WHERE idVehicleModel=".$this -> id_vehicle_model.";";

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
			$query = "SELECT * FROM VehicleModel WHERE ".$filter.";";

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
		public function getVehicleBrands($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM VehicleBrand WHERE ".$condition.";";

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
		public function getIdVehicleModel()
		{
			return $this -> id_vehicle_model;
		}

		public function getVehicleModel()
		{
			return $this -> vehicle_model;
		}

		public function getIdVehicleBrand()
		{
			return $this -> id_vehicle_brand;
		}
	}

?>
