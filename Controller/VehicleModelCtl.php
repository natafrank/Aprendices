<?php

	require_once("StandardCtl.php");

	class VehicleModelCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			//Importamos el archivo del modelo.
			require_once("Model/VehicleModelMdl.php");

			//Creamos el modelo.
			$this -> model = new VehicleModelMdl();

			//Acciones del $_GET
			switch($_GET['act'])
			{
				case "insert":
				{
					if(empty($_POST))
					{
						require_once("View/InsertVehicleModel.php");
					}
					else
					{
						$vehicle_model    = $this -> cleanText($_POST['vehicle_model']);
						$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);

						$result = $this -> model -> insert($vehicle_model, $id_vehicle_brand);

						if($result)
						{
							require_once("View/ShowVehicleModel.php");
						}
						else
						{
							require_once("View/InsertVehicleModelError.php");
						}
					}

					break;
				}

				case "delete":
				{
					if(empty($_POST))
					{
						require_once("View/DeleteVehicleModelError.php");
					}
					else
					{
						/*Para hacer las eliminaciones utilizaremos el id del modelo*/
						$id_vehicle_model = $this -> cleanInt($_POST['id_vehicle_model']);

						$result = $this -> model -> delete($id_vehicle_model);

						if($result)
						{
							require_once("View/DeleteVehicleModel.php");
						}
						else
						{
							require_once("View/DeleteVehicleModelError.php");
						}
					}

					break;
				}

				case "select":
				{
					if(empty($_POST))
					{
						require_once("View/ShowVehicleModelError.php");
					}
					else
					{
						/*Se mostrará el modelo en base a su id.*/
						$id_vehicle_model = $this -> cleanInt($_POST['id_vehicle_model']);

						$result = $this -> model -> select($id_vehicle_model);

						if($result)
						{
							require_once("View/ShowVehicleModel.php");
						}
						else
						{
							require_once("View/ShowVehicleModelError.php");
						}
					}

					break;
				}

				case "update":
				{
					if(empty($_POST))
					{
						require_once("View/UpdateVehicleModelError.php");
					}
					else
					{
						//La modificación se realizará en base el id del modelo
						$id_vehicle_model = $this -> cleanInt($_POST['id_vehicle_model']);

						//En base al id se accederá a la base de datos y se tomarán
						//todos los atributos del modelo.
						//Esto lo hace la función select(), por lo que la llamamos
						$result = $this -> model -> select($id_vehicle_model);

						//Si se accede de manera éxitosa mostramos un formulario
						//con los datos del modelo.
						if($result)
						{
							require_once("View/UpdateVehicleModelForm.php");

							//Una vez modificados los datos del modelo a través del form
							//se llama a la función update la cuál actualizará los valores
							//modificados en el form dentro de la base de datos.
							$update_result = $this -> model -> update();

							//Por último se muestran los datos del modelo modificados.
							require_once("View/UpdateVehicleModelShow.php");
						}
						//Si no pudimos acceder mostramos el error.
						else
						{
							require_once("View/UpdateVehicleModelError.php");
						}
					}

					break;
				}
			}
		}
	}

?>