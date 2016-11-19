<?php
	Require("../fonctions.php");
	Connectionsql();
?>
<?php
	var_dump($_POST);


foreach ($_POST as $key=>$value){
	
	$numerojob=explode("-",$key,3);
	
	
	if ($numerojob[0]=='global'){
		$global[$numerojob[1]]=$value;
	}
	elseif ($numerojob[0]=='info'){
		$info[$numerojob[1]]=$value;
	}
	else	{
		$job[$numerojob[1]]=$value;
	}
}	
var_dump($global);
var_dump($info);
var_dump($job);
	
	//exit();

if (isset($_POST['info-checked']))	{	//type d'insertion (ajout, update, check)
		
	$req='SELECT * FROM techniciens where id_technicien="'.$_POST['info-technicien'].'" AND technicien_actif = 1 ORDER BY technicien;';
	$req_techniciens = $db->query($req);
	$w_techniciens = mysqli_fetch_assoc($req_techniciens);

	$req="";
	if (($_POST['info-technicien']==$w_techniciens['id_technicien']) AND ($_POST['info-mdp']==$w_techniciens['mdp']))	{	//Test du mot de passe
		
		
		if ($_POST['info-checked']==0)	{			//creation de job
	
			
			
			unset($job['id_info_job']); //suppression de la clé id_tbljob de la liste des inserts
			$req_global='INSERT INTO info_jobs (info_job_actif';
	
			$identifiant="";
			$valeur="";
			foreach ($global as $k =>$v){
				$v = ($v=="")?"NULL":'"'.$v.'"';
				$identifiant.= ', '.$k;
				$valeur.=', '.$v;
			}
			$req_global.=$identifiant.') VALUES (1'.$valeur.');';

			echo $req_global.'<br/><br/>';	

			$query_global = $db->query($req_global);

			$new_id_info_job = $db->insert_id;


			unset($job['id_tbljob']); //suppression de la clé id_tbljob de la liste des inserts
			
			$req='INSERT INTO tbljobs (id_info_job, tbljob_actif, createur';
			$identifiant="";
			$valeur="";
			foreach ($job as $k =>$v){
				$v = ($v=="")?"NULL":'"'.$v.'"';
				$identifiant.= ', '.$k;
				$valeur.=', '.$v;
			}
			$req.=$identifiant.') VALUES ('.$new_id_info_job.', 1, '.$info['technicien'].$valeur.');';			
			
			
			$db->query($req);
			$numero_job = $db->insert_id;
			
		}	
		elseif ($_POST['info-checked']==1)	{		//check
			$req='UPDATE tbljobs SET checked = '.$info['technicien'].' WHERE id_tbljob='.$job['id_tbljob'];
			$db->query($req);
			$numero_job = $job['id_tbljob'];
		}
		elseif ($_POST['info-checked']==2)	{		//modif

			$req='UPDATE tbljobs SET createur = '.$info['technicien'].', checked = 0';

			foreach ($job as $k =>$v){
				//ajout de tous les arguments dans l'update
				$v = ($v=="")?"NULL":'"'.$v.'"';
				$req.=', '.$k.' = '.$v;
			}
			$req.=' WHERE id_tbljob = '.$job['id_tbljob'].';';

			envoilog('tbljobs','id_tbljob',$job['id_tbljob'],$req);
			$numero_job = $job['id_tbljob'];
				//gestion global a faire
				
			$req_avant='SELECT * FROM info_jobs WHERE id_info_job = '.$global['id_info_job'];
			//echo $req_avant;
			$req_av = $db->query($req_avant);	
			$tbl_av = mysqli_fetch_array($req_av);
			$av = mysqli_real_escape_string($db, implode(";", $tbl_av));

			$req_global='UPDATE info_jobs SET id_info_job = '.$global['id_info_job'];
			foreach ($global as $k =>$v)	{		//ajout de tous les arguments dans l'update
				$v = ($v=="")?"NULL":'"'.mysqli_real_escape_string($db, $v).'"';
				$req_global.=', '.$k.' = '.$v;
			}
			$req_global.=' WHERE id_info_job = '.$global['id_info_job'].';';
		echo $req_global;
			$instruction = $req_global;		
			$query_global = $db->query($req_global);
			
			if (mysqli_affected_rows($db)==0)	{		//detection si l'update a fait quelque chose
				echo '<br/>PAS DE MODIF<br/><br/>';
			}
			else	{
				echo '<br/>MODIF<br/><br/>';
				$req_apres='SELECT * FROM info_jobs WHERE id_info_job = '.$global['id_info_job'];
				echo $req_apres;	
				$req_ap = $db->query($req_apres);
				$tbl_ap = mysqli_fetch_array($req_ap);
				$ap = mysqli_real_escape_string($db, implode(";", $tbl_ap));	

				$reqmodif='INSERT INTO modifications (tbl, id_table, avant, instruction, apres) VALUES ("info_jobs",'.$global['id_info_job'].',"'.$av.'","'.$instruction.'","'.$ap.'");';
				//echo $reqmodif;
				$modif = $db->query($reqmodif);
			}
	
	
		}
		elseif ($_POST['info-checked']==3)	{		//ajout split
		
			unset($job['id_tbljob']); //suppression de la clé id_tbljob de la liste des inserts
			
			$req='INSERT INTO tbljobs (id_info_job, tbljob_actif, createur';
			$identifiant="";
			$valeur="";
			foreach ($job as $k =>$v){
				$v = ($v=="")?"NULL":'"'.$v.'"';
				$identifiant.= ', '.$k;
				$valeur.=', '.$v;
			}
			$req.=$identifiant.') VALUES ('.$global['id_info_job'].', 1, '.$info['technicien'].$valeur.');';

			echo $req.'<br/><br/>';		
			$db->query($req);
			$numero_job = $db->insert_id;
			
				//gestion global a faire
				
			$req_avant='SELECT * FROM info_jobs WHERE id_info_job = '.$global['id_info_job'];
			//echo $req_avant;
			$req_av = $db->query($req_avant);	
			$tbl_av = mysqli_fetch_array($req_av);
			$av = mysqli_real_escape_string($db, implode(";", $tbl_av));

			$req_global='UPDATE info_jobs SET id_info_job = '.$global['id_info_job'];
			foreach ($global as $k =>$v)	{		//ajout de tous les arguments dans l'update
				$v = ($v=="")?"NULL":'"'.mysqli_real_escape_string($db, $v).'"';
				$req_global.=', '.$k.' = '.$v;
			}
			$req_global.=' WHERE id_info_job = '.$global['id_info_job'].';';
		echo $req_global;
			$instruction = $req_global;		
			$query_global = $db->query($req_global);
			
			if (mysqli_affected_rows($db)==0)	{		//detection si l'update a fait quelque chose
				echo '<br/>PAS DE MODIF<br/><br/>';
			}
			else	{
				echo '<br/>MODIF<br/><br/>';
				$req_apres='SELECT * FROM info_jobs WHERE id_info_job = '.$global['id_info_job'];
				echo $req_apres;	
				$req_ap = $db->query($req_apres);
				$tbl_ap = mysqli_fetch_array($req_ap);
				$ap = mysqli_real_escape_string($db, implode(";", $tbl_ap));	

				$reqmodif='INSERT INTO modifications (tbl, id_table, avant, instruction, apres) VALUES ("info_jobs",'.$global['id_info_job'].',"'.$av.'","'.$instruction.'","'.$ap.'");';
				//echo $reqmodif;
				$modif = $db->query($reqmodif);
			}			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
		else	{
			echo"HEY OH !! pas de $checked ??";
			exit();
		}
		
		
		
		
		
		
		
		
		
		echo $req;
		

		

		
		
		

//echo '<script>window.location.replace("job.php?id_tbljob='.$numero_job.'");</script>';
	echo '<br/><br/><br/><br/><br/>CETTE PAGE EST SENCEE REVENIR SUR job.php?id_tbljob='.$numero_job.'<br/>
	<a href="http://192.168.0.66/Metcut/pages/job.php?id_tbljob='.$numero_job.'">OpenClassrooms</a>';
	}

	else
		echo "ERREUR DE MOT DE PASSE. Merci de recommencer...dsl";

}
else	{				//erreur car pas de $checked
	echo"HEY OH !! pas de $checked ??";	
}
	
	
?>
