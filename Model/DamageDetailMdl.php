<?php
	class DamageDetailMdl
	{
		/**
		 * Variables de los Campos de la tabla DamageDetail.
		 *
		 * @access private
		 * @var int $idDamageDetail - Llave primaria de la tabla.
		 * @access private
		 * @var int $idChecklist - Llave foranea a la tabla Checklist.
		 * @access private
		 * @var int $idVehiclePart - Llave foranea a la tabla VehiclePart.
		 * @access private
		 * @var int $idDamage - Llave foranea a la tabla Damage.
		 */
		private $idDamageDetail;
		private $idCheklist;
		private $idVehiclePart;
		private $idDamage;
		
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
		 * @param int $idDamageDetail - Llave primaria de la tabla.
		 * @param int $idChecklist - Llave foranea a la tabla Checklist.
		 * @param int $idVehiclePart - Llave foranea a la tabla VehiclePart.
		 * @param int $idDamage - Llave foranea a la tabla Damage.
		 *
		 * @return bool - TRUE si la inserción se hizo correctamente, FALSE en caso contrario
		 */
		public function insert($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage)
		{
			//Escapamos las variables.
			$this -> idDamageDetail = $this -> db_driver -> escape($idDamageDetail);
			$this -> idChecklist    = $this -> db_driver -> escape($idChecklist);
			$this -> idVehiclePart  = $this -> db_driver -> escape($idVehiclePart);
			$this -> idDamage       = $this -> db_driver -> escape($idDamage);

			//Query a ejecutar.
			$query = "INSERT INTO DamageDetail VALUES(".$this -> idDamageDetail.", "
												 	   .$this -> idChecklist.", "
												 	   .$this -> idVehiclePart.", "
												 	   .$this -> idDamage.");";
	
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
		 * @param int $idDamageDetail - Llave primaria del registro que se va a eliminar.
		 *
		 * @return bool - TRUE si la eliminación se hizo correctamente, FALSE en caso contrario
		 */
		public function delete($idDamageDetail)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> idDamageDetail = $this -> db_driver -> escape($idDamageDetail);

			//Query a ejecutar
			$query = "DELETE FROM DamageDetail WHERE idDamageDetail=".$this -> idDamageDetail.";";

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
		 * @param int $idDamageDetail - Llave primaria del registro a modificar.
		 * @param int $idChecklist - Llave foranea a la tabla Checklist.
		 * @param int $idVehiclePart - Llave foranea a la tabla VehiclePart.
		 * @param int $idDamage - Llave foranea a la tabla Damage.
		 *
		 * @return array - Arreglo con los datos actualizados si se actualizaron correctamente.
		 * @return bool FALSE - Si no se actualizó el registro correctamente en la base de datos.
		 */
		public function update($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage)
		{
			//Escapamos las variables.
			$this -> idDamageDetail = $this -> db_driver -> escape($idDamageDetail);
			$this -> idChecklist    = $this -> db_driver -> escape($idChecklist);
			$this -> idVehiclePart  = $this -> db_driver -> escape($idVehiclePart);
			$this -> idDamage       = $this -> db_driver -> escape($idDamage);

			//Query que realizará la modificación.
			$query = "UPDATE DamageDetail SET idChecklist=".$this -> idChecklist.", "
									   	 ."idVehiclePart=".$this -> idVehiclePart.", "
									   	 ."idDamage=".$this -> idDamage.   
					  " WHERE idDamageDetail=".$this -> idDamageDetail.";";

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
		 * @param int $idDamageDetail - Llave primaria del registro que se va a mostrar.
		 *
		 * @return array - Arreglo con los datos obtenidos del query.
		 * @return bool FALSE - Si no se obtuvieron datos con el query o si hubo un error.
		 */
		public function select($idDamageDetail)
		{
			//Escapamos la variable.
			$this -> idDamageDetail = $this -> db_driver -> escape($idDamageDetail);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM DamageDetail WHERE idDamageDetail=".$this -> idDamageDetail.";";

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
