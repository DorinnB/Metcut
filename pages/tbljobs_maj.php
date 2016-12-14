<?php
var_dump($_POST);
var_dump($_FILES);



foreach( $_POST as $cle=>$value )
{
	if ($cle<>"table_id_length") {

		$decoupe=explode("-",$cle);
		$id=$decoupe[0];
		$champ=$decoupe[1];
		$val=$value;
					
		if ($id>0)	{
			$job[$id][$champ]=$val;
			$id_job=$id;
		}
		else
			$ep[$decoupe[0]][$decoupe[1]][$decoupe[2]]=$val;
	}
}




foreach ($job as $cle=>$value)	{	//un peu inutile car en théorie uniquement un seul id de job n'est present
	if ($cle==0)	{				//id_job=0 donc c'est un ajout de job
//INSERT INTO Animal (nom, commentaires, date_naissance, espece) 
//    VALUES ('Choupi', 'Né sans oreille gauche', '2010-10-03 16:44:00', 'chat');		
		$req='INSERT INTO tbljobs (id_tbljob';
		foreach ($value as $key=>$val)
		{
			$req=$req.", ".$key;
		}
		$req= $req.") VALUES (NULL";
		foreach ($value as $key=>$val)
		{
			$val = ($val=="") ? 'NULL' : "'".$val."'";
			$req=$req.", ".$val;
		}
		$req=$req.");";
		
		if($db->query($req) === TRUE)
		{
			$cle=$db->insert_id;
			echo "<br/>Insert successfull";
		} else {
			echo "<br/>Error inserting record: " . $conn->error;
		}
		
		
	}
	else	{						//modification des données d'un id_tbljob existant
		
	
		$reqNoCheck='UPDATE tbljobs SET id_tbljob='.$cle;
		$reqCheck='UPDATE tbljobs SET id_tbljob='.$cle;
		$reqNoCheck_add = '';
		$reqCheck_add = '';
		$toBeCheck = '';
		
		foreach ($value as $key=>$val)
		{
		$titre = explode("|",$key)[1];
		$toCheck = explode("|",$key)[0];

			if ($toCheck=="noCheck")	{
				$val = ($val=="") ? 'null' : "'".mysqli_real_escape_string($db, $val)."'";
				$reqNoCheck_add=$reqNoCheck_add.", ".$titre."=".$val;
			}
			else {
				$toBeCheck = ",checked = 0";
				$val = ($val=="") ? 'null' : "'".mysqli_real_escape_string($db, $val)."'";
				$reqCheck_add=$reqCheck_add.", ".$titre."=".$val;				
			}
		}


		//Instructions particulieres
		$IPbdd="";
		if (isset($_FILES[$cle.'-check|instructions_particulieres']['tmp_name']))	{
			$fichierQ = $_FILES[$cle.'-check|instructions_particulieres']['tmp_name'];			
			If ($fichierQ!=""){
				$dest ="c:/Quality/IP/".$_FILES[$cle.'-check|instructions_particulieres']['name'];
				move_uploaded_file($fichierQ, $dest);
				echo '<br/>'.$dest.'<br/>';
				
				$toBeCheck = ",checked = 0";
				$IPbdd=	', instructions_particulieres = "'.mysqli_real_escape_string($db, $_FILES[$cle.'-check|instructions_particulieres']['name']).'"';
			}
		}


		
		$reqNoCheck = $reqNoCheck.$reqNoCheck_add.' WHERE id_tbljob='.$cle;
		$reqCheck = $reqCheck.' '.$toBeCheck.' '.$reqCheck_add.$IPbdd.' WHERE id_tbljob='.$cle;

		
	echo '<br/>noCheck----'.$reqNoCheck;
	echo '<br/>check----'.$reqCheck;
		if ($toBeCheck!='')
			envoilog('tbljobs','id_tbljob',$cle,$reqCheck);		

		envoilog('tbljobs','id_tbljob',$cle,$reqNoCheck);			
//exit();


		

	}
}
	
	
if(!empty($ep)) { 
	foreach ($ep as $cle=>$value)	//pour chaque eprouvette de soustraitance
	{	
		if ($cle=="ep")	{		//update ou ajout eprouvette soustraitance
			foreach ($value as $key=>$val)	//on recupere l'id de l'ep / le champ / la valeur
			{

				//si checkbox est coché
					$req_exist='
					SELECT * 
					FROM `eprouvettes` 
					WHERE id_job='.$id_job.' 
						AND eprouvette_actif = 1
						AND (heritage='.$val['id_eprouvette'].' 
							OR id_eprouvette='.$val['id_eprouvette'].'
							)';
//echo $req_exist;
					$result = $db->query($req_exist);
					$row_cnt = $result->num_rows;
					
					if ($row_cnt>0)	{
						$data = mysqli_fetch_assoc($result);
						$checkbox=(isset($val['checkbox']))?"1":"0";
						$req= 'UPDATE eprouvettes SET d_commentaire="'.mysqli_real_escape_string($db, $val['d_commentaire']).'", eprouvette_actif='.$checkbox.' WHERE id_eprouvette='.$data['id_eprouvette'];
				echo '<br/>'.$req;
						$db->query($req);							
						}
					else	{
						if (isset($val['checkbox']))	{
							
							//il faut copier l'ep original et lui ajouter heritage ?
							$req='INSERT INTO eprouvettes (heritage, prefixe, nom_eprouvette, id_job, d_commentaire, eprouvette_actif) VALUES ('.$val['id_eprouvette'].', "'.mysqli_real_escape_string($db, $val['prefixe']).'", "'.mysqli_real_escape_string($db, $val['nom_eprouvette']).'", '.$id_job.',"'.mysqli_real_escape_string($db, $val['d_commentaire']).'",1)';
				echo '<br/>'.$req;
						$db->query($req);
						}
					}
					


	
			}
		}
		elseif ($cle=="newep")	{	//ajout eprouvette sous traitance	
			foreach ($value as $key=>$val)	//on recupere l'id de l'ep / le champ / la valeur
			{
				if (!empty($val['checkbox']))	{
					$req='INSERT INTO eprouvettes (nom_eprouvette, id_job, d_commentaire, eprouvette_actif) VALUES ("'.mysqli_real_escape_string($db, $val['nom_eprouvette']).'",'.$key.',"'.mysqli_real_escape_string($db, $val['d_commentaire']).'",1)';
			echo '<br/>'.$req;
		//echo '<br/>NE DEVRAIT PLUS APPARAITRE - '.$req;
					$db->query($req);
				}
			}
		}
		else						//cas non prevu
		{
			echo ($cle);
			exit();
		}
	}
}  
?>
<!--<script>window.location.replace("pages/job.php?id_tbljob=<?php echo $cle;	?>");</script>-->