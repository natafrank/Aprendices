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
								//Se carga la vista del formulario.
								require_once("View/InsertUserType.php");
							}
							else
							{
								//Comprobamos que las variables estén seteadas en el POST.
								if(isset($_POST['id_user_type']) && isset($_POST['user_type']))
								{
									//Obtenemos las variables y las limpiamos.
									$id_user_type = $this -> cleanText($_POST['id_user_type']);
									$user_type    = $this -> cleanText($_POST['user_type']);

									//Guardamos el resultado de ejecutar el query.
									$result = $this -> model -> insert($id_user_type, $user_type);

									if($result)
									{
										require_once("View/ShowUserType.php");

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
											echo "<br>Correo enviado con éxito.";
										}
										else
										{
											echo "<br>Error al enviar el correo.";
										}
									}
									else
									{
										$error = "Error al insertar el tipo de usuario.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al insertar el tipo de usuario, faltan variables por setear.";
									require_once("View/Error.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							require_once("View/Error.php");
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
								$error = "Error al eliminar el tipo de usuario, el POST está vacío.";
								require_once("View/Error.php");
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
										require_once("View/DeleteUserType.php");

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Tipo de Usuario";
										$body    = "Se ha eliminado el tipo de usuario con el id: ".$id_user_type;

										//Enviamos el correo solo a admins - 4
										if(Mailer::sendMail($subject, $body, 4))
										{
											echo "Correo enviado con éxito";
										}
										else
										{
											echo "Error al enviar el correo";
										}
									}
									else
									{
										$error = "Error al eliminar el tipo de usuario.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al eliminar el tipo de usuario, falta setear el id.";
									require_once("View/Error.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion.";
							require_once("View/Error.php");	
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
								$error = "Error al mostrar el tipo de usuario, el POST está vacío.";
								require_once("View/Error.php");
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
										var_dump($result);
									}
									else
									{
										$error = "Error al mostrar el tipo de usuario.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al mostrar el tipo de usuario, el id no está seteado.";
									require_once("View/Error.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion.";
							require_once("View/Error.php");	
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
								$error = "Error al tratar de modificar el registro, el POST está vacío.";
								require_once("View/Error.php");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_user_type']))
								{
									//Limpiamos el id.
									$id_user_type = $this -> cleanInt($_POST['id_user_type']);

									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($id_user_type)) != null)
									{
										var_dump($result);

										//Comprobamos que las variables estén seteadas
										if(isset($_POST['user_type']))
										{
											//La modificación se realizará en base al id.
											//Por ahora se modificarán todos los atributos.
											$user_type = $this -> cleanText($_POST['user_type']);

											//Se llama a la función de modificación.
											//Se recoge el resultado y en base a este resultado
											//se imprime un mensaje.
											if($this -> model -> update($id_user_type, $user_type))
											{
												require_once("View/UpdateUserTypeShow.php");

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
													echo "<br>Correo enviado con éxito.";
												}
												else
												{
													echo "<br>Error al enviar el correo.";
												}
											}
											else
											{
												$error = "Error al tratar de modificar el registro.";
												require_once("View/Error.php");
											}
										}
										else
										{
											$error = "Error al tratar de modificar el registro, el tipo de usuario no está seteado.";
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
								else
								{
									$error = "Error al tratar de modificar el registro, el id no está seteado.";
									require_once("View/Error.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion.";
							require_once("View/Error.php");	
						}

						break;
					}
				}
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");	
			}
		}
	}

?>