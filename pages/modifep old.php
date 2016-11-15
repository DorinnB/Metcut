<html>
<head>
<META NAME="Author" CONTENT="Denis Blomme - DB77">
<script language="javascript">
function choisir(truc)
// on affecte la valeur (.value) dans :
// window.opener : la fenêtre appelante (celle qui a fait la demande)
{ window.opener.location.reload();
// on se ferme
self.close(); }
</script>
</head>
<body>

<?php
Require("../fonctions.php");
Connectionsql();
							// ATTENTION SI MODIF DU NOM EP EN "RIEN"

if(isset($_POST['modif']) AND isset($_POST['nom_eprouvette']) AND $_POST['nom_eprouvette']!="")	{
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

	//condition pour de-assigner : ep non testé cad absente d'enregistremente essais
	if($assigne=='NULL')	{
		$req_verifie = mysql_query("SELECT id_eprouvette, n_fichier FROM enregistrementessais WHERE id_eprouvette =".$_GET['ep'].";") or die (mysql_error());
		if(mysql_num_rows($req_verifie)==1)	{
			echo 'Impossible de dé-assigner cette eprouvette.<br/> Elle a déjà été testé.';
			exit;
		}
	}


	$modifep='UPDATE metcut.eprouvettes SET
	 nom_eprouvette ="'.$_POST['nom_eprouvette'].'",
	 prefixe='.$prefixe.',	 
	 temperature='.$temperature.',
	 frequence='.$frequence.',
	 deltaepsilon='.$deltaepsilon.',
	 epsilonmax='.$epsilonmax.',
	 niveau_max='.$niveaumax.',
	 niveau_moy='.$niveaumoy.',
	 niveau_alt='.$niveaualt.',
	 niveau_min='.$niveaumin.',
	 cycle_min='.$cyclemin.',
	 assigne ='.$assigne.'

	 WHERE eprouvettes.id_eprouvette ='.$_POST['ep'];

	envoilog('eprouvettes','id_eprouvette',$_POST['ep'],$modifep);
	//mysql_query($modifep);

	?>
	<script language="JavaScript" type="text/JavaScript">
	window.opener.location.reload();
	self.close();
	</script>
	<?php
	}




$req_ep = mysql_query("SELECT eprouvettes.id_eprouvette, prefixe, nom_eprouvette, temperature, n_essai, frequence, rapport, deltaepsilon, epsilonmax, niveau_max, niveau_moy, niveau_alt, niveau_min, cycle_min, id_job, n_fichier, machine, DATE_FORMAT( date, '%d %b %Y' ) AS date, technicien, assigne
FROM eprouvettes
LEFT JOIN enregistrementessais ON eprouvettes.id_eprouvette = enregistrementessais.id_eprouvette
LEFT JOIN machines ON enregistrementessais.id_machine = machines.id_machine
LEFT JOIN techniciens ON enregistrementessais.id_operateur = techniciens.id_technicien
WHERE eprouvettes.id_eprouvette =".$_GET['ep'].";") or die (mysql_error());
$tbl_ep = mysql_fetch_assoc($req_ep);

?>


<table>
<?php
$assignecheck=($tbl_ep['assigne']==1)? 'CHECKED' : '';
echo '
<form method=POST name="modifep">
	<tr><td>Nom de l\'éprouvette : </td><td><INPUT type=text size="6" name="nom_eprouvette" value="'.$tbl_ep['nom_eprouvette'].'"></td></tr>
	<tr><td>Préfixe : </td><td><INPUT type=text size="6" name="prefixe" value="'.$tbl_ep['prefixe'].'"></td></tr>
	<tr><td>Température : </td><td><INPUT type=text size="3" name="temperature" value="'.$tbl_ep['temperature'].'"></td></tr>
	<tr><td>Fréquence : </td><td><INPUT type=text size="3" name="frequence" value="'.$tbl_ep['frequence'].'"></td></tr>
	<tr><td>&Delta; &epsilon; : </td><td><INPUT type=text size="3" name="deltaepsilon" value="'.$tbl_ep['deltaepsilon'].'"></td></tr>
	<tr><td>&epsilon; max : </td><td><INPUT type=text size="3" name="epsilonmax" value="'.$tbl_ep['epsilonmax'].'"></td></tr>
	<tr><td>&sigma; max : </td><td><INPUT type=text size="3" name="niveaumax" value="'.$tbl_ep['niveau_max'].'"></td></tr>
	<tr><td>&sigma; moy : </td><td><INPUT type=text size="3" name="niveaumoy" value="'.$tbl_ep['niveau_moy'].'"></td></tr>
	<tr><td>&sigma; alt : </td><td><INPUT type=text size="3" name="niveaualt" value="'.$tbl_ep['niveau_alt'].'"></td></tr>
	<tr><td>&sigma; min : </td><td><INPUT type=text size="3" name="niveaumin" value="'.$tbl_ep['niveau_min'].'"></td></tr>
	<tr><td>Cycle min : </td><td><INPUT type=text size="6" name="cyclemin" value="'.$tbl_ep['cycle_min'].'"></td></tr>
	<tr><td>Assigné : </td><td><INPUT type=checkbox name="assigne" value="1" '.$assignecheck.'></td></tr>

<INPUT type=hidden name="ep" value="'.$tbl_ep['id_eprouvette'].'">
<INPUT type=hidden name="modif" value="1">
<tr><td><INPUT type=submit value="Modifier"></td>
</form>
';


echo '
<form method=POST name="copieep" action="ajoutep.php">
<INPUT type=hidden name="idjob" value="'.$tbl_ep['id_job'].'">
<INPUT type=hidden name="nom_eprouvette" value="'.$tbl_ep['nom_eprouvette'].'">
<INPUT type=hidden name="prefixe" value="'.$tbl_ep['prefixe'].'">
<INPUT type=hidden name="temperature" value="'.$tbl_ep['temperature'].'">
<INPUT type=hidden name="frequence" value="'.$tbl_ep['frequence'].'">
<INPUT type=hidden name="deltaepsilon" value="'.$tbl_ep['deltaepsilon'].'">
<INPUT type=hidden  name="epsilonmax" value="'.$tbl_ep['epsilonmax'].'">
<INPUT type=hidden name="niveaumax" value="'.$tbl_ep['niveau_max'].'">
<INPUT type=hidden name="niveaumoy" value="'.$tbl_ep['niveau_moy'].'">
<INPUT type=hidden name="niveaualt" value="'.$tbl_ep['niveau_alt'].'">
<INPUT type=hidden name="niveaumin" value="'.$tbl_ep['niveau_min'].'">
<INPUT type=hidden name="cyclemin" value="'.$tbl_ep['cycle_min'].'">
<td><INPUT type=submit value="Copier (ou retester) l\'eprouvette"></td></tr>
</form>
';
?>
</table>


</body>
</html>