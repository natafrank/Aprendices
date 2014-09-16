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
						$name   = $this -> cleanText($_POST['name']);
						$login  = $this -> cleanText($_POST['login']);
						$pass   = $this -> cleanText($_POST['pass']);
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
						$id = $this -> cleanInt($_POST['id']);

						$result = $this -> model -> delete($id);

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
				case "show" :
				{
					if(empty($_POST))
					{
						require_once("View/ShowUserError.php");
					}
					else
					{
						/*Se mostrará al usuario en base a su id.*/
						$id = $this -> cleanInt($_POST['id']);

						$result = $this -> model -> show($id);

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
						$id = $this -> cleanInt($_POST['id']);

						//En base al id se accederá a la base de datos y se tomarán
						//todos los atributos del usuario.
						//Esto lo hace la función show(), por lo que la llamamos
						$result = $this -> model -> show($id);

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