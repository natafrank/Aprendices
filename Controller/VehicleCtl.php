<?php

	require_once("StandardCtl.php");

	class VehicleCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			require_once("Model/VehicleMdl.php");
			$this -> model = new VehicleMdl();

			switch($_GET['act'])
			{
				case "insert":
				{
					if(empty($_POST))
					{
						//Se carga la vista del formulario
						require_once("View/InsertVehicle.html");
					}
					else
					{
						//Obtenemos las variables por la alta y las limpiamos
						#falta validar si las variables están colocadas.
						$vin             = $_POST['vin'];
						$brand           = $_POST['brand'];
						$vehicle_model          = $_POST['vehicle_model'];
						$color           = $_POST['color'];

						//Limpiamos las variables
						$vin     = $this -> cleanText($vin);
						$brand   = $this -> cleanText($brand);
						$vehicle_model  = $this -> cleanText($vehicle_model);
						$color   = $this -> cleanText($color);

						$result = $this -> model -> insert($vin, $brand, $vehicle_model, $color);

						if($result)
						{
							require_once("View/InsertVehicle.php");
						}
						else
						{
							require_once("View/InsertVehicleError.php");
						}
					}
				}
			}
		}//function run
	}//class VehicleCtl

?>
