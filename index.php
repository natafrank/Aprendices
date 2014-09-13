<?php

switch($_GET["ctl"]){

	case "vehicle":
	{
		require_once("Controller/VehicleCtl.php");
		$ctl = new vehicleCtl();
		break;
	}
	case "user":
	{
		require_once("Controller/UserCtl.php");
		$ctl = new UserCtl();
		break;
	}
	//default:
}

$ctl->run();

?>
