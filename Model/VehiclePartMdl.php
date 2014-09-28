<?php
	class VehiclePartMdl{
		private $idVehiclePart;
		private $VehiclePart;
		
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
		
		public function update($idVehiclePart,$VehiclePart)
		{
			//Escapamos las variables.
			$this -> idVehiclePart  = $this -> db_driver -> escape($idVehiclePart);
			$this -> VehiclePart    = $this -> db_driver -> escape($VehiclePart);

			//Query que realizará la modificación.
			$query = "UPDATE VehiclePart SET VehiclePart='".$this -> VehiclePart."', " 
					  " WHERE idVehiclePart=".$this -> idVehiclePart.";";

		  	//Ejecutamos el query.
		  	$result = $this -> db_driver -> execute($query);

		  	return $result;
		}
		
		public function select()
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
