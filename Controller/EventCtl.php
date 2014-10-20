<?php
	include("Controller/StandardCtl.php");
	
	class EventCtl extends StandardCtl
	{
		private $model;
		
		function __construct()
		{
			require_once("Model/EventMdl.php");
			$this->model = new EventMdl();
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
						//Solo administradores y empleados pueden hacer inserciones de Eventos
						if( !$this -> isClient() )
						{	
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para insertar.
							if(empty($_POST))
							{
								require_once("View/InsertEvent.php");
							}
							else
							{
								//Limpiamos los datos.
								$idEvent = $this->cleanInt($_POST['idEvent']); // Para este dato se creara un Trigger en la BD
								$Event   = $this->cleanText($_POST['Event']);
						
								//Recogemos el resultado de la inserci�n e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this->model->insert($idEvent,$Event))
								{
									require_once("View/ShowInsertEvent.php");

									//Enviamos el correo de que se ha a�adido un Evento.
									require_once("Controller/mail.php");

									//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Evento";
									$body = "El Evento con los siguientes datos se ha a�adido:".
									"\nId   : ". $idEvent.
									"\nEvent : ". $Event;

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
						//Solo administradores y empleados pueden actualizar los Eventos
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para actualizar la informaci�n.
							if(empty($_POST))
							{
								require_once("View/UpdateEvent.php");
							}
							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idEvent']))
								{
									//Limpiamos el id.
									$idEvent = $this->cleanInt($_POST['idEvent']);

									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene informaci�n, la mostramos.
									if(($result = $this->model->select($idEvent)) != null)
									{
										echo var_dump($result);

										//La modificaci�n se realizar� en base al id.
										//Por ahora se modificar�n todos los atributos.
										$Event = $this->cleanText($_POST['Event']);

										//Se llama a la funci�n de modificaci�n.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this->model->update($idEvent, $Event))
										{
											require_once("View/ShowUpdateEvent.php");

											//Enviamos el correo de que se ha actualizado un Evento.
											require_once("Controller/mail.php");

											//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Actualizaci�n de Evento";
											$body = "El Evento con los siguientes datos se ha actualizado:".
											"\nId   : ". $idEvent.
											"\nEvent : ". $Event;

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
											$error = "Error al modificar el evento.";
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
									require_once("View/UpdateEvent.php");
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
						//Solo administradores y empleados pueden ver los Eventos
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para hacer select.
							if(empty($_POST))
							{
								require_once("View/SelectEvent.php");
							}
							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idEvent']))
								{
									//Limpiamos el id.
									$idEvent = $this->cleanInt($_POST['idEvent']);

									//Recogemos el resultado y si contiene informaci�n, la mostramos.
									if(($result = $this->model->select($idEvent)) != null)
									{
										require_once("View/ShowSelectEvent.php");
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
									//Mostrar un mensaje de que no se especific� el ID.
									$error = "No se ha especificado el ID del registro a mostrar.";
									echo $error,'<br/>';
									require_once("View/SelectEvent.php");
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
						//Solo administradores y empleados pueden eliminar Eventos
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o, si lo est� se mostrar� la vista con el 								formulario para eliminar un Evento.	
							if(empty($_POST))
							{
								require_once("View/DeleteEvent.php");
							}

							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idEvent']))
								{
									//Limpiamos el id.
									$idEvent = $this->cleanInt($_POST['idEvent']);

									//Recogemos el resultado de la eliminaci�n.
									$result = $this->model->delete($idEvent);

									//Si la eliminaci�n fue exitosa, mostramos el mensaje.
									if($result)
									{
										require_once("View/DeleteEvent.php");

										//Enviamos el correo de que se ha eliminado un Evento.
										require_once("Controller/mail.php");

										//Mandamos como par�metro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminaci�n de Evento";
										$body = "El Evento con los siguientes datos se ha eliminado:".
										"\nId   : ". $idEvent.
										"\nEvent : ". $Event;

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
										$error = "Error al elimiar el evento.";
										require_once("View/Error.php");
									}
								}
								//Si el id no est� seteado, marcamos el error y se mostrar� la vista para 									eliminar un Evento.
								else
								{

									$error = 'No se ha especificado el ID del registro a eliminar';
									echo $error,'<br/>';
									require_once("View/DeleteEvent.php");	
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
