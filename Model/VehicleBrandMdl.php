<?php
	class VehicleBrandMdl{
		private $id_vehicle_brand;
		private $vehicle_brand;
		
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

		public function insert($id_vehicle_brand,$vehicle_brand)
		{
			//Escapamos las variables.
			$this -> id_vehicle_brand  = $this -> db_driver -> escape($id_vehicle_brand);
			$this -> vehicle_brand    = $this -> db_driver -> escape($vehicle_brand);

			//Query a ejecutar.
			$query = "INSERT INTO VehicleBrand VALUES(".$this -> id_vehicle_brand.", '"
												 	   .$this -> vehicle_brand."');";
	
			//Ejecutamos el query.
			if($this -> db_driver -> execute($query))
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
		
		public function delete($id_vehicle_brand)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> id_vehicle_brand= $this -> db_driver -> escape($id_vehicle_brand);

			//Query a ejecutar
			$query = "DELETE FROM VehicleBrand WHERE idVehicleBrand=".$this -> id_vehicle_brand.";";

			//Ejecutamos el query
			if($this -> db_driver -> execute($query))
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
		
		public function update($id_vehicle_brand,$vehicle_brand)
		{
			//Escapamos las variables.
			$this -> id_vehicle_brand  = $this -> db_driver -> escape($id_vehicle_brand);
			$this -> vehicle_brand     = $this -> db_driver -> escape($vehicle_brand);

			//Query que realizará la modificación.
			$query = "UPDATE VehicleBrand SET Brand='".$this -> vehicle_brand."'" 
					  ." WHERE idVehicleBrand=".$this -> id_vehicle_brand.";";

		  	//Ejecutamos el query.
		  	$result = $this -> db_driver -> execute($query);
	
		  	return $result;
		}
		
		public function select($id_vehicle_brand)
		{
			//Escapamos la variable.
			$this -> id_vehicle_brand = $this -> db_driver -> escape($id_vehicle_brand);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM VehicleBrand WHERE idVehicleBrand=".$this -> id_vehicle_brand.";";

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
			$query = "SELECT * FROM VehicleBrand WHERE ".$filter.";";

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

		public function getIdVehicleBrand()
		{
			return $this -> id_vehicle_brand;
		}

		public function getVehicleBrand()
		{
			return $this -> vehicle_brand;
		}
	}
?>
