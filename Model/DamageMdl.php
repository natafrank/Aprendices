<?php
	class DamageMdl
	{
		
		private $idDamage;
		private $Damage;	

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


		public function insert($idDamage,$Damage)
		{
			//Escapamos las variables.
			$this -> idDamage = $this -> db_driver -> escape($idDamage);
			$this -> Damage   = $this -> db_driver -> escape($Damage);

			//Query a ejecutar.
			$query = "INSERT INTO Damage VALUES(".$this -> idDamage
					 .", '".$this -> Damage."');";
	
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
		
		public function delete($idDamage)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> idDamage = $this -> db_driver -> escape($idDamage);

			//Query a ejecutar
			$query = "DELETE FROM Damage WHERE idDamage=".$this -> idDamage.";";

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
		
		public function update($idDamage, $Damage)
		{
			//Escapamos las variables.
			$this -> idDamage = $this -> db_driver -> escape($idDamage);
			$this -> Damage   = $this -> db_driver -> escape($Damage);

			//Query que realizará la modificación.
			$query = "UPDATE Damage SET Damage='".$Damage."' 
					  WHERE idDamage=".$idDamage.";";

		  	//Ejecutamos el query.
		  	$result = $this -> db_driver -> execute($query);

		  	return $result;
		}
		
		public function select($idDamage)
		{
			//Escapamos la variable.
			$this -> idDamage = $this -> db_driver -> escape($idDamage);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM Damage WHERE idDamage=".$this -> idDamage.";";

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
