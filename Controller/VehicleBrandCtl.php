<?php
	
	require_once("StandardCtl.php");

	class VehicleBrandCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			
			require_once("Model/VehicleBrandMdl.php");
			$this -> model = new VehicleBrandMdl();		

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

					case "insert" :
					{					
						//Solo los admins y emleados podrán insertar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobar si $_POST está vacio, si es así se mostrará el formulario para capturar los datos.
							if(empty($_POST))
							{
								require_once("View/InsertVehicleBrand.php");
							}
							else
							{
								//Limpiamos los datos.
								$id_vehicle_brand = $this->cleanText($_POST['id_vehicle_brand']);  // Para este dato se creara un Trigger en la BD
								$vehicle_brand   = $this->cleanText($_POST['vehicle_brand']);

								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this -> model -> insert($id_vehicle_brand, $vehicle_brand))
								{
									require_once("View/ShowInsertVehicleBrand.php");
								
									//Enviamos el correo de que se ha añadido un usuario.
									require_once("Controller/mail.php");

									//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Marca de Vehículo";
									$body = "La Marca de Vehículo con los siguientes datos se ha añadido:".
									"\nId            : ". $id_vehicle_brand.
									"\nVehicle Brand : ". $vehicle_brand;

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
						//Solo los admins y empleados podrán modificar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que $_POST no este vacio.
							if(empty($_POST))
							{
								$error = "Error al tratar de modificar el registro, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos el ID
									$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);
									
									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($id_vehicle_brand)) != null)
									{
										echo var_dump($result);

										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.  
										$vehicle_brand   = $this->cleanText($_POST['vehicle_brand']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($id_vehicle_brand, $vehicle_brand))
										{
											require_once("View/ShowUpdateVehicleBrand.php");	

											//Enviamos el correo de que se ha añadido un usuario.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificación de Marca de Vehículo";
											$body = "La Marca de Vehículo con los siguientes datos se ha modificado:".
											"\nId            : ". $id_vehicle_brand.
											"\nVehicle Brand : ". $vehicle_brand;

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
											$error = "Error al modificar la marca de vehiculo.";
											require_once("View/Error.php");
										}
									}
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										require_once("View/Error.php");
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
						//Solo admins y empleados podrán consultar
						if($this -> isAdmin() || $this -> isEmployee())
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
								if(isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos el id.
									$id_vehicle_brand = $this -> cleanText($_POST['id_vehicle_brand']);

									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($id_vehicle_brand)) != null)
									{
										var_dump($result);
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
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								require_once("View/DeleteVehicleBrand.php");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos el id.
									$id_vehicle_brand = $this -> cleanText($_POST['id_vehicle_brand']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($id_vehicle_brand);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										require_once("View/DeleteVehicleBrand.php");

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Marca de Vehículo";
										$body    = "Se ha eliminado la marca de vehículo con el id: ".$id_vehicle_brand;

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
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar la marca de vehiculo.";
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
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");
			}
			

		} /* fin run */

	}

?>
