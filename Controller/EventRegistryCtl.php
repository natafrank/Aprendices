<?php
	include("Controller/StandardCtl.php");
	
	class EventRegistryCtl extends StandardCtl
	{
		private $model;
		
		function __construct()
		{
		require_once("Model/EventRegistryMdl.php");
		$this->model = new EventRegistryMdl();
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
						//Solo administradores y empleados pueden hacer inserciones de Registros de Eventos.
						if( !$this->isClient() )
						{	
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para insertar.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/EventRegistryForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los campos van vacios y los input estan activos
								$dictionary = array(
									'{value-idEventRegistry}' => '',
									'{value-idVehicle}' => '',
									'{value-idUser}' => '',
									'{value-idEvent}' => '',
									'{value-Reason}' => '',
									'{active}' => '',
									'{acti}' => 'insert'
								);

								//Sustituir los valores en la plantilla
								$view = strtr($view,$dictionary);

								//Sustituir el usuario en el header
								$dictionary = array(
									'{user-name}' => $_SESSION['user'],
									'{log-link}' => 'index.php?ctl=logout',
									'{log-type}' => 'Logout'
									);

								$header = strtr($header,$dictionary);

								//Agregamos el header y el footer a la vista
								$view = $header.$view.$footer;
								
								//Mostramos la vista
								echo $view;
								//require_once("View/Formulario.html");
							}
							else
							{
								//Limpiamos los datos.
								//Obtenemos la llave primaria
								require_once("Model/PKGenerator.php");									
								$idEventRegistry = PKGenerator::getPK('EventRegistry','idEventRegistry');
								$idVehicle = $this->cleanInt($_POST['idVehicle']);
								$idUser = $this->cleanInt($_POST['idUser']);
								$idEvent = $this->cleanInt($_POST['idEvent']);
								$date_array = getdate();
								$Date = $date_array['year']."-".$date_array['mon']."-".$date_array['mday'];
								$Reason = $this->cleanText($_POST['Reason']);
						
								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Reason))
								{
									//Cargamos el formulario
									$view = file_get_contents("View/EventRegistryForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Creamos el diccionario
									//Despues de insertar los campos van con la info insertada y los input estan inactivos
									$dictionary = array(
											'{value-idEventRegistry}' => $idEventRegistry,
											'{value-idVehicle}' => $_POST['idVehicle'],
											'{value-idUser}' => $_POST['idUser'],
											'{value-idEvent}' => $_POST['idEvent'],
											'{value-Reason}' => $_POST['Reason'],
											'{active}' => 'disabled',
											'{active}' => 'insert'
										);
									
									//Sustituir los valores en la plantilla
									$view = strtr($view,$dictionary);
									
									//Sustituir el usuario en el header
									$dictionary = array(
										'{user-name}' => $_SESSION['user'],
										'{log-link}' => 'index.php?ctl=logout',
										'{log-type}' => 'Logout'
										);

									$header = strtr($header,$dictionary);
									
									//Agregamos el header y el footer
									$view = $header.$view.$footer;
									
									echo $view;
									//require_once("View/ShowUser.php");
			
									//Enviamos el correo de que se ha añadido un Registro de Evento.
									require_once("Controller/mail.php");

									//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Registro de Evento";
									$body = "El Registro de Evento con los siguientes datos se ha añadido:".
									"\nId   : ". $idEventRegistry.
									"\nIdVehicle : ". $idVehicle.
									"\nIdUser : ". $idUser.
									"\nIdEvent : ". $idEvent.
									"\nFecha: ". $Date.
									"\nReason : ". $Reason;

									//Manadamos el correo solo a administradores y empleados - 6
									if(Mailer::sendMail($subject, $body, 6))
									{
										//echo "<br>Correo enviado con éxito.";
									}
									else
									{
										/*$error =  "<br>Error al enviar el correo.";
										$this -> showErrorView($error);*/
									}
								}
								else
								{
									$error = "Error al insertar el nuevo registro de evento"; 
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}
						break;
					}
				
					case "update" : 
					{
						//Solo administradores y empleados pueden actualizar los Registros de Eventos.
						if( !$this->isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para actualizar la información.
							if(empty($_POST))
							{
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en el label del input
								$this -> showGetIdView("eventregistry","update","idEventRegistry","Id RegistroEvento:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idEventRegistry']))
								{
									//Limpiamos el id.
									$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['idVehicle']) && isset($_POST['idUser']) && isset($_POST['idEvent']) && isset($_POST['Reason']))
									{
										//La modificación se realizará en base al id.
										$idVehicle = $this->cleanInt($_POST['idVehicle']);
										$idUser = $this->cleanInt($_POST['idUser']);
										$idEvent = $this->cleanInt($_POST['idEvent']);
										$date_array = getdate();
										$Date = $date_array['year']."-".$date_array['mon']."-".$date_array['mday'];
										$Reason = $this->cleanText($_POST['Reason']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this->model->update($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Reason))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/EventRegistryForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los campos van con la info insertada y los input estan inactivos
											$dictionary = array(
												'{value-idEventRegistry}' => $idEventRegistry, 
												'{value-idVehicle}' => $idVehicle,
												'{value-idUser}' => $idUser, 
												'{value-idEvent}' => $idEvent,
												'{value-Reason}' => $Reason,
												'{active}' => 'disabled',
												'{action}' => 'update'
											);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Sustituir el usuario en el header
											$dictionary = array(
												'{user-name}' => $_SESSION['user'],
												'{log-link}' => 'index.php?ctl=logout',
												'{log-type}' => 'Logout'
																);
											$header = strtr($header,$dictionary);

											//Agregamos el header y el footer
											$view = $header.$view.$footer;

											echo $view;
											//require_once("View/UpdateUserShow.php");

											//Enviamos el correo de que se ha modificado un Registro de Evento.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificación de Registro de Evento";
											$body = "El Registro de Evento con los siguientes datos se ha modificado:".
											"\nId   : ". $idEventRegistry.
											"\nIdVehicle : ". $idVehicle.
											"\nIdUser : ". $idUser.
											"\nIdEvent : ". $idEvent.
											"\nDate: ". $Date.
											"\nReason : ". $Reason;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												/*$error =  "Error al enviar el correo.";
												$this -> showErrorView($error);*/
											}
										}
										else
										{
											$error = "Error al modificar el registro de evento.";
											$this -> showErrorView($error);
										}

									}
									else
									{
										if(($result = $this->model->select($idEventRegistry)) != null)
										{

											//Cargamos el formulario
											$view = file_get_contents("View/EventRegistryForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los campos van con la info insertada y los input estan inactivos
											$dictionary = array(
												'{value-idEventRegistry}' => $result[0]['idEventRegistry'], 
												'{value-idVehicle}' => $result[0]['idVehicle'],
												'{value-idUser}' => $result[0]['idUser'], 
												'{value-idEvent}' => $result[0]['idEvent'],
												'{value-Reason}' => $result[0]['Reason'],
												'{active}' => '',
												'{active}' => 'update'
											);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Sustituir el usuario en el header
											$dictionary = array(
												'{user-name}' => $_SESSION['user'],
												'{log-link}' => 'index.php?ctl=logout',
												'{log-type}' => 'Logout'
																);
											$header = strtr($header,$dictionary);

											//Agregamos el header y el footer
											$view = $header.$view.$footer;

											echo $view;
										}
										//Si el resultado no contiene información, mostramos el error.
										else
										{
											$error = "Error al traer la información para modificar.";
											$this -> showErrorView($error);
										}
									}
								}
								//Sino está seteado, imprimimos el mensaje y se mostrará la vista con 									el formulario para actualizar la información.
								else
								{
									$error = "Error al tratar de modificar el registro, el id no está seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}
						break;
					}
					
					case "select" :
					{
						//Solo administradores y empleados pueden ver los Resgistros de Eventos.
						if( !$this->isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para hacer select.	
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en el label del input
								$this -> showGetIdView("eventregistry","select","idEventRegistry","Id RegistroEvento:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idEventRegistry']))
								{
									//Limpiamos el id.
									$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);

									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this->model->select($idEventRegistry)) != null)
									{
										//Cargamos el formulario
										$view = file_get_contents("View/EventRegistryForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										$dictionary = array(
											'{value-idEventRegistry}' => $result[0]['idEventRegistry'], 
											'{value-idVehicle}' => $result[0]['idVehicle'],
											'{value-idUser}' => $result[0]['idUser'],
											'{value-idEvent}' => $result[0]['idEvent'],
											'{value-Reason}' => $result[0]['Reason'],
											'{active}' => 'disabled',
											'{active}' => 'select'
										);

										//Sustituir los valores en la plantilla
										$view = strtr($view,$dictionary);

										//Sustituir el usuario en el header
										$dictionary = array(
											'{user-name}' => $_SESSION['user'],
											'{log-link}' => 'index.php?ctl=logout',
											'{log-type}' => 'Logout'
										);
										$header = strtr($header,$dictionary);

										//Agregamos el header y el footer
										$view = $header.$view.$footer;

										echo $view;
									}
									//Si el resultado no contiene información, mostramos el error.
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										$this -> showErrorView($error);
									}
								}
								//Si el ID no está seteado, se marcará el error y se mostrará la vista con 									el formulario para hacer select.
								else
								{
									$error = "Error al mostrar el registro, el id no está seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}
						break;
					}
					
					case "delete" :
					{
						//Solo administradores y empleados pueden eliminar Registros de Eventos.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para eliminar un Registro de Evento.	
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar de $_POST y el texto a mostrar en el label del input
								$this -> showGetIdView("eventregistry","delete","idEventRegistry","Id RegistroEvento:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idEventRegistry']))
								{
									//Limpiamos el id.
									$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);

									//Recogemos el resultado de la eliminación.
									$result = $this->model->delete($idEventRegistry);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo de que se ha eliminado un Registro de Evento.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Registro de Evento";
										$body = "El Registro de Evento con los siguientes datos se ha eliminado:".
										"\nId   : ". $idEventRegistry.
										"\nIdVehicle : ". $idVehicle.
										"\nIdUser : ". $idUser.
										"\nIdEvent : ". $idEvent.
										"\nDate: ". $Date.
										"\nReason : ". $Reason;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											//echo "<br>Correo enviado con éxito.";
										}
										else
										{
											/*$error =  "<br>Error al enviar el correo.";
											$this -> showErrorView($error);*/
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar el registro de eventos.";
										$this -> showErrorView($error);
									}
								}
								//Si el id no está seteado, marcamos el error.
								else
								{
									$error = 'No se ha especificado el ID del registro a eliminar';
									$this -> showErrorView($error);	
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

					case "list" :
					{
						//Solo si es empleado o administrador puede consultar la lista de eventos
						if(!$this -> isClient())
						{
							//Revisar si hay un filtro, sino hay se queda el filtro po default
							$filter = "0=0";
							if(isset($_POST['filter_condition'])){
								//Creamos la condicion con el campo seleccionadoo y el filtro
								$filter = $_POST['filter_select']." = '".$_POST['filter_condition']."';"; 
							}


							//Ejecutamos el query y guardamos el resultado.
							$result = $this -> model -> getList($filter);

							if($result !== FALSE)
							{
								//Cargamos el formulario
								$view = file_get_contents("View/EventRegistryTable.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Obtengo la posicion donde va a insertar los registros
								$row_start = strrpos($view,'{row-start}') + 11;
								$row_end = strrpos($view,'{row-end}');

								//Hacer copia de la fila donde se va a reemplazar el contenido
								$base_row = substr($view,$row_start,$row_end-$row_start);

								//Acceder al resultado y crear el diccionario
								//Revisar que el nombre de los campos coincida con los de la base de datos
								$rows = '';
								foreach ($result as $row) {
									$new_row = $base_row;
									$dictionary = array(
										'{value-idEventRegistry}' => $row['idEventRegistry'], 
										'{value-idVehicle}' => $row['idVehicle'],
										'{value-idUser}' => $row['User'], 
										'{value-idEvent}' => $row['Event'],
										'{value-Date}' => $row['Date'], 
										'{value-Reason}' => $row['Reason'],
										'{active}' => 'disabled'
									);
									$new_row = strtr($new_row,$dictionary);
									$rows .= $new_row;
								}

								//Reemplazar en la vista la fila base por las filas creadas
								$view = str_replace($base_row, $rows, $view);
								$view = str_replace('{row-start}', '', $view);
								$view = str_replace('{row-end}', '', $view);

								//Sustituir el usuario en el header
								$dictionary = array(
									'{user-name}' => $_SESSION['user'],
									'{log-link}' => 'index.php?ctl=logout',
									'{log-type}' => 'Logout'
								);
								$header = strtr($header,$dictionary);

								//Agregamos el header y el footer
								$view = $header.$view.$footer;

								echo $view;
							}
							else
							{
								$error = "Error al listar registros.";
								$this -> showErrorView($error);
							}
						}
						else
						{
							$error = "No tiene permisos para ver esta lista";
							$this -> showErrorView($error);
						}

						break;
					}
			
				} /* fin switch */
				//El logout se hace cuando se especifica
				//$this -> logout();
			}
			else
			{
				//Si no ha iniciado sesion mostrar la vista para hacer login
				$this -> showLoginView($_GET['ctl'],$_GET['act']);
			}

		} /* fin run */

	}

?>
