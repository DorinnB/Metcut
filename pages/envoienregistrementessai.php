<?php

extract($_POST);

$req_verif = mysql_query('SELECT assigne, nom_eprouvette FROM eprouvettes WHERE id_eprouvette = '.$eprouvette.';') or die (mysql_error());
if (mysql_result($req_verif,0)==1)
	echo 'Probleme lors de l\'enregistrement.<br/> Votre éprouvette est déjà enregistrée !';
else	{
	


		//Enregistrement du n° fichier dans la BDD
	$ajoutessai='INSERT INTO enregistrementessais (id_acquisition ,id_eprouvette ,id_poste ,date ,id_operateur ,id_controleur)
	VALUES ('.$acquisition.', '.$eprouvette.', '.$poste.', "'.$date.'", '.$operateur.', '.$controleur.')';
	mysql_query($ajoutessai);
	$n_fichier = mysql_insert_id();



	//on recupere le n° d'essai et on l'update dans l'eprouvette

	$req_type = mysql_query('SELECT max(n_essai) FROM eprouvettes WHERE id_job = '.$_POST['job'].';') or die (mysql_error());
	if ($req_type)	{
		$n_essai= mysql_result($req_type,0) + 1;
		$prise_n_essai='UPDATE eprouvettes SET n_essai = '.$n_essai.', assigne = 1 WHERE eprouvettes.id_eprouvette ='.$eprouvette;
		mysql_query($prise_n_essai);
	}



	echo '<div id="afficher">';
	echo 'n° d\'essai : '.$n_essai;
	echo '<br/>';
	echo 'n° de fichier : '.$n_fichier;
	echo '</div>';
	echo '<form method="post" name="creationfeuilleessai" action="index.php?page=creationfeuilleessai">
			<input type="hidden" name="n_fichier" value='.$n_fichier.'>
			<input type="submit" value="Impression de la feuille d\'essai">
		</form>';

}

?>