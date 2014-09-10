<?php

	require_once("StandardCtl.php");

	class VehicleCtl extends StandardCtl
	{
		private $modelo;

		public function ejecutar()
		{
			require_once("Modelo/VehicleMdl.php");
			$this -> modelo = new VehicleMdl();

			switch($_GET['act'])
			{
				case "alta":
				{
					if(empty($_POST))
					{
						//Se carga la vista del formulario
						require_once("Vista/VehiculoAlta.html");
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
						$vin     = $this -> limpiaTexto($vin);
						$marca   = $this -> limpiaTexto($marca);
						$modelo  = $this -> limpiaTexto($modelo);
						$color   = $this -> limpiaTexto($color);

						$resultado = $this -> modelo -> alta($vin, $marca, $modelo, $color);

						if($resultado)
						{
							require_once("Vista/vehiculoAgregado.php");
						}
						else
						{
							require_once("Vista/vehiculoError.php");
						}
					}
				}
			}
		}//function run
	}//class VehicleCtl

?>