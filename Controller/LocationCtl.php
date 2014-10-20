<?php
	include("Controller/StandardCtl.php");
	
	class LocationCtl extends StandardCtl
	{
		private $model;
		
		function __construct()
		{
			require_once("Model/LocationMdl.php");
			$this->model = new LocationMdl();
		}

		public function run()
		{		
			//Verificar que esten seteadas las variables para hacer login
			if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
			{
				$this->login($_POST['session_login'],$_POST['session_pass']);	
			}

			//validar que el login se haya hecho correctamente
			if( $this->isLogged() )
			{ 
				switch($_GET['act'])
				{
					
					case "insert" :
					{
						//Solo administradores y empleados pueden hacer inserciones de Ubicación.
						if( !$this -> isClient() )
						{	
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para insertar.
							if(empty($_POST))
							{
								require_once("View/InsertLocation.php");
							}
							else
							{
								//Limpiamos los datos.
								$idLocation = $this->cleanInt($_POST['idLocation']);
								$location = $this->cleanText($_POST['location']);
								$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);
						
								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this->model->insert($idLocation,$location,$idMasterLocation))
								{
									require_once("View/ShowInserLocation.php");

									//Enviamos el correo de que se ha añadido una Ubicación.
									require_once("Controller/mail.php");

									//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Ubicación";
									$body = "La Ubicación con los siguientes datos se ha añadido:".
									"\nId   : ". $idLocation.
									"\nLocation : ". $location.
									"\nIdMasterLocation : ". $idMasterLocation;

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
						//Solo administradores y empleados pueden actualizar las Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para actualizar la información.
							if(empty($_POST))
							{
								require_once("View/UpdateLocation.php");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this->model->select($idLocation)) != null)
									{
										echo var_dump($result);

										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.
										$idLocation = $this->cleanInt($_POST['idLocation']);
										$location = $this->cleanText($_POST['location']);
										$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this->model->update($idLocation,$location,$idMasterLocation))
										{
											require_once("View/ShowUpdateLocation.php");

											//Enviamos el correo de que se ha modificado una Ubicación.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificación de Ubicación";
											$body = "La Ubicación con los siguientes datos se ha modificado:".
											"\nId   : ". $idLocation.
											"\nLocation : ". $location.
											"\nIdMasterLocation : ". $idMasterLocation;

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
											$error = "Error al modificar la ubicación.";
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
									require_once("View/UpdateLocation.php");
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
						//Solo administradores y empleados pueden ver las Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para hacer select.	
							if(empty($_POST))
							{
								require_once("View/SelectLocation.php");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this->model->select($idLocation)) != null)
									{
										require_once("View/ShowSelectLocation.php");
									}
									//Si el resultado no contiene información, mostramos el error.
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										require_once("View/Error.php");
									}
								}
								//Si el ID no está seteado, se marcará el error y se mostrará la vista con 									el formulario para hacer select.
								else
								{
									$error = "El id no esta seteado.";
									echo $error,'<br/>';
									require_once("View/SelectLocation.php");
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
					
					case "delete" :
					{
						//Solo administradores y empleados pueden eliminar Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para eliminar una Ubicación.
							if(empty($_POST))
							{
								require_once("View/DeleteLocation.php");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Recogemos el resultado de la eliminación.
									$result = $this->model->delete($idLocation);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										require_once("View/DeleteLocation.php");

										//Enviamos el correo de que se ha eliminado una Ubicación.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Ubicación";
										$body = "La Ubicación con los siguientes datos se ha eliminado:".
										"\nId   : ". $idLocation.
										"\nLocation : ". $location.
										"\nIdMasterLocation : ". $idMasterLocation;

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
										$error = "Error al elimiar la ubicación.";
										require_once("View/Error.php");
									}
								}
								//Si el id no está seteado, marcamos el error y se mostrará la vista para 									eliminar un Evento.
								else
								{
									$error = 'No se ha especificado el ID del registro a eliminar';
									echo $error,'<br/>';
									require_once("View/DeleteLocation.php");	
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
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");	
			}

		} /* fin run */

	}

?>
