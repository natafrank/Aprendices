<?php
	
	require_once("StandardCtl.php");

	class VehicleBrandCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			
			require_once("Model/VehicleBrandMdl.php");
			$this -> model = new VehicleBrandMdl();		

			//Verificar si hay una sesion iniciada
			if(!$this -> isLogged())
			{
				//Si no verificar que esten seteadas las variables para hacer login
				if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
				{
					$this -> login($_POST['session_login'],$_POST['session_pass']);	
				}
			}	
			//Validar que el login se haya hecho correctamente
			if($this -> isLogged())
			{
				switch($_GET['act'])
				{

					case "insert" :
					{					
						//Solo los admins y emleados podrán insertar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobar si $_POST está vacio, si es así se mostrará el formulario para capturar los datos.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/VehicleBrandForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-vehicle-brand}' => '', 
													'{value-vehicle-brand}' => '', 
													'{active}' => '',  
													'{action}' => 'insert'
												);
								
								//Sustituir los valores en la plantilla
								$view = strtr($view,$dictionary);

								//Sustituir el usuario en el header
								$dictionary = array(
													'{user-name}' => $_SESSION['user'],
													'{log-link}' => 'index.php?ctl=logout',
													'{log-type}' => 'Logout'
												);
								$header = strtr($header,$dictionary);

								//Agregamos el header y el footer a la vista
								$view = $header.$view.$footer;

								//Mostramos la vista
								echo $view;
							}
							else
							{
								//Comprobamos que las variables estén seteadas.
								if(isset($_POST['id_vehicle_brand']) && isset($_POST['vehicle_brand']))
								{
									//Limpiamos los datos.
									$id_vehicle_brand = $this->cleanInt($_POST['id_vehicle_brand']);  // Para este dato se creara un Trigger en la BD
									$vehicle_brand   = $this->cleanText($_POST['vehicle_brand']);

									//Recogemos el resultado de la inserción e imprimimos un mensaje
									//en base a este resultado.
									if($result = $this -> model -> insert($id_vehicle_brand, $vehicle_brand))
									{
										//Cargamos el formulario
											$view = file_get_contents("View/VehicleBrandForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-vehicle-brand}' => $_POST['id_vehicle_brand'], 
																'{value-vehicle-brand}' => $_POST['vehicle_brand'], 
																'{active}' => 'disabled',  
																'{action}' => 'insert'
															);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Sustituir el usuario en el header
											$dictionary = array(
																'{user-name}' => $_SESSION['user'],
																'{log-link}' => 'index.php?ctl=logout',
																'{log-type}' => 'Logout'
															);
											$header = strtr($header,$dictionary);

											//Agregamos el header y el footer
											$view = $header.$view.$footer;

											echo $view;
									
										//Enviamos el correo de que se ha añadido un usuario.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Alta de Marca de Vehículo";
										$body = "La Marca de Vehículo con los siguientes datos se ha añadido:".
										"\nId            : ". $id_vehicle_brand.
										"\nVehicle Brand : ". $vehicle_brand;

										//Manadamos el correo solo a administradores - 4.
										if(Mailer::sendMail($subject, $body, 4))
										{
											//echo "<br>Correo enviado con éxito.";
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
								else
								{
									$error = "Error al insertar el usuario, faltan variables por setear.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							require_once("View/Error.php");
						}
						
						break;
					}
					
					case "update" : 
					{	
						//Solo los admins y empleados podrán modificar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que $_POST no este vacio.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclebrand","update","id_vehicle_brand","Id Marca Vehículo:");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos el ID
									$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);
									
									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['vehicle_brand']))
									{
										//La modificación se realizará en base al id.  
										$vehicle_brand   = $this->cleanText($_POST['vehicle_brand']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($id_vehicle_brand, $vehicle_brand))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleBrandForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-vehicle-brand}' => $id_vehicle_brand, 
																'{value-vehicle-brand}' => $vehicle_brand,  
																'{active}' => 'disabled',  
																'{action}' => 'update'
															);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Sustituir el usuario en el header
											$dictionary = array(
																'{user-name}' => $_SESSION['user'],
																'{log-link}' => 'index.php?ctl=logout',
																'{log-type}' => 'Logout'
															);
											$header = strtr($header,$dictionary);

											//Agregamos el header y el footer
											$view = $header.$view.$footer;

											echo $view;	

											//Enviamos el correo de que se ha añadido un usuario.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificación de Marca de Vehículo";
											$body = "La Marca de Vehículo con los siguientes datos se ha modificado:".
											"\nId            : ". $id_vehicle_brand.
											"\nVehicle Brand : ". $vehicle_brand;

											//Manadamos el correo solo a administradores - 4.
											if(Mailer::sendMail($subject, $body, 4))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												echo "<br>Error al enviar el correo.";
											}
										}
										else
										{
											$error = "Error al modificar la marca de vehiculo.";
											$this -> showErrorView($error);
										}
									}
									else
									{
										if(($result = $this -> model -> select($id_vehicle_brand)) != null)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleBrandForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-vehicle-brand}' => $result[0]['idVehicleBrand'], 
																'{value-vehicle-brand}' => $result[0]['Brand'],  
																'{active}' => '',  
																'{action}' => 'update'
															);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Sustituir el usuario en el header
											$dictionary = array(
																'{user-name}' => $_SESSION['user'],
																'{log-link}' => 'index.php?ctl=logout',
																'{log-type}' => 'Logout'
															);
											$header = strtr($header,$dictionary);

											//Agregamos el header y el footer
											$view = $header.$view.$footer;

											echo $view;	
										}
										else
										{
											$error = "Error al traer la información para modificar.";
											$this -> showErrorView($error);
										}
									}
								}
								else
								{
									$error = 'No se especifico el ID del registro a modificar';
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							$this -> showErrorView($error);
						}

						break;
					}
						
					case "select" :
					{		
						//Solo admins y empleados podrán consultar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que el $_POST no esté vacío.	
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
							//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
							$this -> showGetIdView("vehiclebrand","select","id_vehicle_brand","Id Marca Vehículo:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos el id.
									$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);

									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($id_vehicle_brand)) != null)
									{
										//Cargamos el formulario
										$view = file_get_contents("View/VehicleBrandForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										foreach ($result as $row) {
											$dictionary = array(
																'{value-id-vehicle-brand}' => $result['idVehicleBrand'], 
																'{value-vehicle-brand}' => $result['Brand'], 	
																'{active}' => 'disabled',  
																'{action}' => 'select'
															);
										}

										//Sustituir los valores en la plantilla
										$view = strtr($view,$dictionary);

										//Sustituir el usuario en el header
										$dictionary = array(
															'{user-name}' => $_SESSION['user'],
															'{log-link}' => 'index.php?ctl=logout',
															'{log-type}' => 'Logout'
														);
										$header = strtr($header,$dictionary);

										//Agregamos el header y el footer
										$view = $header.$view.$footer;

										echo $view;
									}
									//Si el resultado no contiene información, mostramos el error.
									else
									{
										$error = "Error al tratar de mostrar el registro.";
										$this -> showErrorView($error);
									}
								}
								//Imprimimos el error si la variable no está seteada.
								else
								{
									$error = "El id no esta seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							$this -> showErrorView($error);
						}

						break;
					}
						
					case "delete" :
					{
						//Solo los admins podrán eliminar
						if($this -> isAdmin())
						{
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclebrand","delete","id_vehicle_brand","Id Marca Vehículo:");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos el id.
									$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($id_vehicle_brand);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Marca de Vehículo";
										$body    = "Se ha eliminado la marca de vehículo con el id: ".$id_vehicle_brand;

										//Enviamos el correo solo a admins - 4
										if(Mailer::sendMail($subject, $body, 4))
										{
											//echo "Correo enviado con éxito";
										}
										else
										{
											echo "Error al enviar el correo";
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar la marca de vehiculo.";
										$this -> showErrorView($error);
									}
								}
								//Si el id no está seteado, marcamos el error.
								else
								{
									$error = 'No se ha especificado el ID del registro a eliminar';
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta accion";
							$this -> showErrorView($error);
						}

						break;
					}

					case "list" :
					{
						//Revisar si hay un filtro, sino hay se queda el filtro po default
						$filter = "0=0";
						if(isset($_POST['filter_condition'])){
							//Creamos la condicion con el campo seleccionadoo y el filtro
							$filter = $_POST['filter_select']." = ".$_POST['filter_condition']; 
						}


						//Ejecutamos el query y guardamos el resultado.
						$result = $this -> model -> getList($filter);

						if($result !== FALSE)
						{
							//Cargamos el formulario
							$view = file_get_contents("View/VehicleBrandTable.html");
							$header = file_get_contents("View/header.html");
							$footer = file_get_contents("View/footer.html");

							//Obtengo la posicion donde va a insertar los registros
							$row_start = strrpos($view,'{row-start}') + 11;
							$row_end = strrpos($view,'{row-end}');

							//Hacer copia de la fila donde se va a reemplazar el contenido
							$base_row = substr($view,$row_start,$row_end-$row_start);

							//Acceder al resultado y crear el diccionario
							//Revisar que el nombre de los campos coincida con los de la base de datos
							$rows = '';
							foreach ($result as $row) {
								$new_row = $base_row;
								$dictionary = array(
													'{value-id-vehicle-brand}' => $result['idVehicleBrand'], 
													'{value-vehicle-brand}' => $result['Brand'], 
													'{active}' => 'disabled'
												);
								$new_row = strtr($new_row,$dictionary);
								$rows .= $new_row;
							}

							//Reemplazar en la vista la fila base por las filas creadas
							$view = str_replace($base_row, $rows, $view);
							$view = str_replace('{row-start}', '', $view);
							$view = str_replace('{row-end}', '', $view);

							//Sustituir el usuario en el header
							$dictionary = array(
												'{user-name}' => $_SESSION['user'],
												'{log-link}' => 'index.php?ctl=logout',
												'{log-type}' => 'Logout'
											);
							$header = strtr($header,$dictionary);

							//Agregamos el header y el footer
							$view = $header.$view.$footer;

							echo $view;
						}
						else
						{
							$error = "Error al listar las marcas de vehículos.";
							$this -> showErrorView($error);
						}

						break;
					}
				
				} /* fin switch */
			}
			else
			{
				//Si no ha iniciado sesion mostrar la vista para hacer login
				$this -> showLoginView($_GET['ctl'],$_GET['act']);
			}
			

		} /* fin run */

	}

?>
