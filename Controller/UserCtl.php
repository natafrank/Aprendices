<?php
	include("Controller/StandardCtl.php");
	
	class UserCtl extends StandardCtl{
		
		private $model;

		public function run()
		{
			
			//Importamos el archivo del modelo
			require_once("Model/UserMdl.php");
			
			//Creamos el modelo
			$this -> model = new UserMdl();
			
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
						//Unicamente los administradores podran hacer insercion de usuarios
						if( $this -> isAdmin() )
						{
						
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/UserForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-user}' => '', 
													'{value-name}' => '', 
													'{value-login}' => '', 
													'{value-pass}' => '', 
													'{value-email}' => '', 
													'{value-tel}' => '', 
													'{value-type}' => '', 
													'{active}' => ''
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
								//Comprobamos que las variables estén seteadas.
								if(isset($_POST['id_user']) && isset($_POST['name'])
									&& isset($_POST['login']) && isset($_POST['pass'])
									&& isset($_POST['email']) && isset($_POST['tel'])
									&& isset($_POST['type']))
								{
									//Limpiamos las variables.
									$id_user = $this -> cleanText($_POST['id_user']);
									$name    = $this -> cleanName($_POST['name']);
									$login   = $this -> cleanLogin($_POST['login']);
									$pass    = $this -> cleanPassword($_POST['pass']);
									$email   = $this -> cleanEmail($_POST['email']);
									$tel     = $this -> cleanTel($_POST['tel']);  
									$type    = $this -> cleanText($_POST['type']);

									//Si alguno de los campos es inválido.
									if(!$name || !$login || !$pass || !$type || !$email || !$tel )
									{
										$error = "Error al insertar el usuario, alguno de los campos es inválido.";
										$this -> showErrorView($error);
									}
									else
									{
										//Guardamos el resultado de ejecutar el query.
										$result = $this -> model -> insert($id_user, $name,$login,$pass ,$email,$tel, $type);

										if($result)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/UserForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-user}' => $_POST['id_user'], 
																'{value-name}' => $_POST['name'], 
																'{value-login}' => $_POST['login'], 
																'{value-pass}' => $_POST['pass'], 
																'{value-email}' => $_POST['email'], 
																'{value-tel}' => $_POST['tel'], 
																'{value-type}' => $_POST['type'], 
																'{active}' => 'disabled'
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
											$subject = "Alta de Usuario";
											$body = "El usuario con los siguientes datos se ha añadido:".
											"\nId   : ". $id_user.
											"\nName : ". $name.
											"\nLogin: ". $login.
											"\nPass : ". $pass.
											"\nEmail: ". $email.
											"\nTel  : ". $tel.
											"\nType : ". $type;

											//Manadamos el correo solo a administradores - 4.
											if(Mailer::sendMail($subject, $body, 4))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												echo "<br />Error al enviar el correo.";
											}
										}
										else
										{
											$error = "Error al insertar el usuario.";
											$this -> showErrorView($error);
										}
									}
								}
								else
								{
									$error = "Error al insertar el usuario, faltan variables por setear.";
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
					case "delete" :
					{
						//Unicamente los administradores podran hacer eliminacion de usuarios
						if( $this -> isAdmin() )
						{
				
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("user","delete","id_user","Id Usuario:");

							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_user']))
								{
									//Limpiamos el id.
									$id_user = $this -> cleanText($_POST['id_user']);

									//Ejecutamos el query y guardamos el resultado.
									$result = $this -> model -> delete($id_user);

									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Usuario";
										$body    = "Se ha eliminado el usuario con el id: ".$id_user;

										if(Mailer::sendMail($subject, $body, 4))
										{
											//echo "Correo enviado con éxito";
										}
										else
										{
											echo "<br />Error al enviar el correo";
										}
									}
									else
									{
										$error = "Error al eliminar el usuario.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al eliminar el usuario, el id no está seteado.";
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
					case "select" :
					{
						//Si es empleado o administrador podra consultar cualquier perfil
						//Si es cliente puede consultar unicamente su propio perfil
						
						//Comprobamos que el POST no esté vacío cuando el usuario no sea cliente
						if(!$this -> isClient() && empty($_POST))
						{
							//Si el post está vacio cargamos la vista para solicitar el id a consultar
							//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
							$this -> showGetIdView("user","select","id_user","Id Usuario:");
						}
						else
						{
							//Comprobamos que el id esté seteado si el usuario no es cliente.
							if( $this -> isClient() || isset($_POST['id_user']))
							{
								//Si es cliente tomamos el id de la session
								if( $this -> isClient() )
								{
									$id_user = $_SESSION['id_user'];
								}
								//Limpiamos el id en caso contrario.
								else
								{
									$id_user = $this -> cleanInt($_POST['id_user']);
								}

								//Ejecutamos el query y guardamos el resultado.
								$result = $this -> model -> select($id_user);

								if($result !== FALSE)
								{
									//Cargamos el formulario
									$view = file_get_contents("View/UserForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Acceder al resultado y crear el diccionario
									//Revisar que el nombre de los campos coincida con los de la base de datos
									foreach ($result as $row) {
										$dictionary = array(
															'{value-id-user}' => $result['idUser'], 
															'{value-name}' => $result['User'], 
															'{value-login}' => $result['Login'], 
															'{value-pass}' => $result['Password'], 
															'{value-email}' => $result['Email'], 
															'{value-tel}' => $result['Tel'], 
															'{value-type}' => $result['idUserType'], 
															'{active}' => 'disabled'
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
								else
								{
									$error = "Error al mostrar el usuario.";
									$this -> showErrorView($error);
								}
							}
							else
							{
								$error = "Error al mostrar el usuario, el id no está seteado.";
								$this -> showErrorView($error);
							}
						}

						break;
					}
					case "update" :
					{
					
						//Si es administrador podra modificar cualquier perfil
						//Si es cliente o empleado puede modificar unicamente su propio perfil
						
						//Comprobamos que el POST no esté vacío en caso de que el usuario sea tipo Admin.
						if( $this -> isAdmin() && empty($_POST))
						{
							//Si el post está vacio cargamos la vista para solicitar el id a consultar
							//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
							$this -> showGetIdView("user","update","id_user","Id Usuario:");
						}
						else
						{
							//Comprobamos que el id esté seteado en caso de que el usuario sea tipo admin.
							if(!$this -> isAdmin() || isset($_POST['id_user']))
							{
								//Si el usuario no es admin tomamos el id de la sesion y el tipo de usuario ya que solo el admin lo puede modificar.
								if( !$this -> isAdmin() )
								{
									$id_user = $_SESSION['id_user'];
									$type    = $_SESSION['user_type'];	
								}
								//En caso contrario limpiamos el id.
								else
								{
									$id_user = $this -> cleanText($_POST['id_user']);
								}

								//Primero mostramos el id que se quire modificar.
								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($id_user)) != null)
								{									
									//Comprobamos que las variables a modificar estén seteadas
									if(isset($_POST['name'])
										&& isset($_POST['login']) && isset($_POST['pass'])
										&& isset($_POST['email']) && isset($_POST['tel'])
										&& (!$this -> isAdmin() || isset($_POST['type'])))  //Solo si es Admin validamos que este seteado el tipo de usuario
									{
										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.
										//Limpiamos las variables.
										$name    = $this -> cleanName($_POST['name']);
										$login   = $this -> cleanLogin($_POST['login']);
										$pass    = $this -> cleanPassword($_POST['pass']);
										$email   = $this -> cleanEmail($_POST['email']);
										$tel     = $this -> cleanTel($_POST['tel']);
										//Si es admin ponemos el tipo de usuario
										if( $this -> isAdmin() )
										{  
											$type    = $this -> cleanText($_POST['type']);
										}

										//Si alguno de los campos es inválido.
										if(!$name || !$login || !$pass || !$email || !$tel )
										{
											$error = "Error al insertar el usuario, alguno de los campos es inválido.";
											$this -> showErrorView($error);
										}
										else
										{
											//Se llama a la función de modificación.
											//Se recoge el resultado y en base a este resultado
											//se imprime un mensaje.
											if($this -> model -> update($id_user, $name,$login,$pass ,$email,$tel, $type))
											{
												//Cargamos el formulario
												$view = file_get_contents("View/UserForm.html");
												$header = file_get_contents("View/header.html");
												$footer = file_get_contents("View/footer.html");

												//Creamos el diccionario
												//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
												$dictionary = array(
																	'{value-id-user}' => $id_user, 
																	'{value-name}' => $name, 
																	'{value-login}' => $login, 
																	'{value-pass}' => $pass, 
																	'{value-email}' => $email, 
																	'{value-tel}' => $tel, 
																	'{value-type}' => $type, 
																	'{active}' => 'disabled'
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

												//Enviamos correo de usuario actualizado a los admin
												require_once("Controller/mail.php");

												$subject = "Modificación de Usuario";
												$body = "El usuario con los siguientes datos se ha modificado:".
														"\nId   : ". $id_user.
														"\nName : ". $name.
														"\nLogin: ". $login.
														"\nPass : ". $pass.
														"\nEmail: ". $email.
														"\nTel  : ". $tel.
														"\nType : ". $type;

												//Manadamos el correo solo a administradores - 4.
												if(Mailer::sendMail($subject, $body, 4))
												{
													//echo "<br>Correo enviado con éxito.";
												}
												else
												{
													echo "<br />Error al enviar el correo.";
												}
											}
											else
											{
												$error = "Error al tratar de modificar el registro.";
												$this -> showErrorView($error);
											}
										}
									}	
									else
									{
										$error = "Error al tratar de modificar el registro, el tipo de usuario no está seteado.";
										$this -> showErrorView($error);
									}
								}
								//Si el resultado no contiene información, mostramos el error.
								else
								{
									$error = "Error al tratar de mostrar el registro.";
									$this -> showErrorView($error);
								}
							}
							else
							{
								$error = "Error al tratar de modificar el registro, el id no está seteado.";
								$this -> showErrorView($error);
							}
						}

						break;
					}
					case "list" :
					{
						//Solo si es empleado o administrados puede consultar la lista de usuarios
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
								$view = file_get_contents("View/UserTable.html");
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
														'{value-id-user}' => $result['idUser'], 
														'{value-name}' => $result['User'], 
														'{value-login}' => $result['Login'], 
														'{value-pass}' => $result['Password'], 
														'{value-email}' => $result['Email'], 
														'{value-tel}' => $result['Tel'], 
														'{value-type}' => $result['idUserType'], 
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
								$error = "Error al listar usuarios.";
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
