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
					if(empty($_POST))
					{
						require_once("View/InsertUser.php");
					}
					else
					{
						$name   = $this -> cleanName($_POST['name']);
						$login  = $this -> cleanLogin($_POST['login']);
						$pass   = $this -> cleanPassword($_POST['pass']);
						$type   = $this -> cleanInt($_POST['type']); 

						$result = $this -> model -> insert($name,$login,$pass,$type);

						if($result)
						{
							require_once("View/ShowUser.php");
						}
						else
						{
							require_once("View/InsertUserError.php");
						}
					}

					break;
				}	
				case "delete" :
				{
					if(empty($_POST))
					{
						require_once("View/DeleteUserError.php");
					}
					else
					{
						/*Para hacer las eliminaciones utilizaremos el id del usuario*/
						$id_user = $this -> cleanInt($_POST['id_user']);

						$result = $this -> model -> delete($id_user);

						if($result)
						{
							require_once("View/DeleteUser.php");
						}
						else
						{
							require_once("View/DeleteUserError.php");
						}
					}

					break;
				}
				case "select" :
				{
					if(empty($_POST))
					{
						require_once("View/ShowUserError.php");
					}
					else
					{
						/*Se mostrará al usuario en base a su id.*/
						$id_user = $this -> cleanInt($_POST['id_user']);

						$result = $this -> model -> select($id_user);

						if($result)
						{
							require_once("View/ShowUser.php");
						}
						else
						{
							require_once("View/ShowUserError.php");
						}
					}

					break;
				}
				case "update" :
				{
					if(empty($_POST))
					{
						require_once("View/UpdateUserError.php");
					}
					else
					{
						//La modificación se realizará en base el id del usuario
						$id_user = $this -> cleanInt($_POST['id_user']);

						//En base al id se accederá a la base de datos y se tomarán
						//todos los atributos del usuario.
						//Esto lo hace la función select(), por lo que la llamamos
						$result = $this -> model -> select($id_user);

						//Si se accede de manera éxitosa mostramos un formulario
						//con los datos del usuario.
						if($result)
						{
							require_once("View/UpdateUserForm.php");

							//Una vez modificados los datos del usuario a través del form
							//se llama a la función update la cuál actualizará los valores
							//modificados en el form dentro de la base de datos.
							$update_result = $this -> model -> update();

							//Por último se muestran los datos del usuario modificados.
							require_once("View/UpdateUserShow.php");
						}
						//Si no pudimos acceder mostramos el error.
						else
						{
							require_once("View/UpdateUserError.php");
						}
					}

					break;
				}		
			} /* fin switch */
		} /* fin ejecutar */
	}
?>
