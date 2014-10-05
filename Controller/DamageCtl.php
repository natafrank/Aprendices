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
						//Comprobamos que las variables estén seteada
						if(isset($_POST['id_damage']) && isset($_POST['damage']))
						{
							//Limpiamos los datos.
							$id_damage = $this -> cleanText($_POST['id_damage']); // Para este dato se creara un Trigger en la BD
							$damage    = $this -> cleanText($_POST['damage']);
							
							//Recogemos el resultado de la inserción e imprimimos un mensaje
							//en base a este resultado.
							if($result= $this -> model -> insert($id_damage,$damage))
							{
								require_once("View/ShowInsertDamage.php");
							}
							else
							{
								$error = "Error al insertar el nuevo registro"; 
								require_once("View/Error.php");
							}
						}
						else
						{
							$error = "Error al insertar el nuevo registro, falta id o daño."; 
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
						$error = 'No se ha especificado el ID del registro a eliminar';
						require_once("View/Error.php");
					}

					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['id_damage']))
						{
							//Limpiamos el id.
							$id_damage = $this -> cleanText($_POST['id_damage']);

							//Recogemos el resultado de la eliminación.
							$result = $this -> model -> delete($id_damage);

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
						if(isset($_POST['id_damage']))
						{
							//Limpiamos el id.
							$id_damage = $this -> cleanText($_POST['id_damage']);

							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this -> model -> select($id_damage)) != null)
							{
								var_dump($result);
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
					
				case "update" : 
				{
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						$error = "Error al tratar de modificar el daño, el id no está seteado.";
						require_once("View/Error.php");
					}

					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['id_damage']))
						{
							//Limpiamos el id.
							$id_damage = $this -> cleanText($_POST['id_damage']);

							//Primero mostramos el id que se quire modificar.
							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this -> model -> select($id_damage)) != null)
							{
								echo var_dump($result);

								//Verificamos que las variables estén seteadas.
								if(isset($_POST['damage']))
								{
									//La modificación se realizará en base al id.
									//Por ahora se modificarán todos los atributos.
									$damage = $this -> cleanText($_POST['damage']);

									//Se llama a la función de modificación.
									//Se recoge el resultado y en base a este resultado
									//se imprime un mensaje.
									if($this -> model -> update($id_damage, $damage))
									{
										require_once("View/ShowUpdateDamage.php");	
									}
									else
									{
										$error = "Error al modificar el daño.";
										require_once("View/Error.php");
									}
								}
								else
								{
									$error = "Error al modificar el daño, el daño no está seteado.";
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
							$error = "Error al tratar de modificar el daño, el id no está seteado.";
							require_once("View/Error.php");
						}

					}
					break;
				}
			
			} /* fin switch */

		} /* fin run */

	}

?>
