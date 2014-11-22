<?php
	include("Controller/StandardCtl.php");
	
	class DamageDetailCtl extends StandardCtl
	{
		/**
		 * Variable Modelo de la clase DamageDetail.
		 *
		 * @access private
		 * @var DamageDetailMdl $model - Variable para realizar las funciones de Modelo en la estructura MVC.
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
			
			require_once("Model/DamageDetailMdl.php");
			$this -> model = new DamageDetailMdl();
			
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
						//Solo administradores y empleados pueden hacer inserciones de Detalles de Daños
						if( !$this -> isClient() )
						{
							//Comprobar si $_POST está vacio, si es así se mostrará el formulario para capturar los datos.
							if(empty($_POST))
							{
								//Cargamos el formulario
								$view = file_get_contents("View/DamageDetailForm.html");
								$header = file_get_contents("View/header.html");
								$footer = file_get_contents("View/footer.html");

								//Creamos el diccionario
								//Para el insert los cmapos van vacios y los input estan activos
								$dictionary = array(
													'{value-id-damage-detail}' => '', 
													'{value-id-checklist}' => '', 
													'{value-id-vehicle-part}' => '', 
													'{value-id-damage}' => '', 
													'{value-damage-severity}' => '', 
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
								//require_once("View/InsertDamageDetail.php");
							}
							else
							{
								//Limpiamos los datos.
								$idDamageDetail = $this -> cleanInt($_POST['idDamageDetail']);  // Para este dato se creara un Trigger en la BD
								$idChecklist    = $this -> cleanInt($_POST['idChecklist']);
								$idVehiclePart  = $this -> cleanInt($_POST['idVehiclePart']);
								$idDamage       = $this -> cleanInt($_POST['idDamage']);
								$DamageSeverity = $this -> cleanInt($_POST['DamageSeverity']);

								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this -> model -> insert($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage,$DamageSeverity))
								{
									//Cargamos el formulario
									$view = file_get_contents("View/DamageDetailForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Creamos el diccionario
									//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
									$dictionary = array(
														'{value-id-damage-detail}' => $_POST['idDamageDetail'], 
														'{value-id-checklist}' => $_POST['idChecklist'], 
														'{value-id-vehicle-part}' => $_POST['idVehiclePart'], 
														'{value-id-damage}' => $_POST['idDamage'], 
														'{value-damage-severity}' => $_POST['DamageSeverity'], 
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
									//require_once("View/ShowInsertDamageDetail.php");
								}
								else
								{
									$error = "Error al insertar el nuevo registro"; 
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
				
					case "update" : 
					{
						//Solo administradores y empleados pueden hacer actualizaciones de Detalles de Daños
						if( !$this -> isClient() )
						{	
							//Comprobamos que $_POST no este vacio.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("damagedetail","update","idDamageDetail","Id Detalle de Daño:");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['idDamageDetail']))
								{
									//Limpiamos el ID
									$idDamageDetail = $this -> cleanInt($_POST['idDamageDetail']);
							
									//Primero mostramos el id que se quire modificar.
									//Comprobamos si están seteadas las variables en el POST
									if(isset($_POST['idChecklist']) && isset($_POST['idVehiclePart']) && isset($_POST['idDamage']) && isset($_POST['DamageSeverity']))
									{
										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.  
										$idChecklist    = $this -> cleanInt($_POST['idChecklist']);
										$idVehiclePart  = $this -> cleanInt($_POST['idVehiclePart']);
										$idDamage       = $this -> cleanInt($_POST['idDamage']);
										$DamageSeverity = $this -> cleanInt($_POST['DamageSeverity']);
										
										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage,$DamageSeverity))
										{
											//Cargamos el formulario
											$view = file_get_contents("View/DamageDetailForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
														'{value-id-damage-detail}' => $idDamageDetail, 
														'{value-id-checklist}' => $idChecklist, 
														'{value-id-vehicle-part}' => $idVehiclePart, 
														'{value-id-damage}' => $idDamage, 
														'{value-damage-severity}' => $DamageSeverity, 
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
											//require_once("View/ShowUpdateDamageDetail.php");	
										}
										else
										{
											$error = "Error al modificar el detalle de daños.";
											$this -> showErrorView($error);
										}	
									}
									else
									{
										if(($result = $this -> model -> select($idDamageDetail)) != null)
										{
											//Cargamos el formulario
											$view = file_get_contents("View/DamageDetailForm.html");
											$header = file_get_contents("View/header.html");
											$footer = file_get_contents("View/footer.html");

											//Creamos el diccionario
											//Despues de insertar los cmapos van con la info insertada y los input estan inactivos
											$dictionary = array(
														'{value-id-damage-detail}' => $result[0]['idDamageDetail'], 
														'{value-id-checklist}' => $result[0]['idChecklist'], 
														'{value-id-vehicle-part}' => $result[0]['idVehiclePart'], 
														'{value-id-damage}' => $result[0]['idDamage'], 
														'{value-damage-severity}' => $result[0]['DamageSeverity'], 
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
						//Comprobamos que el $_POST no esté vacío.	
						if(empty($_POST))
						{
							//Si el post está vacio cargamos la vista para solicitar el id a consultar
							//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
							$this -> showGetIdView("damagedetail","select","idDamageDetail","Id Detalle de Daño:");
						}
						else
						{
							//Comprobamos que el id esté seteado.
							if(isset($_POST['idDamageDetail']))
							{
								//Limpiamos el id.
								$idDamageDetail = $this -> cleanText($_POST['idDamageDetail']);

								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($idDamageDetail)) != null)
								{
									//Cargamos el formulario
									$view = file_get_contents("View/DamageDetailForm.html");
									$header = file_get_contents("View/header.html");
									$footer = file_get_contents("View/footer.html");

									//Acceder al resultado y crear el diccionario
									//Revisar que el nombre de los campos coincida con los de la base de datos
									foreach ($result as $row) {
										$dictionary = array(
															'{value-id-damage-detail}' => $result['idDamageDetail'], 
															'{value-id-checklist}' => $result['idChecklist'], 
															'{value-id-vehicle-part}' => $result['idVehiclePart'], 
															'{value-id-damage}' => $result['idDamage'], 
															'{value-damage-severity}' => $result['DamageSeverity'], 
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
					
					case "delete" :
					{
						//Solo administradores y empleados pueden hacer eliminaciones de Detalles de Daños
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								//Si el post está vacio cargamos la vista para solicitar el id a consultar
								//Se envia como parametro el controlador, la accion, el campo como nos lo va a regresar ne $_POST y el texto a mostrar en ellabel del input
								$this -> showGetIdView("damagedetail","delete","idDamageDetail","Id Detalle de Daño:");
							}

							else
							{
								//Comprobamos que el id esté seteado.
								if(isset($_POST['idDamageDetail']))
								{
									//Limpiamos el id.
									$idDamageDetail = $this -> cleanText($_POST['idDamageDetail']);

									//Recogemos el resultado de la eliminación.
									$result = $this -> model -> delete($idDamageDetail);

									//Si la eliminación fue exitosa, mostramos el mensaje.
									if($result)
									{
										//Muestra la vista de que la eliminación se realizó con éxito
										$this -> showDeleteView();
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar el detalle de daños.";
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
							$view = file_get_contents("View/DamageDetailTable.html");
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
													'{value-id-damage-detail}' => $result['idDamageDetail'], 
													'{value-id-checklist}' => $result['idChecklist'], 
													'{value-id-vehicle-part}' => $result['VehiclePart'], 
													'{value-id-damage}' => $result['Damage'], 
													'{value-damage-severity}' => $result['DamageSeverity'], 
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
							$error = "Error al listar detalles de daños.";
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
