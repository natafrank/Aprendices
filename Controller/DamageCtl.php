<?php
	include("Controller/StandardCtl.php");
	
	class DamageCtl extends StandardCtl{
		private $model;

		public function run(){
			
			require_once("Model/DamageMdl.php");
			$this->model = new DamageMdl();			
			
			switch($_GET['act'])
			{
					
				case "insert" :
				{	
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						require_once("View/InsertDamage.php");
					}

					else
					{
						//Limpiamos los datos.
						$idDamage = $this->cleanText($_POST['idDamage']); // Para este dato se creara un Trigger en la BD

						$Damage   = $this->cleanText($_POST['Damage']);
						
						//Recogemos el resultado de la inserción e imprimimos un mensaje
						//en base a este resultado.
						if($result= $this->model->insert($idDamage,$Damage))
						{
							require_once("View/ShowInsertDamage.php");
						}
						else
						{
							$error = "Error al insertar el nuevo registro"; 
							require_once("View/Error.php");
						}
					}
					break;
				}
				
				case "update" : 
				{
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						require_once("View/UpdateDamage.php");
					}

					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idDamage']))
						{
							//Limpiamos el id.
							$idDamage = $this->cleanText($_POST['idDamage']);

							//Primero mostramos el id que se quire modificar.
							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this -> model -> select($idDamage)) != null)
							{
								echo var_dump($result);

								//La modificación se realizará en base al id.
								//Por ahora se modificarán todos los atributos.
								$Damage = $this -> cleanText($_POST['Damage']);

								//Se llama a la función de modificación.
								//Se recoge el resultado y en base a este resultado
								//se imprime un mensaje.
								if($this -> model -> update($idDamage, $Damage))
								{
									require_once("View/ShowUpdateDamage.php");	
								}
								else
								{
									$error = "Error al modificar el daño.";
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
						//Sino está seteado, imprimimos el mensaje.
						else
						{
							$error = "El id no está seteado.";
							require_once("View/Error.php");
						}

					}
					break;
				}
					
				case "select" :
				{
					//Comprobamos que el $_POST no esté vacío.	
					if(empty($_POST))
					{
						$error = "No se especificó el id.";
						require_once("View/Error.php");
					}
					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idDamage']))
						{
							//Limpiamos el id.
							$idDamage = $this->cleanText($_POST['idDamage']);

							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this -> model -> select($idDamage)) != null)
							{
								echo var_dump($result);
							}
							//Si el resultado no contiene información, mostramos el error.
							else
							{
								$error = "Error al tratar de mostrar el registro.";
								require_once("View/Error.php");
							}
						}
						//Imprimimos el error si la variable no está seteada.
						else
						{
							$error = "El id no esta seteado.";
							require_once("View/Error.php");
						}
					}
					break;
				}
					
				case "delete" :
				{
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						require_once("View/DeleteDamage.php");
					}

					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idDamage']))
						{
							//Limpiamos el id.
							$idDamage = $this->cleanText($_POST['idDamage']);

							//Recogemos el resultado de la eliminación.
							$result = $this -> model -> delete($idDamage);

							//Si la eliminación fue exitosa, mostramos el mensaje.
							if($result)
							{
								require_once("View/DeleteDamage.php");
							}
							//Si no pudimos eliminar, señalamos el error.
							else
							{
								$error = "Error al elimiar el daño.";
								require_once("View/Error.php");
							}
						}
						//Si el id no está seteado, marcamos el error.
						else
						{
							$error = 'No se ha especificado el ID del registro a eliminar';
							require_once("View/Error.php");	
						}
					}
					break;
				}
			
			} /* fin switch */

		} /* fin run */

	}

?>
