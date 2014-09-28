<?php
	class DamageDetailMdl
	{
		private $idDamageDetail;
		private $idCheklist;
		private $idVehiclePart;
		private $idDamage;
		
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

		  	//Ejecutamos el query.
		  	$result = $this -> db_driver -> execute($query);

		  	return $result;
		}
		
		public function select()
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
