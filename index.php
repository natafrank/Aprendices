<?php

switch($_GET["ctl"]){

	case "vehicle":
	{
		require_once("Controlador/VehicleCtl.php");
		$ctl = new vehicleCtl();
		break;
	}
	case "user":
	{
		require_once("Controlador/UserCtl.php");
		$ctl = new UserCtl();
		break;
	}
	//default:
}

$ctl->run();

?>
