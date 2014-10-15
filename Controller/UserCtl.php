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
								require_once("View/InsertUser.php");
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
										require_once("View/Error.php");
									}
									else
									{
										//Guardamos el resultado de ejecutar el query.
										$result = $this -> model -> insert($id_user, $name,$login,$pass ,$email,$tel, $type);

										if($result)
										{
											require_once("View/ShowUser.php");
										}
										else
										{
											$error = "Error al insertar el usuario.";
											require_once("View/Error.php");
										}
									}
								}
								else
								{
									$error = "Error al insertar el usuario, faltan variables por setear.";
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
					case "delete" :
					{
						//Unicamente los administradores podran hacer eliminacion de usuarios
						if( $this -> isAdmin() )
						{
				
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								$error = "Error al eliminar el usuario, el POST está vacío.";
								require_once("View/Error.php");
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
										require_once("View/DeleteUser.php");
									}
									else
									{
										$error = "Error al eliminar el usuario.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al eliminar el usuario, el id no está seteado.";
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
					case "select" :
					{
						//Si es empleado o administrador podra consultar cualquier perfil
						//Si es cliente puede consultar unicamente su propio perfil
						
						//Comprobamos que el POST no esté vacío cuando el usuario no sea cliente
						if(!$this -> isClient() && empty($_POST))
						{
							$error = "Error al mostrar el usuario, el POST está vacío.";
							require_once("View/Error.php");
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

								if($result != null)
								{
									var_dump($result);
								}
								else
								{
									$error = "Error al mostrar el usuario.";
									require_once("View/Error.php");
								}
							}
							else
							{
								$error = "Error al mostrar el usuario, el id no está seteado.";
								require_once("View/Error.php");
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
							$error = "Error al tratar de modificar el registro, el POST está vacío.";
							require_once("View/Error.php");
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
									var_dump($result);
									
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
											require_once("View/Error.php");
										}
										else
										{
											//Se llama a la función de modificación.
											//Se recoge el resultado y en base a este resultado
											//se imprime un mensaje.
											if($this -> model -> update($id_user, $name,$login,$pass ,$email,$tel, $type))
											{
												require_once("View/UpdateUserShow.php");
											}
											else
											{
												$error = "Error al tratar de modificar el registro.";
												require_once("View/Error.php");
											}
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

						break;
					}		
				} /* fin switch */
				$this -> logout();
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");	
			}
		} /* fin run */
	}
?>
