<?php
	class DamageMdl
	{
		/**
		 * Variables de los Campos de la tabla Damage.
		 *
		 * @access private
		 * @var int $id_damage - Llave primaria de la tabla.
		 * @access private
		 * @var string $damage - Nombre del daño.
		 */
		private $id_damage;
		private $damage;	

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
		 * @param int $id_damage - Llave primaria de la tabla.
		 * @param string $damage - Nombre del daño.
		 *
		 * @return array - con el registro insertado si se inserto correctamente
		 * @return bool - FALSE si falló la inserción
		 */
		public function insert($id_damage,$damage)
		{
			//Escapamos las variables.
			$this -> id_damage = $this -> db_driver -> escape($id_damage);
			$this -> damage   = $this -> db_driver -> escape($damage);

			//Query a ejecutar.
			$query = "INSERT INTO Damage VALUES(".$this -> id_damage
					 .", '".$this -> damage."');";
	
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
		 * @param int $id_damage - Llave primaria del registro que se va a eliminar.
		 *
		 * @return array - con el registro eliminado si se inserto correctamente
		 * @return bool - FALSE si falló la eliminacion
		 */
		public function delete($id_damage)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> id_damage = $this -> db_driver -> escape($id_damage);

			//Query a ejecutar
			$query = "DELETE FROM Damage WHERE idDamage=".$this -> id_damage.";";

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
		 * @param int $id_damage - Llave primaria de la tabla.
		 * @param string $damage - Nombre del daño.
		 *
		 * @return array - Arreglo con los datos actualizados si se actualizaron correctamente.
		 * @return bool FALSE - Si no se actualizó el registro correctamente en la base de datos.
		 */
		public function update($id_damage, $damage)
		{
			//Escapamos las variables.
			$this -> id_damage = $this -> db_driver -> escape($id_damage);
			$this -> damage   = $this -> db_driver -> escape($damage);

			//Query que realizará la modificación.
			$query = "UPDATE Damage SET Damage='".$damage."' 
					  WHERE idDamage=".$id_damage.";";

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
		 * @param int $id_damage - Llave primaria del registro que se va a mostrar.
		 *
		 * @return array - Arreglo con los datos obtenidos del query.
		 * @return bool FALSE - Si no se obtuvieron datos con el query o si hubo un error.
		 */
		public function select($id_damage)
		{
			//Escapamos la variable.
			$this -> id_damage = $this -> db_driver -> escape($id_damage);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM Damage WHERE idDamage=".$this -> id_damage.";";

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
			$query = "SELECT * FROM Damage WHERE ".$filter.";";

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
		public function getIdDamage()
		{
			return $this -> id_damage;
		}

		public function getDamage()
		{
			return $this -> damage;
		}
	}
?>
