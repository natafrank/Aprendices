<?php

	require_once("StandardCtl.php");

	class UserTypeCtl extends StandardCtl
	{
		private $model;

		function run()
		{
			require_once("Model/UserTypeMdl.php");

			$this -> model = new UserTypeMdl();

			//Acciones del $_GET
			switch($_GET['act'])
			{
				case "insert":
				{
					if(empty($_POST))
					{
						//Se carga la vista del formulario
						require_once("View/InsertUserType.php");
					}
					else
					{
						//Obtenemos las variables y las limpiamos
						$user_type = $this -> cleanText($_POST['user_type']);

						$result = $this -> model -> insert($user_type);

						if($result)
						{
							require_once("View/ShowUserType.php");
						}
						else
						{
							require_once("View/InsertUserTypeError.php");
						}
					}

					break;
				}

				case "delete":
				{
					if(empty($_POST))
					{
						require_once("View/DeleteUserTypeError.php");
					}
					else
					{
						//Las eliminaciones se harán por medio del id.
						$id_user_type = $this -> cleanInt($_POST['id_user_type']);

						$result = $this -> model -> delete($id_user_type);

						if($result)
						{
							require_once("View/DeleteUserType.php");
						}
						else
						{
							require_once("View/DeleteUserTypeError.php");

						}
					}

					break;
				}

				case "select":
				{
					if(empty($_POST))
					{
						require_once("View/ShowUserTypeError.php");
					}
					else
					{
						//Se accederá por medio del id.
						$id_user_type = $this -> cleanInt($_POST['id_user_type']);

						$result = $this -> model -> select($id_user_type);

						if($result)
						{
							require_once("View/ShowUserType.php");
						}
						else
						{
							require_once("View/ShowUserTypeError.php");
						}
					}

					break;
				}

				case "update":
				{
					if(empty($_POST))
					{
						require_once("View/UpdateUserTypeError.php");
					}
					else
					{
						//La modificación se realizará en base el id.
						$id_user_type = $this -> cleanInt($_POST['id_user_type']);

						//En base al id se accederá a la base de datos y se tomarán
						//todos los atributos.
						//Esto lo hace la función select(), por lo que la llamamos.
						$result = $this -> model -> select($id_user_type);
					
						//Si se accede de manera éxitosa mostramos un formulario
						//con los datos.
						if($result)
						{
							require_once("View/UpdateUserTypeForm.php");

							//Una vez modificados los datos a través del form
							//se llama a la función update la cuál actualizará los valores
							//modificados en el form dentro de la base de datos.
							$update_result = $this -> model -> update();

							//Por último se muestran los datos modificados.
							require_once("View/UpdateUserTypeShow.php");
						}
						//Si no pudimos acceder mostramos el error.
						else
						{
							require_once("View/UpdateUserTypeError.php");
						}
					}

					break;
				}
			}
		}
	}

?>