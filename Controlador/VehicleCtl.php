<?php

	require_once("StandardCtl.php");

	class VehicleCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			require_once("Modelo/VehicleMdl.php");
			$this -> model = new VehicleMdl();

			switch($_GET['act'])
			{
				case "insert":
				{
					if(empty($_POST))
					{
						//Se carga la vista del formulario
						require_once("Vista/InsertVehicle.html");
					}
					else
					{
						//Obtenemos las variables por la alta y las limpiamos
						#falta validar si las variables están colocadas.
						$vin             = $_POST['vin'];
						$marca           = $_POST['marca'];
						$modelo          = $_POST['modelo'];
						$color           = $_POST['color'];

						//Limpiamos las variables
						$vin     = $this -> cleanText($vin);
						$marca   = $this -> cleanText($marca);
						$modelo  = $this -> cleanText($modelo);
						$color   = $this -> cleanText($color);

						$result = $this -> model -> insert($vin, $marca, $modelo, $color);

						if($result)
						{
							require_once("Vista/InsertVehicle.php");
						}
						else
						{
							require_once("Vista/InsertVehicleError.php");
						}
					}
				}
			}
		}//function run
	}//class VehicleCtl

?>
