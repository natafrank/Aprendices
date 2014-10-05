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
			
			switch($_GET['act'])
			{	
				case "insert" :
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

					break;
				}	
				case "delete" :
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

					break;
				}
				case "select" :
				{
					//Comprobamos que el POST no esté vacío.
					if(empty($_POST))
					{
						$error = "Error al mostrar el usuario, el POST está vacío.";
						require_once("View/Error.php");
					}
					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['id_user']))
						{
							//Limpiamos el id.
							$id_user = $this -> cleanInt($_POST['id_user']);

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
					//Comprobamos que el POST no esté vacío.
					if(empty($_POST))
					{
						$error = "Error al tratar de modificar el registro, el POST está vacío.";
						require_once("View/Error.php");
					}
					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['id_user']))
						{
							//Limpiamos el id.
							$id_user = $this -> cleanText($_POST['id_user']);

							//Primero mostramos el id que se quire modificar.
							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this -> model -> select($id_user)) != null)
							{
								var_dump($result);

								//Comprobamos que las variables estén seteadas
								if(isset($_POST['name'])
									&& isset($_POST['login']) && isset($_POST['pass'])
									&& isset($_POST['email']) && isset($_POST['tel'])
									&& isset($_POST['type']))
								{
									//La modificación se realizará en base al id.
									//Por ahora se modificarán todos los atributos.
									//Limpiamos las variables.
									$name    = $this -> cleanName($_POST['name']);
									$login   = $this -> cleanLogin($_POST['login']);
									$pass    = $this -> cleanPassword($_POST['pass']);
									$email   = $this -> cleanEmail($_POST['email']);
									$tel     = $this -> cleanTel($_POST['tel']);  
									$type    = $this -> cleanText($_POST['type']);

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
		} /* fin ejecutar */
	}
?>
