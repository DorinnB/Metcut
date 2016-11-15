<?php

extract($_POST);

$req_essai = mysql_query('
	SELECT n_fichier, id_type_essai, eprouvettes.id_job, n_essai, eprouvettes.id_eprouvette, id_machine, id_acquisition, id_operateur, id_controleur
	FROM enregistrementessais
	LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
	LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
	WHERE n_fichier ='.$modif.'
	;') or die (mysql_error());
while ($tbl_essai = mysql_fetch_assoc($req_essai)) {
	$ancien_n_fichier=$tbl_essai['n_fichier'];
	$ancien_n_essai=$tbl_essai['n_essai'];
	$ancien_id_eprouvette=$tbl_essai['id_eprouvette'];
	$ancien_job=$tbl_essai['id_job'];
}








$annulation_ancien_ep='UPDATE metcut.eprouvettes SET n_essai = NULL, assigne = NULL WHERE eprouvettes.id_eprouvette ='.$ancien_id_eprouvette;
	envoilog('eprouvettes','id_eprouvette',$ancien_id_eprouvette,$annulation_ancien_ep);
//mysql_query($annulation_ancien_ep);





$req_verif = mysql_query('SELECT assigne, nom_eprouvette FROM eprouvettes WHERE id_eprouvette = '.$eprouvette.';') or die (mysql_error());
if (mysql_result($req_verif,0)==1)
	echo 'Probleme lors de l\'enregistrement.<br/> Votre éprouvette est déjà enregistrée !';
else	{
	


		//Modification du n° fichier dans la BDD
	$ajoutessai='UPDATE metcut.enregistrementessais SET id_acquisition='.$acquisition.' ,id_eprouvette='.$eprouvette.' ,id_machine='.$machine.' ,date="'.$date.'" ,id_operateur='.$operateur.' ,id_controleur='.$controleur.' WHERE n_fichier='.$ancien_n_fichier;
		envoilog('enregistrementessais','n_fichier',$modif,$ajoutessai);
	//mysql_query($ajoutessai);

	

	if($ancien_job==$job)	{	//Si meme job
		$prise_n_essai='UPDATE metcut.eprouvettes SET n_essai = '.$ancien_n_essai.', assigne = 1 WHERE eprouvettes.id_eprouvette ='.$eprouvette;
		$n_essai=$ancien_n_essai;
	}
	else	{					//Si job different : on cherche nfichier+1
		$req_type = mysql_query('SELECT max(n_essai) FROM eprouvettes WHERE id_job = '.$job.';') or die (mysql_error());
		if ($req_type)	{
			$n_essai= mysql_result($req_type,0) + 1;
			$prise_n_essai='UPDATE metcut.eprouvettes SET n_essai = '.$n_essai.', assigne = 1 WHERE eprouvettes.id_eprouvette ='.$eprouvette;
		}
	}
	
	
		envoilog('eprouvettes','id_eprouvette',$eprouvette,$prise_n_essai);
	//mysql_query($prise_n_essai);	
	

	echo 'n° de fichier modifié = '.$ancien_n_fichier;
	echo '<br/>';
	echo 'n° d\'essai modifié = '.$n_essai;
}

?>