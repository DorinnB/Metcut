<?php
	Require("../fonctions.php");
	Connectionsql();
	

	if(isset($_POST['id_ep']))	{

		$req='UPDATE eprouvettes SET eprouvette_actif = 0 WHERE id_eprouvette = '.$_POST['id_ep'];

		$req_consigne = $db->query($req);

		$maReponse = array('result' => 'ok', 'req'=> $req);
//echo $req;
		echo json_encode($maReponse);


	}
	

?>