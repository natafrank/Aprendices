<?php
	
	require_once("StandardCtl.php");

	class VehicleBrandCtl extends StandardCtl
	{
		private $model;

		function run()
		{
			require_once("Model/VehicleBrandMdl.php");

			$this -> model = new VehicleBrandMdl();

			switch($_GET['act'])
			{
				case "insert" :
				{
					if(empty($_POST))
					{
						//Se carga la vista del formulario
						require_once("View/InsertVehicleBrand.php");
					}
					else
					{
						//Obtenemos las variables y las limpiamos
						$brand = $this -> cleanText($_POST['brand']);

						$result = $this -> model -> insert($brand);

						if($result)
						{
							require_once("View/ShowVehicleBrand.php");
						}
						else
						{
							require_once("View/InsertVehicleBrandError.php");
						}
					}

					break;
				}

				case "delete" :
				{
					if(empty($_POST))
					{
						require_once("View/DeleteVehicleBrandError.php");
					}
					else
					{
						//Las eliminaciones se harán por medio del id.
						$id = $this -> cleanInt($_POST['id']);

						$result = $this -> model -> delete($id);

						if($result)
						{
							require_once("View/DeleteVehicleBrand.php");
						}
						else
						{
							require_once("View/DeleteVehicleBrandError.php");

						}
					}

					break;
				}

				case "show" :
				{
					if(empty($_POST))
					{
						require_once("View/ShowVehicleBrandError.php");
					}
					else
					{
						//Se accederá por medio del id.
						$id = $this -> cleanInt($_POST['id']);

						$result = $this -> model -> show($id);

						if($result)
						{
							require_once("View/ShowVehicleBrand.php");
						}
						else
						{
							require_once("View/ShowVehicleBrandError.php");
						}
					}

					break;
				}

				case "update" :
				{
					if(empty($_POST))
					{
						require_once("View/UpdateVehicleBrandError.php");
					}
					else
					{
						//La modificación se realizará en base el id.
						$id = $this -> cleanInt($_POST['id']);

						//En base al id se accederá a la base de datos y se tomarán
						//todos los atributos.
						//Esto lo hace la función show(), por lo que la llamamos.
						$result = $this -> model -> show($id);
					
						//Si se accede de manera éxitosa mostramos un formulario
						//con los datos.
						if($result)
						{
							require_once("View/UpdateVehicleBrandForm.php");

							//Una vez modificados los datos a través del form
							//se llama a la función update la cuál actualizará los valores
							//modificados en el form dentro de la base de datos.
							$update_result = $this -> model -> update();

							//Por último se muestran los datos modificados.
							require_once("View/UpdateVehicleBrandShow.php");
						}
						//Si no pudimos acceder mostramos el error.
						else
						{
							require_once("View/UpdateVehicleBrandError.php");
						}
					}

					break;
				}
			}
		}
	}

?>