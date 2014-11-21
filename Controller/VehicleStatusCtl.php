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
								//Cargamos el formulario
								$view = file_get_contents("View/VehicleStatusForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los campos van vacios y los input estan activos
								$dictionary = array(
									'{value-idVehicleStatus}' => '',
									'{value-vehicleStatus}' => '',
									'{value-Fuel}' => '',
									'{value-Km}' => '',
									'{active}' => '',
									'{action}' => 'insert'
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
								$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);
								$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
								$Fuel = $this->cleanFloat($_POST['Fuel']);
								$Km = $this->cleanFloat($_POST['Km']);
						
								//Recogemos el resultado de la inserci�n e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this->model->insert($idVehicleStatus,$vehicleStatus,$Fuel,$Km))
								{
									//Cargamos el formulario
									$view = file_get_contents("View/VehicleStatusForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Creamos el diccionario
									//Despues de insertar los campos van con la info insertada y los input estan inactivos
									$dictionary = array(
										'{value-idVehicleStatus}' => $_POST['idVehicleStatus'],
										'{value-vehicleStatus}' => $_POST['vehicleStatus'],
										'{value-Fuel}' => $_POST['Fuel'],
										'{value-Km}' => $_POST['Km'],
										'{active}' => 'disabled',
										'{action}' => 'insert'
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
										//echo "<br>Correo enviado con �xito.";
									}
									else
									{
										$error =  "Error al enviar el correo.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al insertar el nuevo registro"; 
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acci�n";
							$this -> showErrorView($error);
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
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclestatus","update","idVehicleStatus","Id EstatusVehiculo:");
							}
							else
							{
								//Comprobamos que el id est� seteado.
								if(isset($_POST['idVehicleStatus']))
								{
									//Limpiamos el id.
									$idVehicleStatus = $this->cleanInt($_POST['idVehicleStatus']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si est�n seteadas las variables en el POST
									if(isset($_POST['vehicleStatus']) && isset($_POST['Fuel']) && isset($_POST['Km']))
									{
										//La modificaci�n se realizar� en base al id.
										$vehicleStatus = $this->cleanText($_POST['vehicleStatus']);
										$Fuel = $this->cleanFloat($_POST['Fuel']);
										$Km = $this->cleanFloat($_POST['Km']);

										//Se llama a la funci�n de modificaci�n.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this->model->update($idVehicleStatus,$vehicleStatus,$Fuel,$Km))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleStatusForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los campos van con la info insertada y los input estan inactivos
											$dictionary = array(
												'{value-idVehicleStatus}' => $idVehicleStatus, 
												'{value-vehicleStatus}' => $vehicleStatus,
												'{value-Fuel}' => $Fuel, 
												'{value-Km}' => $Km,
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
												//echo "<br>Correo enviado con �xito.";
											}
											else
											{
												$error =  "Error al enviar el correo.";
												$this -> showErrorView($error);
											}
										}
										else
										{
											$error = "Error al modificar el evento.";
											$this -> showErrorView($error);
										}

									}
									else
									{
										if(($result = $this->model->select($idVehicleStatus)) != null)
										{

											//Cargamos el formulario
											$view = file_get_contents("View/VehicleStatusForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los campos van con la info insertada y los input estan inactivos
											$dictionary = array(
												'{value-idVehicleStatus}' => $result[0]['idVehicleStatus'], 
												'{value-vehicleStatus}' => $result[0]['vehicleStatus'],
												'{value-Fuel}' => $result[0]['Fuel'], 
												'{value-Km}' => $result[0]['Km'],
												'{active}' => '',
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
										}
										//Si el resultado no contiene informaci�n, mostramos el error.
										else
										{
											$error = "Error al traer informacion para modificar.";
											$this -> showErrorView($error);
										}
									}
								}
								//Sino est� seteado, imprimimos el mensaje y se mostrar� la vista con 									el formulario para actualizar la informaci�n.
								else
								{
									$error = "Error al tratar de modificar el registro, el id no est� seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acci�n";
							$this -> showErrorView($error);
						}
						break;
					}
					
					case "select" :
					{
						//Solo administradores y empleados pueden ver los Eventos
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no est� vac�o.	
							if(empty($_POST))
							{
								//Si el post est� vacio cargamos la vista para solicitar el id a consultar
									//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
									$this -> showGetIdView("vehiclestatus","select","idVehicleStatus","Id EstatusVehiculo:");
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
										//Cargamos el formulario
										$view = file_get_contents("View/VehicleStatusForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										foreach ($result as $row) {
											$dictionary = array(
												'{value-idVehicleStatus}' => $result['idVehicleStatus'], 
												'{value-vehicleStatus}' => $result['vehicleStatus'],
												'{value-Fuel}' => $result['Fuel'], 
												'{value-Km}' => $result['Km'],
												'{active}' => 'disabled',
												'{action}' => 'select'
											);
										}

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
									//Si el resultado no contiene informaci�n, mostramos el error.
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										$this -> showErrorView($error);
									}
								}
								//Si el ID no est� seteado, se marcar� el error y se mostrar� la vista con 								el formulario para hacer select.
								else
								{
									$error = "Error al mostrar el evento, el id no est� seteado.";
										$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acci�n";
							$this -> showErrorView($error);
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
								//Si el post est� vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar de $_POST y el texto a mostrar en el label del input
								$this -> showGetIdView("vehiclestatus","delete","idVehicleStatus","Id EstatusVehiculo:");
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
										//Muestra la vista de que la eliminaci�n se realiz� con �xito
										$this -> showDeleteView();

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
											//echo "<br>Correo enviado con �xito.";
										}
										else
										{
											$error =  "Error al enviar el correo.";
											$this -> showErrorView($error);
										}
									}
									//Si no pudimos eliminar, se�alamos el error.
									else
									{
										$error = "Error al elimiar el registro.";
										$this -> showErrorView($error);
									}
								}
								//Si el id no est� seteado, marcamos el error y se mostrar� la vista para 									eliminar un Evento.
								else
								{
									$error = "Error al eliminar el evento, el id no est� seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acci�n";
							$this -> showErrorView($error);
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
								$filter = $_POST['filter_select']." = ".$_POST['filter_condition']; 
							}


							//Ejecutamos el query y guardamos el resultado.
							$result = $this -> model -> getList($filter);

							if($result !== FALSE)
							{
								//Cargamos el formulario
								$view = file_get_contents("View/VehicleStatusTable.html");
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
										'{value-idVehicleStatus}' => $result['idVehicleStatus'], 
										'{value-vehicleStatus}' => $result['vehicleStatus'],
										'{value-Fuel}' => $result['Fuel'], 
										'{value-Km}' => $result['Km'],
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
								$error = "Error al listar los estatus.";
								$this -> showErrorView($error);
							}
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
