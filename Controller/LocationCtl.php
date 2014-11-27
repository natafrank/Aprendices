<?php
	include("Controller/StandardCtl.php");
	
	class LocationCtl extends StandardCtl
	{
		private $model;
		
		function __construct()
		{
			require_once("Model/LocationMdl.php");
			$this->model = new LocationMdl();
		}

		public function run()
		{		
			//Verificar que esten seteadas las variables para hacer login
			if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
			{
				$this->login($_POST['session_login'],$_POST['session_pass']);	
			}

			//validar que el login se haya hecho correctamente
			if( $this->isLogged() )
			{ 
				switch($_GET['act'])
				{
					
					case "insert" :
					{
						//Solo administradores y empleados pueden hacer inserciones de Ubicación.
						if( !$this -> isClient() )
						{	
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para insertar.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/LocationForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Traer el idMasterLocation, la condicion es 0=0 para que los traiga todos
								$result = $this -> model -> getMasterLocations("0=0");
								//Obtengo la posicion donde se van a insertar los option
								$row_start = strrpos($view,'{master-location-options-start}') + 31;
								$row_end= strrpos($view,'{master-location-options-end}');
								//Hacer copia de la fila donde se va a reemplazar el contenido
								$base_row = substr($view,$row_start,$row_end-$row_start);
								//Acceder al resultado y crear el diccionario
								//Revisar que el nombre de los campos coincida con los de la base de datos
								$rows = '';
								foreach ($result as $row) {
									$new_row = $base_row;
									$dictionary = array(
										'{id-master-location}' => $row['idMasterLocation'], 
										'{master-location}' => $row['Location']
									);
									$new_row = strtr($new_row,$dictionary);
									$rows .= $new_row;
								}
								//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
								$view = str_replace($base_row, $rows, $view);
								$view = str_replace('{master-location-options-start}', '', $view);
								$view = str_replace('{master-location-options-end}', '', $view);

								//Creamos el diccionario
								//Para el insert los campos van vacios y los input estan activos
								$dictionary = array(
									'{value-idLocation}' => '',
									'{value-location}' => '',
									//'{value-idMasterlocation}' => '',
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
								//require_once("View/Formulario.html");
							}
							else
							{
								//Limpiamos los datos.
								//Obtenemos la llave primaria
								require_once("Model/PKGenerator.php");									
								$idLocation = PKGenerator::getPK('Location','idLocation');
								$location = $this->cleanText($_POST['location']);
								$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);
						
								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this->model->insert($idLocation,$location,$idMasterLocation))
								{
									//Cargamos el formulario
									$view = file_get_contents("View/LocationForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Traer el idMasterLocation insertado, ahora si se pone condicion en el comando
									$result = $this -> model -> getMasterLocations("idLocation=".$idMasterLocation);
									//Obtengo la posicion donde se van a insertar los option
									$row_start = strrpos($view,'{master-location-options-start}') + 31;
									$row_end= strrpos($view,'{master-location-options-end}');
									//Hacer copia de la fila donde se va a reemplazar el contenido
									$base_row = substr($view,$row_start,$row_end-$row_start);
									//Acceder al resultado y crear el diccionario
									//Revisar que el nombre de los campos coincida con los de la base de datos
									$rows = '';
									foreach ($result as $row) {
										$new_row = $base_row;
										$dictionary = array(
											'{id-master-location}' => $row['idMasterLocation'], 
											'{master-location}' => $row['Location']
										);
										$new_row = strtr($new_row,$dictionary);
										$rows .= $new_row;
									}
									//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
									$view = str_replace($base_row, $rows, $view);
									$view = str_replace('{master-location-options-start}', '', $view);
									$view = str_replace('{master-location-options-end}', '', $view);

									//Creamos el diccionario
									//Despues de insertar los campos van con la info insertada y los input estan inactivos
									$dictionary = array(
											'{value-idLocation}' => $idLocation,
											'{value-location}' => $_POST['location'],
											//'{value-idMasterLocation}' => $_POST['idMasterLocation'],
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
									//require_once("View/ShowUser.php");

									//Enviamos el correo de que se ha añadido una Ubicación.
									require_once("Controller/mail.php");

									//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
									$subject = "Alta de Ubicación";
									$body = "La Ubicación con los siguientes datos se ha añadido:".
									"\nId   : ". $idLocation.
									"\nLocation : ". $location.
									"\nIdMasterLocation : ". $idMasterLocation;

									//Manadamos el correo solo a administradores y empleados - 6
									if(Mailer::sendMail($subject, $body, 6))
									{
										//echo "<br>Correo enviado con éxito.";
									}
									else
									{
										/*$error =  "Error al enviar el correo.";
										$this -> showErrorView($error);*/
									}
								}
								else
								{
									$error = "Error al insertar la nueva ubicación"; 
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}
						break;
					}
				
					case "update" : 
					{
						//Solo administradores y empleados pueden actualizar las Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para actualizar la información.
							if(empty($_POST))
							{
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("location","update","idLocation","Id Ubicación:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['location']) && isset($_POST['idMasterLocation']))
									{
										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.
										$location = $this->cleanText($_POST['location']);
										$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this->model->update($idLocation,$location,$idMasterLocation))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/LocationForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Traer el idMasterLocation insertado, ahora si se pone condicion en el comando
											$result = $this -> model -> getMasterLocations("idLocation=".$idMasterLocation);
											//Obtengo la posicion donde se van a insertar los option
											$row_start = strrpos($view,'{master-location-options-start}') + 31;
											$row_end= strrpos($view,'{master-location-options-end}');
											//Hacer copia de la fila donde se va a reemplazar el contenido
											$base_row = substr($view,$row_start,$row_end-$row_start);
											//Acceder al resultado y crear el diccionario
											//Revisar que el nombre de los campos coincida con los de la base de datos
											$rows = '';
											foreach ($result as $row) {
												$new_row = $base_row;
												$dictionary = array(
													'{id-master-location}' => $row['idMasterLocation'], 
													'{master-location}' => $row['Location']
												);
												$new_row = strtr($new_row,$dictionary);
												$rows .= $new_row;
											}
											//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
											$view = str_replace($base_row, $rows, $view);
											$view = str_replace('{master-location-options-start}', '', $view);
											$view = str_replace('{master-location-options-end}', '', $view);

											//Creamos el diccionario
											//Despues de insertar los campos van con la info insertada y los input estan inactivos
											$dictionary = array(
												'{value-idLocation}' => $idLocation, 
												'{value-location}' => $location,
												//'{value-idMasterLocation}' => $idMasterLocation,
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
											//require_once("View/UpdateUserShow.php");

											//Enviamos el correo de que se ha modificado una Ubicación.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Modificación de Ubicación";
											$body = "La Ubicación con los siguientes datos se ha modificado:".
											"\nId   : ". $idLocation.
											"\nLocation : ". $location.
											"\nIdMasterLocation : ". $idMasterLocation;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												/*$error = "Error al enviar el correo.";
												$this -> showErrorView($error);*/
											}
										}
										else
										{
											$error = "Error al modificar la ubicación.";
											$this -> showErrorView($error);
										}

									}
									else
									{
										if(($result = $this->model->select($idLocation)) != null)
										{

											//Cargamos el formulario
											$view = file_get_contents("View/LocationForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Se muestra el formulario para modificar
											$dictionary = array(
												'{value-idLocation}' => $result[0]['idLocation'], 
												'{value-location}' => $result[0]['Location'],
												//'{value-idMasterLocation}' => $result[0]['idMasterLocation'],
												'{active}' => '',
												'{action}' => 'update'
											);

											//Sustituir los valores en la plantilla
											$view = strtr($view,$dictionary);

											//Poner despues de sustituir los demas datos para no perder la información del select
											//Para actualizar no se pone condicion, para que esten todas las opciones disponibles
											$result = $this -> model -> getMasterLocations("0=0");
											//Obtengo la posicion donde se van a insertar los option
											$row_start = strrpos($view,'{master-location-options-start}') + 31;
											$row_end= strrpos($view,'{master-location-options-end}');
											//Hacer copia de la fila donde se va a reemplazar el contenido
											$base_row = substr($view,$row_start,$row_end-$row_start);
											//Acceder al resultado y crear el diccionario
											//Revisar que el nombre de los campos coincida con los de la base de datos
											$rows = '';
											foreach ($result as $row) {
												$new_row = $base_row;
												$dictionary = array(
													'{id-master-location}' => $row['idMasterLocation'], 
													'{master-location}' => $row['Location']
												);
												$new_row = strtr($new_row,$dictionary);
												$rows .= $new_row;
											}
											//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
											$view = str_replace($base_row, $rows, $view);
											$view = str_replace('{master-location-options-start}', '', $view);
											$view = str_replace('{master-location-options-end}', '', $view);

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
											$error = "Error al traer los datos para modificar.";
											$this -> showErrorView($error);
										}
									}
								}
								//Sino está seteado, imprimimos el mensaje y se mostrará la vista con 									el formulario para actualizar la información.
								else
								{
									$error = "Error al tratar de modificar el registro, el id no está seteado.";
									$this -> showErrorView($error);
								}

							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}
						break;
					}
					
					case "select" :
					{
						//Solo administradores y empleados pueden ver las Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para hacer select.	
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("location","select","idLocation","Id Ubicación:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this->model->select($idLocation)) != null)
									{
										//Cargamos el formulario
										$view = file_get_contents("View/LocationForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										foreach ($result as $row) {
											$dictionary = array(
												'{value-idLocation}' => $result[0]['idLocation'], 
												'{value-location}' => $result[0]['Location'],
												//'{value-idMasterLocation}' => $result[0]['idMasterLocation'],
												'{active}' => 'disabled',
												'{action}' => 'select'
											);
										}

										//Sustituir los valores en la plantilla
										$view = strtr($view,$dictionary);

										//Poner despues de sustituir los demas datos para no perder la información del select
										//Traer el idMasterLocation, ahora si se pone condicion en el comando
										$result = $this -> model -> getMasterLocations("idLocation=".$result[0]['idMasterLocation']);
										//Obtengo la posicion donde se van a insertar los option
										$row_start = strrpos($view,'{master-location-options-start}') + 31;
										$row_end= strrpos($view,'{master-location-options-end}');
										//Hacer copia de la fila donde se va a reemplazar el contenido
										$base_row = substr($view,$row_start,$row_end-$row_start);
										//Acceder al resultado y crear el diccionario
										//Revisar que el nombre de los campos coincida con los de la base de datos
										$rows = '';
										foreach ($result as $row) {
											$new_row = $base_row;
											$dictionary = array(
												'{id-master-location}' => $row['idMasterLocation'], 
												'{master-location}' => $row['Location']
											);
											$new_row = strtr($new_row,$dictionary);
											$rows .= $new_row;
										}

										//Reemplazar en la vista la fila base por los option creados y eliminar inicio y fin del option
										$view = str_replace($base_row, $rows, $view);
										$view = str_replace('{master-location-options-start}', '', $view);
										$view = str_replace('{master-location-options-end}', '', $view);

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
								//Si el ID no está seteado, se marcará el error y se mostrará la vista con 									el formulario para hacer select.
								else
								{
									$error = "Error al mostrar la ubicación, el id no está seteado.";
									$this -> showErrorView($error);
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);
						}
						break;
					}
					
					case "delete" :
					{
						//Solo administradores y empleados pueden eliminar Ubicaciones.
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío, si lo está se mostrará la vista con el 								formulario para eliminar una Ubicación.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a eliminar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar de $_POST y el texto a mostrar en el label del input
								$this -> showGetIdView("location","delete","idLocation","Id Ubicación:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idLocation']))
								{
									//Limpiamos el id.
									$idLocation = $this->cleanInt($_POST['idLocation']);

									//Recogemos el resultado de la eliminación.
									$result = $this->model->delete($idLocation);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo de que se ha eliminado una Ubicación.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Ubicación";
										$body = "La Ubicación con los siguientes datos se ha eliminado:".
										"\nId   : ". $idLocation.
										"\nLocation : ". $location.
										"\nIdMasterLocation : ". $idMasterLocation;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											//echo "<br>Correo enviado con éxito.";
										}
										else
										{
											/*echo "Error al enviar el correo.";
											$this -> showErrorView($error);*/
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar la ubicación.";
										$this -> showErrorView($error);
									}
								}
								//Si el id no está seteado, marcamos el error y se mostrará la vista para 									eliminar un Evento.
								else
								{
									$error = "Error al eliminar el registro, el id no está seteado.";
									$this -> showErrorView($error);	
								}
							}
						}
						else
						{
							$error = "No tiene permisos para realizar esta acción";
							$this -> showErrorView($error);	
						}
						break;
					}

					case "list" :
					{
						//Solo si es empleado o administrador puede consultar la lista de eventos
						if(!$this -> isClient())
						{
							//Revisar si hay un filtro, sino hay se queda el filtro po default
							$filter = "0=0";
							if(isset($_POST['filter_condition'])){
								//Creamos la condicion con el campo seleccionadoo y el filtro
								$filter = $_POST['filter_select']." = '".$_POST['filter_condition']."';"; 
							}


							//Ejecutamos el query y guardamos el resultado.
							$result = $this -> model -> getList($filter);

							if($result !== FALSE)
							{
								//Cargamos el formulario
								$view = file_get_contents("View/LocationTable.html");
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
										'{value-idLocation}' => $row['idLocation'], 
										'{value-location}' => $row['Location'],
										'{value-idMasterLocation}' => $row['idMasterLocation'],
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
								$error = "No hay registros para mostrar.";
								$this -> showErrorView($error);
							}
						}
						else
						{
							$error = "No tiene permisos para ver esta lista";
							$this -> showErrorView($error);
						}

						break;
					}
			
				} /* fin switch */
				//El logout se hace cuando se especifica
				//$this -> logout();
			}
			else
			{
				//Si no ha iniciado sesion mostrar la vista para hacer login
				$this -> showLoginView($_GET['ctl'],$_GET['act']);
			}

		} /* fin run */

	}

?>
