<?php
	class VehicleBrandMdl{
		private $idVehicleBrand;
		private $VehicleBrand;
		
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

		public function insert($idVehicleBrand,$VehicleBrand)
		{
			//Escapamos las variables.
			$this -> idVehicleBrand  = $this -> db_driver -> escape($idVehicleBrand);
			$this -> VehicleBrand    = $this -> db_driver -> escape($VehicleBrand);

			//Query a ejecutar.
			$query = "INSERT INTO VehicleBrand VALUES('".$this -> idVehileBrand."', "
												 	   .$this -> VehicleBrand.");";
	
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
		
		public function delete($idVehicleBrand)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> idVehicleBrand = $this -> db_driver -> escape($idVehicleBrand);

			//Query a ejecutar
			$query = "DELETE FROM VehicleBrand WHERE idVehicleBrand=".$this -> idVehicleBrand.";";

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
		
		public function update($idVehicleBrand,$VehicleBrand)
		{
			//Escapamos las variables.
			$this -> idVehicleBrand  = $this -> db_driver -> escape($idVehicleBrand);
			$this -> VehicleBrand    = $this -> db_driver -> escape($VehicleBrand);

			//Query que realizará la modificación.
			$query = "UPDATE VehicleBrand SET VehicleBrand='".$this -> VehicleBrand."', " 
					  " WHERE idVehicleBrand=".$this -> idVehicleBrand.";";

		  	//Ejecutamos el query.
		  	$result = $this -> db_driver -> execute($query);

		  	return $result;
		}
		
		public function select()
		{
			//Escapamos la variable.
			$this -> idVehicleBrand = $this -> db_driver -> escape($idVehicleBrand);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM VehicleBrand WHERE idVehicleBrand=".$this -> idVehicleBrand.";";

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
