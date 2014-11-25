<?php
	
	session_start();

	if(count($_GET) > 0)
	{
		$is_standard = FALSE;
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
				require_once("Controller/EventCtl.php");
				$ctl = new EventCtl();
				break;
			}
			case "eventregistry":
			{
				require_once("Controller/EventRegistryCtl.php");
				$ctl = new EventRegistryCtl();
				break;
			}
			case "location":
			{
				require_once("Controller/LocationCtl.php");
				$ctl = new LocationCtl();
				break;
			}
			case "vehiclestatus":
			{
				require_once("Controller/VehicleStatusCtl.php");
				$ctl = new VehicleStatusCtl();
				break;
			}
			case "logout":
			{
				$is_standard = TRUE;
				//Si no se especifico ctl mostramos la pestaña de login
				require_once("Controller/StandardCtl.php");
				$ctl = new StandardCtl();
				//Terminamos la sesion
				$ctl -> logout();
				$ctl -> showLoginView('welcome','none');
				break;
			}
			case "forgotpassword":
			{
				$is_standard = TRUE;
				require_once("Controller/ForgotPasswordCtl.php");
				$ctl = new ForgotPasswordCtl();
				break;
			}
			case "welcome":
			{
				$is_standard = TRUE;
				//Si esta seteada la info para hacer login
				if(isset($_POST['session_login']) && isset($_POST['session_pass']))
				{					
					require_once("Controller/StandardCtl.php");
					$ctl = new StandardCtl();

					//Si el login se hace correctamente mostramos un mensaje de bienvenida
					if($ctl -> login($_POST['session_login'],$_POST['session_pass']) )
					{
						$message = "Bienvenido ".$_SESSION['user'];
						$ctl -> showErrorView($message);	
					}
					//Si no mostramos nuevamente la vista de login
					else
					{
						$ctl -> showLoginView('welcome','none');							
					}					
				}
				else
				{
					$ctl -> showLoginView('welcome','none');							
				}
			}
			/*default:
			{
				//Si no se especifico ctl mostramos la pestaña de login
				require_once("Controller/StandardCtl.php");
				$ctl = new StandardCtl();
				$ctl -> showLoginView('none','none');
			}*/
		}

		if(!$is_standard)
		{
			$ctl->run();
		}
	}
	else
	{
		//Si no se especifico ctl mostramos la pestaña de login
		require_once("Controller/StandardCtl.php");
		$ctl = new StandardCtl();
		//Si no esta logeado mostramos el login, si si mostramos mensaje de bienvenida
		if( !$ctl -> isLogged() )
		{
			$message = "Bienvenido ".$_SESSION['user'];
			$ctl -> showErrorView($message);	
		}
		else
		{
			$ctl -> showLoginView('welcome','none');			
		}
	}

?>
