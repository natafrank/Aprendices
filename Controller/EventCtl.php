<?php

include("Controller/StandardCtl.php");

class EventCtl extends StandardCtl{
	private $model;
	private $rows = array( 1=>array('Event'=>'Cambio de Ubicacion',),
			       2=>array('Event'=>'Cambio de ubicacion',),
			       3=>array('Event'=>'Cambio de ubicacion',) );

	function __construct(){
		require_once("Model/EventMdl.php");
		$this->model = new EventMdl();
	}

	function run(){
		switch($_GET['act']){
			case "insert" :
				if(empty($_POST)){
					require_once("View/InsertEvent.php");
				}
				else{
					$idEvent = $this->cleanText($_POST['idEvent']);
					$Event = $this->cleanText($_POST['Event']);

					$resul = $this->model->insert($idEvent,$Event);

					if($result){
						require_once("View/ShowInserEvent.php");
					}
					else{
						$error = "Error al insertar el nuevo registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'update':
				if(empty($_POST)){
					require_once("View/UpdateEvent.php");
				}
				else{
					$idEvent = $this->cleanInt($_POST['idEvent']);
					$Event = $this->cleanText($_POST['Event']);

					$resul = $this->model->update($idEvent,$Event);

					if($result){
						require_once("View/ShowUpdateEvent.php");
					}
					else{
						$error = "Error al actualizar el registro";
						require_once("View/Error.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/SelectEvent.php");
				}
				else{
					if(!isset($_POST['idEvent'])){
						$error = 'No se ha especificado el ID del registro que se va a mostrar';
						require_once("View/Error.php");	
					}
					else{
						if(($idEvent = $this->cleanInt($_POST['idEvent'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->select($idEvent);
		
							if(array_key_exists($idEvent,$this->rows)){
								var_dump($this->rows[$idEvent]);
							}

							//if($result){
							//	require_once("View/SelectEvent.php");
							//}
							else{
								$error = "No se encuentra el registro";
								require_once("View/Error.php");
							}
						}
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteEvent.php");
				}
				else{
					if(!isset($_POST['idEvent'])){
						$error = 'No se ha especificado el ID del registro que se va a eliminar';
						require_once("View/Error.php");	
					}
					else{
						if(($idEvent = $this->cleanInt($_POST['idEvent'])) == 0){
							$error = 'No se ingreso un entero';
							require_once("View/Error.php");
						}
						else{
							//$result = $this->model->delete($idEvent);
		
							if(array_key_exists($idEvent,$this->rows)){
								unset($this->rows[$idEvent]);
								require_once("View/ShowDeleteEvent.php");
							}

							//if($result){
							//	require_once("View/SelectEvent.php");
							//}
							else{
								$error = "No se encuentra el registro";
								require_once("View/Error.php");
							}
						}
					}
				}
				break;
		}
	}
}

?>
