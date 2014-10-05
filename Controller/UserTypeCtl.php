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

			//Acciones del $_GET
			switch($_GET['act'])
			{
				case "insert":
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

					break;
				}

				case "delete":
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

					break;
				}

				case "select":
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

					break;
				}

				case "update":
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

					break;
				}
			}
		}
	}

?>