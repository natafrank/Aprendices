<?php
	include("Controller/StandardCtl.php");
	
	class VehicleStatusCtl extends StandardCtl
	{
		private $model;
		
		function __construct()
		{
			require_once("Model/VehicleStatusMdl.php");
			$this->model = new VehicleStatusMdl();
		}

		public function run()
		{		
			
			switch($_GET['act'])
			{
					
				case "insert" :
				{	
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						require_once("View/InsertVehicleStatus.php");
					}
					else
					{
						//Limpiamos los datos.
						$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);
						$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
						$Fuel = $this->cleanFloat($_POST['Fuel']);
						$Km = $this->cleanFloat($_POST['Km']);
						
						//Recogemos el resultado de la inserción e imprimimos un mensaje
						//en base a este resultado.
						if($result = $this->model->insert($idVehicleStatus,$vehicleStatus,$Fuel,$Km))
						{
							require_once("View/ShowInserVehicleStatus.php");
						}
						else
						{
							$error = "Error al insertar el nuevo registro"; 
							require_once("View/Error.php");
						}
					}
					break;
				}
				
				case "update" : 
				{
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						require_once("View/UpdateVehicleStatus.php");
					}
					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idVehicleStatus']))
						{
							//Limpiamos el id.
							$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);

							//Primero mostramos el id que se quire modificar.
							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this->model->select($idVehicleStatus)) != null)
							{
								echo var_dump($result);

								//La modificación se realizará en base al id.
								//Por ahora se modificarán todos los atributos.
								$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);
								$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
								$Fuel = $this->cleanFloat($_POST['Fuel']);
								$Km = $this->cleanFloat($_POST['Km']);

								//Se llama a la función de modificación.
								//Se recoge el resultado y en base a este resultado
								//se imprime un mensaje.
								if($this->model->update($idVehicleStatus,$vehicleStatus,$Fuel,$Km))
								{
									require_once("View/ShowUpdateVehicleStatus.php");	
								}
								else
								{
									$error = "Error al modificar el estatus del vehiculo.";
									require_once("View/Error.php");
								}
							}
							//Si el resultado no contiene información, mostramos el error.
							else
							{
								$error = "Error al tratar de mostrar el registro.";
								require_once("View/Error.php");
							}
						}
						//Sino está seteado, imprimimos el mensaje.
						else
						{
							$error = "El id no está seteado.";
							require_once("View/Error.php");
						}

					}
					break;
				}
					
				case "select" :
				{
					//Comprobamos que el $_POST no esté vacío.	
					if(empty($_POST))
					{
						$error = "No se especificó el id.";
						require_once("View/Error.php");
					}
					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idVehicleStatus']))
						{
							//Limpiamos el id.
							$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);

							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this->model->select($idVehicleStatus)) != null)
							{
								echo var_dump($result);
							}
							//Si el resultado no contiene información, mostramos el error.
							else
							{
								$error = "Error al tratar de mostrar el registro.";
								require_once("View/Error.php");
							}
						}
						//Imprimimos el error si la variable no está seteada.
						else
						{
							$error = "El id no esta seteado.";
							require_once("View/Error.php");
						}
					}
					break;
				}
					
				case "delete" :
				{
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						require_once("View/DeleteVehicleStatus.php");
					}

					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idVehicleStatus']))
						{
							//Limpiamos el id.
							$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);

							//Recogemos el resultado de la eliminación.
							$result = $this->model->delete($idVehicleStatus);

							//Si la eliminación fue exitosa, mostramos el mensaje.
							if($result)
							{
								require_once("View/DeleteVehicleStatus.php");
							}
							//Si no pudimos eliminar, señalamos el error.
							else
							{
								$error = "Error al elimiar el estatus del vehiculo.";
								require_once("View/Error.php");
							}
						}
						//Si el id no está seteado, marcamos el error.
						else
						{
							$error = 'No se ha especificado el ID del registro a eliminar';
							require_once("View/Error.php");	
						}
					}
					break;
				}
			
			} /* fin switch */

		} /* fin run */

	}

?>