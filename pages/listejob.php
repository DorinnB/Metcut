<script language="javascript">
function popup(fic)
// on ouvre dans une fenêtre le fichier passé en paramètre.
// cette ouverture peut être améliorée en passant d'autres
// paramètres que la taille et la position de la fenêtre.
{ window.open(fic,'Choisir','width=400,height=350,top=50,left=50'); }
</script>

<?php
if(isset($_POST['cloture']))	{
$cloture='UPDATE jobs SET termine =1 WHERE jobs.id_job ='.$_POST['id_job'];
$db->query($cloture);
}
if(isset($_POST['decloture']))	{
$decloture='UPDATE jobs SET termine = NULL WHERE jobs.id_job ='.$_POST['id_job'];
$db->query($decloture);
}

?>



<div id="choixjob">
<form method="post" name="choixjob">
	<select name="job" onchange="document.choixjob.submit()">		
	<?php
	$filtre=(isset($_POST['filtre']) AND $_POST['filtre']==1)? " AND termine=1" : " AND termine is null";
		$req_job = $db->query('
		SELECT id_job, n_client, n_job, indice
		FROM jobs WHERE job_actif =1'.$filtre.'
		ORDER BY n_job;') or die (mysql_error());
	  if ($req_job) {
		echo '<option value="-">-</option>';
		while ($tbl_job = mysqli_fetch_array($req_job)) {
			if (isset($tbl_job['indice']))
				$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'].'-'.$tbl_job['indice'];
			else
				$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'];
			
			if ($_POST['job'] == $tbl_job['id_job']) {
				echo '<option value="'.$tbl_job['id_job'].'" selected>'.$jobcomplet.'</option>';
			} else {
				echo '<option value="'.$tbl_job['id_job'].'">'.$jobcomplet.'</option>';
			}
		}
	  }
	?>
	</select>
	 - Affichage des jobs terminés
	<input type=checkbox name="filtre" value=1 onchange="document.choixjob.submit()" <?php echo (isset($_POST['filtre']) AND $_POST['filtre']==1)? "checked" : ""; ?>>
</form>
</div>

<?php		
if (isset($_POST['job']) AND $_POST['job']!="-")	{		//recuperation des données du job
	
	$req_job = $db->query("
			SELECT id_job, n_client, n_job, indice, control, jobs.id_type_essai, type_essai, temperature, rapport, frequence, format, matiere, forme_cycle, DATE_FORMAT( date, '%d-%b-%y' ) AS date, STL, F_STL, suivi_extenso, arret, arret_cycle, termine, job_commentaire, tech1.technicien AS preparateur, tech2.technicien AS controleur
			FROM jobs
			LEFT JOIN type_essais ON jobs.id_type_essai = type_essais.id_type_essai
			LEFT OUTER JOIN techniciens tech1 ON jobs.preparateur = tech1.id_technicien
			LEFT OUTER JOIN techniciens tech2 ON jobs.controleur = tech2.id_technicien
			WHERE id_job =".$_POST['job'].";") or die (mysql_error());
	$tbl_job = mysqli_fetch_array($req_job);
	if (isset($tbl_job['indice']))
		$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'].'-'.$tbl_job['indice'];
	else
		$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'];

?>




	<div id="jobTop">
		<table class="job1">
			<tr>
				<td colspan="4" align="right"><?php echo 'FICHE DE DONNEES D\'ESSAIS '.$tbl_job['type_essai'].' en '.$tbl_job['control'].' Control';?></td>
				<td align="right">n° Travail :</td>
				<td><?php echo '<a href="index.php?page=listeessais&cat=jobs.id_job&val='.$tbl_job['id_job'].'&debut=1999-01-01&fin=2999-01-01&sens=DESC&nb=99999#menu">'.$jobcomplet.'</a>';?></td>
				
				
				

			</tr>
			<tr>
				<td width="15%">Température</td>
				<td width="15%"><?php echo $tbl_job['temperature'];?></td>
				<td width="15%">type éprouvette</td>
				<td width="15%"><?php echo $tbl_job['format'];?></td>
				<td width="15%">Date</td>
				<td width="15%"><?php echo $tbl_job['date'];?></td>
			</tr>
			<tr>
				<td width="15%">Rapport R</td>
				<td width="15%"><?php echo $tbl_job['rapport'];?></td>
				<td width="15%">Matière</td>
				<td width="15%"><?php echo $tbl_job['matiere'];?></td>
				<td width="15%">Préparateur</td>
				<td width="15%"><?php echo $tbl_job['preparateur'];?></td>
			</tr>
			<tr>
				<td width="15%">Fréquence (Hz)</td>
				<td width="15%"><?php echo $tbl_job['frequence'];?></td>
				<td width="15%">Forme Cycle</td>
				<td width="15%"><?php echo $tbl_job['forme_cycle'];?></td>
				<td width="15%">Contrôleur</td>
				<td width="15%"><?php echo $tbl_job['controleur'];?></td>
			</tr>
		</table>
	</div>
	
	<div id="jobPrincipale">
	
	<?php
		unset($liste_ep);
		$req_ep = $db->query("SELECT 
		eprouvettes.id_eprouvette, prefixe, nom_eprouvette, temperature, n_essai, n_fichier, assigne, machine, DATE_FORMAT(enregistrementessais.date,'%d %b %y') as date, technicien, frequence, rapport, deltaepsilon, epsilonmax, niveau_max, niveau_moy, niveau_alt, niveau_min, cycle_min,c1_E_montant,	c1_E_descendant,	c1_max_strain,	c1_min_strain,	c1_max_stress,	c1_min_stress,	c1_calc_inelastic_strain,	c1_meas_inelastic_strain,	c2_E_montant,	c2_E_descendant,	c2_max_strain,	c2_min_strain,	c2_max_stress,	c2_min_stress,	c2_calc_inelastic_strain,	c2_meas_inelastic_strain,	Ni,	Nf75, check_data, flag_qualite

		FROM eprouvettes 
		LEFT JOIN enregistrementessais ON eprouvettes.id_eprouvette = enregistrementessais.id_eprouvette
		LEFT JOIN postes ON enregistrementessais.id_poste=postes.id_poste
		LEFT JOIN machines ON postes.id_machine=machines.id_machine
		LEFT JOIN techniciens ON enregistrementessais.id_operateur=techniciens.id_technicien
		WHERE id_job =".$_POST['job']." ORDER BY id_eprouvette ;") or die (mysql_error());
		while ($tbl_ep = mysqli_fetch_array($req_ep)) {
			$liste_ep[]=$tbl_ep;
		}
		
	?>	











	
		<table><tr><td>
			<table class="job2">
			
			<?php
				$titre=array('Préfixe', 'N° Essai', 'N° Fichier', 'operateur', 'N° Machine','Date', 'Température', 'Fréquence', '&Delta; &epsilon;', '&epsilon; max', '&sigma; max', '&sigma; moy', '&sigma; alt', '&sigma; min', 'Cycle min', 'c1_E_montant', 'c1_E_descendant', 'c1_max_strain',	'c1_min_strain',	'c1_max_stress',	'c1_min_stress',	'c1_calc_inelastic_strain',	'c1_meas_inelastic_strain',	'c2_E_montant',	'c2_E_descendant',	'c2_max_strain',	'c2_min_strain',	'c2_max_stress',	'c2_min_stress',	'c2_calc_inelastic_strain',	'c2_meas_inelastic_strain',	'Ni',	'Nf75', 'check_data', 'flag_qualite');
				$titresql=array('prefixe', 'n_essai', 'n_fichier', 'technicien', 'machine', 'date', 'temperature', 'frequence', 'deltaepsilon', 'epsilonmax', 'niveau_max', 'niveau_moy', 'niveau_alt', 'niveau_min', 'cycle_min', 'c1_E_montant', 'c1_E_descendant', 'c1_max_strain', 'c1_min_strain',	'c1_max_stress',	'c1_min_stress',	'c1_calc_inelastic_strain',	'c1_meas_inelastic_strain',	'c2_E_montant',	'c2_E_descendant',	'c2_max_strain',	'c2_min_strain',	'c2_max_stress',	'c2_min_stress',	'c2_calc_inelastic_strain',	'c2_meas_inelastic_strain',	'Ni',	'Nf75', 'check_data', 'flag_qualite');



				
				echo '<tr><td>Nom éprouvette</td>';
				for($k=0;$k < count($liste_ep);$k++)	{
					$color=($liste_ep[$k]['assigne']==1)? "#E0E0E0" : "";
					echo '<td bgcolor="'.$color.'"><a href="javascript:popup(\'pages/modifep.php?ep='.$liste_ep[$k]['id_eprouvette'].'\')">'.$liste_ep[$k]['nom_eprouvette'].'</a></td>';
				}
				echo '</tr>';
				
				for($i=0; $i<count($titre); $i++){
					echo '
					<tr>
						<td>'.$titre[$i].'</td>';
							for($j=0;$j < count($liste_ep);$j++)	{
								$color=($liste_ep[$j]['assigne']==1)? "#E0E0E0" : "";
								echo '<td bgcolor="'.$color.'">'.$liste_ep[$j][$titresql[$i]].'</td>';
								}
					echo '</tr>';
				}
			?>
			</table>
		</td>
		<td align="left" valign="top" style="padding:7">
			<button type="button" onclick="javascript:alert('Ajout d\'une eprouvette') + popup('pages/ajoutep.php?job='+document.choixjob.job.value)">
				<img src="./css/croix.png">
			</button>		
		</td></tr>
		</table>
	</div>
	
	<div id="jobBottom">
		<table class="job3">
			<tr>
		<?php if($tbl_job['id_type_essai']==1)	{	?>
				<td width="25%">Passage Contrôle Effort :</td>
				<td width="25%" align="center">
				<?php echo ($tbl_job['STL']!=0)? $tbl_job['STL'] : ""; ?>
				 (Cycles)</td>
				<td width="25%" align="center">Fréquence :</td>
				<td width="25%">
				<?php echo ($tbl_job['F_STL']!=0)? $tbl_job['F_STL'] : ""; ?>
				 Hz</td>
		<?php }	
		else	{	?>
				<td width="25%">Suivi extensométrique :</td>
				<td align="center"><input type="radio" name="suivi" value="NON" checked> NON</td>
				<td align="center"><input type="radio" name="suivi" value="OUI"<?php echo ($tbl_job['suivi_extenso']==1)? "checked" : ""; ?>> OUI</td>
		<?php 	}	?>
			</tr>
			<tr>
				<td>Arrêt des essais :</td>
				<td align="center"><input type="radio" name="arret" value="rupture" checked>Rupture</td>
				<td align="center"><input type="radio" name="arret" value="apres"<?php echo ($tbl_job['arret']==1)? "checked" : ""; ?>>Après :</td>
				<td><?php echo ($tbl_job['arret']==1)? number_format($tbl_job['arret_cycle'], 0, ',', ' ') : ""; ?> cycles</td>
			</tr>
		</table>
		
		<table class="job3">	
			<tr>
				<td><b>Commentaire :</b></td>
			</tr>
			<tr>
				<td id="commentaire"><?php echo stripslashes($tbl_job['job_commentaire']); ?></td>
			</tr>
		</table>		
		
	</div>
</br>
	<?php
}
Else	//aucun job d'affiché, liste des jobs terminés
{
	echo "<table>";
	$sql_acloturer='SELECT sum( if( assigne IS NULL , 1, 0 ) ) AS nbrestant, jobs.id_job, jobs.n_client, jobs.n_job, jobs.indice
		FROM `jobs`
		LEFT JOIN eprouvettes ON eprouvettes.id_job = jobs.id_job
		WHERE jobs.job_actif =1
		AND termine IS NULL
		GROUP BY jobs.id_job
		ORDER BY jobs.n_job';
    $req_acloturer = $db->query($sql_acloturer) or die (mysql_error());
	if ($req_acloturer) {
		echo '<tr><td width=150></td><td>Job dont il ne reste plus aucune éprouvette :</tr></td>';
		while ($tbl_acloturer = mysqli_fetch_array($req_acloturer)) {
			if($tbl_acloturer['nbrestant']==0)	{
			echo '<tr><td></td><td>
			<form method="post" name="cloture">
				<input type="hidden" name="cloture" value=1>
				<input type="hidden" name="id_job" value="'.$tbl_acloturer['id_job'].'">
				<input type="submit" value="Cloture du job">';
			echo (isset($tbl_acloturer['indice']))? $tbl_acloturer['n_client'].'-'.$tbl_acloturer['n_job'].'-'.$tbl_acloturer['indice'].'<br/>' : $tbl_acloturer['n_client'].'-'.$tbl_acloturer['n_job'].'<br/>';
			echo '</form></td></tr>';
			}			
		}
	}
	echo '</table>
		<a href="index.php?page=derniersjob#menu">Liste des derniers jobs ajoutés</a>
		<br><a href="index.php?page=dernieresmodifications#menu">Liste des dernieres modifications apportées</a>';
}
?>

<?php
if(isset($_POST['validation']))	{
	?>
	<form method="post" name="ajout" action="index.php?page=validationjob">
		<input type="hidden" name="id_job" value="<?php echo $_POST['job'];	?>">
		<input type="hidden" name="job_actif" value="1">
		<input type="submit" value="Validation du job">
	</form>
	<form method="post" name="suppression" action="index.php?page=accueil">
		<input type="submit" value="Suppression du job">
	</form>
	<?php
}
else	{
	if (isset($_POST['job']) AND $_POST['job']!="-")	{
		if(isset($tbl_job['termine']) AND $tbl_job['termine']==1)	{
			echo '
			<form method="post" name="decloture">
				<input type="hidden" name="decloture" value=1>
				<input type="hidden" name="id_job" value="'.$_POST['job'].'">
				<input type="submit" value="Re-activation du job">
			</form>';
		}
		else	{
			echo '
			<form method="post" name="cloture">
				<input type="hidden" name="cloture" value=1>
				<input type="hidden" name="id_job" value="'.$_POST['job'].'">
				<input type="submit" value="Cloture du job"> (Ne cloturer qu\'après édition du rapport final)
			</form>';
		}
	echo '</br><a href="javascript:if(confirm(\'Etes vous sur de vouloir modifier les données du job ?\')) document.location.href=\'index.php?page=modifjob&job='.$tbl_job['id_job'].'\'">Modification des données du job</a></br></br>';
	}
}
?>





<!--<a href="javascript:if(confirm('Etes vous sur ?')) document.location.href='mon_lien.htm'">Mon lien</a> -->

