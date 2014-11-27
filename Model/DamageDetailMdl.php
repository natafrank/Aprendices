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
		 * @access private
		 * @var int $DamageSeverity - Numero que indica la severidad del daño siendo 1 el mínimo y 5 el máximo.
		 */
		private $idDamageDetail;
		private $idCheklist;
		private $idVehiclePart;
		private $idDamage;		
		private $DamageSeverity;
		
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
		 * @param int $DamageSeverity - Severidad del daño.
		 *
		 * @return array - con el registro insertado si se inserto correctamente
		 * @return bool - FALSE si falló la inserción
		 */
		public function insert($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage,$DamageSeverity)
		{
			//Escapamos las variables.
			$this -> idDamageDetail = $this -> db_driver -> escape($idDamageDetail);
			$this -> idChecklist    = $this -> db_driver -> escape($idChecklist);
			$this -> idVehiclePart  = $this -> db_driver -> escape($idVehiclePart);
			$this -> idDamage       = $this -> db_driver -> escape($idDamage);
			$this -> DamageSeverity = $this -> db_driver -> escape($DamageSeverity);

			//Query a ejecutar.
			$query = "INSERT INTO DamageDetail VALUES(".$this -> idDamageDetail.", "
												 	   .$this -> idChecklist.", "
												 	   .$this -> idVehiclePart.", "
												 	   .$this -> idDamage.", "
												 	   .$this -> DamageSeverity.");";
	
			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se insertaron los datos correctamente.
			//Retornará falso en caso de no poder insertar.
			return $this -> db_driver -> execute($query);
		}
		
		/**
		 * Funcion de Eliminación.
		 *
		 * Elimina un registro de la tabla en la base de datos.
		 *
		 * @param int $idDamageDetail - Llave primaria del registro que se va a eliminar.
		 *
		 * @return array - con el registro eliminado si se inserto correctamente
		 * @return bool - FALSE si falló la eliminacion
		 */
		public function delete($idDamageDetail)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> idDamageDetail = $this -> db_driver -> escape($idDamageDetail);

			//Query a ejecutar
			$query = "DELETE FROM DamageDetail WHERE idDamageDetail=".$this -> idDamageDetail.";";

			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se eliminó el registro correctamente.
			//Retornará falso en caso de no poder eliminar.
			return $this -> db_driver -> execute($query);
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
			$this -> DamageSeverity = $this -> db_driver -> escape($DamageSeverity);

			//Query que realizará la modificación.
			$query = "UPDATE DamageDetail SET idChecklist=".$this -> idChecklist.", "
									   	 ."idVehiclePart=".$this -> idVehiclePart.", "
									   	 ."idDamage=".$this -> idDamage.", "
									   	 ."DamageSeverity=".$this -> DamageSeverity.   
					  " WHERE idDamageDetail=".$this -> idDamageDetail.";";

		  	//Ejecutamos el query y retornamos el resultado.
			//Retornará verdadero si se modificó el registro correctamente.
			//Retornará falso en caso de no poder modificar.
			return $this -> db_driver -> execute($query);
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
			$query = "SELECT * FROM VDamageDetail WHERE ".$filter.";";

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
		 * Funcion para obtener las partes de vehículo.
		 *
		 * Obtiene todos los registros de la tabla VehiclePart.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getVehicleParts($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM VehiclePart WHERE ".$condition.";";

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
		 * Funcion para obtener los daños de vehículo.
		 *
		 * Obtiene todos los registros de la tabla Damage.
		 *
		 * @return array - con los registros obtenidos si la consulta fue exitosa
		 * @return bool - FALSE si hubo un error
		 */
		public function getidDamages($condition)
		{
			//Query a ejecutar
			$query = "SELECT * FROM Damage WHERE ".$condition.";";

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
