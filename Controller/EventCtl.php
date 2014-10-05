<?php
	include("Controller/StandardCtl.php");
	
	class EventCtl extends StandardCtl
	{
		private $model;
		
		function __construct()
		{
			require_once("Model/EventMdl.php");
			$this->model = new EventMdl();
		}

		public function run()
		{		
			
			switch($_GET['act'])
			{
					
				case "insert" :
				{	
					//Comprobamos que el $_POST no est� vac�o.
					if(empty($_POST))
					{
						require_once("View/InsertEvent.php");
					}
					else
					{
						//Limpiamos los datos.
						$idEvent = $this->cleanInt($_POST['idEvent']); // Para este dato se creara un Trigger en la BD
						$Event   = $this->cleanText($_POST['Event']);
						
						//Recogemos el resultado de la inserci�n e imprimimos un mensaje
						//en base a este resultado.
						if($result = $this->model->insert($idEvent,$Event))
						{
							require_once("View/ShowInsertEvent.php");
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
					//Comprobamos que el $_POST no est� vac�o.
					if(empty($_POST))
					{
						require_once("View/UpdateEvent.php");
					}
					else
					{
						//Comprobamos que el id est� seteado.
						if(isset($_POST['idEvent']))
						{
							//Limpiamos el id.
							$idEvent = $this->cleanInt($_POST['idEvent']);

							//Primero mostramos el id que se quire modificar.
							//Recogemos el resultado y si contiene informaci�n, la mostramos.
							if(($result = $this->model->select($idEvent)) != null)
							{
								echo var_dump($result);

								//La modificaci�n se realizar� en base al id.
								//Por ahora se modificar�n todos los atributos.
								$Event = $this->cleanText($_POST['Event']);

								//Se llama a la funci�n de modificaci�n.
								//Se recoge el resultado y en base a este resultado
								//se imprime un mensaje.
								if($this->model->update($idEvent, $Event))
								{
									require_once("View/ShowUpdateEvent.php");	
								}
								else
								{
									$error = "Error al modificar el evento.";
									require_once("View/Error.php");
								}
							}
							//Si el resultado no contiene informaci�n, mostramos el error.
							else
							{
								$error = "Error al tratar de mostrar el registro.";
								require_once("View/Error.php");
							}
						}
						//Sino est� seteado, imprimimos el mensaje.
						else
						{
							$error = "El id no est� seteado.";
							require_once("View/Error.php");
						}

					}
					break;
				}
					
				case "select" :
				{
					//Comprobamos que el $_POST no est� vac�o.	
					if(empty($_POST))
					{
						$error = "No se especific� el id.";
						require_once("View/Error.php");
					}
					else
					{
						//Comprobamos que el id est� seteado.
						if(isset($_POST['idEvent']))
						{
							//Limpiamos el id.
							$idEvent = $this->cleanInt($_POST['idEvent']);

							//Recogemos el resultado y si contiene informaci�n, la mostramos.
							if(($result = $this->model->select($idEvent)) != null)
							{
								echo var_dump($result);
							}
							//Si el resultado no contiene informaci�n, mostramos el error.
							else
							{
								$error = "Error al tratar de mostrar el registro.";
								require_once("View/Error.php");
							}
						}
						//Imprimimos el error si la variable no est� seteada.
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
					//Comprobamos que el $_POST no est� vac�o.
					if(empty($_POST))
					{
						require_once("View/DeleteEvent.php");
					}

					else
					{
						//Comprobamos que el id est� seteado.
						if(isset($_POST['idEvent']))
						{
							//Limpiamos el id.
							$idEvent = $this->cleanInt($_POST['idEvent']);

							//Recogemos el resultado de la eliminaci�n.
							$result = $this->model->delete($idEvent);

							//Si la eliminaci�n fue exitosa, mostramos el mensaje.
							if($result)
							{
								require_once("View/DeleteEvent.php");
							}
							//Si no pudimos eliminar, se�alamos el error.
							else
							{
								$error = "Error al elimiar el evento.";
								require_once("View/Error.php");
							}
						}
						//Si el id no est� seteado, marcamos el error.
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