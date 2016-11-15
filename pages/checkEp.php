<?php
	Require("../fonctions.php");
	Connectionsql();


	if(isset($_POST['id_ep']))	{

		if ($_POST['type']=="consigne")	{
			$req='UPDATE eprouvettes SET c_checked = '.$_POST['tech'].' WHERE id_eprouvette = '.$_POST['id_ep'];

			$req_consigne = $db->query($req);

			$maReponse = array('result' => 'ok', 'req'=> $req);

			echo json_encode($maReponse);
		}
		elseif ($_POST['type']=="data")	{
			$req='UPDATE eprouvettes SET dchecked  = '.$_POST['tech'].' WHERE id_eprouvette = '.$_POST['id_ep'];

			$req_consigne = $db->query($req);

			$maReponse = array('result' => 'ok', 'req'=> $req);

			echo json_encode($maReponse);
		}
	}
	

?>