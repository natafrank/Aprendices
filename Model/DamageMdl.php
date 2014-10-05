<?php
	class DamageMdl
	{
		
		private $id_damage;
		private $damage;	

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


		public function insert($id_damage,$damage)
		{
			//Escapamos las variables.
			$this -> id_damage = $this -> db_driver -> escape($id_damage);
			$this -> damage   = $this -> db_driver -> escape($damage);

			//Query a ejecutar.
			$query = "INSERT INTO Damage VALUES(".$this -> id_damage
					 .", '".$this -> damage."');";
	
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
		} /* fin alta*/
		
		public function delete($id_damage)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> id_damage = $this -> db_driver -> escape($id_damage);

			//Query a ejecutar
			$query = "DELETE FROM Damage WHERE idDamage=".$this -> id_damage.";";

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
		
		public function update($id_damage, $damage)
		{
			//Escapamos las variables.
			$this -> id_damage = $this -> db_driver -> escape($id_damage);
			$this -> damage   = $this -> db_driver -> escape($damage);

			//Query que realizará la modificación.
			$query = "UPDATE Damage SET Damage='".$damage."' 
					  WHERE idDamage=".$id_damage.";";

		  	//Ejecutamos el query.
		  	$result = $this -> db_driver -> execute($query);

		  	return $result;
		}
		
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
