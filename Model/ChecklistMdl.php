<?php
	class ChecklistMdl
	{
		private $idChecklist;
		private $idVehicle;
		private $idVehicleStatus;
		private $Date;
		private $InOut;
		
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

		public function insert($idChecklist,$idVehicle,$idVehicleStatus,$Date,$InOut)
		{
			//Escapamos las variables.
			$this -> idChecklist     = $this -> db_driver -> escape($idChecklist);
			$this -> idVehicle       = $this -> db_driver -> escape($idVehicle);
			$this -> idVehicleStatus = $this -> db_driver -> escape($idVehicleStatus);
			$this -> Date            = $this -> db_driver -> escape($Date);
			$this -> InOut           = $this -> db_driver -> escape($InOut);

			//Query a ejecutar.
			$query = "INSERT INTO Checklist VALUES(".$this -> idChecklist.", "
												 	.$this -> idVehicle.", "
												 	.$this -> idVehicleStatus.", '"
												 	.$this -> Date."', "
												 	.$this -> InOut.");";
	
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
		
		public function delete($idChecklist)
		{
			//Escapamos el id con el que vamos a realizar la eliminación.
			$this -> idChecklist = $this -> db_driver -> escape($idChecklist);

			//Query a ejecutar
			$query = "DELETE FROM Checklist WHERE idChecklist=".$this -> idChecklist.";";

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
		
		public function update($idChecklist,$idVehicle,$idVehicleStatus,$Date,$InOut)
		{
			//Escapamos las variables.
			$this -> idChecklist     = $this -> db_driver -> escape($idChecklist);
			$this -> idVehicle       = $this -> db_driver -> escape($idVehicle);
			$this -> idVehicleStatus = $this -> db_driver -> escape($idVehicleStatus);
			$this -> Date            = $this -> db_driver -> escape($Date);
			$this -> InOut           = $this -> db_driver -> escape($InOut);

			//Query que realizará la modificación.
			$query = "UPDATE Checklist SET idVehicle=".$this -> idVehicle.", "
									   	 ."idVehicleStatus=".$this -> idVehicleStatus.", "
									   	 ."Date='".$this -> Date."', "
									   	 ."InOut=".$this -> InOut.   
					  " WHERE idChecklist = ".$this -> idChecklist.";";

		  	//Ejecutamos el query.
		  	$result = $this -> db_driver -> execute($query);

		  	return $result;
		}
		
		public function select()
		{
			//Escapamos la variable.
			$this -> idChecklist = $this -> db_driver -> escape($idChecklist);

			//Para el primer ejemplo se ejecutará un SELECT * con el id deseado.
			$query = "SELECT * FROM Checklist WHERE idChecklist=".$this -> idChecklist.";";

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
