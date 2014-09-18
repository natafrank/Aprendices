<?php

include("Controller/StandardCtl.php");

class EventCtl extends StandardCtl{
	private $model;

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
					$idEvent = $this->cleanFloat($_POST['idEvent']);
					$Event = $this->cleanFloat($_POST['Event']);

					$resul = $this->model->insert($idEvent,$Event);

					if($result){
						require_once("View/InserEvent.php");
					}
					else{
						require_once("View/ErrorInsert.php");
					}
				}
				break;
			case 'update':
				if(empty($_POST)){
					require_once("View/UpdateEvent.php");
				}
				else{
					$idEvent = $this->cleanText($_POST['idEvent']);
					$Event = $this->cleanText($_POST['Event']);

					$resul = $this->model->insert($idEvent,$Event);

					if($result){
						require_once("View/UpdateEvent.php");
					}
					else{
						require_once("View/ErrorUpdate.php");
					}
				}
				break;
			case 'select':
				if(empty($_POST)){
					require_once("View/DeleteEvent.php");
				}
				else{
					$idEvent = $this->cleanText($_POST['idEvent']);

					$result = $this->model->select($idEvent);

					if($result){
						require_once("View/SelectEvent.php");
					}
					else{
						require_once("View/ErrorSelect.php");
					}
				}
				break;
			case 'delete':
				if(empty($_POST)){
					require_once("View/DeleteEvent.php");
				}
				else{
					$idEvent = $this->cleanText($_POST['idEvent']);
					//validar que exista el registro en la base de datos
					//if(){
						$result = $this->model->delete($idEvent);
						if($result){
							require_once("View/DeleteEvent.php");
						}
						else{
							require_once("View/ErrorDelete.php");
						}
					//}
					//else{
					//	require_once("View/ErrorDelete");
					//}
				}
				break;
		}
	}
}

?>
