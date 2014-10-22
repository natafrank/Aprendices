<?php
	class VehiclePartMdl
	{
		/**
		 * Variables de los Campos de la tabla VehiclePart.
		 *
		 * @access private
		 * @var int $idVehiclePart - Llave primaria de la tabla.
		 * @access private
		 * @var string $VehiclePart - Nombre del daño.
		 */
		private $idVehiclePart;
		private $VehiclePart;
		
		//CONEXIÓN A LA BASE DE DATOS
		/*************************************************************/
		/**
		 * Variable para la conexion con la base de datos.
		 *
		 * @access public
		 * @var MySqlProvider $db_driver.
		 */
		public $db_driver;

		/**
		 * Constructor de la clase.
		 *
		 * Contructor del Modelo en que se crea la conexion con la base de datos.
		 *
		 */
		function __construct()
		{
			//Importamos la capa de la base de datos.
			require("Model/Database Motor/DatabaseLayer.php");

			//Creamos la conexión.
			$this -> db_driver = DatabaseLayer::getConnection("MySqlProvider");
		}
		/*************************************************************/		

		/**
		 * Funcion de Insercion.
		 *
		 * Inserta un nuevo registro en la base de datos.
		 *
		 * @param int $idVehiclePart - Llave primaria de la tabla.
		 * @param string $VehiclePart - Nombre del daño.
		 *
		 * @return bool - TRUE si la inserción se hizo correctamente, FALSE en caso contrario
		 */
		public function insert($idVehiclePart,$VehiclePart)
		{
			//Escapamos las variables.
			$this -> idVehiclePart  = $this -> db_driver -> escape($idVehiclePart);
			$this -> VehiclePart    = $this -> db_driver -> escape($VehiclePart);

			//Query a ejecutar.
			$query = "INSERT INTO VehiclePart VALUES('".$this -> idVehilePart."', "
												 	   .$this -> VehiclePart.");";
	
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
		
		/**
		 * Funcion de Eliminación.
		 *
		 * Elimina un registro de la tabla en la base de datos.
		 *
		 * @param int $idVehiclePart- Llave primaria del registro que se va a eliminar.
		 *
		 * @return bool - TRUE si la eliminación se hizo correctamente, FALSE en caso contrario
		 */
		public function delete($idVehiclePart)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> idVehiclePart = $this -> db_driver -> escape($idVehiclePart);

			//Query a ejecutar
			$query = "DELETE FROM VehiclePart WHERE idVehiclePart=".$this -> idVehiclePart.";";

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
		
		/**
		 * Funcion de Actualización.
		 *
		 * Actualiza un registro en la base de datos.
		 *
		 * @param int $idVehiclePart - Llave primaria de la tabla.
		 * @param string $VehiclePart - Nombre de la parte de vehiculo.
		 *
		 * @return array - Arreglo con los datos actualizados si se actualizaron correctamente.
		 * @return bool FALSE - Si no se actualizó el registro correctamente en la base de datos.
		 */
		public function update($idVehiclePart,$VehiclePart)
		{
			//Escapamos las variables.
			$this -> idVehiclePart  = $this -> db_driver -> escape($idVehiclePart);
			$this -> VehiclePart    = $this -> db_driver -> escape($VehiclePart);

			//Query que realizará la modificación.
			$query = "UPDATE VehiclePart SET VehiclePart='".$this -> VehiclePart."', " 
					  " WHERE idVehiclePart=".$this -> idVehiclePart.";";

		  	//Ejecutamos el query
			if($this -> db_driver -> execute($query))
			{
				//Retornamos los datos si se actualizó el registro correctamente.
				return $result;
			}		
			else
			{
				//Retornamos falso en caso contrario.
				return FALSE;
			}
		}
		
		/**
		 * Funcion de Selección.
		 *
		 * Muestra un registro en la base de datos.
		 *
		 * @param int $idVehiclePart - Llave primaria del registro que se va a mostrar.
		 *
		 * @return array - Arreglo con los datos obtenidos del query.
		 * @return bool FALSE - Si no se obtuvieron datos con el query o si hubo un error.
		 */
		public function select($idVehiclePart)
		{
			//Escapamos la variable.
			$this -> idVehiclePart = $this -> db_driver -> escape($idVehiclePart);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM VehiclePart WHERE idVehiclePart=".$this -> idVehiclePart.";";

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

	}
?>
