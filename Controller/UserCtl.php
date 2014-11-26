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


								//Traer los usertype, la condicion es 0=0 para que los traiga todos (crear funcion en modelo)
								$result = $this -> model -> getUserTypes("0=0");
								//Obtengo la posicion donde se van a insertar los option
								$row_start = strrpos($view,'{user-type-options-start}') + 25;
								$row_end= strrpos($view,'{user-type-options-end}');
								//Hacer copia de la fila donde se va a reemplazar el contenido
								$base_row = substr($view,$row_start,$row_end-$row_start);
								//Acceder al resultado y crear el diccionario
								//Revisar que el nombre de los campos coincida con los de la base de datos
								$rows = '';
								foreach ($result as $row) {
									$new_row = $base_row;
									$dictionary = array(
														'{id-user-type}' => $row['idUserType'], 
														'{user-type}' => $row['UserType']
													);
									$new_row = strtr($new_row,$dictionary);
									$rows .= $new_row;
								}
								//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
								$view = str_replace($base_row, $rows, $view);
								$view = str_replace('{user-type-options-start}', '', $view);
								$view = str_replace('{user-type-options-end}', '', $view);


								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-user}' => '', 
													'{value-name}' => '', 
													'{value-login}' => '', 
													'{value-pass}' => '', 
													'{value-email}' => '', 
													'{value-tel}' => '', 
													//'{value-type}' => '',  //El value en type ya no es necesario
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
								//Comprobamos que las variables estén seteadas.
								if(isset($_POST['name'])
									&& isset($_POST['login']) && isset($_POST['pass'])
									&& isset($_POST['email']) && isset($_POST['tel'])
									&& isset($_POST['type']))
								{
									//Limpiamos las variables.
									//Obtenemos la llave primaria
									require_once("Model/PKGenerator.php");									
									$id_user = PKGenerator::getPK('User','idUser');
									$name    = $this -> cleanName($_POST['name']);
									$login   = $this -> cleanLogin($_POST['login']);
									$pass    = $this -> cleanPassword($_POST['pass']);
									$email   = $this -> cleanEmail($_POST['email']);
									$tel     = $this -> cleanTel($_POST['tel']);  
									$type    = $this -> cleanInt($_POST['type']);

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

											//Traer el usertype insertado, ahora si se pone condicion en el comando
											$result = $this -> model -> getUserTypes("idUserType=".$type);
											//Obtengo la posicion donde se va a insertar el option
											$row_start = strrpos($view,'{user-type-options-start}') + 25;
											$row_end= strrpos($view,'{user-type-options-end}');
											//Hacer copia de la fila donde se va a reemplazar el contenido
											$base_row = substr($view,$row_start,$row_end-$row_start);
											//Acceder al resultado y crear el diccionario
											//Revisar que el nombre de los campos coincida con los de la base de datos
											$rows = '';
											foreach ($result as $row) {
												$new_row = $base_row;
												$dictionary = array(
																	'{id-user-type}' => $row['idUserType'], 
																	'{user-type}' => $row['UserType']
																);
												$new_row = strtr($new_row,$dictionary);
												$rows .= $new_row;
											}
											//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
											$view = str_replace($base_row, $rows, $view);
											$view = str_replace('{user-type-options-start}', '', $view);
											$view = str_replace('{user-type-options-end}', '', $view);


											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-user}' => $id_user, 
																'{value-name}' => $_POST['name'], 
																'{value-login}' => $_POST['login'], 
																'{value-pass}' => $_POST['pass'], 
																'{value-email}' => $_POST['email'], 
																'{value-tel}' => $_POST['tel'], 
																//'{value-type}' => $_POST['type'], 
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
											$subject = "Alta de Usuario";
											$body = "El usuario con los siguientes datos se ha añadido:".
											"\nId   : ". $id_user.
											"\nName : ". $name.
											"\nLogin: ". $login.
											"\nPass : ". $pass.
											"\nEmail: ". $email.
											"\nTel  : ". $tel.
											"\nType : ". $type;

											//Manadamos el correo solo a administradores y al cliente que se agregó - 5.
											if(Mailer::sendMail($subject, $body, 5, $id_user))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br />Error al enviar el correo.";
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
									$id_user = $this -> cleanInt($_POST['id_user']);

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
											//echo "<br />Error al enviar el correo";
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
									if(isset($_POST['id_user']))
										$id_user = $this -> cleanInt($_POST['id_user']);
									else
										$id_user = $_SESSION['id_user'];
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
									//foreach ($result as $row) {
									$dictionary = array(
														'{value-id-user}' => $result[0]['idUser'], 
														'{value-name}' => $result[0]['User'], 
														'{value-login}' => $result[0]['Login'], 
														'{value-pass}' => $result[0]['Password'], 
														'{value-email}' => $result[0]['Email'], 
														'{value-tel}' => $result[0]['Tel'], 
														//'{value-type}' => $result[0]['idUserType'], 
														'{active}' => 'disabled',
														'{action}' => 'select'
													);
									//}

									//Sustituir los valores en la plantilla
									$view = strtr($view,$dictionary);

									//poner despues de sustituir los demás valores para no perder los datos traidos del select
									//Traer el usertype insertado, ahora si se pone condicion en el comando
									$result = $this -> model -> getUserTypes("idUserType=".$result[0]['idUserType']);
									//Obtengo la posicion donde se va a insertar el option
									$row_start = strrpos($view,'{user-type-options-start}') + 25;
									$row_end= strrpos($view,'{user-type-options-end}');
									//Hacer copia de la fila donde se va a reemplazar el contenido
									$base_row = substr($view,$row_start,$row_end-$row_start);
									//Acceder al resultado y crear el diccionario
									//Revisar que el nombre de los campos coincida con los de la base de datos
									$rows = '';
									foreach ($result as $row) {
										$new_row = $base_row;
										$dictionary = array(
															'{id-user-type}' => $row['idUserType'], 
															'{user-type}' => $row['UserType']
														);
										$new_row = strtr($new_row,$dictionary);
										$rows .= $new_row;
									}
									//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
									$view = str_replace($base_row, $rows, $view);
									$view = str_replace('{user-type-options-start}', '', $view);
									$view = str_replace('{user-type-options-end}', '', $view);

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
					
						//Sólo si es administrador podra modificar cualquier perfil
						if($this -> isAdmin())
						{
							
							//Comprobamos que el POST no este vacio.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("user","update","id_user","Id Usuario:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_user']))
								{
									
									$id_user = $this -> cleanInt($_POST['id_user']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['name']) && isset($_POST['login']) && isset($_POST['pass']) && isset($_POST['email']) 
									&& isset($_POST['tel']) && isset($_POST['type']))
									{
										//La modificación se realizará en base al id.
										//Limpiamos las variables.
										$name    = $this -> cleanName($_POST['name']);
										$login   = $this -> cleanLogin($_POST['login']);
										$pass    = $this -> cleanPassword($_POST['pass']);
										$email   = $this -> cleanEmail($_POST['email']);
										$tel     = $this -> cleanTel($_POST['tel']);
										$type    = $this -> cleanInt($_POST['type']);

										//Si alguno de los campos es inválido.
										if(!$name || !$login || !$pass || !$email || !$tel )
										{
											$error = "Error al actualizar el usuario, alguno de los campos es inválido.";
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

												//Traer el usertype insertado, ahora si se pone condicion en el comando
												$result = $this -> model -> getUserTypes("idUserType=".$type);
												//Obtengo la posicion donde se va a insertar el option
												$row_start = strrpos($view,'{user-type-options-start}') + 25;
												$row_end= strrpos($view,'{user-type-options-end}');
												//Hacer copia de la fila donde se va a reemplazar el contenido
												$base_row = substr($view,$row_start,$row_end-$row_start);
												//Acceder al resultado y crear el diccionario
												//Revisar que el nombre de los campos coincida con los de la base de datos
												$rows = '';
												foreach ($result as $row) {
													$new_row = $base_row;
													$dictionary = array(
																		'{id-user-type}' => $row['idUserType'], 
																		'{user-type}' => $row['UserType']
																	);
													$new_row = strtr($new_row,$dictionary);
													$rows .= $new_row;
												}
												//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
												$view = str_replace($base_row, $rows, $view);
												$view = str_replace('{user-type-options-start}', '', $view);
												$view = str_replace('{user-type-options-end}', '', $view);

												//Creamos el diccionario
												//Despues de actualizar los cmapos van con la info nueva y los input estan inactivos
												$dictionary = array(
																	'{value-id-user}' => $id_user, 
																	'{value-name}' => $name, 
																	'{value-login}' => $login, 
																	'{value-pass}' => $pass, 
																	'{value-email}' => $email, 
																	'{value-tel}' => $tel, 
																	//'{value-type}' => $type, 
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
													//echo "<br />Error al enviar el correo.";
													/*$error = "<br />Error al enviar el correo.";
													$this -> showErrorView($error);*/
												}
											}
											else
											{
												$error = "Error al tratar de modificar el registro.";
												$this -> showErrorView($error);
											}
										}
									}
									//Si no estan seteadas traemos la info y la mostramos en el formulario.
									else
									{
										//Recogemos el resultado y si contiene información, la mostramos.
										if(($result = $this -> model -> select($id_user)) != null)
										{	
											
											//Cargamos el formulario
											$view = file_get_contents("View/UserForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues se muestra la informacion para modificar por lo que los campos quedan activos
											$dictionary = array(
																'{value-id-user}' => $result[0]['idUser'], 
																'{value-name}' => $result[0]['User'], 
																'{value-login}' => $result[0]['Login'], 
																'{value-pass}' => $result[0]['Password'], 
																'{value-email}' => $result[0]['Email'], 
																'{value-tel}' => $result[0]['Tel'], 
																//'{value-type}' => $result[0]['idUserType'], 
																'{active}' => '',
																'{action}' => 'update'
															);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Poner despues de sustituir los demas datos para no perder la información del select
											//Para actualizar no se pone condicion, para que esten todas las opciones disponibles
											$result = $this -> model -> getUserTypes("0=0");
											//Obtengo la posicion donde se va a insertar el option
											$row_start = strrpos($view,'{user-type-options-start}') + 25;
											$row_end= strrpos($view,'{user-type-options-end}');
											//Hacer copia de la fila donde se va a reemplazar el contenido
											$base_row = substr($view,$row_start,$row_end-$row_start);
											//Acceder al resultado y crear el diccionario
											//Revisar que el nombre de los campos coincida con los de la base de datos
											$rows = '';
											foreach ($result as $row) {
												$new_row = $base_row;
												$dictionary = array(
																	'{id-user-type}' => $row['idUserType'], 
																	'{user-type}' => $row['UserType']
																);
												$new_row = strtr($new_row,$dictionary);
												$rows .= $new_row;
											}
											//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
											$view = str_replace($base_row, $rows, $view);
											$view = str_replace('{user-type-options-start}', '', $view);
											$view = str_replace('{user-type-options-end}', '', $view);

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
										}
										else
										{
											$error = "Error al traer la información para modificar..";
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
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
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
								$filter = $_POST['filter_select']." = '".$_POST['filter_condition']."'"; 
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
														'{value-id-user}' => $row['idUser'], 
														'{value-name}' => $row['User'], 
														'{value-login}' => $row['Login'], 
														'{value-pass}' => $row['Password'], 
														'{value-email}' => $row['Email'], 
														'{value-tel}' => $row['Tel'], 
														'{value-type}' => $row['UserType'], 
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
