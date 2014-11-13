<?php
	class EventMdl
	{
		
		private $idEvent;
		private $Event;	

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


		public function insert($idEvent, $Event)
		{
			//Escapamos las variables.
			$this->idEvent = $this->db_driver->escape($idEvent);
			$this->Event   = $this->db_driver->escape($Event);

			//Query a ejecutar.
			$query = "INSERT INTO Event VALUES(".$this->idEvent
					 .", '".$this->Event."');";
	
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
		
		public function delete($idEvent)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this->idEvent = $this->db_driver->escape($idEvent);

			//Query a ejecutar
			$query = "DELETE FROM Event WHERE idEvent=".$this->idEvent.";";

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
		
		public function update($idEvent, $Event)
		{
			//Escapamos las variables.
			$this->idEvent = $this->db_driver->escape($idEvent);
			$this->Event   = $this->db_driver->escape($Event);

			//Query que realizará la modificación.
			$query = "UPDATE Event SET Event='".$Event." 
					  WHERE idEvent=".$idEvent.";";

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
		
		public function select($idEvent)
		{
			//Escapamos la variable.
			$this->idEvent = $this->db_driver->escape($idEvent);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM Event WHERE idEvent=".$this->idEvent.";";

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
			$query = "SELECT * FROM Event WHERE ".$filter.";";

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
		public function getIdEvent()
		{
			return $this -> idEvent;
		}

		public function getEvent()
		{
			return $this -> Event;
		}

	}
?>
