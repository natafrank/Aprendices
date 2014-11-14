<?php
	
	session_start();

	if(count($_GET) > 0)
	{
		switch($_GET["ctl"])
		{

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
			case "vehiclebrand":
			{
				require_once("Controller/VehicleBrandCtl.php");
				$ctl = new VehicleBrandCtl();
				break;
			}
			case "vehiclemodel" :
			{
				require_once("Controller/VehicleModelCtl.php");
				$ctl = new VehicleModelCtl();
				break;
			}
			case "usertype":
			{
				require_once("Controller/UserTypeCtl.php");
				$ctl = new UserTypeCtl();
				break;
			}
			case "event":
			{
				require_once("Controller/EventCtl");
				$ctl = new EventCtl();
				break;
			}
			case "eventregistry":
			{
				require_once("Controller/EventRegistryCtl");
				$ctl = new EventRegistryCtl();
				break;
			}
			case "location":
			{
				require_once("Controller/LocationCtl");
				$ctl = new LocationCtl();
				break;
			}
			case "vehiclestatus":
			{
				require_once("Controller/VehicleStatusCtl");
				$ctl = new VehicleStatusCtl();
				break;
			}
			case "logout":
			{
				//Si no se especifico ctl mostramos la pestaña de login
				require_once("Controller/StandardCtl");
				$ctl = new StandardCtl();
				//Terminamos la sesion
				$ctl -> logout();
				//Por default nos envia a la consulta de un usuario después del login
				$ctl -> showLoginView('user','select');
				break;
			}
			default:
			{
				//Si no se especifico ctl mostramos la pestaña de login
				require_once("Controller/StandardCtl");
				$ctl = new StandardCtl();
				//Por default nos envia a la consulta de un usuario despues del login
				$ctl -> showLoginView('user','select');
			}
		}

		$ctl->run();
	}
	else
	{
		echo "Aprendices WEB";
	}

?>
