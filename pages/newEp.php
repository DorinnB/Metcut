
<?php
var_dump($_POST);

	foreach ($_POST as $key=>$value){
		
		$numeroep=explode("-",$key,3);
		
		
		if ($numeroep[0]=='job'){
			if ($numeroep[1]=='id_user')
				$id_user=$value;
			elseif ($numeroep[1]=='idjob')
				$id_tbljob=$value;
			elseif ($numeroep[1]=='id_c_type_1')
				$id_c_type_1=$value;
			elseif ($numeroep[1]=='id_c_type_2')
				$id_c_type_2=$value;
			elseif ($numeroep[1]=='c_unite')
				$c_unite=$value;
		}
		else
			$info[$numeroep[0]][$numeroep[1]]=$value;
	}

	$maReponse=array();	

	foreach ($info as $key => $value)	{	//pour chaque eprouvette

		if (isset($value['id_eprouvette']))	{	//modification d'eprouvette

			//select avant modif
			$req_avant='SELECT * FROM eprouvettes WHERE id_eprouvette = '.$value['id_eprouvette'];
			$req_av = $db->query($req_avant);	
			$tbl_av = mysqli_fetch_assoc($req_av);
			$av = mysqli_real_escape_string($db, implode(";", $tbl_av));


			$req='UPDATE eprouvettes SET id_eprouvette = '.$value['id_eprouvette'];
			foreach ($value as $k =>$v){		//ajout de tous les arguments dans l'update
				$v = mysqli_real_escape_string($db, $v);
				$v = ($v=="")?"NULL":'"'.$v.'"';			
				$req.=', '.$k.' = '.$v;
			}
			$req.=' WHERE id_eprouvette = '.$value['id_eprouvette'].';';
			//echo $req.'<br/>';			
			$req_updateEp = $db->query($req);

			if (mysqli_affected_rows($db)==0)	{		//detection si l'update a fait quelque chose
			//			echo 'PAS DE MODIF<br/><br/>';
				$maReponse[$value['id_eprouvette']] = 'no_update';
			}
			else	{									//on update l'eprouvette avec le nom de l'operateur

				$req='UPDATE eprouvettes SET c_modif = '.$id_user.', c_checked = 0
				WHERE id_eprouvette = '.$value['id_eprouvette'].';';

				//echo $req.'<br/>';

				$req_updateEp = $db->query($req);
				$req_apres='SELECT * FROM eprouvettes WHERE id_eprouvette = '.$value['id_eprouvette'];		
				// echo $req_apres;	
				$req_ap = $db->query($req_apres);
				$tbl_ap = mysqli_fetch_assoc($req_ap);
				$ap = mysqli_real_escape_string($db, implode(";", $tbl_ap));

				$reqmodif='INSERT INTO modifications (tbl, id_table, avant, instruction, apres) VALUES ("eprouvettes",'.$value['id_eprouvette'].',"'.$av.'","'.$req_updateEp.'","'.$ap.'");';
				//echo $reqmodif;
				$modif = $db->query($reqmodif);




				$maReponse[$value['id_eprouvette']] = 'update';
				//			echo 'MODIF<br/><br/>';
			}
			
		}
		else	{								//ajout d'éprouvette
			if ($value['prefixe']!="")
				$prefixe=' AND prefixe = "'.$value['prefixe'].'"';
			else
				$prefixe="";
			
			//test si le nom d'eprouvette existe deja
			$req='
				SELECT * 
				FROM eprouvettes 
				LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job 
				WHERE nom_eprouvette = "'.$value['nom_eprouvette'].'"
					'.$prefixe.'
					AND eprouvette_actif = 1 
					AND heritage is null 
					AND id_info_job = ( 
						SELECT id_info_job 
						FROM tbljobs 
						WHERE id_tbljob = '.$id_tbljob.' 
						)';
			//echo $req;				
			$res_heritage = $db->query($req);

			$heritage = mysqli_fetch_assoc($res_heritage);
			if (!empty($heritage))	{		
				echo $heritage['id_eprouvette'];
				$value['heritage'] = $heritage['id_eprouvette'];
			}


	
			
			
			
			
			
			
		
			$req='INSERT INTO eprouvettes (id_job, c_creation, eprouvette_actif';
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

	if (($tbl_tbljobs['c_unite']==$c_unite) AND ($tbl_tbljobs['c_1']==$id_c_type_1) AND ($tbl_tbljobs['c_2']==$id_c_type_2))	{
		$maReponse[$id_tbljob] = 'job-no_update';
	}
	else	{
		$maReponse[$id_tbljob] = 'job-update';
			
		$update='UPDATE tbljobs SET 
			c_unite="'.$c_unite.'",
			c_1='.$id_c_type_1.',
			c_2='.$id_c_type_2.',
			createur = '.$id_user.',
			checked = 0			
		WHERE id_tbljob='.$id_tbljob;
		
	//			echo $update.'<br/>';
		envoilog('tbljobs','id_tbljob',$id_tbljob,$update);	
	}
	

	
//echo json_encode($maReponse);



echo '<br/><br/><br/><br/><br/>CETTE PAGE EST SENCEE REVENIR SUR job.php?id_tbljob='.$id_tbljob.'<br/>
	<a href="http://192.168.0.66/Metcut/pages/job.php?id_tbljob='.$id_tbljob.'">OpenClassrooms</a>';
?>
