<?php

	echo '/***** VEHÍCULO MODIFICADO *****/',
		 '<br/>       ID            : ', $this -> model -> getIdVehicle(),
		 '<br/>       Location      : ', $this -> model -> getIdLocation(),
		 '<br/>       Vehicle Model : ', $this -> model -> getIdVehicleModel(),
		 '<br/>       VIN           : ', $this -> model -> getVin(),
		 '<br/>       Color         : ', $this -> model -> getColor(), '<br/><br/><br/>';

?>