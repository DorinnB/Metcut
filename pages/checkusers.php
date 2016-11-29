<?php
	Require("../fonctions.php");
	Connectionsql();
	//Select techniciens

	if(isset($_POST['username']))	{

		$req='SELECT * FROM techniciens WHERE id_technicien='.$_POST['username'];

		$req_technicien = $db->query($req);
	
		if (mysqli_num_rows($req_technicien)==0)	{

			
			if (isset($_COOKIE['id_user']))	{
				$req='SELECT * FROM techniciens WHERE id_technicien='.$_COOKIE['id_user'];
				$req_technicien = $db->query($req);
				$tbl_technicien = mysqli_fetch_assoc($req_technicien);
				
				if ($_COOKIE['password']==$tbl_technicien['mdp'])	{
					$maReponse = array('result' => 'correct', 'id_technicien' => $tbl_technicien['id_technicien'], 'technicien' => $tbl_technicien['technicien']);
					echo json_encode($maReponse);
				}
				else	{
					$maReponse = array('result' => 'Password incorrect');
					echo json_encode($maReponse);
				}
			}
			else{
				$maReponse = array('result' => 'Utilisateur inconnu');
				echo json_encode($maReponse);	
			}			
		}
		else	{
			$tbl_technicien = mysqli_fetch_assoc($req_technicien);
			if ($_POST['password']==$tbl_technicien['mdp'])	{
				//	echo $tbl_technicien['technicien'];
				$maReponse = array('result' => 'correct', 'id_technicien' => $tbl_technicien['id_technicien'], 'technicien' => $tbl_technicien['technicien']);
				echo json_encode($maReponse);
			}
			else	{
				$maReponse = array('result' => 'Password incorrect');
				echo json_encode($maReponse);
			}
		}
	}
?>