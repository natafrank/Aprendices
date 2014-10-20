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
				//Acciones del $_GET
				switch($_GET['act'])
				{
					case "insert":
					{
						//Solo el admin y empleados podrán insertar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								require_once("View/InsertVehicleModel.php");
							}
							else
							{
								//Comprobamos que las variables estén seteadas.
								if(    isset($_POST['id_vehicle_model']) 
									&& isset($_POST['vehicle_model']) 
									&& isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos los datos.
									$id_vehicle_model = $this -> cleanText($_POST['id_vehicle_model']);
									$vehicle_model    = $this -> cleanText($_POST['vehicle_model']);
									$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);

									//Guardamos el resultado de ejecutar el query.
									$result = $this -> model -> insert($id_vehicle_model, $vehicle_model, $id_vehicle_brand);

									if($result)
									{
										require_once("View/ShowVehicleModel.php");

										//Enviamos el correo de que se ha añadido un usuario.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Alta de Modelo de Vehículo";
										$body = "El Modelo de Vehículo con los siguientes datos se ha añadido:".
										"\nId            : ". $id_vehicle_model.
										"\nVehicle Model : ". $vehicle_model;

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
										$error = "Error al agregar un modelo de vehículo.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al intentar insertar el Modelo, faltan variables por setear.";
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

					case "delete":
					{
						//Solo los admins podrán eliminar
						if($this -> isAdmin())
						{
							//Comprobamos que el $_POST no esté vacío
							if(empty($_POST))
							{
								$error = "Error al eliminar el modelo de vehículo, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id este seteado.
								if(isset($_POST['id_vehicle_model']))
								{
									/*Para hacer las eliminaciones utilizaremos el id del modelo*/
									//Limpiamos la variable.
									$id_vehicle_model = $this -> cleanText($_POST['id_vehicle_model']);

									//Recogemos el resultado del query.
									$result = $this -> model -> delete($id_vehicle_model);

									if($result)
									{
										require_once("View/DeleteVehicleModel.php");

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Modelo de Vehículo";
										$body    = "Se ha eliminado el modelo de vehículo con el id: ".$id_vehicle_model;

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
										$error = "Error al eliminar el modelo de vehículo.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al eliminar el modelo de vehículo, el id no está seteado.";
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

					case "select":
					{
						//Solo admins y empleados podrán consultar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamso que el $_POST no esté vacío
							if(empty($_POST))
							{
								$error = "Error al intentar mostrar el modelo de vehículo, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id esté seteado
								if(isset($_POST['id_vehicle_model']))
								{
									/*Se mostrará el modelo en base a su id.*/
									//Limpiamos la variable.
									$id_vehicle_model = $this -> cleanText($_POST['id_vehicle_model']);

									//Recogemos el resultado de ejecutar el query.
									$result  = $this -> model -> select($id_vehicle_model);

									//Si el resultado contiene información, la imprimimos.
									if($result != null)
									{
										var_dump($result);
									}
									else
									{
										$error = "Error al intentar mostrar el modelo de vehículo.";
										require_once("View/Error.php");
									}
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

					case "update":
					{
						//Solo admins y empleados podrán modificar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							if(empty($_POST))
							{
								$error = "Error al intentar modificar el modelo de vehículo, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['id_vehicle_model']))
								{
									//Limpiamos el id
									$id_vehicle_model = $this -> cleanText($_POST['id_vehicle_model']);

									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($id_vehicle_model)) != null)
									{
										var_dump($result);

										//Comprobamos que las variables estén seteadas
										if(isset($_POST['id_vehicle_brand']) && isset($_POST['vehicle_model']))
										{
											//La modificación se realizará en base al id.
											//Por ahora se modificarán todos los atributos.
											$id_vehicle_brand = $this -> cleanText($_POST['id_vehicle_brand']);
											$vehicle_model    = $this -> cleanText($_POST['vehicle_model']);

											//Se llama a la función de modificación.
											//Se recoge el resultado y en base a este resultado
											//se imprime un mensaje.
											if($this -> model -> update($id_vehicle_model, $vehicle_model, $id_vehicle_brand))
											{
												require_once("View/UpdateVehicleModelShow.php");

												//Enviamos el correo de que se ha añadido un usuario.
												require_once("Controller/mail.php");

												//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
												$subject = "Modificación de Modelo de Vehículo";
												$body = "El Modelo de Vehículo con los siguientes datos se ha modificado:".
												"\nId            : ". $id_vehicle_model.
												"\nVehicle Model : ". $vehicle_model;

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
												$error = "Error al modificar el modelo de vehículo.";
												require_once("View/Error.php");
											}
										}
										else
										{
											$error = "Error al intentar modificar el registro, faltan varibales por setear.";
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
									$error = "Error al intentar modificar el modelo de vehículo, el id no está seteado.";
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
		}
	}

?>