<?php
	include("Controller/StandardCtl.php");
	
	class VehiclePartCtl extends StandardCtl
	{
		/**
		 * Variable Modelo de la clase VehiclePart.
		 *
		 * @access private
		 * @var VehiclePartMdl $model - Variable para realizar las funciones de Modelo en la estructura MVC.
		 */
		private $model;

		/**
		 * Funcion principal del controlador.
		 *
		 * Se encarga del manejo de vistas y funciones del modelo
		 * de acuerdo a la accion que se indica con la llave 'act' en $_GET
		 *
		 */
		public function run()
		{
			
			require_once("Model/VehiclePartMdl.php");
			$this -> model = new VehiclePartMdl();
			
			//Verificar si hay una sesion iniciada
			if(!$this -> isLogged())
			{
				//Si no verificar que esten seteadas las variables para hacer login
				if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
				{
					$this -> login($_POST['session_login'],$_POST['session_pass']);	
				}
			}
			
			//validar que el login se haya hecho correctamente
			if( $this -> isLogged() )
			{ 			
			
				switch($_GET['act'])
				{
					
					case "insert" :
					{	
						//Solo administradores y empleados pueden hacer inserciones de Partes de Vehiculos
						if( !$this -> isClient() )
						{				
							//Comprobar si $_POST está vacio, si es así se mostrará el formulario para capturar los datos.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/VehiclePartForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-vehicle-part}' => '', 
													'{value-vehicle-part}' => '', 
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
								//require_once("View/InsertVehiclePart.php");
							}
							else
							{
								//Limpiamos los datos.
								$idVehiclePart = $this->cleanText($_POST['idVehiclePart']);  // Para este dato se creara un Trigger en la BD
								$VehiclePart   = $this->cleanText($_POST['VehiclePart']);

								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this -> model -> insert($idVehiclePart, $VehiclePart))
								{
									//Cargamos el formulario
									$view = file_get_contents("View/VehiclePartForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Creamos el diccionario
									//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
									$dictionary = array(
														'{value-id-vehicle-part}' => $_POST['idVehiclePart'], 
														'{value-vehicle-part}' => $_POST['VehiclePart'], 
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
									//require_once("View/ShowInsertVehiclePart.php");

									//Enviamos el correo de que se ha añadido una parte de vehiculo.
									require_once("Controller/mail.php");

									//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Parte de Vehiculo";
									$body = "La parte de vehiculo con los siguientes datos se ha añadido:".
									"\nId   : ". $idVehiclePart.
									"\nDaño : ". $VehiclePart;

									//Manadamos el correo solo a administradores y empleados - 6
									if(Mailer::sendMail($subject, $body, 6))
									{
										//echo "<br>Correo enviado con éxito.";
									}
									else
									{
										//echo "<br />Error al enviar el correo.";
										/*$error = "Error al enviar el correo.";
										$this -> showErrorView($error);*/
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
							$error = "No tiene permisos para realizar esta accion";
							$this -> showErrorView($error);
						}
						break;
					}
				
					case "update" : 
					{	
						//Solo administradores y empleados pueden hacer actualizaciones de Partes de Vehiculos
						if( !$this -> isClient() )
						{
							//Comprobamos que $_POST no este vacio.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclepart","update","idVehiclePart","Id Parte de Vehículo:");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['idVehiclePart']))
								{
									//Limpiamos el ID
									$idVehiclePart = $this -> cleanInt($_POST['idVehiclePart']);
							
									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['VehiclePart']))
									{
										//La modificación se realizará en base al id.  
										$VehiclePart   = $this->cleanText($_POST['VehiclePart']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($idVehiclePart, $VehiclePart))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehiclePartForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
														'{value-id-vehicle-part}' => $idVehiclePart, 
														'{value-vehicle-part}' => $VehiclePart, 
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
											
											//Enviamos el correo de que se ha modificado una parte de vehiculo.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Actualizacion de Parte de Vehiculo";
											$body = "La parte de vehiculo con los siguientes datos se ha modificado:".
											"\nId   : ". $idVehiclePart.
											"\nDaño : ". $VehiclePart;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br />Error al enviar el correo.";
												/*$error = "Error al enviar el correo.";
												$this -> showErrorView($error);*/
											}	
										}
										else
										{
											$error = "Error al modificar la parte de vehiculo.";
											$this -> showErrorView($error);
										}

									}
									else
									{
										if(($result = $this -> model -> select($idVehiclePart)) != null)
										{

											//Cargamos el formulario
											$view = file_get_contents("View/VehiclePartForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
														'{value-id-vehicle-part}' => $result[0]['idVehiclePart'], 
														'{value-vehicle-part}' => $result[0]['VehiclePart'], 
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
										else
										{
											$error = "Error al traer la información para modificar.";
											$this -> showErrorView($error);
										}
									}
								}
								else
								{
									$error = 'No se especifico el ID del registro a modificar';
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							$this -> showErrorView($error);
						}
						break;
					}
					
					case "select" :
					{		
						//Comprobamos que el $_POST no esté vacío.	
						if(empty($_POST))
						{
							//Si el post está vacio cargamos la vista para solicitar el id a consultar
							//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
							$this -> showGetIdView("vehiclepart","select","idVehiclePart","Id Parte de Vehículo:");
						}
						else
						{
							//Comprobamos que el id esté seteado.
							if(isset($_POST['idVehiclePart']))
							{
								//Limpiamos el id.
								$idVehiclePart = $this -> cleanText($_POST['idVehiclePart']);

								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($idVehiclePart)) != null)
								{
									//Cargamos el formulario
									$view = file_get_contents("View/VehiclePartForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Acceder al resultado y crear el diccionario
									//Revisar que el nombre de los campos coincida con los de la base de datos
									$dictionary = array(
														'{value-id-vehicle-part}' => $result[0]['idVehiclePart'], 
														'{value-vehicle-part}' => $result[0]['VehiclePart'], 
														'{active}' => 'disabled', 
														'{action}' => 'select'
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
							//Imprimimos el error si la variable no está seteada.
							else
							{
								$error = "El id no esta seteado.";
								$this -> showErrorView($error);
							}
						}
						break;
					}
					
					case "delete" :
					{
						//Solo administradores y empleados pueden hacer eliminaciones de Partes de Vehiculos
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclepart","delete","idVehiclePart","Id Parte de Vehículo:");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idVehiclePart']))
								{
									//Limpiamos el id.
									$idVehiclePart = $this -> cleanText($_POST['idVehiclePart']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($idVehiclePart);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo de que se ha eliminado una parte de vehiculo.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Parte de Vehiculo";
										$body = "Se ha eliminado la parte de vehiculo con ID: ".$idVehiclePart;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											//echo "<br>Correo enviado con éxito.";
										}
										else
										{
											//echo "<br />Error al enviar el correo.";
											/*$error = "Error al enviar el correo.";
											$this -> showErrorView($error);*/
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar la parte de vehiculo.";
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
							$error = "No tiene permisos para realizar esta accion";
							$this -> showErrorView($error);
						}
						break;
					}

					case "list" :
					{
						//Solo empleados y administradores pueden ver la lista
						if( $this -> isEmployee() || $this -> isAdmin() )
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
								$view = file_get_contents("View/VehiclePartTable.html");
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
														'{value-id-vehicle-part}' => $row['idVehiclePart'], 
														'{value-vehicle-part}' => $row['VehiclePart'], 
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
								$error = "Error al listar partes de vehículo.";
								$this -> showErrorView($error);
							}
						}
						else
						{
							$error = "No tiene permisos para ver esta lista.";
							$this -> showErrorView($error);	
						}

						break;
					}
			
				} /* fin switch */
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
