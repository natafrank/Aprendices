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
			//Verificar que esten seteadas las variables para hacer login.
			if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
			{
				$this->login($_POST['session_login'],$_POST['session_pass']);	
			}

			//Validar que el login se haya hecho correctamente.
			if( $this->isLogged() )
			{ 
				switch($_GET['act'])
				{
					
					case "insert" :
					{
						//Solo administradores y empleados pueden hacer inserciones de Estatus de Vehiculos.
						if( !$this -> isClient() )
						{	
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para insertar.
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

									//Enviamos el correo de que se ha añadido un Estatus de Vehículo.
									require_once("Controller/mail.php");

									//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Estatus de Vehículo";
									$body = "El Estatus de Vehículo con los siguientes datos se ha añadido:".
									"\nId   : ". $idVehicleStatus.
									"\nVehicleStatus : ". $vehicleStatus.
									"\nFuel : ". $Fuel.
									"\nKm : ". $Km;

									//Manadamos el correo solo a administradores y empleados - 6
									if(Mailer::sendMail($subject, $body, 6))
									{
										echo "<br>Correo enviado con éxito.";
									}
									else
									{
										echo "<br>Error al enviar el correo.";
									}
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
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
						}
						break;
					}
				
					case "update" : 
					{
						//Solo administradores y empleados pueden actualizar los Estatus de Vehiculos.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para actualizar la información.
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

											//Enviamos el correo de que se ha modificado un Estatus de Vehículo.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificación de Estatus de Vehículo";
											$body = "El Estatus de Vehículo con los siguientes datos se ha modificado:".
											"\nId   : ". $idVehicleStatus.
											"\nVehicleStatus : ". $vehicleStatus.
											"\nFuel : ". $Fuel.
											"\nKm : ". $Km;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												echo "<br>Correo enviado con éxito.";
											}
											else
											{
												echo "<br>Error al enviar el correo.";
											}
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
								//Sino está seteado, imprimimos el mensaje y se mostrará la vista con 									el formulario para actualizar la información.
								else
								{
									$error = "El id no está seteado.";
									echo $error,'<br/>';
									require_once("View/UpdateVehicleStatus.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
						}
						break;
					}
					
					case "select" :
					{
						//Comprobamos que el $_POST no esté vacío.	
						if(empty($_POST))
						{
							require_once("View/SelectVehicleStatus.php");
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
									require_once("View/ShowSelectVehicleStatus.php");
								}
								//Si el resultado no contiene información, mostramos el error.
								else
								{
									$error = "Error al tratar de mostrar el registro.";
									require_once("View/Error.php");
								}
							}
							//Si el ID no está seteado, se marcará el error y se mostrará la vista con 								el formulario para hacer select.
							else
							{
								$error = "El id no esta seteado.";
								echo $error,'<br/>';
								require_once("View/SelectVehicleStatus.php");
							}
						}
						break;
					}
					
					case "delete" :
					{
						//Solo administradores y empleados pueden eliminar Estatus de Vehiculos.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para eliminar un Evento.
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

										//Enviamos el correo de que se ha eliminado un Estatus de Vehículo.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Estatus de Vehículo";
										$body = "El Estatus de Vehículo con los siguientes datos se ha eiminado:".
										"\nId   : ". $idVehicleStatus.
										"\nVehicleStatus : ". $vehicleStatus.
										"\nFuel : ". $Fuel.
										"\nKm : ". $Km;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											echo "<br>Correo enviado con éxito.";
										}
										else
										{
											echo "<br>Error al enviar el correo.";
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar el estatus del vehiculo.";
										require_once("View/Error.php");
									}
								}
								//Si el id no está seteado, marcamos el error y se mostrará la vista para 									eliminar un Evento.
								else
								{
									$error = 'No se ha especificado el ID del registro a eliminar';
									echo $error,'<br/>';
									require_once("View/DeleteVehicleStatus.php");	
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
						}
						break;
					}
			
				} /* fin switch */
				$this->logout();
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");	
			}

		} /* fin run */

	}

?>
