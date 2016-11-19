<?php
	Require("../fonctions.php");
	Connectionsql();
?>
<?php
//var_dump($_POST);
extract($_POST);
?>

<?php	//Verifi sir l'essai n'a pas deja été enregistré
$req='SELECT * FROM enregistrementessais WHERE id_eprouvette = '.$id_eprouvette;
$req_verif = $db->query($req);

if ( $req_verif->num_rows > 0 )	{
	echo 'Probleme lors de l\'enregistrement.<br/> Votre éprouvette est déjà enregistrée !';
	exit();
}
?>



<?php
//Enregistrement du n° fichier dans la BDD
	$ajoutessai='INSERT INTO enregistrementessais (id_acquisition ,id_eprouvette ,id_poste ,date ,id_operateur ,id_controleur)
	VALUES (7, '.$id_eprouvette.', '.$poste.', "'.date('Y-m-d H:i:s').'", '.$operateur.', '.$checkeur.')';
	
	$db->query($ajoutessai);
	$n_fichier = $db->insert_id;



	//on recupere le n° d'essai et on l'update dans l'eprouvette
	
	$req='SELECT MAX(n_essai) as max
			FROM eprouvettes 
			WHERE id_job=(
				select id_job 
				from eprouvettes
				where id_eprouvette= '.$id_eprouvette.'
				)
				AND id_eprouvette != '.$id_eprouvette.'
			';

	$req_max=$db->query($req);
	$max_n_essai = mysqli_fetch_assoc($req_max);

	if ($max_n_essai['max']>0)
		$n_essai=$max_n_essai['max']+1;
	else
		$n_essai=1;
			
	
	$req='UPDATE eprouvettes 
		SET n_essai = '.$n_essai.'
		WHERE id_eprouvette = '.$id_eprouvette;	

	$db->query($req);


	echo '<div id="afficher">';
	echo 'n° d\'essai : '.$n_essai;
	echo '<br/>';
	echo 'n° de fichier : '.$n_fichier;
	echo '</div>';
	
	?>