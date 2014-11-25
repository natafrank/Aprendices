<?php
	include("Controller/StandardCtl.php");
	
	class ChecklistCtl extends StandardCtl
	{
		/**
		 * Variable Modelo de la clase Checklist.
		 *
		 * @access private
		 * @var ChecklistMdl $model - Variable para realizar las funciones de Modelo en la estructura MVC.
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
			
			require_once("Model/ChecklistMdl.php");
			$this -> model = new ChecklistMdl();			
			
			//Verificar que esten seteadas las variables para hacer login
			if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
			{
				$this -> login($_POST['session_login'],$_POST['session_pass']);	
			}
			
			//validar que el login se haya hecho correctamente
			if( $this -> isLogged() )
			{ 			
			
				switch($_GET['act'])
				{
					
					case "insert" :
					{
						//Solo administradores y empleados pueden hacer inserciones de Checklists
						if( !$this -> isClient() )
						{
											
							//Comprobar si $_POST está vacio, si es así se mostrará el formulario para capturar los datos.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/ChecklistForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-checklist}' => '', 
													'{value-id-vehicle}' => '', 
													'{value-id-vehicle-status}' => '', 
													'{value-date}' => '', 
													'{value-inout}' => '', 
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
								//require_once("View/InsertChecklist.php");
							}
							else
							{
								//Limpiamos los datos.
								$idChecklist 	 = $this -> cleanInt($_POST['idChecklist']);  // Para este dato se creara un Trigger en la BD
								$idVehicle   	 = $this -> cleanInt($_POST['idVehicle']);
								$idVehicleStatus = $this -> cleanInt($_POST['idVehicleStatus']);
								$Date        	 = $this -> cleanDateTime($_POST['Date']);
								$InOut       	 = $this -> cleanBit($_POST['InOut']);

								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this -> model -> insert($idChecklist,$idVehicle,$idVehicleStatus,$Date,$InOut))
								{
									if($result = $this -> model -> createEvent($_SESSION['id_user'],$idVehicle,$InOut)){
										//Cargamos el formulario
										$view = file_get_contents("View/ChecklistForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Creamos el diccionario
										//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
										$dictionary = array(
															'{value-id-checklist}' => $_POST['idChecklist'], 
															'{value-id-vehicle}' => $_POST['idVehicle'], 
															'{value-id-vehicle-status}' => $_POST['idVehicleStatus'], 
															'{value-date}' => $_POST['Date'], 
															'{value-inout}' => $_POST['InOut'],
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
										//require_once("View/ShowInsertChecklist.php");

										//Enviamos el correo de que se ha añadido un checklist.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Alta de Checklist";
										$body = "El checklist con los siguientes datos se ha añadido:".
										"\nId   : ". $idChecklist.
										"\nIdVehicle : ". $idVehicle.
										"\nidVehicleStatus: ". $idVehicleStatus.
										"\nFecha : ". $Date.
										"\nInOut : ". $InOut;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											//echo "<br>Correo enviado con éxito.";
										}
										else
										{
											//echo "<br />Error al enviar el correo.";
										}

										//Si es salida, enviamos correo al usuario de que ya se dió salida a su vehiculo
										if($InOut == 1){
											if($result = $this -> model -> getVehicleInfo($idVehicle)){
												//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
												$subject = "Salida de Vehículo";
												$body = "Ya se dio salida a su vehículo con la siguiente información:".
												"\nId              : ". $result[0]['idVehicle'].
												"\nId Usuario      : ". $result[0]['idUser'].
												"\nId Location     : ". $result[0]['idLocation'].
												"\nId Vehicle Model: ". $result[0]['idVehicleModel'].
												"\nVin             : ". $result[0]['vin'].
												"\nColor           : ". $result[0]['color'];

												//Manadamos el correo solo al usuario del vehículo - 1
												if(Mailer::sendMail($subject, $body, 1, $result[0]['idUser']))
												{
													//echo "<br>Correo enviado con éxito.";
												}
												else
												{
													/*$error = "Error al enviar el correo."; 
													$this -> showErrorView($error);*/
												}
											}
											else
											{
												$error = "Error al obtener la información del vehículo"; 
												$this -> showErrorView($error);
											}	
										}
									}
									else
									{
										$error = "Error al crear el evento"; 
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
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}
						break;
					}
				
					case "update" : 
					{
						//Solo administradores y empleados pueden actualizar Checklists
						if( !$this -> isClient() )
						{	
							//Comprobamos que $_POST no este vacio.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("checklist","update","idCheckList","Id Checklist:");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['idChecklist']))
								{
									//Limpiamos el ID
									$idChecklist = $this -> cleanInt($_POST['idChecklist']);
							
									//Primero mostramos el id que se quire modificar.
									//Comprobamos si estan seteadas las variables en el POST
									if(isset($_POST['idVehicle']) && isset($_POST['idVehicleStatus']) && isset($_POST['Date']) && isset($_POST['InOut']))
									{
										//La modificación se realizará en base al id.
										$idVehicle   	 = $this -> cleanInt($_POST['idVehicle']);
										$idVehicleStatus = $this -> cleanInt($_POST['idVehicleStatus']);
										$Date        	 = $this -> cleanDateTime($_POST['Date']);
										$InOut       	 = $this -> cleanBit($_POST['InOut']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y se muestra
										if($this -> model -> update($idChecklist, $idVehicle, $idVehicleStatus, $Date, $InOut))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/ChecklistForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-checklist}' => $idChecklist, 
																'{value-id-vehicle}' => $idVehicle, 
																'{value-id-vehicle-status}' => $idVehicleStatus, 
																'{value-date}' => $Date, 
																'{value-inout}' => $InOut, 
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
											//require_once("View/ShowUpdateChecklist.php");
											
											//Enviamos el correo de que se ha añadido un checklist.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Actualizacion de Checklist";
											$body = "El checklist con los siguientes datos se ha modificado:".
											"\nId   : ". $idChecklist.
											"\nIdVehicle : ". $idVehicle.
											"\nidVehicleStatus: ". $idVehicleStatus.
											"\nFecha : ". $Date.
											"\nInOut : ". $InOut;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br> Error al enviar el correo.";
												//$error = "Error al enviar el correo."; 
												//$this -> showErrorView($error);
											}
										}
										else
										{
											$error = "Error al modificar el Checklist.";
											$this -> showErrorView($error);
										}

									}
									//Si no estan seteadas mostramos la info para actualizar
									else
									{
										if(($result = $this -> model -> select($idChecklist)) != null)
										{

											//Cargamos el formulario
											$view = file_get_contents("View/ChecklistForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-checklist}' => $result[0]['idCheckList'], 
																'{value-id-vehicle}' => $result[0]['idVehicle'], 
																'{value-id-vehicle-status}' => $result[0]['idVehicleStatus'], 
																'{value-date}' => $result[0]['Date'], 
																'{value-inout}' => $result[0]['InOut'], 
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
											$error = 'Error al traer la ind¿formación para modificar';
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
							$error = "No tiene permisos para realizar esta acción";
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
							$this -> showGetIdView("checklist","select","idCheckList","Id Checklist:");
						}
						else
						{
							//Comprobamos que el id esté seteado.
							if(isset($_POST['idChecklist']))
							{
								//Limpiamos el id.
								$idChecklist = $this -> cleanText($_POST['idChecklist']);

								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($idChecklist)) !== FALSE)
								{
									//Cargamos el formulario
									$view = file_get_contents("View/ChecklistForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Acceder al resultado y crear el diccionario
									//Revisar que el nombre de los campos coincida con los de la base de datos
									$dictionary = array(
															'{value-id-user}' => $result[0]['idCheckList'], 
															'{value-id-vehicle}' => $result[0]['idVehicle'], 
															'{value-id-vehicle-status}' => $result[0]['idVehicleStatus'], 
															'{value-date}' => $result[0]['Date'], 
															'{value-inout}' => $result[0]['InOut'], 
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
						//Solo administradores y empleados pueden eliminar Checklists
						if( !$this -> isClient() )
						{
					
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("checklist","delete","idCheckList","Id Checklist:");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idChecklist']))
								{
									//Limpiamos el id.
									$idChecklist = $this -> cleanText($_POST['idChecklist']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($idChecklist);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo de que se ha eliminado un checklist.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Checklist";
										$body = "Se ha eliminado el Checklist con ID: ".$idChecklist;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											//echo "<br>Correo enviado con éxito.";
										}
										else
										{
											/*$error = "Error al enviar el correo."; 
											$this -> showErrorView($error);*/
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar el Checklist.";
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
								$view = file_get_contents("View/ChecklistTable.html");
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
														'{value-id-checklist}' => $row['idCheckList'], 
														'{value-id-vehicle}' => $row['idVehicle'], 
														'{value-id-vehicle-status}' => $row['idVehicleStatus'], 
														'{value-date}' => $row['Date'], 
														'{value-inout}' => $row['InOut'],  
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
								$error = "Error al listar checklists.";
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
