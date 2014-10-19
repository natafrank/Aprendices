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
						//Solo administradores y empleados pueden hacer inserciones de Ubicaci�n.
						if( !$this -> isClient() )
						{	
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para insertar.
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
						
								//Recogemos el resultado de la inserci�n e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this->model->insert($idLocation,$location,$idMasterLocation))
								{
									require_once("View/ShowInserLocation.php");

									//Enviamos el correo de que se ha a�adido una Ubicaci�n.
									require_once("Controller/mail.php");

									//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Ubicaci�n";
									$body = "La Ubicaci�n con los siguientes datos se ha a�adido:".
									"\nId   : ". $idLocation.
									"\nLocation : ". $location.
									"\nIdMasterLocation : ". $idMasterLocation;

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
						//Solo administradores y empleados pueden actualizar las Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para actualizar la informaci�n.
							if(empty($_POST))
							{
								require_once("View/UpdateLocation.php");
							}
							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene informaci�n, la mostramos.
									if(($result = $this->model->select($idLocation)) != null)
									{
										echo var_dump($result);

										//La modificaci�n se realizar� en base al id.
										//Por ahora se modificar�n todos los atributos.
										$idLocation = $this->cleanInt($_POST['idLocation']);
										$location = $this->cleanText($_POST['location']);
										$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);

										//Se llama a la funci�n de modificaci�n.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this->model->update($idLocation,$location,$idMasterLocation))
										{
											require_once("View/ShowUpdateLocation.php");

											//Enviamos el correo de que se ha modificado una Ubicaci�n.
											require_once("Controller/mail.php");

											//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificaci�n de Ubicaci�n";
											$body = "La Ubicaci�n con los siguientes datos se ha modificado:".
											"\nId   : ". $idLocation.
											"\nLocation : ". $location.
											"\nIdMasterLocation : ". $idMasterLocation;

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
											$error = "Error al modificar la ubicaci�n.";
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
									require_once("View/UpdateLocation.php");
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
						//Solo administradores y empleados pueden ver las Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para hacer select.	
							if(empty($_POST))
							{
								require_once("View/SelectLocation.php");
							}
							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Recogemos el resultado y si contiene informaci�n, la mostramos.
									if(($result = $this->model->select($idLocation)) != null)
									{
										require_once("View/ShowSelectLocation.php");
									}
									//Si el resultado no contiene informaci�n, mostramos el error.
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										require_once("View/Error.php");
									}
								}
								//Si el ID no est� seteado, se marcar� el error y se mostrar� la vista con 									el formulario para hacer select.
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
							$error = "No tiene permisos para realizar esta acci�n";
							require_once("View/Error.php");
						}
						break;
					}
					
					case "delete" :
					{
						//Solo administradores y empleados pueden eliminar Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para eliminar una Ubicaci�n.
							if(empty($_POST))
							{
								require_once("View/DeleteLocation.php");
							}
							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Recogemos el resultado de la eliminaci�n.
									$result = $this->model->delete($idLocation);

									//Si la eliminaci�n fue exitosa, mostramos el mensaje.
									if($result)
									{
										require_once("View/DeleteLocation.php");

										//Enviamos el correo de que se ha eliminado una Ubicaci�n.
										require_once("Controller/mail.php");

										//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminaci�n de Ubicaci�n";
										$body = "La Ubicaci�n con los siguientes datos se ha eliminado:".
										"\nId   : ". $idLocation.
										"\nLocation : ". $location.
										"\nIdMasterLocation : ". $idMasterLocation;

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
										$error = "Error al elimiar la ubicaci�n.";
										require_once("View/Error.php");
									}
								}
								//Si el id no est� seteado, marcamos el error y se mostrar� la vista para 									eliminar un Evento.
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
							$error = "No tiene permisos para realizar esta acci�n";
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
