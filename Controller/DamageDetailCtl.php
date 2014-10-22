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
								require_once("View/InsertDamageDetail.php");
							}
							else
							{
								//Limpiamos los datos.
								$idDamageDetail = $this -> cleanInt($_POST['idDamageDetail']);  // Para este dato se creara un Trigger en la BD
								$idChecklist    = $this -> cleanInt($_POST['idChecklist']);
								$idVehiclePart  = $this -> cleanInt($_POST['idVehiclePart']);
								$idDamage       = $this -> cleanInt($_POST['idDamage']);

								//Recogemos el resultado de la inserción e imprimimos un mensaje
								//en base a este resultado.
								if($result = $this -> model -> insert($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage))
								{
									require_once("View/ShowInsertDamageDetail.php");
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
							$error = "No tiene permisos para realizar esta accion";
							require_once("View/Error.php");
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
								require_once("View/UpdateDamageDetail.php");
							}
							else
							{
								//Comprobamos que el id este seteado
								if(isset($_POST['idDamageDetail']))
								{
									//Limpiamos el ID
									$idDamageDetail = $this -> cleanInt($_POST['idDamageDetail']);
							
									//Primero mostramos el id que se quire modificar.
									//Recogemos el resultado y si contiene información, la mostramos.
									if(($result = $this -> model -> select($idDamageDetail)) != null)
									{
										echo var_dump($result);

										//La modificación se realizará en base al id.
										//Por ahora se modificarán todos los atributos.  
										$idChecklist    = $this -> cleanInt($_POST['idChecklist']);
										$idVehiclePart  = $this -> cleanInt($_POST['idVehiclePart']);
										$idDamage       = $this -> cleanInt($_POST['idDamage']);

										//Se llama a la función de modificación.
										//Se recoge el resultado y en base a este resultado
										//se imprime un mensaje.
										if($this -> model -> update($idDamageDetail,$idChecklist,$idVehiclePart,$idDamage))
										{
											require_once("View/ShowUpdateDamageDetail.php");	
										}
										else
										{
											$error = "Error al modificar el detalle de daños.";
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
							$error = "No tiene permisos para realizar esta accion";
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
							if(isset($_POST['idDamageDetail']))
							{
								//Limpiamos el id.
								$idDamageDetail = $this -> cleanText($_POST['idDamageDetail']);

								//Recogemos el resultado y si contiene información, la mostramos.
								if(($result = $this -> model -> select($idDamageDetail)) != null)
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
						//Solo administradores y empleados pueden hacer eliminaciones de Detalles de Daños
						if( !$this -> isClient() )
						{
							//Comprobamos que el $_POST no esté vacío.
							if(empty($_POST))
							{
								require_once("View/DeleteDamageDetail.php");
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
										require_once("View/DeleteDamageDetail.php");
									}
									//Si no pudimos eliminar, señalamos el error.
									else
									{
										$error = "Error al elimiar el detalle de daños.";
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
							$error = "No tiene permisos para realizar esta accion";
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
