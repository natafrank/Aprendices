<?php
	class ChecklistMdl
	{
		/**
		 * Variables de los Campos de la tabla Checklist.
		 *
		 * @access private
		 * @var int $idChecklist - Llave primaria de la tabla.
		 * @access private
		 * @var int $idVehicle - Llave foranea a la tabla Vehicle.
		 * @access private
		 * @var int $idVehicleStatus - Llave foranea a la tabla VehicleStatus.
		 * @access private
		 * @var datetime $Date - Fecha de creación del registro.
		 * @access private
		 * @var bool $InOut - Indicador de si el registro es de entrada o salida.
		 */
		private $idChecklist;
		private $idVehicle;
		private $idVehicleStatus;
		private $Date;
		private $InOut;
		
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
		 * @param int $idChecklist - Llave primaria de la tabla.
		 * @param int $idVehicle - Llave foranea a la tabla Vehicle.
		 * @param int $idVehicleStatus - Llave foranea a la tabla VehicleStatus.
		 * @param datetime $Date - Fecha de creación del registro.
		 * @param bit $InOut - Indicador de si el registro es de entrada o salida.
		 *
		 * @return array - con el registro insertado si se inserto correctamente
		 * @return bool - FALSE si falló la inserción
		 */
		public function insert($idChecklist,$idVehicle,$idVehicleStatus,$Date,$InOut)
		{
			//Escapamos las variables.
			$this -> idChecklist     = $this -> db_driver -> escape($idChecklist);
			$this -> idVehicle       = $this -> db_driver -> escape($idVehicle);
			$this -> idVehicleStatus = $this -> db_driver -> escape($idVehicleStatus);
			$this -> Date            = $this -> db_driver -> escape($Date);
			$this -> InOut           = $this -> db_driver -> escape($InOut);

			//Query a ejecutar.
			$query = "INSERT INTO CheckList VALUES(".$this -> idChecklist.", "
												 	.$this -> idVehicle.", '"
												 	.$this -> Date."', "
												 	.$this -> InOut.", "
												 	.$this -> idVehicleStatus.");";
	
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
		 * @param int $idChecklist - Llave primaria del registro que se va a eliminar.
		 *
		 * @return array - con el registro eliminado si se inserto correctamente
		 * @return bool - FALSE si falló la eliminacion
		 */
		public function delete($idChecklist)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> idChecklist = $this -> db_driver -> escape($idChecklist);

			//Query a ejecutar
			$query = "DELETE FROM CheckList WHERE idCheckList=".$this -> idChecklist.";";

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
		 * @param int $idChecklist - Llave primaria del registro que se va a modificar.
		 * @param int $idVehicle - Llave foranea a la tabla Vehicle.
		 * @param int $idVehicleStatus - Llave foranea a la tabla VehicleStatus.
		 * @param datetime $Date - Fecha de creación del registro.
		 * @param bit $InOut - Indicador de si el registro es de entrada o salida.
		 *
		 * @return array - Arreglo con los datos actualizados si se actualizaron correctamente.
		 * @return bool FALSE - Si no se actualizó el registro correctamente en la base de datos.
		 */
		public function update($idChecklist,$idVehicle,$idVehicleStatus,$InOut)
		{
			//Escapamos las variables.
			$this -> idChecklist     = $this -> db_driver -> escape($idChecklist);
			$this -> idVehicle       = $this -> db_driver -> escape($idVehicle);
			$this -> idVehicleStatus = $this -> db_driver -> escape($idVehicleStatus);
			//$this -> Date            = $this -> db_driver -> escape($Date);
			$this -> InOut           = $this -> db_driver -> escape($InOut);

			//Query que realizará la modificación.
			$query = "UPDATE CheckList SET idVehicle=".$this -> idVehicle.", "
									   	 ."idVehicleStatus=".$this -> idVehicleStatus.", "
									   	 ."InOut=".$this -> InOut.   
					  " WHERE idCheckList = ".$this -> idChecklist.";";

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
		 * @param int $idChecklist - Llave primaria del registro que se va a mostrar.
		 *
		 * @return array - Arreglo con los datos obtenidos del query.
		 * @return bool FALSE - Si no se obtuvieron datos con el query o si hubo un error.
		 */
		public function select($idChecklist)
		{
			//Escapamos la variable.
			$this -> idChecklist = $this -> db_driver -> escape($idChecklist);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM CheckList WHERE idCheckList=".$this -> idChecklist.";";

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
			$query = "SELECT * FROM CheckList WHERE ".$filter.";";

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
		 * Funcion Crear evento.
		 *
		 * Crea el evento de entrada o salida al crear un checklist.
		 *
		 * @param int $idUser - id del usuario que creo el checklist.
		 * @param int $idVehicle - id del vehiculo al que se le hizo el checklist.
		 * @param int $InOut - entero que indica si el checklist es de entrada o salida.
		 *
		 * @return bool - FALSE si falló la inserción, TRUE en caso contrario
		 */
		public function createEvent($idUser,$idVehicle,$InOut)
		{
			//Escapamos las variables.
			$idUser    = $this -> db_driver -> escape($idUser);
			$idVehicle = $this -> db_driver -> escape($idVehicle);
			$InOut     = $this -> db_driver -> escape($InOut);

			//Obtener el siguiente id
			$idEventRegistry = $this -> db_driver -> execute('SELECT MAX(idEventRegistry) ID FROM EventRegistry;');
			$idEventRegistry = $idEventRegistry[0]['ID'] + 1;

			//Setear el id del evento dependiendo de si es entrada o salida
			$idEvent = 1;
			$Reason = "Entrada del vehiculo con ID: ".$idVehicle;
			if($InOut == 1){
				$idEvent = 3;
				$Reason = "Salida del vehiculo con ID: ".$idVehicle;
			}

			$date_array = getdate();
			$Date = $date_array['year']."-".$date_array['mon']."-".$date_array['mday'];

			//Query a ejecutar.
			$query = "INSERT INTO EventRegistry VALUES(".$idEventRegistry.", "
												 	.$idVehicle.", "
												 	.$idUser.", "
												 	.$idEvent.", '"
												 	.$Date."', '"
													.$Reason."');";
	
			//Ejecutamos el query y retornamos el resultado.
		    //Retornará verdadero si se insertaron los datos correctamente.
			//Retornará falso en caso de no poder insertar.
			if(!$this -> db_driver -> execute($query)){
				return FALSE;
			}
			return TRUE;
		}

		/**
		 * Funcion obtener id del usuario del vehiculo.
		 *
		 * Regresa el id del usuario al que pertenece el vehiculo especificado.
		 *
		 * @param int $idVehicle - id del vehiculo del que se buscará el propietario.
		 *
		 * @return int - con el id del usuario al que pertenece el vehiculo si se encuentra
		 * @return bool - FALSE si falló la consulta
		 */
		public function getIdVehicleUser($idVehicle)
		{
			//Escapamos la variable.
			$this -> idVehicle = $this -> db_driver -> escape($idVehicle);

			//Se obtendra el id del usuario al que pertenece el vehiculo.
			$query = "SELECT idUser FROM Vehicle WHERE idVehicle=".$this -> idVehicle.";";

			//Ejecutamos el query y recogemos el resultado.
			$result = $this -> db_driver -> execute($query);

			//Si el resultado no es null, procesamos la información.
			if($result != null)
			{
				//Si el resultado contiene información retornamos el id del usuario.
				return $result[0]['idUser'];
			}
			else
			{
				//Si el resultado es null, retornamos FALSE.
				return FALSE;
			}	
		}

		/**
		 * Funcion obtener datos del vehiculo.
		 *
		 * Regresa la iformación del vehículo especificado.
		 *
		 * @param int $idVehicle - id del vehículo del que se buscará la información.
		 *
		 * @return array - con la información del vehículo
		 * @return bool - FALSE si falló la consulta
		 */
		public function getVehicleInfo($idVehicle)
		{
			//Escapamos la variable.
			$this -> idVehicle = $this -> db_driver -> escape($idVehicle);

			//Se obtendra el id del usuario al que pertenece el vehiculo.
			$query = "SELECT * FROM Vehicle WHERE idVehicle=".$this -> idVehicle.";";

			//Ejecutamos el query y recogemos el resultado.
			$result = $this -> db_driver -> execute($query);

			//Si el resultado no es null, procesamos la información.
			if($result != null)
			{
				//Si el resultado contiene información retornamos el id del usuario.
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
		public function getDamages($condition)
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
