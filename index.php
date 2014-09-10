<?php

switch($_GET["ctl"]){

	case "vehicle":
	{
		require_once("Controlador/VehicleCtl.php");
		$ctl = new vehicleCtl();
		break;
	}
	//default:
}

$ctl->ejecutar();

?>
