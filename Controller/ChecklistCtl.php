<?php
	include("Controller/StandardCtl.php");
	
	class ChecklistCtl extends StandardCtl
	{
		/**
		 * Variable Modelo de la clase Checklist.
		 *
		 * @access private
		 * @var ChecklistMdl $model - Variable para realizar las funciones de Modelo en la estructura MVC.
		 */
		private $model;

		/**
		 * Funcion principal del controlador.
		 *
		 * Se encarga del manejo de vistas y funciones del modelo
		 * de acuerdo a la accion que se indica con la llave 'act' en $_GET
		 *
		 */
		public function run()
		{
			
			require_once("Model/ChecklistMdl.php");
			$this -> model = new ChecklistMdl();			
			
			//Verificar que esten seteadas las variables para hacer login
			if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
			{
				$this -> login($_POST['session_login'],$_POST['session_pass']);	
			}
			
			//validar que el login se haya hecho correctamente
			if( $this -> isLogged() )
			{ 			
			
				switch($_GET['act'])
				{
					
					case "insert" :
					{
						//Solo administradores y empleados pueden hacer inserciones de Checklists
						if( !$this -> isClient() )
						{
											
							//Comprobar si $_POST está vacio, si es así se mostrará el formulario para capturar los datos.
							if(empty($_POST))
							{
								require_once("View/InsertChecklist.php");
							}
							else
							{
								//Limpiamos los datos.
								$idChecklist 	 = $this -> cleanInt($_POST['idChecklist']);  // Para este dato se creara un Trigger en la BD
								$idVehicle   	 = $this -> cleanInt($_POST['idVehicle']);
								$idVehicleStatus = $this -> cleanInt($_POST['idVehicleStatus']);
								$Date        	 = $this -> cleanDateTime($_POST['Date']);
								$InOut       	 = $this -> cleanBit($_POST['InOut']);

								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this -> model -> insert($idChecklist,$idVehicle,$idVehicleStatus,$Date,$InOut))
								{
									require_once("View/ShowInsertChecklist.php");

									//Enviamos el correo de que se ha añadido un checklist.
									require_once("Controller/mail.php");

									//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Checklist";
									$body = "El checklist con los siguientes datos se ha añadido:".
									"\nId   : ". $idChecklist.
									"\nIdVehicle : ". $idVehicle.
									"\nidVehicleStatus: ". $idVehicleStatus.
									"\nFecha : ". $Date.
									"\nInOut : ". $InOut;

									//Manadamos el correo solo a administradores y empleados - 6
									if(Mailer::sendMail($subject, $body, 6))
									{
										echo "<br>Correo enviado con éxito.";
									}
									else
									{
										echo "<br>Error al enviar el correo.";
									}
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
						//Solo administradores y empleados pueden actualizar Checklists
						if( !$this -> isClient() )
						{	
							//Comprobamos que $_POST no este vacio.
							if(empty($_POST))
							{
								require_once("View/UpdateChecklist.php");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['idChecklist']))
								{
									//Limpiamos el ID
									$idChecklist = $this -> cleanInt($_POST['idChecklist']);
							
									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($idChecklist)) != null)
									{
										echo var_dump($result);

										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.
										$idVehicle   	 = $this -> cleanInt($_POST['idVehicle']);
										$idVehicleStatus = $this -> cleanInt($_POST['idVehicleStatus']);
										$Date        	 = $this -> cleanDateTime($_POST['Date']);
										$InOut       	 = $this -> cleanBit($_POST['InOut']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($idChecklist, $idVehicle, $idVehicleStatus, $Date, $InOut))
										{
											require_once("View/ShowUpdateChecklist.php");
											
											//Enviamos el correo de que se ha añadido un checklist.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Actualizacion de Checklist";
											$body = "El checklist con los siguientes datos se ha modificado:".
											"\nId   : ". $idChecklist.
											"\nIdVehicle : ". $idVehicle.
											"\nidVehicleStatus: ". $idVehicleStatus.
											"\nFecha : ". $Date.
											"\nInOut : ". $InOut;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												echo "<br>Correo enviado con éxito.";
											}
											else
											{
												echo "<br>Error al enviar el correo.";
											}
										}
										else
										{
											$error = "Error al modificar el Checklist.";
											require_once("View/Error.php");
										}
									}
								}
								else
								{
									$error = 'No se especifico el ID del registro a modificar';
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
							if(isset($_POST['idChecklist']))
							{
								//Limpiamos el id.
								$idChecklist = $this -> cleanText($_POST['idChecklist']);

								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($idChecklist)) != null)
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
						//Solo administradores y empleados pueden eliminar Checklists
						if( !$this -> isClient() )
						{
					
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								require_once("View/DeleteChecklist.php");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idChecklist']))
								{
									//Limpiamos el id.
									$idChecklist = $this -> cleanText($_POST['idChecklist']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($idChecklist);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										require_once("View/DeleteChecklist.php");

										//Enviamos el correo de que se ha eliminado un checklist.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Checklist";
										$body = "Se ha eliminado el Checklist con ID: ".$idChecklist;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											echo "<br>Correo enviado con éxito.";
										}
										else
										{
											echo "<br>Error al enviar el correo.";
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar el Checklist.";
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
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							require_once("View/Error.php");
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
