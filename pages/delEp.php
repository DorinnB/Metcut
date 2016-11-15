<?php
	Require("../fonctions.php");
	Connectionsql();
	

	if(isset($_POST['id_ep']))	{

		$req='UPDATE consigne_eprouvettes SET consigne_eprouvette_actif = 0 WHERE id_consigne_eprouvette = '.$_POST['id_ep'];

		$req_consigne = $db->query($req);

		$maReponse = array('result' => 'ok', 'req'=> $req);

		echo json_encode($maReponse);


	}
	

?>