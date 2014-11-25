<?php

	require_once("StandardCtl.php");

	class UserTypeCtl extends StandardCtl
	{
		private $model;

		function run()
		{
			//Importamos el modelo
			require_once("Model/UserTypeMdl.php");

			$this -> model = new UserTypeMdl();

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
						//Solo los admin podrán ingresar tipos de usuarios
						if($this -> isAdmin())
						{
							//Comprobamos que no esté vacío el POST
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/UserTypeForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-user-type}' => '', 
													'{value-user-type}' => '', 
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
							}
							else
							{
								//Comprobamos que las variables estén seteadas en el POST.
								if(isset($_POST['id_user_type']) && isset($_POST['user_type']))
								{
									//Obtenemos las variables y las limpiamos.
									$id_user_type = $this -> cleanInt($_POST['id_user_type']);
									$user_type    = $this -> cleanText($_POST['user_type']);

									//Si alguno de los campos es inválido.
									if(!$id_user_type || !$user_type)
									{
										$error = "Error al insertar el tipo de usuario, alguno de los campos es inválido.";
										$this -> showErrorView($error);
									}
									else
									{
										//Guardamos el resultado de ejecutar el query.
										$result = $this -> model -> insert($id_user_type, $user_type);

										if($result)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/UserTypeForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-user-type}' => $_POST['id_user_type'], 
																'{value-user-type}' => $_POST['user_type'], 
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

											//Enviamos el correo de que se ha añadido un usuario.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Alta de Tipo de Usuario";
											$body = "El tipo de usuario con los siguientes datos se ha añadido:".
											"\nId        : ". $id_user_type.
											"\nUser Type : ". $user_type;

											//Manadamos el correo solo a administradores - 4.
											if(Mailer::sendMail($subject, $body, 4))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br>Error al enviar el correo.";
												/*$error = "Error al enviar el correo.";
												$this -> showErrorView($error);*/
											}
										}
									}
								}
								else
								{
									$error = "Error al insertar el tipo de usuario, faltan variables por setear.";
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

					case "delete":
					{
						//Unicamente los administradores podran hacer eliminacion de tipo de usuario
						if($this -> isAdmin())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("usertype","delete","id_user_type","Id Tipo Usuario:");

							}
							else
							{
								//Comprobamos que la variable esté seteada.
								if(isset($_POST['id_user_type']))
								{
									//Limpiamos la variable.
									$id_user_type = $this -> cleanInt($_POST['id_user_type']);

									//Ejecutamos el query y guardamos el resultado.
									$result = $this -> model -> delete($id_user_type);
									
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Tipo de Usuario";
										$body    = "Se ha eliminado el tipo de usuario con el id: ".$id_user_type;

										//Enviamos el correo solo a admins - 4
										if(Mailer::sendMail($subject, $body, 4))
										{
											//echo "Correo enviado con éxito";
										}
										else
										{
											//echo "<br>Error al enviar el correo.";
											/*$error = "Error al enviar el correo.";
											$this -> showErrorView($error);*/
										}
									}
									else
									{
										$error = "Error al eliminar el tipo de usuario.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al eliminar el tipo de usuario, falta setear el id.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion.";
							$this -> showErrorView($error);	
						}
	
						break;
					}

					case "select":
					{
						//Solo admins y empleados podrán consultar los tipos de usuarios
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("usertype","select","id_user_type","Id Tipo Usuario:");
							}
							else
							{
								//Comprobamos que la variable esté seteada.
								if(isset($_POST['id_user_type']))
								{
									//Limpiamos el id.
									$id_user_type = $this -> cleanInt($_POST['id_user_type']);

									//Ejecutamos el query y guardamos el resultado.
									$result = $this -> model -> select($id_user_type);

									if($result != null)
									{
										//Cargamos el formulario
										$view = file_get_contents("View/UserTypeForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										$dictionary = array(
															'{value-id-user-type}' => $result[0]['idUserType'], 
															'{value-user-type}' => $result[0]['UserType'], 
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
									else
									{
										$error = "Error al mostrar el tipo de usuario.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al mostrar el tipo de usuario, el id no está seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion.";
							$this -> showErrorView($error);
						}

						break;
					}

					case "update":
					{
						//Solo los admins podrán modificar los tipos de usuarios
						if($this -> isAdmin())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("usertype","update","id_user_type","Id Tipo Usuario:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_user_type']))
								{
									//Limpiamos el id.
									$id_user_type = $this -> cleanInt($_POST['id_user_type']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['user_type']))
									{
										//La modificación se realizará en base al id.
										$user_type = $this -> cleanText($_POST['user_type']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($id_user_type, $user_type))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/UserTypeForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-user-type}' => $id_user_type, 
																'{value-user-type}' => $user_type, 
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

											//Enviamos el correo de que se ha añadido un usuario.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificación de Tipo de Usuario";
											$body = "El tipo de usuario con los siguientes datos se ha modificado:".
											"\nId        : ". $id_user_type.
											"\nUser Type : ". $user_type;

											//Manadamos el correo solo a administradores - 4.
											if(Mailer::sendMail($subject, $body, 4))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br>Error al enviar el correo.";
												/*$error = "Error al enviar el correo.";
												$this -> showErrorView($error);*/
											}
										}
										else
										{
											$error = "Error al tratar de modificar el registro.";
											$this -> showErrorView($error);
										}
									}
									else
									{
										if(($result = $this -> model -> select($id_user_type)) != null)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/UserTypeForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-user-type}' => $result[0]['idUserType'], 
																'{value-user-type}' => $result[0]['UserType'], 
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
										//Si el resultado no contiene información, mostramos el error.
										else
										{
											$error = "Error al traer la información para modificar.";
											$this -> showErrorView($error);
										}
									}
								}
								else
								{
									$error = "Error al tratar de modificar el registro, el id no está seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion.";
							$this -> showErrorView($error);	
						}

						break;
					}

					case "list":
					{
						//Solo si es empleado o administrados puede consultar la lista de usuarios
						if(!$this -> isClient())
						{
							//Revisar si hay un filtro, sino hay se queda el filtro po default
							$filter = "0=0";
							if(isset($_POST['filter_condition'])){
								//Creamos la condicion con el campo seleccionadoo y el filtro
								$filter = $_POST['filter_select']." = '".$_POST['filter_condition']."'"; 
							}


							//Ejecutamos el query y guardamos el resultado.
							$result = $this -> model -> getList($filter);

							if($result !== FALSE)
							{
								//Cargamos el formulario
								$view = file_get_contents("View/UserTypeTable.html");
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
														'{value-id-user-type}' => $row['idUserType'], 
														'{value-user-type}' => $row['UserType'], 
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
								$error = "Error al listar los tipos de usuarios.";
								$this -> showErrorView($error);
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}

						break;
					}
				}
			}
			else
			{
				//Si no ha iniciado sesion mostrar la vista para hacer login
				$this -> showLoginView($_GET['ctl'],$_GET['act']);
			}
		}
	}

?>