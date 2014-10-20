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
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para insertar.
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
						
								//Recogemos el resultado de la inserci�n e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this->model->insert($idVehicleStatus,$vehicleStatus,$Fuel,$Km))
								{
									require_once("View/ShowInserVehicleStatus.php");

									//Enviamos el correo de que se ha a�adido un Estatus de Veh�culo.
									require_once("Controller/mail.php");

									//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Estatus de Veh�culo";
									$body = "El Estatus de Veh�culo con los siguientes datos se ha a�adido:".
									"\nId   : ". $idVehicleStatus.
									"\nVehicleStatus : ". $vehicleStatus.
									"\nFuel : ". $Fuel.
									"\nKm : ". $Km;

									//Manadamos el correo solo a administradores y empleados - 6
									if(Mailer::sendMail($subject, $body, 6))
									{
										echo "<br>Correo enviado con �xito.";
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
							$error = "No tiene permisos para realizar esta acci�n";
							require_once("View/Error.php");
						}
						break;
					}
				
					case "update" : 
					{
						//Solo administradores y empleados pueden actualizar los Estatus de Vehiculos.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para actualizar la informaci�n.
							if(empty($_POST))
							{
								require_once("View/UpdateVehicleStatus.php");
							}
							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idVehicleStatus']))
								{
									//Limpiamos el id.
									$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);

									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene informaci�n, la mostramos.
									if(($result = $this->model->select($idVehicleStatus)) != null)
									{
										echo var_dump($result);

										//La modificaci�n se realizar� en base al id.
										//Por ahora se modificar�n todos los atributos.
										$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);
										$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
										$Fuel = $this->cleanFloat($_POST['Fuel']);
										$Km = $this->cleanFloat($_POST['Km']);

										//Se llama a la funci�n de modificaci�n.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this->model->update($idVehicleStatus,$vehicleStatus,$Fuel,$Km))
										{
											require_once("View/ShowUpdateVehicleStatus.php");

											//Enviamos el correo de que se ha modificado un Estatus de Veh�culo.
											require_once("Controller/mail.php");

											//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificaci�n de Estatus de Veh�culo";
											$body = "El Estatus de Veh�culo con los siguientes datos se ha modificado:".
											"\nId   : ". $idVehicleStatus.
											"\nVehicleStatus : ". $vehicleStatus.
											"\nFuel : ". $Fuel.
											"\nKm : ". $Km;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												echo "<br>Correo enviado con �xito.";
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
									//Si el resultado no contiene informaci�n, mostramos el error.
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										require_once("View/Error.php");
									}
								}
								//Sino est� seteado, imprimimos el mensaje y se mostrar� la vista con 									el formulario para actualizar la informaci�n.
								else
								{
									$error = "El id no est� seteado.";
									echo $error,'<br/>';
									require_once("View/UpdateVehicleStatus.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acci�n";
							require_once("View/Error.php");
						}
						break;
					}
					
					case "select" :
					{
						//Comprobamos que el $_POST no est� vac�o.	
						if(empty($_POST))
						{
							require_once("View/SelectVehicleStatus.php");
						}
						else
						{
							//Comprobamos que el id est� seteado.
							if(isset($_POST['idVehicleStatus']))
							{
								//Limpiamos el id.
								$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);

								//Recogemos el resultado y si contiene informaci�n, la mostramos.
								if(($result = $this->model->select($idVehicleStatus)) != null)
								{
									require_once("View/ShowSelectVehicleStatus.php");
								}
								//Si el resultado no contiene informaci�n, mostramos el error.
								else
								{
									$error = "Error al tratar de mostrar el registro.";
									require_once("View/Error.php");
								}
							}
							//Si el ID no est� seteado, se marcar� el error y se mostrar� la vista con 								el formulario para hacer select.
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
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para eliminar un Evento.
							if(empty($_POST))
							{
								require_once("View/DeleteVehicleStatus.php");
							}

							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idVehicleStatus']))
								{
									//Limpiamos el id.
									$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);

									//Recogemos el resultado de la eliminaci�n.
									$result = $this->model->delete($idVehicleStatus);

									//Si la eliminaci�n fue exitosa, mostramos el mensaje.
									if($result)
									{
										require_once("View/DeleteVehicleStatus.php");

										//Enviamos el correo de que se ha eliminado un Estatus de Veh�culo.
										require_once("Controller/mail.php");

										//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminaci�n de Estatus de Veh�culo";
										$body = "El Estatus de Veh�culo con los siguientes datos se ha eiminado:".
										"\nId   : ". $idVehicleStatus.
										"\nVehicleStatus : ". $vehicleStatus.
										"\nFuel : ". $Fuel.
										"\nKm : ". $Km;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											echo "<br>Correo enviado con �xito.";
										}
										else
										{
											echo "<br>Error al enviar el correo.";
										}
									}
									//Si no pudimos eliminar, se�alamos el error.
									else
									{
										$error = "Error al elimiar el estatus del vehiculo.";
										require_once("View/Error.php");
									}
								}
								//Si el id no est� seteado, marcamos el error y se mostrar� la vista para 									eliminar un Evento.
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
							$error = "No tiene permisos para realizar esta acci�n";
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
