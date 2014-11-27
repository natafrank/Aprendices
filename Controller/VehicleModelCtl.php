<?php

	require_once("StandardCtl.php");

	class VehicleModelCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			//Importamos el archivo del modelo.
			require_once("Model/VehicleModelMdl.php");

			//Creamos el modelo.
			$this -> model = new VehicleModelMdl();

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
				//Acciones del $_GET
				switch($_GET['act'])
				{
					case "insert":
					{
						//Solo el admin y empleados podrán insertar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamos que el POST no esté vacío.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/VehicleModelForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Traer el idVehicleBrand, la condicion es 0=0 para que los traiga todos (crear funcion en modelo)
								$result = $this -> model -> getVehicleBrands("0=0");
								//Obtengo la posicion donde se van a insertar los option
								$row_start = strrpos($view,'{vehicle-brand-options-start}') + 29;
								$row_end= strrpos($view,'{vehicle-brand-options-end}');
								//Hacer copia de la fila donde se va a reemplazar el contenido
								$base_row = substr($view,$row_start,$row_end-$row_start);
								//Acceder al resultado y crear el diccionario
								//Revisar que el nombre de los campos coincida con los de la base de datos
								$rows = '';
								foreach ($result as $row) {
									$new_row = $base_row;
									$dictionary = array(
										'{id-vehicle-brand}' => $row['idVehicleBrand'], 
										'{vehicle-brand}' => $row['Brand']
									);
									$new_row = strtr($new_row,$dictionary);
									$rows .= $new_row;
								}
								//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
								$view = str_replace($base_row, $rows, $view);
								$view = str_replace('{vehicle-brand-options-start}', '', $view);
								$view = str_replace('{vehicle-brand-options-end}', '', $view);

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-vehicle-model}' => '', 
													'{value-vehicle-model}' => '', 
													//'{value-id-vehicle-brand}' => '', 
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
								if(    isset($_POST['vehicle_model']) 
									&& isset($_POST['id_vehicle_brand']))
								{
									//Limpiamos los datos.
									//Obtenemos la llave primaria
									require_once("Model/PKGenerator.php");									
									$id_vehicle_model = PKGenerator::getPK('VehicleModel','idVehicleModel');
									$vehicle_model    = $this -> cleanText($_POST['vehicle_model']);
									$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);

									if(!$id_vehicle_brand || !$id_vehicle_model || !$vehicle_model)
									{
										$error = "Error al insertar el modelo de vehículo, alguno de los campos es inválido.";
										$this -> showErrorView($error);
									}
									else
									{
										//Guardamos el resultado de ejecutar el query.
										$result = $this -> model -> insert($id_vehicle_model, $vehicle_model, $id_vehicle_brand);

										if($result)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleModelForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Traer el idVehicleBrand insertado, ahora si se pone condicion en el comando.
											$result = $this -> model -> getVehicleBrands("idVehicleBrand=".$id_vehicle_brand);
											//Obtengo la posicion donde se van a insertar los option
											$row_start = strrpos($view,'{vehicle-brand-options-start}') + 29;
											$row_end= strrpos($view,'{vehicle-brand-options-end}');
											//Hacer copia de la fila donde se va a reemplazar el contenido
											$base_row = substr($view,$row_start,$row_end-$row_start);
											//Acceder al resultado y crear el diccionario
											//Revisar que el nombre de los campos coincida con los de la base de datos
											$rows = '';
											foreach ($result as $row) {
												$new_row = $base_row;
												$dictionary = array(
													'{id-vehicle-brand}' => $row['idVehicleBrand'], 
													'{vehicle-brand}' => $row['Brand']
												);
												$new_row = strtr($new_row,$dictionary);
												$rows .= $new_row;
											}
											//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
											$view = str_replace($base_row, $rows, $view);
											$view = str_replace('{vehicle-brand-options-start}', '', $view);
											$view = str_replace('{vehicle-brand-options-end}', '', $view);

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-vehicle-model}' => $id_vehicle_model, 
																'{value-vehicle-model}' => $_POST['vehicle_model'], 
																//'{value-id-vehicle-brand}' => $_POST['id_vehicle_brand'],
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
											$subject = "Alta de Modelo de Vehículo";
											$body = "El Modelo de Vehículo con los siguientes datos se ha añadido:".
											"\nId            : ". $id_vehicle_model.
											"\nVehicle Model : ". $vehicle_model;

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
											$error = "Error al agregar un modelo de vehículo.";
											$this -> showErrorView($error);
										}
									}
								}
								else
								{
									$error = "Error al intentar insertar el Modelo, faltan variables por setear.";
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

					case "delete":
					{
						//Solo los admins podrán eliminar
						if($this -> isAdmin())
						{
							//Comprobamos que el $_POST no esté vacío
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclemodel","delete","id_vehicle_model","Id Modelo Vehículo:");
							}
							else
							{
								//Comprobamos que el id este seteado.
								if(isset($_POST['id_vehicle_model']))
								{
									/*Para hacer las eliminaciones utilizaremos el id del modelo*/
									//Limpiamos la variable.
									$id_vehicle_model = $this -> cleanInt($_POST['id_vehicle_model']);

									//Recogemos el resultado del query.
									$result = $this -> model -> delete($id_vehicle_model);

									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo del usuario que se eliminó a los admin
										require_once("Controller/mail.php");

										$subject = "Eliminación de Modelo de Vehículo";
										$body    = "Se ha eliminado el modelo de vehículo con el id: ".$id_vehicle_model;

										//Enviamos el correo solo a admins - 4
										if(Mailer::sendMail($subject, $body, 4))
										{
											//echo "Correo enviado con éxito";
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
										$error = "Error al eliminar el modelo de vehículo.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al eliminar el modelo de vehículo, el id no está seteado.";
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

					case "select":
					{
						//Solo admins y empleados podrán consultar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							//Comprobamso que el $_POST no esté vacío
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclemodel","select","id_vehicle_model","Id Marca Vehículo:");
							}
							else
							{
								//Comprobamos que el id esté seteado
								if(isset($_POST['id_vehicle_model']))
								{
									/*Se mostrará el modelo en base a su id.*/
									//Limpiamos la variable.
									$id_vehicle_model = $this -> cleanInt($_POST['id_vehicle_model']);

									//Recogemos el resultado de ejecutar el query.
									$result  = $this -> model -> select($id_vehicle_model);

									//Si el resultado contiene información, la imprimimos.
									if($result != null)
									{
										//Cargamos el formulario
										$view = file_get_contents("View/VehicleModelForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										$dictionary = array(
															'{value-id-vehicle-model}' => $result[0]['idVehicleModel'], 
															'{value-vehicle-model}' => $result[0]['Model'], 
															//'{value-id-vehicle-brand}' => $result[0]['idVehicleBrand'],
															'{active}' => 'disabled',
															'{action}' => 'select'
													);

										//Sustituir los valores en la plantilla
										$view = strtr($view,$dictionary);

										//poner despues de sustituir los demás valores para no perder los datos traidos del select
										//Traer el idVehicleBrand insertado, ahora si se pone condicion en el comando.
										$result = $this -> model -> getVehicleBrands("idVehicleBrand=".$result[0]['idVehicleBrand']);
										//Obtengo la posicion donde se van a insertar los option
										$row_start = strrpos($view,'{vehicle-brand-options-start}') + 29;
										$row_end= strrpos($view,'{vehicle-brand-options-end}');
										//Hacer copia de la fila donde se va a reemplazar el contenido
										$base_row = substr($view,$row_start,$row_end-$row_start);
										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										$rows = '';

										foreach ($result as $row) {
											$new_row = $base_row;
											$dictionary = array(
												'{id-vehicle-brand}' => $row['idVehicleBrand'], 
												'{vehicle-brand}' => $row['Brand']
											);
											$new_row = strtr($new_row,$dictionary);
											$rows .= $new_row;
										}+
										//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
										$view = str_replace($base_row, $rows, $view);
										$view = str_replace('{vehicle-brand-options-start}', '', $view);
										$view = str_replace('{vehicle-brand-options-end}', '', $view);

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
										$error = "Error al intentar mostrar el modelo de vehículo.";
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al mostrar la marca de vehículo, el id no está seteado.";
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

					case "update":
					{
						//Solo admins y empleados podrán modificar
						if($this -> isAdmin() || $this -> isEmployee())
						{
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("vehiclemodel","update","id_vehicle_model","Id Modelo Vehículo:");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['id_vehicle_model']))
								{
									//Limpiamos el id
									$id_vehicle_model = $this -> cleanInt($_POST['id_vehicle_model']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['vehicle_model']) && isset($_POST['id_vehicle_brand']))
									{
										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.
										$id_vehicle_brand = $this -> cleanInt($_POST['id_vehicle_brand']);
										$vehicle_model    = $this -> cleanText($_POST['vehicle_model']);

										//Se llama a la función de modificación.
										if($this -> model -> update($id_vehicle_model, $vehicle_model, $id_vehicle_brand))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleModelForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Traer el idVehicleBrand insertado, ahora si se pone condicion en el comando.
											$result = $this -> model -> getVehicleBrands("idVehicleBrand=".$id_vehicle_brand);
											//Obtengo la posicion donde se van a insertar los option
											$row_start = strrpos($view,'{vehicle-brand-options-start}') + 29;
											$row_end= strrpos($view,'{vehicle-brand-options-end}');
											//Hacer copia de la fila donde se va a reemplazar el contenido
											$base_row = substr($view,$row_start,$row_end-$row_start);
											//Acceder al resultado y crear el diccionario
											//Revisar que el nombre de los campos coincida con los de la base de datos
											$rows = '';
											foreach ($result as $row) {
												$new_row = $base_row;
												$dictionary = array(
													'{id-vehicle-brand}' => $row['idVehicleBrand'], 
													'{vehicle-brand}' => $row['Brand']
												);
												$new_row = strtr($new_row,$dictionary);
												$rows .= $new_row;
											}
											//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
											$view = str_replace($base_row, $rows, $view);
											$view = str_replace('{vehicle-brand-options-start}', '', $view);
											$view = str_replace('{vehicle-brand-options-end}', '', $view);

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-vehicle-model}' => $id_vehicle_model, 
																'{value-vehicle-model}' => $vehicle_model, 
																//'{value-id-vehicle-brand}' => $id_vehicle_brand,
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
											$subject = "Modificación de Modelo de Vehículo";
											$body = "El Modelo de Vehículo con los siguientes datos se ha modificado:".
											"\nId            : ". $id_vehicle_model.
											"\nVehicle Model : ". $vehicle_model;

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
											$error = "Error al modificar el modelo de vehículo.";
											$this -> showErrorView($error);
										}
									}
									else
									{
										if(($result = $this -> model -> select($id_vehicle_model)) != null)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/VehicleModelForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
																'{value-id-vehicle-model}' => $result[0]['idVehicleModel'], 
																'{value-vehicle-model}' => $result[0]['Model'], 
																//'{value-id-vehicle-brand}' => $result[0]['idVehicleBrand'],
																'{active}' => '',
																'{action}' => 'update'
															);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Poner despues de sustituir los demas datos para no perder la información del select.
											//Para actualizar no se pone condicion, para que esten todas las opciones disponibles
											$result = $this -> model -> getVehicleBrands("0=0");
											//Obtengo la posicion donde se van a insertar los option
											$row_start = strrpos($view,'{vehicle-brand-options-start}') + 29;
											$row_end= strrpos($view,'{vehicle-brand-options-end}');
											//Hacer copia de la fila donde se va a reemplazar el contenido
											$base_row = substr($view,$row_start,$row_end-$row_start);
											//Acceder al resultado y crear el diccionario
											//Revisar que el nombre de los campos coincida con los de la base de datos
											$rows = '';

											foreach ($result as $row) {
												$new_row = $base_row;
												$dictionary = array(
													'{id-vehicle-brand}' => $row['idVehicleBrand'], 
													'{vehicle-brand}' => $row['Brand']
												);
												$new_row = strtr($new_row,$dictionary);
												$rows .= $new_row;
											}

											//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
											$view = str_replace($base_row, $rows, $view);
											$view = str_replace('{vehicle-brand-options-start}', '', $view);
											$view = str_replace('{vehicle-brand-options-end}', '', $view);

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
									$error = "Error al intentar modificar el modelo de vehículo, el id no está seteado.";
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


						//Ejecutamos el query y guardamos el resultado.
						$result = $this -> model -> getList($filter);

						if($result !== FALSE)
						{
							//Cargamos el formulario
							$view = file_get_contents("View/VehicleModelTable.html");
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
													'{value-id-vehicle-model}' => $row['idVehicleModel'], 
													'{value-vehicle-model}' => $row['Model'], 
													'{value-id-vehicle-brand}' => $row['idVehicleBrand'],
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
							$error = "Error al listar los modelos de vehículos.";
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
		}
	}

?>
