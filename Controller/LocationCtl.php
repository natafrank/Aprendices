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
			
			switch($_GET['act'])
			{
					
				case "insert" :
				{	
					//Comprobamos que el $_POST no est� vac�o.
					if(empty($_POST))
					{
						require_once("View/InsertLocation.php");
					}
					else
					{
						//Limpiamos los datos.
						$idLocation = $this->cleanInt($_POST['idLocation']);
						$location = $this->cleanText($_POST['location']);
						$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);
						
						//Recogemos el resultado de la inserci�n e imprimimos un mensaje
						//en base a este resultado.
						if($result = $this->model->insert($idLocation,$location,$idMasterLocation))
						{
							require_once("View/ShowInserLocation.php");
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
						require_once("View/UpdateLocation.php");
					}
					else
					{
						//Comprobamos que el id est� seteado.
						if(isset($_POST['idLocation']))
						{
							//Limpiamos el id.
							$idLocation = $this->cleanInt($_POST['idLocation']);

							//Primero mostramos el id que se quire modificar.
							//Recogemos el resultado y si contiene informaci�n, la mostramos.
							if(($result = $this->model->select($idLocation)) != null)
							{
								echo var_dump($result);

								//La modificaci�n se realizar� en base al id.
								//Por ahora se modificar�n todos los atributos.
								$idLocation = $this->cleanInt($_POST['idLocation']);
								$location = $this->cleanText($_POST['location']);
								$idMasterLocation = $this->cleanInt($_POST['idMasterLocation']);

								//Se llama a la funci�n de modificaci�n.
								//Se recoge el resultado y en base a este resultado
								//se imprime un mensaje.
								if($this->model->update($idLocation,$location,$idMasterLocation))
								{
									require_once("View/ShowUpdateLocation.php");	
								}
								else
								{
									$error = "Error al modificar la ubicaci�n.";
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
						if(isset($_POST['idLocation']))
						{
							//Limpiamos el id.
							$idLocation = $this->cleanInt($_POST['idLocation']);

							//Recogemos el resultado y si contiene informaci�n, la mostramos.
							if(($result = $this->model->select($idLocation)) != null)
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
						require_once("View/DeleteLocation.php");
					}
					else
					{
						//Comprobamos que el id est� seteado.
						if(isset($_POST['idLocation']))
						{
							//Limpiamos el id.
							$idLocation = $this->cleanInt($_POST['idLocation']);

							//Recogemos el resultado de la eliminaci�n.
							$result = $this->model->delete($idLocation);

							//Si la eliminaci�n fue exitosa, mostramos el mensaje.
							if($result)
							{
								require_once("View/DeleteLocation.php");
							}
							//Si no pudimos eliminar, se�alamos el error.
							else
							{
								$error = "Error al elimiar la ubicaci�n.";
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