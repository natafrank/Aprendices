<?php
	include("Controller/StandardCtl.php");
	
	class DamageCtl extends StandardCtl
	{
		/**
		 * Variable Modelo de la clase Damage.
		 *
		 * @access private
		 * @var DamageMdl $model - Variable para realizar las funciones de Modelo en la estructura MVC.
		 */
		private $model;

		/**
		 * Funcion principal del controlador.
		 *
		 * Se encarga del manejo de vistas y funciones del modelo
		 * de acuerdo a la accion que se indica con la llave 'act' en $_GET
		 *
		 */
		public function run(){
			
			require_once("Model/DamageMdl.php");
			$this->model = new DamageMdl();
			
			//Verificar si hay una sesion iniciada
			if(!$this -> isLogged())
			{
				//Si no verificar que esten seteadas las variables para hacer login
				if( isset($_POST['session_login']) && isset($_POST['session_pass']) )
				{
					$this -> login($_POST['session_login'],$_POST['session_pass']);	
				}
			}
			
			//validar que el login se haya hecho correctamente
			if( $this -> isLogged() )
			{ 			
			
				switch($_GET['act'])
				{
					
					case "insert" :
					{	
						//Solo administradores y empleados pueden hacer inserciones de Daños
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/DamageForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-damage}' => '', 
													'{value-damage}' => '', 
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
								//require_once("View/InsertDamage.php");
							}
							else
							{
								//Comprobamos que las variables estén seteada
								if(isset($_POST['idDamage']) && isset($_POST['Damage']))
								{
									//Limpiamos los datos.
									$idDamage = $this -> cleanText($_POST['idDamage']); // Para este dato se creara un Trigger en la BD
									$Damage    = $this -> cleanText($_POST['Damage']);
							
									//Recogemos el resultado de la inserción e imprimimos un mensaje
									//en base a este resultado.
									if($result= $this -> model -> insert($idDamage,$Damage))
									{
										//Cargamos el formulario
										$view = file_get_contents("View/DamageForm.html");
										$header = file_get_contents("View/header.html");
										$footer = file_get_contents("View/footer.html");

										//Creamos el diccionario
										//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
										$dictionary = array(
															'{value-id-damage}' => $_POST['idDamage'], 
															'{value-damage}' => $_POST['Damage'], 
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
										//require_once("View/ShowInsertDamage.php");

										//Enviamos el correo de que se ha añadido un daño.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Alta de Daño";
										$body = "El daño con los siguientes datos se ha añadido:".
										"\nId   : ". $idDamage.
										"\nDaño : ". $Damage;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											//echo "<br>Correo enviado con éxito.";
										}
										else
										{
											//echo "<br />Error al enviar el correo.";
											$error = "Error al enviar el correo.";
											$this -> showErrorView($error);
										}
								
									}
									else
									{
										$error = "Error al insertar el nuevo registro"; 
										$this -> showErrorView($error);
									}
								}
								else
								{
									$error = "Error al insertar el nuevo registro, falta id o daño."; 
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
						//Solo administradores y empleados pueden eliminar Daños
						if( !$this -> isClient() )
						{
						
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("damage","delete","idDamage","Id Daño:");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idDamage']))
								{
									//Limpiamos el id.
									$idDamage = $this -> cleanText($_POST['idDamage']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($idDamage);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();

										//Enviamos el correo de que se ha eliminado un daño.
										require_once("Controller/mail.php");

										//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
										$subject = "Eliminación de Daño";
										$body = "Se ha eliminado el daño con ID: ". $idDamage;

										//Manadamos el correo solo a administradores y empleados - 6
										if(Mailer::sendMail($subject, $body, 6))
										{
											//echo "<br>Correo enviado con éxito.";
										}
										else
										{
											//echo "<br />Error al enviar el correo.";
											$error = "Error al enviar el correo.";
											$this -> showErrorView($error);
										}
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar el daño.";
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


					case "select" :
					{
						//Comprobamos que el $_POST no esté vacío.	
						if(empty($_POST))
						{
							//Si el post está vacio cargamos la vista para solicitar el id a consultar
							//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
							$this -> showGetIdView("damage","select","idDamage","Id Daño:");
						}
						else
						{
							//Comprobamos que el id esté seteado.
							if(isset($_POST['idDamage']))
							{
								//Limpiamos el id.
								$idDamage = $this -> cleanText($_POST['idDamage']);

								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($idDamage)) != null)
								{
									//Cargamos el formulario
									$view = file_get_contents("View/DamageForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Acceder al resultado y crear el diccionario
									//Revisar que el nombre de los campos coincida con los de la base de datos
									foreach ($result as $row) {
										$dictionary = array(
															'{value-id-damage}' => $result['idDamage'], 
															'{value-damage}' => $result['Damage'], 
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
						break;
					}
					
					case "update" : 
					{
						//Solo administradores y empleados pueden actualizar Daños
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("damage","update","idDamage","Id Daño:");
							}
							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idDamage']))
								{
									//Limpiamos el id.
									$idDamage = $this -> cleanText($_POST['idDamage']);

									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['Damage']))
									{
										//La modificación se realizará en base al id.
										$Damage = $this -> cleanText($_POST['Damage']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y se muestra
										if($this -> model -> update($idDamage, $Damage))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/DamageForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
														'{value-id-damage}' => $idDamage, 
														'{value-damage}' => $Damage, 
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
											
											//Enviamos el correo de que se ha modificado un daño.
											require_once("Controller/mail.php");

											//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
											$subject = "Actualización de Daño";
											$body = "El daño con los siguientes datos se ha modificado:".
											"\nId   : ". $idDamage.
											"\nDaño : ". $Damage;

											//Manadamos el correo solo a administradores y empleados - 6
											if(Mailer::sendMail($subject, $body, 6))
											{
												//echo "<br>Correo enviado con éxito.";
											}
											else
											{
												//echo "<br />Error al enviar el correo.";
												$error = "Error al enviar el correo.";
												$this -> showErrorView($error);
											}	
										}
										else
										{
											$error = "Error al modificar el daño.";
											$this -> showErrorView($error);
										}
									}
									//Si no estan seteadas mostramos el formulario de actualizacion
									else
									{
										if(($result = $this -> model -> select($idDamage)) != null)
										{									
											//Cargamos el formulario
											$view = file_get_contents("View/DamageForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
														'{value-id-damage}' => $result[0]['idDamage'], 
														'{value-damage}' => $result[0]['Damage'], 
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
											$error = "Error al tratar de mostrar el registro.";
											$this -> showErrorView($error);
										}
									}
								}
								//Sino está seteado, imprimimos el mensaje.
								else
								{
									$error = "Error al tratar de modificar el daño, el id no está seteado.";
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
						//Solo empleados y administradores pueden ver la lista
						if( $this -> isEmployee() || $this -> isAdmin() )
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
								$view = file_get_contents("View/DamageTable.html");
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
														'{value-id-damage}' => $row['idDamage'], 
														'{value-damage}' => $row['Damage'], 
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
								$error = "Error al listar daños.";
								$this -> showErrorView($error);
							}
						}
						else
						{
							$error = "No tiene permisos para ver esta lista.";
							$this -> showErrorView($error);	
						}

						break;
					}
			
				} /* fin switch */
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
