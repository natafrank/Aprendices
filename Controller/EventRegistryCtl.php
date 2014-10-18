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
			//Verificar que esten seteadas las variables para hacer login.
			if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
			{
				$this->login($_POST['session_login'],$_POST['session_pass']);	
			}

			//Validar que el login se haya hecho correctamente.
			if( $this->isLogged() )
			{ 
				switch($_GET['act'])
				{
					
					case "insert" :
					{	
						//Solo administradores y empleados pueden hacer inserciones de Registros de Eventos.
						if( !$this->isClient() )
						{	
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para insertar.
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
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
						}
						break;
					}
				
					case "update" : 
					{
						//Solo administradores y empleados pueden actualizar los Registros de Eventos.
						if( !$this->isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para actualizar la información.
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
								//Sino está seteado, imprimimos el mensaje y se mostrará la vista con 									el formulario para actualizar la información.
								else
								{
									$error = "El id no está seteado.";
									echo $error,'<br/>';
									require_once("View/UpdateEventRegistry.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
						}
						break;
					}
					
					case "select" :
					{
						//Solo administradores y empleados pueden ver los Resgistros de Eventos.
						if( !$this->isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para hacer select.	
							if(empty($_POST))
							{
								require_once("View/SelectEventRegistry.php");
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
										require_once("View/ShowSelectEventRegistry.php");
									}
									//Si el resultado no contiene información, mostramos el error.
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										require_once("View/Error.php");
									}
								}
								//Si el ID no está seteado, se marcará el error y se mostrará la vista con 									el formulario para hacer select.
								else
								{
									$error = "El id no esta seteado.";
									echo $error,'<br/>';
									require_once("View/SelectEventRegistry.php");
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
						}
						break;
					}
					
					case "delete" :
					{
						//Solo administradores y empleados pueden eliminar Registros de Eventos.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para eliminar un Registro de Evento.	
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
									echo $error,'<br/>';
									require_once("View/DeleteEventRegistry.php");	
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
						}
						break;
					}
			
				} /* fin switch */
				$this->logout();
			}
			else
			{
				$error = "No se ha iniciado ninguna sesion.";
				require_once("View/Error.php");	
			}

		} /* fin run */

	}

?>
