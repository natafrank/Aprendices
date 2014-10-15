<?php
	include("Controller/StandardCtl.php");
	
	class VehiclePartCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			
			require_once("Model/VehiclePartMdl.php");
			$this -> model = new VehiclePartMdl();
			
			//Verificar si hay una sesion iniciada
			if(!$this -> isLogged())
			{
				//Si no verificar que esten seteadas las variables para hacer login
				if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
				{
					$this -> login($_POST['session_login'],$_POST['session_pass']);	
				}
			}
			
			//validar que el login se haya hecho correctamente
			if( $this -> isLogged() )
			{ 			
			
				switch($_GET['act'])
				{
					
					case "insert" :
					{	
						//Solo administradores y empleados pueden hacer inserciones de Partes de Vehiculos
						if( !$this -> isClient() )
						{				
							//Comprobar si $_POST está vacio, si es así se mostrará el formulario para capturar los datos.
							if(empty($_POST))
							{
								require_once("View/InsertVehiclePart.php");
							}
							else
							{
								//Limpiamos los datos.
								$idVehiclePart = $this->cleanText($_POST['idVehiclePart']);  // Para este dato se creara un Trigger en la BD
								$VehiclePart   = $this->cleanText($_POST['VehiclePart']);

								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this -> model -> insert($idVehiclePart, $VehiclePart))
								{
									require_once("View/ShowInsertVehiclePart.php");
								}
								else
								{
									$error = "Error al insertar el nuevo registro"; 
									require_once("View/Error.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							require_once("View/Error.php");
						}
						break;
					}
				
					case "update" : 
					{	
						//Solo administradores y empleados pueden hacer actualizaciones de Partes de Vehiculos
						if( !$this -> isClient() )
						{
							//Comprobamos que $_POST no este vacio.
							if(empty($_POST))
							{
								require_once("View/UpdateVehiclePart.php");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['idVehiclePart']))
								{
									//Limpiamos el ID
									$idVehiclePart = $this -> cleanInt($_POST['idVehiclePart']);
							
									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($idVehiclePart)) != null)
									{
										echo var_dump($result);

										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.  
										$VehiclePart   = $this->cleanText($_POST['VehiclePart']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($idVehiclePart, $VehiclePart))
										{
											require_once("View/ShowUpdateVehiclePart.php");	
										}
										else
										{
											$error = "Error al modificar la parte de vehiculo.";
											require_once("View/Error.php");
										}
									}
								}
								else
								{
									$error = 'No se especifico el ID del registro a modificar';
									require_once("View/Error.php");	
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							require_once("View/Error.php");
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
							if(isset($_POST['idVehiclePart']))
							{
								//Limpiamos el id.
								$idVehiclePart = $this -> cleanText($_POST['idVehiclePart']);

								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($idVehiclePart)) != null)
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
						//Solo administradores y empleados pueden hacer eliminaciones de Partes de Vehiculos
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								require_once("View/DeleteVehiclePart.php");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idVehiclePart']))
								{
									//Limpiamos el id.
									$idVehiclePart = $this -> cleanText($_POST['idVehiclePart']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($idVehiclePart);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										require_once("View/DeleteVehiclePart.php");
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar la parte de vehiculo.";
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
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							require_once("View/Error.php");
						}
						break;
					}
			
				} /* fin switch */
				$this -> logout();
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");	
			}

		} /* fin run */

	}

?>
