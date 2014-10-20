<?php

	require_once("StandardCtl.php");

	class VehicleCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			//Importamos el modelo.
			require_once("Model/VehicleMdl.php");
			
			$this -> model = new VehicleMdl();

			//Verificar si hay una sesion iniciada
			if(!$this -> isLogged())
			{
				//Si no verificar que esten seteadas las variables para hacer login
				if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
				{
					$this -> login($_POST['session_login'],$_POST['session_pass']);	
				}
			}

			//Validar que el login se haya hecho correctamente
			if($this -> isLogged())
			{
				switch($_GET['act'])
				{
					case "insert":
					{
						//Solo admins y empleados podrán insertar vehículos
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos si el POST no está vacío.
							if(empty($_POST))
							{
								//Se carga la vista del formulario.
								require_once("View/InsertVehicle.php");
							}
							else
							{
								//Comprobamos que las variables estén seteadas.
								if(isset($_POST['id_vehicle']) && isset($_POST['vin']) && isset($_POST['id_location'])
									&& isset($_POST['id_vehicle_model']) && isset($_POST['color']))
								{
									//Obtenemos las variables por la alta y las limpiamos.
									$id_vehicle        = $this -> cleanText($_POST['id_vehicle']);
									$id_location       = $this -> cleanInt($_POST['id_location']);
									$id_vehicle_model  = $this -> cleanInt($_POST['id_vehicle_model']);
									$vin               = $this -> cleanText($_POST['vin']);
									$color             = $this -> cleanText($_POST['color']);

									//Guardamos el resultado de ejecutar el query.
									$result = $this -> model -> insert($id_vehicle, $id_location, $id_vehicle_model, 
											$vin, $color);

									if($result)
									{
										require_once("View/ShowVehicle.php");

										//Enviamos el correo de que se ha añadido un usuario.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Alta de Vehículo";
										$body = "El vehículo con los siguientes datos se ha añadido:".
										"\nId              : ". $id_vehicle.
										"\nId Location     : ". $id_location.
										"\nId Vehicle Model: ". $id_vehicle_model.
										"\nVin             : ". $vin.
										"\nColor           : ". $color;
								
										//Manadamos el correo solo a administradores - 4.
										if(Mailer::sendMail($subject, $body, 4))
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
										$error = "Error al intentar insertar el vehículo.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Faltan variables por setear.";
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
					case "delete" :
					{
						//Solo los admins podrán eliminar
						if($this -> isAdmin())
						{
							//Comprobamos que el POST np esté vacío.
							if(empty($_POST))
							{
								$error = "Error al eliminar el vehículo, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle']))
								{
									//Limpiamos la variable.
									$id_vehicle = $this -> cleanText($_POST['id_vehicle']);

									//Ejecutamos el query y recogemos el resultado.
									$result = $this -> model -> delete($id_vehicle);

									if($result)
									{
										require_once("View/DeleteVehicle.php");

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Vehículo";
										$body    = "Se ha eliminado el vehículo con el id: ".$id_vehicle;

										//Enviamos el correo solo a admins - 4
										if(Mailer::sendMail($subject, $body, 4))
										{
											echo "Correo enviado con éxito";
										}
										else
										{
											echo "Error al enviar el correo";
										}
									}
									else
									{
										$error = "Error al intentar eliminar el vehículo.";
									require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al eliminar el vehículo, el vin no está seteado.";
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
						//Solo los admins y los empleados podrán consultar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								$error = "Error al mostrar el vehículo, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle']))
								{
									//Limpiamos el id.
									$id_vehicle = $this -> cleanText($_POST['id_vehicle']);

									//Ejecutamos el query y recogemos el resultado.
									$result = $this -> model -> select($id_vehicle);

									if($result != null)
									{
										var_dump($result);
									}
									else
									{
										$error = "Error al mostrar el vehículo.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al mostrar el vehículo, el vin no está seteado.";
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
						//Solo los admins y empleados podrán modificar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								$error = "Error al tratar de modificar el registro, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle']))
								{
									//Limpiamos el id.
									$id_vehicle = $this -> cleanText($_POST['id_vehicle']);

									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($id_vehicle)) != null)
									{
										var_dump($result);

										//Comprobamos que las demás variables estén seteadas.
										if(isset($_POST['id_location'])
											&& isset($_POST['id_vehicle_model'])
											&& isset($_POST['vin'])
											&& isset($_POST['color']))
										{
											/*La modificación se realizará en base al vin.
											 *Por ahora se modificarán todos los atributos.*/
											//Limpiamos las variables.
											$id_location      = $this -> cleanText($_POST['id_location']);
											$id_vehicle_model = $this -> cleanText($_POST['id_vehicle_model']);
											$vin              = $this -> cleanText($_POST['vin']);
											$color            = $this -> cleanText($_POST['color']);

											//Se llama a la función de modificación.
											//Se recoge el resultado y en base a este resultado
											//se imprime un mensaje.
											if($this -> model -> update($id_vehicle, $id_location, 
												$id_vehicle_model, $vin,  $color))
											{
												require_once("View/UpdateVehicleShow.php");

												//Enviamos el correo de que se ha añadido un usuario.
												require_once("Controller/mail.php");

												//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
												$subject = "Modificación de Vehículo";
												$body = "El vehículo con los siguientes datos se ha modificado:".
												"\nId              : ". $id_vehicle.
												"\nId Location     : ". $id_location.
												"\nId Vehicle Model: ". $id_vehicle_model.
												"\nVin             : ". $vin.
												"\nColor           : ". $color;
								
												//Manadamos el correo solo a administradores - 4.
												if(Mailer::sendMail($subject, $body, 4))
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
												$error = "Error al tratar de modificar el registro.";
												require_once("View/Error.php");
											}
										}
										else
										{
											$error = "Error al tratar de modificar el registro, faltan variables por setear.";
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
								else
								{
									$error = "Error al tratar de modificar el registro, el vin no está seteado.";
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
				}
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");	
			}
			
		}//function run
	}//class VehicleCtl

?>
