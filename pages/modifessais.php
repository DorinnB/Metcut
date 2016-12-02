<?php		//mémorisation du job en temporaire pour les requetes liées (machines, acquisitions ...)

$_POST['modif'] = (isset($_POST['edit']))? $_POST['edit'] : $_POST['modif'] ;
$req='
		SELECT n_fichier, id_type_essai, eprouvettes.temperature, eprouvettes.id_job, n_essai, eprouvettes.id_eprouvette, prefixe, id_poste, id_acquisition, enregistrementessais.date, id_operateur, id_controleur
		FROM enregistrementessais
		LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
		LEFT JOIN tbljobs ON eprouvettes.id_job = tbljobs.id_tbljob
		WHERE n_fichier ='.$_POST['modif'].'
		;';
	//	echo $req;
$req_essai = $db->query($req) or die (mysql_error());
$tbl_essai = mysqli_fetch_assoc($req_essai);
	
if(isset($_POST['edit']))	{

		$_POST['job']=$tbl_essai['id_job'];
		$_POST['prefixe']=$tbl_essai['prefixe'];
		$_POST['eprouvette']=$tbl_essai['id_eprouvette'];
		$_POST['poste']=$tbl_essai['id_poste'];
		$_POST['acquisition']=$tbl_essai['id_acquisition'];
		$_POST['operateur']=$tbl_essai['id_operateur'];
		$_POST['controleur']=$tbl_essai['id_controleur'];
		
		
		list($annee, $moisUS, $jour) = explode('-', $tbl_essai['date']);	
		$_POST['dateUS']= date("d M Y",mktime(0, 0, 0, $moisUS, $jour, $annee));
}

(isset($_POST['job']) AND $_POST['job']!='-') ?	$jobtemp=$_POST['job'] :	$jobtemp=0;
(isset($_POST['eprouvette']) AND $_POST['eprouvette']!='-') ?	$eprouvettetemp=$_POST['eprouvette'] :	$eprouvettetemp=0;
(isset($_POST['prefixe']) AND $_POST['prefixe']!='-' AND $_POST['prefixe']!="") ?	$prefixetemp=' = "'.$_POST['prefixe'].'"' :	$prefixetemp='IS NULL';
?>



/!\ vous etes en train de modifier l'essai n° <?php echo $_POST['modif']; ?> !<br/><br/>

<form method="post" name="modifessais"> 
<table class="enregistrementessais">
	<tr>
		<th>Type</th>
		<th>Température</th>
		<th>n° du Job</th>
		<th>Préfixe</th>
		<th>Eprouvette</th>
		<th>Machine</th>
		<th>Acquisition</th>
		<th>Date</th>
		<th>Opérateur</th>
		<th>Controleur</th>
	</tr>
	<tr>
		<td>	<!--Type d'Essais-->
<?php
    $req_type = $db->query('SELECT type_essai FROM tbljobs LEFT JOIN type_essais ON tbljobs.id_type_essai=type_essais.id_type_essai WHERE id_tbljob = '.$jobtemp.';') or die (mysql_error());
	if ($req_type) {
		$tbl_type = mysqli_fetch_assoc($req_type);
		echo $tbl_type['type_essai'];
	}
?>			
		</td>		
		<td>	<!--Température d'essais-->
<?php
    $req_temperature = $db->query('SELECT c_temperature FROM eprouvettes WHERE id_eprouvette = '.$eprouvettetemp.';') or die (mysql_error());
	if ($req_temperature) {
		$tempe= mysqli_fetch_assoc($req_temperature);
		if ($tempe['c_temperature']=="")
			echo '';
		else
			echo $tempe['c_temperature'].'°C';
	}
?>		
		</td>
		<td>	<!--Job-->
		<select name="job" onchange="document.modifessais.submit()">		
<?php
    $req_job = $db->query("SELECT tbljobs.id_tbljob, customer, job, split, id_type_essai 
							FROM tbljobs 
							LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
							WHERE tbljob_actif =1 
								AND id_statut <100 
							GROUP BY tbljobs.id_tbljob 
							ORDER BY job ASC , split ASC ;") or die (mysql_error());
  if ($req_job) {
    echo '<option value="-">-</option>';
    while ($tbl_job = mysqli_fetch_assoc($req_job)) {
		if (isset($tbl_job['split']))		//groupement du nom du job avec ou sans split
			$jobcomplet= $tbl_job['customer'].'-'.$tbl_job['job'].'-'.$tbl_job['split'];
		else
			$jobcomplet= $tbl_job['customer'].'-'.$tbl_job['job'];
        if ($_POST['job'] == $tbl_job['id_tbljob']) {
            echo '<option value="'.$tbl_job['id_tbljob'].'" selected>'.$jobcomplet.'</option>';
        } else {
            echo '<option value="'.$tbl_job['id_tbljob'].'">'.$jobcomplet.'</option>';
        }
    }
  }
?>
		</select></td>
		<td>	<!--Prefixe-->
<?php
	$req='SELECT prefixe FROM eprouvettes
	LEFT JOIN tbljobs ON eprouvettes.id_job = tbljobs.id_tbljob
	WHERE eprouvette_actif =1 
	AND assigne IS NULL
	AND tbljobs.id_tbljob ='.$jobtemp.'
	OR (tbljobs.id_tbljob='.$jobtemp.' AND id_eprouvette='.$tbl_essai['id_eprouvette'].')
	GROUP BY prefixe;';
	//echo $req;
    $req_prefixe = $db->query($req) or die (mysql_error());
	if ($req_prefixe) {
		$num_prefixe = mysqli_num_rows($req_prefixe);

		if ($num_prefixe == 1)	{		// 1 seul préfixe
			$prefixeunique = mysqli_fetch_assoc($req_prefixe);
			echo $prefixeunique['prefixe'];
			echo '<input type="hidden" name="prefixe" value="'.$prefixeunique['prefixe'].'">';
			if ($prefixeunique['prefixe'] =="")
				$prefixeunique['prefixe']=' is null';
			else
				$prefixeunique['prefixe']='= "'.mysqli_fetch_assoc($req_prefixe).'"';
		}
		else if ($num_prefixe > 1)	{	// Plusieurs préfixe
			echo '<select name="prefixe" onchange="document.modifessais.submit()"><option value="-">-</option>';
				while ($tbl_prefixe = mysqli_fetch_assoc($req_prefixe)) {
					if ($_POST['prefixe'] == $tbl_prefixe['prefixe']) {
						echo '<option value="'.$tbl_prefixe['prefixe'].'" selected>'.$tbl_prefixe['prefixe'].'</option>';
					} else {
						echo '<option value="'.$tbl_prefixe['prefixe'].'">'.$tbl_prefixe['prefixe'].'</option>';
					}
				}
			echo '</select>';
		}
		else	{						//rien
			echo "";
			echo '<input type="hidden" name="prefixe" value="">';
			$prefixetemp="is null";
		}

	}
?>		
		</td>
		<td>	<!--Eprouvette-->
			<select name="eprouvette" onchange="document.modifessais.submit()">
<?php
$req='SELECT id_eprouvette, nom_eprouvette, tbljob_commentaire
			FROM eprouvettes
			LEFT JOIN tbljobs ON eprouvettes.id_job = tbljobs.id_tbljob
			WHERE eprouvette_actif =1
			AND assigne IS NULL 
			AND tbljobs.id_tbljob ='.$jobtemp.'
			AND prefixe '.$prefixetemp.'
			OR (tbljobs.id_tbljob='.$jobtemp.' AND prefixe '.$prefixetemp.' AND id_eprouvette='.$tbl_essai['id_eprouvette'].') ;';
echo $req;			
    $req_ep = $db->query($req) or die (mysql_error());
	if ($req_ep) {
		echo '<option value="-">-</option>';
		while ($tbl_eprouvette = mysqli_fetch_assoc($req_ep)) {
			
			if ($_POST['eprouvette'] == $tbl_eprouvette['id_eprouvette']) {
				echo '<option value="'.$tbl_eprouvette['id_eprouvette'].'" selected>'.$tbl_eprouvette['nom_eprouvette'].'</option>';
			} else {
				echo '<option value="'.$tbl_eprouvette['id_eprouvette'].'">'.$tbl_eprouvette['nom_eprouvette'].'</option>';
			}
		}
	}
?>		
		</select></td>
		<td>	<!--Machine-->
		<select name="poste" onchange="document.modifessais.submit()">
<?php
    $req_poste = $db->query('SELECT t1.id_poste, t1.id_machine, machines.machine
		FROM postes t1
		LEFT JOIN machines ON machines.id_machine = t1.id_machine
		WHERE t1.id_poste = ( 
		SELECT MAX( t2.id_poste ) 
		FROM postes t2
		WHERE t2.id_machine = t1.id_machine ) 
		AND machines.machine_actif =1
		ORDER BY machines.id_machine;') or die (mysql_error());
	if ($req_poste) {
		echo '<option value="-">-</option>';
		//recup des postes/machines des essais précédent sur ce job
		$req_machinejob = $db->query('SELECT postes.id_poste, machines.id_machine, machines.machine
			FROM postes
			LEFT JOIN machines ON postes.id_machine = machines.id_machine
			LEFT JOIN enregistrementessais ON enregistrementessais.id_poste = postes.id_poste
			LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
			LEFT JOIN tbljobs ON eprouvettes.id_job = tbljobs.id_tbljob
			WHERE tbljobs.id_tbljob ='.$jobtemp.'
			GROUP BY postes.id_poste;') or die (mysql_error());
		while ($tbl_machinejob = mysqli_fetch_assoc($req_machinejob)) {
			if ($_POST['poste'] == $tbl_machinejob['id_poste']) {
				echo '<option class="formdefaut" value="'.$tbl_machinejob['id_poste'].'" selected>'.$tbl_machinejob['machine'].'</option>';
			} else {
				echo '<option class="formdefaut" value="'.$tbl_machinejob['id_poste'].'">'.$tbl_machinejob['machine'].'</option>';
			}
		}
		echo '<option value="-">---------</option>';
		//affichage de toutes les machines avec le dernier indice de poste
		while ($tbl_poste = mysqli_fetch_assoc($req_poste)) {
			
			if ($_POST['poste'] == $tbl_poste['id_poste']) {
				echo '<option value="'.$tbl_poste['id_poste'].'" selected>'.$tbl_poste['machine'].'</option>';
			} else {
				echo '<option value="'.$tbl_poste['id_poste'].'">'.$tbl_poste['machine'].'</option>';
			}
		}
	}
?>		
		</select></td>
		<td>	<!--Acquisition-->
		<select name="acquisition" onchange="document.modifessais.submit()">
<?php
    $req_acquisition = $db->query('SELECT id_acquisition, acquisition FROM acquisitions WHERE acquisition_actif=1;') or die (mysql_error());
	if ($req_acquisition) {
		echo '<option value="-">-</option>';
		
		$req='SELECT acquisitions.id_acquisition, acquisitions.acquisition 
				FROM enregistrementessais 
				LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition 
				LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette 
				LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job 
				WHERE jobs.id_job ='.$jobtemp.' 
				GROUP BY acquisitions.id_acquisition;';
				echo $req;
		$req_acquisitionjob = $db->query($req) or die (mysql_error());
		while ($tbl_acquisitionjob = mysqli_fetch_assoc($req_acquisitionjob)) {
			echo '<option class="formdefaut" value="'.$tbl_acquisitionjob['id_acquisition'].'">'.$tbl_acquisitionjob['acquisition'].'</option>';
		}
		echo '<option value="-">---------</option>';
	
		while ($tbl_acquisition = mysqli_fetch_assoc($req_acquisition)) {
			
			if ($_POST['acquisition'] == $tbl_acquisition['id_acquisition']) {
				echo '<option value="'.$tbl_acquisition['id_acquisition'].'" selected>'.$tbl_acquisition['acquisition'].'</option>';
			} else {
				echo '<option value="'.$tbl_acquisition['id_acquisition'].'">'.$tbl_acquisition['acquisition'].'</option>';
			}
		}
	}
?>		
		</select></td>		
		<td>	<!--Date-->
		<INPUT type=text name="dateUS" onchange="document.modifessais.submit()"
		size=10 value="<?php	
		if (isset($_POST['dateUS']))
			echo $_POST['dateUS'];
		else
			echo date("d M Y");	
		?>">/!\ pb de date fr/en. Jui au lieu de Jul
		</td>
		<td>	<!--Opérateur-->
		<select name="operateur" onchange="document.modifessais.submit()">
<?php
    $req_operateur = $db->query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1 ORDER BY technicien;') or die (mysql_error());
	if ($req_operateur) {
		echo '<option value="-">-</option>';
		while ($tbl_operateur = mysqli_fetch_assoc($req_operateur)) {
			
			if ($_POST['operateur'] == $tbl_operateur['id_technicien']) {
				echo '<option value="'.$tbl_operateur['id_technicien'].'" selected>'.$tbl_operateur['technicien'].'</option>';
			} else {
				echo '<option value="'.$tbl_operateur['id_technicien'].'">'.$tbl_operateur['technicien'].'</option>';
			}
		}
	}
?>		
		</select></td>
		<td>	<!--Controleur-->
		<select name="controleur" onchange="document.modifessais.submit()">
<?php
    $req_controleur = $db->query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1 ORDER BY technicien;') or die (mysql_error());
	if ($req_controleur) {
		echo '<option value="-">-</option>';
		while ($tbl_controleur = mysqli_fetch_assoc($req_controleur)) {
			
			if ($_POST['controleur'] == $tbl_controleur['id_technicien']) {
				echo '<option value="'.$tbl_controleur['id_technicien'].'" selected>'.$tbl_controleur['technicien'].'</option>';
			} else {
				echo '<option value="'.$tbl_controleur['id_technicien'].'">'.$tbl_controleur['technicien'].'</option>';
			}
		}
	}
?>		
		</select></td>
	</tr>
</table>
	<input type="hidden" name="modif" value="<?php echo $_POST['modif'];	?>">
</form>

<?php	//affichage tableau récapitulatif du poste
if (isset($_POST['poste']) AND $_POST['poste']!='-')	{
	?>
	<div id="poste">
		<table class="poste">
			<CAPTION>Récapitulatif du poste</CAPTION>
			<tr>
				<th>Cartouche Stroke (mm)</th><th>Cartouche Load (kN)</th><th>Cartouche Strain (%)</th><th>Enregistreur</th><th>Extensometre</th><th>Outillage Top</th><th>Outillage Bot</th><th>Chauffage</th><th>Ind. Temp Top</th><th>Ind. Temp Strap</th><th>Ind. Temp Bot</th><th>Compresseur</th>
			</tr>
			<?php 
				$req_historique = $db->query('SELECT cartouche_stroke, cartouche_load, cartouche_strain, enregistreur, extensometre, o1.outillage as outillage_top, o2.outillage as outillage_bot, chauffage, i1.ind_temp as ind_temp_top, i2.ind_temp as ind_temp_strap, i3.ind_temp as ind_temp_bot,  IF( compresseur = 1,  "&#10004;",  "" ) as compresseur, date
				FROM postes 
				LEFT JOIN enregistreurs ON enregistreurs.id_enregistreur=postes.id_enregistreur
				LEFT JOIN extensometres ON extensometres.id_extensometre=postes.id_extensometre
				LEFT JOIN outillages o1 ON o1.id_outillage = postes.id_outillage_top
				LEFT JOIN outillages o2 ON o2.id_outillage = postes.id_outillage_bot
				LEFT JOIN chauffages ON chauffages.id_chauffage=postes.id_chauffage
				LEFT JOIN ind_temps i1 ON i1.id_ind_temp = postes.id_ind_temp_top
				LEFT JOIN ind_temps i2 ON i2.id_ind_temp = postes.id_ind_temp_strap
				LEFT JOIN ind_temps i3 ON i3.id_ind_temp = postes.id_ind_temp_bot
				WHERE id_poste='.$_POST['poste'].' ORDER BY date DESC LIMIT 0,5') or die (mysql_error());
				if ($req_historique) {
					while ($tbl_historique = mysqli_fetch_assoc($req_historique)) {
						$titresql=array('cartouche_stroke', 'cartouche_load', 'cartouche_strain', 'enregistreur', 'extensometre','outillage_top', 'outillage_bot', 'chauffage', 'ind_temp_top', 'ind_temp_strap', 'ind_temp_bot', 'compresseur');
						echo'<tr>';
						for($i=0; $i<count($titresql); $i++){
							echo '<td>'.$tbl_historique[$titresql[$i]].'</td>';
						}
						echo '</tr>';
					}
				}
			?>
		</table>
	</div>
	<?php
}
?>


<?php	//Verification des données avant envoi
extract($_POST);

$ok=1;
(isset($job) and $job!="-")	?	$ok=$ok*1	:	$ok=0;
(isset($eprouvette) and $eprouvette!="-")	?	$ok=$ok*1	:	$ok=0;
(isset($poste) and $poste!="-")	?	$ok=$ok*1	:	$ok=0;
(isset($acquisition) and $acquisition!="-")	?	$ok=$ok*1	:	$ok=0;


if(isset($dateUS) and $dateUS!="-")	{

	list($jour, $moisUS, $annee) = explode(' ', $dateUS);
		
$month=array (
'Jan'=> '01',
'Feb'=> '02',
'Mar'=> '03',
'Apr'=> '04',
'May'=> '05',
'Jun'=> '06',
'Jui'=> '07',
'Aug'=> '08',
'Sep'=> '09',
'Oct'=> '10',
'Nov'=> '11',
'Dec'=> '12'
);
$mois=$month[$moisUS];			//Transformation du mois "US" en chiffre
$date=date("Y-m-j",mktime(0, 0, 0, $mois, $jour, $annee)); // transformation de la date pret à etre envoyé à MYSQL

	list($jour2, $mois2, $annee2) = explode('/', date("d/m/Y"));
	
	if(checkdate($mois,$jour,$annee))	{	// Date existante ?
		$ok=$ok*1;
		
		$timestamp1 = mktime(0,0,0,$mois,$jour,$annee); 
		$timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2); 
		$ecart = ($timestamp1 - $timestamp2)/86400;			//Ecart entre la date d'essai et aujourdhui (mais non bloquant)
		if($ecart<0)	{
			echo '<div id="ErreurFormulaire2">Date antérieur à aujourdhui</div>';

		}
		if($ecart>15)	{
			echo '<div id="ErreurFormulaire2">Attention, date supérieur à 15 jours</div>';

		}
	}
	else	{
		$ok=0;
		Echo '<div id="ErreurFormulaire">date incorrecte<br/></div>';
	}
}	else	$ok=0;


if(isset($operateur) and $operateur!="-")	{	//Check des opérateur/controleur
	if(isset($controleur) and $controleur!="-")	{
		if(($controleur!=$operateur))
			$ok=$ok*1;
		else	{
			$ok=0;
			echo '<div id="ErreurFormulaire">Veuillez choisir un controleur différent de l\'opérateur<br/></div>';
		}
	}	else	$ok=0;
}	else	$ok=0;


if(isset($_POST['edit']))	{				// Protection en cas de non changement de valeur
	$ok=0;
}

?>

<br/>
<form method="post" name="envoi" action="index.php?page=envoimodifenregistrementessai">
	<input type="hidden" name="job" value="<?php echo $job;	?>">
	<input type="hidden" name="eprouvette" value="<?php echo $eprouvette;	?>">
	<input type="hidden" name="poste" value="<?php echo $poste;	?>">
	<input type="hidden" name="acquisition" value="<?php echo $acquisition;	?>">
	<input type="hidden" name="date" value="<?php echo $date;	?>">
	<input type="hidden" name="operateur" value="<?php echo $operateur;	?>">
	<input type="hidden" name="controleur" value="<?php echo $controleur;	?>">
	<input type="hidden" name="modif" value="<?php echo $modif;	?>">
	<?php if ($ok==1)
		echo '<input type="submit" value="Enregistrement de l\'essai">';
	?>
</form>




<div class="todo">TODO :<br/>
look<br/>
</div>