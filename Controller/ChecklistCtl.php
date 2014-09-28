<?php
	include("Controller/StandardCtl.php");
	
	class ChecklistCtl extends StandardCtl
	{
		private $model;

		public function run()
		{
			
			require_once("Model/ChecklistMdl.php");
			$this -> model = new ChecklistMdl();			
			
			switch($_GET['act'])
			{
					
				case "insert" :
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
					break;
				}
			
			} /* fin switch */

		} /* fin run */


	}

?>
