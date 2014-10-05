<?php
	include("Controller/StandardCtl.php");
	
	class EventRegistryCtl extends StandardCtl
	{
		private $model;
		
		function __construct()
		{
		require_once("Model/EventRegistryMdl.php");
		$this->model = new EventRegistryMdl();
		}

		public function run()
		{		
			
			switch($_GET['act'])
			{
					
				case "insert" :
				{	
					//Comprobamos que el $_POST no esté vacío.
					if(empty($_POST))
					{
						require_once("View/InsertEventRegistry.php");
					}
					else
					{
						//Limpiamos los datos.
						$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);
						$idVehicle = $this->cleanInt($_POST['idVehicle']);
						$idUser = $this->cleanInt($_POST['idUser']);
						$idEvent = $this->cleanInt($_POST['idEvent']);
						$Date= $this->cleanDateTime($_POST['Date']);
						$Reason = $this->cleanText($_POST['Reason']);
						
						//Recogemos el resultado de la inserción e imprimimos un mensaje
						//en base a este resultado.
						if($result = $this->model->insert($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Reason))
						{
							require_once("View/ShowInserEventRegistry.php");
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
						require_once("View/UpdateEventRegistry.php");
					}
					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idEventRegistry']))
						{
							//Limpiamos el id.
							$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);

							//Primero mostramos el id que se quire modificar.
							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this->model->select($idEventRegistry)) != null)
							{
								echo var_dump($result);

								//La modificación se realizará en base al id.
								//Por ahora se modificarán todos los atributos.
								$idVehicle = $this->cleanInt($_POST['idVehicle']);
								$idUser = $this->cleanInt($_POST['idUser']);
								$idEvent = $this->cleanInt($_POST['idEvent']);
								$Date= $this->cleanDateTime($_POST['Date']);
								$Reason = $this->cleanText($_POST['Reason']);

								//Se llama a la función de modificación.
								//Se recoge el resultado y en base a este resultado
								//se imprime un mensaje.
								if($this->model->update($idEventRegistry,$idVehicle,$idUser,$idEvent,$Date,$Reason))
								{
									require_once("View/ShowUpdateEventRegistry.php");	
								}
								else
								{
									$error = "Error al modificar el registro de evento.";
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
						if(isset($_POST['idEventRegistry']))
						{
							//Limpiamos el id.
							$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);

							//Recogemos el resultado y si contiene información, la mostramos.
							if(($result = $this->model->select($idEventRegistry)) != null)
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
						require_once("View/DeleteEventRegistry.php");
					}
					else
					{
						//Comprobamos que el id esté seteado.
						if(isset($_POST['idEventRegistry']))
						{
							//Limpiamos el id.
							$idEventRegistry = $this->cleanInt($_POST['idEventRegistry']);

							//Recogemos el resultado de la eliminación.
							$result = $this->model->delete($idEventRegistry);

							//Si la eliminación fue exitosa, mostramos el mensaje.
							if($result)
							{
								require_once("View/DeleteEventRegistry.php");
							}
							//Si no pudimos eliminar, señalamos el error.
							else
							{
								$error = "Error al elimiar el registro de evento.";
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