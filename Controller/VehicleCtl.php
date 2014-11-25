<?php

	require_once("StandardCtl.php");

	class VehicleCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			//Importamos el modelo.
			require_once("Model/VehicleMdl.php");
			
			$this -> model = new VehicleMdl();

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
					case "insert":
					{
						//Solo admins y empleados podrán insertar vehículos
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos si el POST no está vacío.
							if(empty($_POST))
							{
								//Cargamos el formulario.
								$view = file_get_contents("View/VehicleForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-vehicle}' => '',
													'{value-id-user}' => '',
													'{value-id-location}' => '',
													'{value-id-vehicle-model}' => '',
													'{value-vin}' => '',
													'{value-color}' => '',
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
								if(isset($_POST['id_vehicle']) && isset($_POST['id_user']) && isset($_POST['vin']) && isset($_POST['id_location']) && isset($_POST['id_vehicle_model']) && isset($_POST['color']))
								{
									//Obtenemos las variables por la alta y las limpiamos.
									$id_vehicle        = $this -> cleanInt($_POST['id_vehicle']);
									$id_user           = $this -> cleanInt($_POST['id_user']);
									$id_location       = $this -> cleanInt($_POST['id_location']);
									$id_vehicle_model  = $this -> cleanInt($_POST['id_vehicle_model']);
									$vin               = $this -> cleanText($_POST['vin']);
									$color             = $this -> cleanText($_POST['color']);

									//Si alguno de los campos es inválido.
									if(!$id_vehicle || !$id_user || !$id_location || !$id_vehicle_model || !$vin || !$color)
									{
										$error = "Error al insertar el vehículo, alguno de los campos es inválido.";
										$this -> showErrorView($error);
									}
									else
									{
										//Guardamos el resultado de ejecutar el query.
										$result = $this -> model -> insert($id_vehicle, $id_user, $id_location, $id_vehicle_model, $vin, $color);

										if($result)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
													'{value-id-vehicle}' => $_POST['id_vehicle'],
													'{value-id-user}' => $_POST['id_user'],
													'{value-id-location}' => $_POST['id_location'],
													'{value-id-vehicle-model}' => $_POST['id_vehicle_model'],
													'{value-vin}' => $_POST['vin'],
													'{value-color}' => $_POST['color'],
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
											$subject = "Alta de Vehículo";
											$body = "El vehículo con los siguientes datos se ha añadido:".
											"\nId              : ". $id_vehicle.
											"\nId Usuario      : ". $id_user.
											"\nId Location     : ". $id_location.
											"\nId Vehicle Model: ". $id_vehicle_model.
											"\nVin             : ". $vin.
											"\nColor           : ". $color;
									
											//Manadamos el correo solo a administradores y al cliente que corresponde - 5.
											if(Mailer::sendMail($subject, $body, 5, $id_user))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br>Error al enviar el correo.";
												/*$error = "Error al enviar el correo.";
												$this -> showErrorView($error);*/
											}
										}
										else
										{
											$error = "Error al intentar insertar el vehículo.";
											$this -> showErrorView($error);
										}
									}
								}
								else
								{
									$error = "Faltan variables por setear.";
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
							//Comprobamos que el POST np esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehicle","delete","id_vehicle","Id Vehículo:");

							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle']))
								{
									//Limpiamos la variable.
									$id_vehicle = $this -> cleanInt($_POST['id_vehicle']);

									//Ejecutamos el query y recogemos el resultado.
									$result = $this -> model -> delete($id_vehicle);

									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Vehículo";
										$body    = "Se ha eliminado el vehículo con el id: ".$id_vehicle;

										//Enviamos el correo solo a admins - 4
										if(Mailer::sendMail($subject, $body, 4))
										{
											//echo "Correo enviado con éxito";
										}
										else
										{
											//echo "Error al enviar el correo";
											/*$error = "Error al enviar el correo";
											$this -> showErrorView($error);*/
										}
									}
									else
									{
										$error = "Error al intentar eliminar el vehículo.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al eliminar el vehículo, el vin no está seteado.";
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
						//Solo los admins y los empleados podrán consultar, los usuarios pueden consultar sus propios vehiculos
						if($this -> isAdmin() || $this -> isEmployee() || $this -> isClient())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehicle","select","id_vehicle","Id Vehículo:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle']))
								{
									//Limpiamos el id.
									$id_vehicle = $this -> cleanInt($_POST['id_vehicle']);

									//Ejecutamos el query y recogemos el resultado.
									$result = $this -> model -> select($id_vehicle);

									//Si es cliente y el id de usuario obtenido no corresponde, mostramos un error
									if($this -> isClient() && $result[0]['idUser'] != $_SESSION['id_user'])
									{
										if($result != null)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											foreach($result as $row)
											{
												$dictionary = array(
														'{value-id-vehicle}' => $result[0]['idVehicle'],
														'{value-id-user}' => $result[0]['idUser'],
														'{value-id-location}' => $result[0]['idLocation'],
														'{value-id-vehicle-model}' => $result[0]['idVehicleModel'],
														'{value-vin}' => $result[0]['VIN'],
														'{value-color}' => $result[0]['Color'],
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
										else
										{
											$error = "Error al mostrar el vehículo.";
											$this -> showErrorView($error);
										}
									}
									else
									{
										$error = "No tiene permiso de ver el vehiculo especificado.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al mostrar el vehículo, el vin no está seteado.";
									$this -> showErrorView($error);;
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

					case "update" :
					{
						//Solo los admins y empleados podrán modificar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehicle","update","id_vehicle","Id Vehículo:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['id_vehicle']))
								{
									//Limpiamos el id.
									$id_vehicle = $this -> cleanInt($_POST['id_vehicle']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['id_user']) && isset($_POST['id_location']) && isset($_POST['id_vehicle_model']) && isset($_POST['vin']) && isset($_POST['color']))
									{
										//La modificación se realizará en base al id.
										//Limpiamos las variables.
										$id_user      	  = $this -> cleanInt($_POST['id_user']);
										$id_location      = $this -> cleanInt($_POST['id_location']);
										$id_vehicle_model = $this -> cleanInt($_POST['id_vehicle_model']);
										$vin              = $this -> cleanText($_POST['vin']);
										$color            = $this -> cleanText($_POST['color']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($id_vehicle, $id_user, $id_location,$id_vehicle_model, $vin,  $color))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos	
											$dictionary = array(
												'{value-id-vehicle}' => $id_vehicle,
												'{value-id-user}' => $id_user,
												'{value-id-location}' => $id_location,
												'{value-id-vehicle-model}' => $id_vehicle_model,
												'{value-vin}' => $vin,
												'{value-color}' => $color,
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
											$subject = "Modificación de Vehículo";
											$body = "El vehículo con los siguientes datos se ha modificado:".
											"\nId              : ". $id_vehicle.
											"\nId Usuario      : ". $id_user.
											"\nId Location     : ". $id_location.
											"\nId Vehicle Model: ". $id_vehicle_model.
											"\nVin             : ". $vin.
											"\nColor           : ". $color;
							
											//Manadamos el correo solo a administradores - 4.
											if(Mailer::sendMail($subject, $body, 4))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br>Error al enviar el correo.";
												/*$error = "Error al enviar el correo.";
												$this -> showErrorView($error);*/
											}
										}
										else
										{
											$error = "Error al tratar de modificar el registro.";
											$this -> showErrorView($error);
										}

									}
									else
									{
										if(($result = $this -> model -> select($id_vehicle)) != null)
										{

												//Cargamos el formulario
												$view = file_get_contents("View/VehicleForm.html");
												$header = file_get_contents("View/header.html");
												$footer = file_get_contents("View/footer.html");

												//Creamos el diccionario
												//Despues de insertar los cmapos van con la info insertada y los input estan inactivos	
												$dictionary = array(
													'{value-id-vehicle}' => $result[0]['idVehicle'],
													'{value-id-user}' => $result[0]['idUser'],
													'{value-id-location}' => $result[0]['idLocation'],
													'{value-id-vehicle-model}' => $result[0]['idVehicleModel'],
													'{value-vin}' => $result[0]['vin'],
													'{value-color}' => $result[0]['color'],
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
										//Si el resultado no contiene información, mostramos el error.
										else
										{
											$error = "Error al traer la información para modificar.";
											$this -> showErrorView($error);
										}

									}
								}
								else
								{
									$error = "Error al tratar de modificar el registro, el vin no está seteado.";
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

					case "list":
					{
						//Revisar si hay un filtro, sino hay se queda el filtro po default
						$filter = "0=0";
						if(isset($_POST['filter_condition'])){
							//Creamos la condicion con el campo seleccionadoo y el filtro
							$filter = $_POST['filter_select']." = '".$_POST['filter_condition']."'"; 
						}

						//Si es cliente se le agrega al filtro la condicion para que solo vea sus propios vehiculos
						if($this -> isClient())
						{
							$filter = $filter." AND idUser = ".$_SESSION['id_user'];
						}

						//Ejecutamos el query y guardamos el resultado.
						$result = $this -> model -> getList($filter);

						if($result !== FALSE)
						{
							//Cargamos el formulario
							$view = file_get_contents("View/VehicleTable.html");
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

							foreach ($result as $row) 
							{
								$new_row = $base_row;
								$dictionary = array(
													'{value-id-vehicle}' => $row['idVehicle'],
												'{value-user}' => $row['User'],
												'{value-location}' => $row['idLocation'],
												'{value-vehicle-model}' => $row['idVehicleModel'],
												'{value-vehicle-brand}' => $row['Brand'],
												'{value-vin}' => $row['VIN'],
												'{value-color}' => $row['Color'],
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
							$error = "Error al listar vehículos.";
									$this -> showErrorView($error);
						}

						break;
					}

					case "loadfile" :
					{
						//Solo los admins pueden cargar archivos
						if($this -> isAdmin())
						{
							//Si el Post está vacio cargamos la vista para cargar el archivo
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/LoadFileVehicle.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								$view = $header.$view.$footer;

								echo $view;
							}
							//Si no esta vacio tomamos el texto del archivo para hacer las inserciones de los vehiculos
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['file_text']))
								{
									//Separamos el archivo en filas
									$array = explode("\n",$_POST['file_text']);

									//Por cada linea hacemos la inserción de vehiculo
									foreach($array as $row){
										$data = explode("|",$row);
										//Mandar a insertar el vehiculo
										$result = $this -> model -> insert($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
									}
									$error = "Los vehiculos fueron insertados";
									$this -> showErrorView($error);
								}
								else
								{
									$error = "No hay información en el archivo";
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
				}
			}
			else
			{
				//Si no ha iniciado sesion mostrar la vista para hacer login
				$this -> showLoginView($_GET['ctl'],$_GET['act']);	
			}
			
		}//function run
	}//class VehicleCtl

?>
