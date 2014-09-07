<?php

switch($_GET["ctl"]){
	case "nombre_controlador":
		require_once("Controlador/archivo_controlador.php");
		$ctl = new clase_archivo_controlador();
		break;
	//default:
}

$ctl->ejecutar();

?>
