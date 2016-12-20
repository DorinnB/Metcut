<?php

extract($_POST);
$req_essai = $db->query('
	SELECT n_fichier, id_type_essai, eprouvettes.id_job, n_essai, eprouvettes.id_eprouvette, id_poste, id_acquisition, id_operateur, id_controleur
	FROM enregistrementessais
	LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
	LEFT JOIN tbljobs ON eprouvettes.id_job = tbljobs.id_tbljob
	WHERE n_fichier ='.$modif.'
	;') or die (mysql_error());
while ($tbl_essai = mysqli_fetch_assoc($req_essai)) {
	$ancien_n_fichier=$tbl_essai['n_fichier'];
	$ancien_n_essai=$tbl_essai['n_essai'];
	$ancien_id_eprouvette=$tbl_essai['id_eprouvette'];
	$ancien_job=$tbl_essai['id_job'];
}








$annulation_ancien_ep='UPDATE eprouvettes SET n_essai = NULL, assigne = NULL WHERE eprouvettes.id_eprouvette ='.$ancien_id_eprouvette;
	envoilog('eprouvettes','id_eprouvette',$ancien_id_eprouvette,$annulation_ancien_ep);
//$db->query($annulation_ancien_ep);




$req='SELECT assigne, nom_eprouvette FROM eprouvettes WHERE id_eprouvette = '.$eprouvette.';';
$req_verif = $db->query($req) or die (mysql_error());
$verif = mysqli_fetch_assoc($req_verif);
if ( $verif['assigne']==1 )	{
	echo 'Probleme lors de l\'enregistrement.<br/> Votre éprouvette est déjà enregistrée !';
}
else	{
	


		//Modification du n° fichier dans la BDD
	$ajoutessai='UPDATE enregistrementessais SET id_acquisition='.$acquisition.' ,id_eprouvette='.$eprouvette.' ,id_poste='.$poste.' ,date="'.$date.'" ,id_operateur='.$operateur.' ,id_controleur='.$controleur.' WHERE n_fichier='.$ancien_n_fichier;
		envoilog('enregistrementessais','n_fichier',$modif,$ajoutessai);
	//$db->query($ajoutessai);

	

	if($ancien_job==$job)	{	//Si meme job
		$prise_n_essai='UPDATE eprouvettes SET n_essai = '.$ancien_n_essai.', assigne = 1 WHERE eprouvettes.id_eprouvette ='.$eprouvette;
		$n_essai=$ancien_n_essai;
	}
	else	{					//Si job different : on cherche nfichier+1
	$req='SELECT max(n_essai) as n_essai FROM eprouvettes WHERE id_job = '.$job.';';
		$req_type = $db->query($req) or die (mysql_error());
		if ($req_type)	{
			$max = mysqli_fetch_assoc($req_type);
			$n_essai= $max['n_essai'];	
			$n_essai = $n_essai + 1;
			$prise_n_essai='UPDATE eprouvettes SET n_essai = '.$n_essai.', assigne = 1 WHERE eprouvettes.id_eprouvette ='.$eprouvette;	
		}
	}
	
	
		envoilog('eprouvettes','id_eprouvette',$eprouvette,$prise_n_essai);
	//$db->query($prise_n_essai);	
	

	echo '<div id="afficher">';
	echo 'n° de fichier modifié = '.$ancien_n_fichier;
	echo '<br/>';
	echo 'n° d\'essai modifié = '.$n_essai;
	echo '</div>';
	echo '<a class="pascache" href="creationfeuilleessai.php?n_fichier='.$ancien_n_fichier.'">Feuille d\'essai</a>';

}

?>