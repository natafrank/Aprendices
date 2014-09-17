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
						require_once("View/InsertVehicle.php");
					}
					else
					{
						//Obtenemos las variables por la alta y las limpiamos.
						$vin               = $this -> cleanText($_POST['vin']);
						$id_vehicle_model  = $this -> cleanText($_POST['id_vehicle_model']);
						$id_location       = $this -> cleanText($_POST['id_location']);
						$color             = $this -> cleanText($_POST['color']);

						$result = $this -> model -> insert($vin, $id_vehicle_model, $id_location, $color);

						if($result)
						{
							require_once("View/ShowVehicle.php");
						}
						else
						{
							require_once("View/InsertVehicleError.php");
						}
					}

					break;
				}
				case "delete" :
				{
					if(empty($_POST))
					{
						require_once("View/DeleteVehicleError.php");
					}
					else
					{
						/*Para hacer las eliminaciones utilizaremos el id del vehículo.*/
						$id_vehicle = $this -> cleanInt($_POST['id_vehicle']);

						$result = $this -> model -> delete($id_vehicle);

						if($result)
						{
							require_once("View/DeleteVehicle.php");
						}
						else
						{
							require_once("View/DeleteVehicleError.php");
						}
					}

					break;
				}

				case "select" :
				{
					if(empty($_POST))
					{
						require_once("View/ShowVehicleError.php");
					}
					else
					{
						/*Se mostrará el vehículo en base a su id.*/
						$id_vehicle = $this -> cleanInt($_POST['id_vehicle']);

						$result = $this -> model -> select($id_vehicle);

						if($result)
						{
							require_once("View/ShowVehicle.php");
						}
						else
						{
							require_once("View/ShowVehicleError.php");
						}
					}

					break;
				}

				case "update" :
				{
					if(empty($_POST))
					{
						require_once("View/UpdateVehicleError.php");
					}
					else
					{
						//La modificación se realizará en base el id del vehículo
						$id_vehicle = $this -> cleanInt($_POST['id_vehicle']);

						//En base al id se accederá a la base de datos y se tomarán
						//todos los atributos del vehículo.
						//Esto lo hace la función select(), por lo que la llamamos
						$result = $this -> model -> select($id_vehicle);

						//Si se accede de manera éxitosa mostramos un formulario
						//con los datos del vehículo.
						if($result)
						{
							require_once("View/UpdateVehicleForm.php");

							//Una vez modificados los datos del vehículo a través del form
							//se llama a la función update la cuál actualizará los valores
							//modificados en el form dentro de la base de datos.
							$update_result = $this -> model -> update();

							//Por último se muestran los datos del vehículo modificados.
							require_once("View/UpdateVehicleShow.php");
						}
						//Si no pudimos acceder mostramos el error.
						else
						{
							require_once("View/UpdateVehicleError.php");
						}
					}

					break;
				}
			}
		}//function run
	}//class VehicleCtl

?>
