<?php
	Require("../fonctions.php");
	Connectionsql();
?>
<?php


foreach ($_POST as $key=>$value){
	
	$numeroep=explode("-",$key,3);
	
	
	if ($numeroep[0]=='job'){
		if ($numeroep[1]=='id_user')
			$id_user=$value;
		elseif ($numeroep[1]=='idjob')
			$id_tbljob=$value;
		elseif ($numeroep[1]=='id_consigne_type_1')
			$id_consigne_type_1=$value;
		elseif ($numeroep[1]=='id_consigne_type_2')
			$id_consigne_type_2=$value;
		elseif ($numeroep[1]=='consigne_unite')
			$consigne_unite=$value;
	}
	else
		$info[$numeroep[0]][$numeroep[1]]=$value;

	
	

}

$maReponse=array();	
foreach ($info as $key => $value)	{	//pour chaque eprouvette


	if (isset($value['id_consigne_eprouvette']))	{	//modification d'eprouvette
	
		$req='UPDATE consigne_eprouvettes SET id_consigne_eprouvette = '.$value['id_consigne_eprouvette'];

		foreach ($value as $k =>$v){		//ajout de tous les arguments dans l'update
			$v = ($v=="")?"NULL":'"'.$v.'"';
			$req.=', '.$k.' = '.$v;
		}
		$req.=' WHERE id_consigne_eprouvette = '.$value['id_consigne_eprouvette'].';';

		echo $req.'<br/>';

	
		$req_updateEp = $db->query($req);
			
		if (mysqli_affected_rows($db)==0)	{		//detection si l'update a fait quelque chose
		//	echo 'PAS DE MODIF<br/><br/>';
			$maReponse[$value['id_consigne_eprouvette']] = 'no_update';
		}
		else	{									//on update l'eprouvette avec le nom de l'operateur
			$req='UPDATE consigne_eprouvettes SET consigne_eprouvette_modif = '.$id_user.', consigne_eprouvette_checked = 0';

			$req.=' WHERE id_consigne_eprouvette = '.$value['id_consigne_eprouvette'].';';

			//echo $req.'<br/>';

		
			$req_updateEp = $db->query($req);
			
			$maReponse[$value['id_consigne_eprouvette']] = 'update';
			//echo 'MODIF<br/><br/>';
		}
		
	}
	elseif (isset($value['nom_eprouvette']))	{	//ajout d'éprouvette
		$req='INSERT INTO consigne_eprouvettes (id_job, consigne_eprouvette_creation, consigne_eprouvette_actif';
		$identifiant="";
		$valeur="";
		foreach ($value as $k =>$v){
			$v = ($v=="")?"NULL":'"'.$v.'"';
			$identifiant.= ', '.$k;
			$valeur.=', '.$v;
		}
		$req.=$identifiant.') VALUES ('.$id_tbljob.', '.$id_user.', 1'.$valeur.');';

		//echo $req.'<br/><br/>';		
		
		$req_insertEp = $db->query($req);
			
		$maReponse[mysqli_insert_id($db)] = $id_user;
	}
	
}

	
	
	
//pour le job

	$req='SELECT * FROM tbljobs where id_tbljob='.$id_tbljob;
	
		$req_tbljobs = $db->query($req);
		$tbl_tbljobs = mysqli_fetch_assoc($req_tbljobs);
		
		if (($tbl_tbljobs['consigne_unite']==$consigne_unite) AND ($tbl_tbljobs['consigne_1']==$id_consigne_type_1) AND ($tbl_tbljobs['consigne_2']==$id_consigne_type_2))	{
			$maReponse[$id_tbljob] = 'job-no_update';
		}
		else	{
			$maReponse[$id_tbljob] = 'job-update';
				
			$update='UPDATE tbljobs SET 
				consigne_unite="'.$consigne_unite.'",
				consigne_1='.$id_consigne_type_1.',
				consigne_2='.$id_consigne_type_2.'
				createur = '.$id_user.',
				checked = 0			
			WHERE id_tbljob='.$id_tbljob;
			
			echo $update.'<br/>';
			envoilog($tbljobs,'id_tbljob',$id_tbljob,$update);	
		}
			
			
			

			











	
	
		echo json_encode($maReponse);
?>
