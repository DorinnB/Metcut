<html>
<head>
<META NAME="Author" CONTENT="Denis Blomme - DB77">
<script language="javascript">
function fermer()
// window.opener : la fenêtre appelante (celle qui a fait la demande)
{ window.opener.location.reload();
// on se ferme
self.close(); }
</script>
<link type="text/css" rel="stylesheet" href="../css/style.css" />
</head>
<body>







<?php
Require("../fonctions.php");
Connectionsql();

// Verif des variables
if(isset($_POST['ajout']) AND $_POST['ajout']==1)	{

	$valide=1;
	
	
	
	$valide=(is_numeric($_POST['temperature']) OR $_POST['temperature']=="") ? $valide : 0;
	$valide=(isset($_POST['ajout'])) ? $valide : 0;
	$valide=($_POST['nom_eprouvette']!="") ? $valide : 0;

	$assigne=(isset($_POST['assigne']) AND $_POST['assigne']==1)? '1' : 'NULL';
	$prefixe=(isset($_POST['prefixe']) AND $_POST['prefixe']!="")? '"'.$_POST['prefixe'].'"' : 'NULL';
	$temperature=(isset($_POST['temperature']) AND $_POST['temperature']!=0)? '"'.$_POST['temperature'].'"' : 'NULL';
	$frequence=(isset($_POST['frequence']) AND $_POST['frequence']!="")? '"'.$_POST['frequence'].'"' : 'NULL';
	$deltaepsilon=(isset($_POST['deltaepsilon']) AND $_POST['deltaepsilon']!="")? '"'.$_POST['deltaepsilon'].'"' : 'NULL';
	$epsilonmax=(isset($_POST['epsilonmax']) AND $_POST['epsilonmax']!="")? '"'.$_POST['epsilonmax'].'"' : 'NULL';
	$niveaumax=(isset($_POST['niveaumax']) AND $_POST['niveaumax']!="")? '"'.$_POST['niveaumax'].'"' : 'NULL';
	$niveaumoy=(isset($_POST['niveaumoy']) AND $_POST['niveaumoy']!="")? '"'.$_POST['niveaumoy'].'"' : 'NULL';
	$niveaualt=(isset($_POST['niveaualt']) AND $_POST['niveaualt']!="")? '"'.$_POST['niveaualt'].'"' : 'NULL';
	$niveaumin=(isset($_POST['niveaumin']) AND $_POST['niveaumin']!="")? '"'.$_POST['niveaumin'].'"' : 'NULL';
	$cyclemin=(isset($_POST['cyclemin']) AND $_POST['cyclemin']!="")? '"'.$_POST['cyclemin'].'"' : 'NULL';
	
//Vérification si le nom d'eprouvette existe
	$sql_ep='SELECT nom_eprouvette FROM eprouvettes WHERE eprouvette_actif=1 AND prefixe '.(($prefixe=="NULL")?'IS NULL': '='.$prefixe).' AND nom_eprouvette="'.$_POST['nom_eprouvette'].'" AND id_job = '.$_POST['idjob'];
	$req_ep = mysql_query($sql_ep) or die (mysql_error());
	if ($req_ep) {
		if(mysql_num_rows($req_ep)>=1)	{
			echo '<div id="ErreurFormulaire">Nom deja utilisé</div>';
			$valide=0;
		}

	}
	else
		exit('probleme');




	
	if($valide==1)	{
		$ajoutep='INSERT INTO metcut.eprouvettes (nom_eprouvette, prefixe, id_job, temperature, frequence, deltaepsilon, epsilonmax, niveau_max, niveau_moy, niveau_alt, niveau_min, cycle_min, assigne) VALUES ("'.$_POST['nom_eprouvette'].'", '.$prefixe.', '.$_POST['idjob'].', '.$temperature.', '.$frequence.', '.$deltaepsilon.', '.$epsilonmax.', '.$niveaumax.', '.$niveaumoy.', '.$niveaualt.', '.$niveaumin.', '.$cyclemin.', '.$assigne.')';
		mysql_query($ajoutep);
		echo '<script>fermer()</script>';
	}
}
?>




<form method=POST name="ajoutep">
<table>
	<tr><td>Nom de l'éprouvette : </td><td><INPUT type=text size="6" name="nom_eprouvette" value="<?php echo (isset($_POST['nom_eprouvette'])) ? $_POST['nom_eprouvette'] : "" ;?>"></td></tr>
	<tr><td>Préfixe : </td><td><INPUT type=text size="6" name="prefixe" value="<?php echo (isset($_POST['prefixe'])) ? $_POST['prefixe'] : "" ;?>"></td></tr>
	<tr><td>Température : </td><td><INPUT type=text size="3" name="temperature" value="<?php echo (isset($_POST['temperature'])) ? $_POST['temperature'] : "" ;?>"></td></tr>
	<tr><td>Fréquence : </td><td><INPUT type=text size="3" name="frequence" value="<?php echo (isset($_POST['frequence'])) ? $_POST['frequence'] : "" ;?>"></td></tr>
	<tr><td>&Delta; &epsilon; : </td><td><INPUT type=text size="3" name="deltaepsilon" value="<?php echo (isset($_POST['deltaepsilon'])) ? $_POST['deltaepsilon'] : "" ;?>"></td></tr>
	<tr><td>&epsilon; max : </td><td><INPUT type=text size="3" name="epsilonmax" value="<?php echo (isset($_POST['epsilonmax'])) ? $_POST['epsilonmax'] : "" ;?>"></td></tr>
	<tr><td>&sigma; max : </td><td><INPUT type=text size="3" name="niveaumax" value="<?php echo (isset($_POST['niveaumax'])) ? $_POST['niveaumax'] : "" ;?>"></td></tr>
	<tr><td>&sigma; moy : </td><td><INPUT type=text size="3" name="niveaumoy" value="<?php echo (isset($_POST['niveaumoy'])) ? $_POST['niveaumoy'] : "" ;?>"></td></tr>
	<tr><td>&sigma; alt : </td><td><INPUT type=text size="3" name="niveaualt" value="<?php echo (isset($_POST['niveaualt'])) ? $_POST['niveaualt'] : "" ;?>"></td></tr>
	<tr><td>&sigma; min : </td><td><INPUT type=text size="3" name="niveaumin" value="<?php echo (isset($_POST['niveaumin'])) ? $_POST['niveaumin'] : "" ;?>"></td></tr>
	<tr><td>Cycle min : </td><td><INPUT type=text size="6" name="cyclemin" value="<?php echo (isset($_POST['cyclemin'])) ? $_POST['cyclemin'] : "" ;?>"></td></tr>
	<tr><td>Assigné : </td><td><INPUT type=checkbox name="assigne" value="1"></td></tr>
</table>
<INPUT type=hidden name="ajout" value="1">
<INPUT type=hidden name="idjob" value="<?php echo (isset($_POST['idjob'])) ? $_POST['idjob'] : $_GET['job'] ;?>">
<INPUT type=submit value="Ajouter">
</form>


</body>
</html>