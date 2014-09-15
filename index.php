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
	case "checklist":
	{
		require_once("Controller/ChecklistCtl.php");
		$ctl = new ChecklistCtl();
		break;
	}
	case "damagedetail":
	{
		require_once("Controller/DamageDetailCtl.php");
		$ctl = new DamageDetailCtl();
		break;
	}
	case "damage":
	{
		require_once("Controller/DamageCtl.php");
		$ctl = new DamageCtl();
		break;
	}
	case "vehiclepart":
	{
		require_once("Controller/VehiclePartCtl.php");
		$ctl = new VehiclePartCtl();
		break;
	}
	default:
		echo "Controlador indefinido"; 
}

$ctl->run();

?>
