<?php		//mémorisation du job en temporaire pour les requetes liées (machines, acquisitions ...)
	(isset($_POST['job']) AND $_POST['job']!='-') ?	$jobtemp=$_POST['job'] :	$jobtemp=0;
	(isset($_POST['eprouvette']) AND $_POST['eprouvette']!='-') ?	$eprouvettetemp=$_POST['eprouvette'] :	$eprouvettetemp=0;
	(isset($_POST['prefixe']) AND $_POST['prefixe']!='-' AND $_POST['prefixe']!="") ?	$prefixetemp=' = "'.$_POST['prefixe'].'"' :	$prefixetemp='IS NULL';
?>


<form method="post" name="enregistrementessais2"> 
<table class="enregistrementessais">
	<tr>
		<th>Contrôle</th>
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
		<td>	<!--Mode de controle-->
<?php
    $req_type = mysql_query('SELECT type_essai, Control FROM jobs LEFT JOIN type_essais ON jobs.id_type_essai=type_essais.id_type_essai WHERE id_job = '.$jobtemp.';') or die (mysql_error());
	if ($req_type) {
		$tbl_type = mysql_fetch_assoc($req_type);
		echo $tbl_type['Control'];
	}
?>		
		</td>
		<td>	<!--Type d'Essais-->
<?php
		echo $tbl_type['type_essai'];
?>			
		</td>		
		<td>	<!--Température d'essais-->
<?php
    $req_temperature = mysql_query('SELECT temperature FROM eprouvettes WHERE id_eprouvette = '.$eprouvettetemp.';') or die (mysql_error());
	if ($req_temperature) {
		$tempe= mysql_result($req_temperature,0);
		if ($tempe=="")
			echo '';
		else
			echo $tempe.'°C';
	}
?>		
		</td>
		<td>	<!--Job-->
		<select name="job" onchange="document.enregistrementessais2.submit()">		
<?php
    $req_job = mysql_query("SELECT jobs.id_job, n_client, n_job, indice, id_type_essai
							FROM jobs
							WHERE job_actif =1
							AND termine IS NULL
							GROUP BY jobs.id_job
							ORDER BY n_job ASC , indice ASC;") or die (mysql_error());
  if ($req_job) {
    echo '<option value="-">-</option>';
    while ($tbl_job = mysql_fetch_assoc($req_job)) {
		if (isset($tbl_job['indice']))		//groupement du nom du job avec ou sans indice
			$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'].'-'.$tbl_job['indice'];
		else
			$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'];
//		$jobcouleur=($tbl_job['nbrestant']==$tbl_job['nbtotal'])? "" : "class='formdefaut'";	//couleur si le job contient des eprouvettes non testées
        if ($_POST['job'] == $tbl_job['id_job']) {
            echo '<option value="'.$tbl_job['id_job'].'" selected>'.$jobcomplet.'</option>';
        } else {
            echo '<option value="'.$tbl_job['id_job'].'">'.$jobcomplet.'</option>';
        }
    }
  }
?>
		</select></td>
		<td>	<!--Prefixe-->
<?php
    $req_prefixe = mysql_query('SELECT prefixe FROM eprouvettes LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job WHERE eprouvette_actif =1 AND assigne IS NULL AND jobs.id_job ='.$jobtemp.' GROUP BY prefixe;') or die (mysql_error());
	if ($req_prefixe) {
		$num_prefixe = mysql_num_rows($req_prefixe);

		if ($num_prefixe == 1)	{		// 1 seul préfixe
			$prefixeunique = mysql_result($req_prefixe,0);
			echo $prefixeunique;
			echo '<input type="hidden" name="prefixe" value="'.$prefixeunique.'">';
			if ($prefixeunique =="")
				$prefixetemp=' is null';
			else
				$prefixetemp='= "'.mysql_result($req_prefixe,0).'"';
		}
		else if ($num_prefixe > 1)	{	// Plusieurs préfixe
			echo '<select name="prefixe" onchange="document.enregistrementessais2.submit()"><option value="-">-</option>';
				while ($tbl_prefixe = mysql_fetch_assoc($req_prefixe)) {
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
		<select name="eprouvette" onchange="document.enregistrementessais2.submit()">
<?php
    $req_ep = mysql_query('SELECT id_eprouvette,nom_eprouvette, job_commentaire FROM eprouvettes LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job WHERE eprouvette_actif =1 AND assigne IS NULL AND jobs.id_job ='.$jobtemp.' AND prefixe '.$prefixetemp.' ;') or die (mysql_error());
  
	if ($req_ep) {
		echo '<option value="-">-</option>';
		while ($tbl_eprouvette = mysql_fetch_assoc($req_ep)) {
			
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
		<select name="poste" onchange="document.enregistrementessais2.submit()">
<?php
    $req_poste = mysql_query('SELECT t1.id_poste, t1.id_machine, machines.machine
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
		
		$req_machinejob = mysql_query('SELECT (select max(p2.id_poste) from postes p2 where p2.id_machine=p1.id_machine) as id_poste, machines.id_machine, machines.machine
			FROM postes p1
			LEFT JOIN machines ON p1.id_machine = machines.id_machine
			LEFT JOIN enregistrementessais ON enregistrementessais.id_poste = p1.id_poste
			LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
			LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
			WHERE jobs.id_job ='.$jobtemp.'
			GROUP BY machines.id_machine;') or die (mysql_error());
		while ($tbl_machinejob = mysql_fetch_assoc($req_machinejob)) {
			echo '<option class="formdefaut" value="'.$tbl_machinejob['id_poste'].'">'.$tbl_machinejob['machine'].'</option>';
		}
		echo '<option value="-">---------</option>';

		while ($tbl_poste = mysql_fetch_assoc($req_poste)) {
			
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
		<select name="acquisition" onchange="document.enregistrementessais2.submit()">
<?php
    $req_acquisition = mysql_query('SELECT id_acquisition, acquisition FROM acquisitions WHERE acquisition_actif=1;') or die (mysql_error());
	if ($req_acquisition) {
		echo '<option value="-">-</option>';
		
		$req_acquisitionjob = mysql_query('SELECT acquisitions.id_acquisition, acquisitions.acquisition FROM enregistrementessais LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job WHERE jobs.id_job ='.$jobtemp.' GROUP BY acquisitions.id_acquisition;') or die (mysql_error());
		while ($tbl_acquisitionjob = mysql_fetch_assoc($req_acquisitionjob)) {
			echo '<option class="formdefaut" value="'.$tbl_acquisitionjob['id_acquisition'].'">'.$tbl_acquisitionjob['acquisition'].'</option>';
		}
		echo '<option value="-">---------</option>';
	
		while ($tbl_acquisition = mysql_fetch_assoc($req_acquisition)) {
			
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
		<INPUT type=text name="dateUS" onchange="document.enregistrementessais2.submit()"
		size=10 value="<?php	
		if (isset($_POST['dateUS']))
			echo $_POST['dateUS'];
		else
			echo date("d M Y");	
		?>">
		</td>
		<td>	<!--Opérateur-->
		<select name="operateur" onchange="document.enregistrementessais2.submit()">
<?php
    $req_operateur = mysql_query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1 ORDER BY technicien;') or die (mysql_error());
	if ($req_operateur) {
		echo '<option value="-">-</option>';
		while ($tbl_operateur = mysql_fetch_assoc($req_operateur)) {
			
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
		<select name="controleur" onchange="document.enregistrementessais2.submit()">
<?php
    $req_controleur = mysql_query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1 ORDER BY technicien;') or die (mysql_error());
	if ($req_controleur) {
		echo '<option value="-">-</option>';
		while ($tbl_controleur = mysql_fetch_assoc($req_controleur)) {
			
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
				$req_historique = mysql_query('SELECT cartouche_stroke, cartouche_load, cartouche_strain, enregistreur, extensometre, o1.outillage as outillage_top, o2.outillage as outillage_bot, chauffage, i1.ind_temp as ind_temp_top, i2.ind_temp as ind_temp_strap, i3.ind_temp as ind_temp_bot,  IF( compresseur = 1,  "&#10004;",  "" ) as compresseur, date
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
					while ($tbl_historique = mysql_fetch_assoc($req_historique)) {
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


<div id="ErreurFormulaire">
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
'Jul'=> '07',
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
		$ecart = ($timestamp1 - $timestamp2)/86400;			//Ecart entre la date d'essai et aujourdhui
		if($ecart<0)	{
			echo 'il n\'est pas autorisé d\'antidater un essai<br/>';
			$ok=0;
		}
		if($ecart>15)	{
			echo 'il n\'est pas autorisé d\'enregistrer un essai 15 jours à l\'avance<br/>';
			$ok=0;
		}
	}
	else	{
		$ok=0;
		Echo 'date incorrecte';
	}
}	else	$ok=0;


if(isset($operateur) and $operateur!="-")	{	//Check des opérateur/controleur
	if(isset($controleur) and $controleur!="-")	{
		if(($controleur!=$operateur))
			$ok=$ok*1;
		else	{
			$ok=0;
			echo 'Veuillez choisir un controleur différent de l\'opérateur<br/>';
		}
	}	else	$ok=0;
}	else	$ok=0;


?>
</div>

<form method="post" name="envoi" action="index.php?page=envoienregistrementessai">
	<input type="hidden" name="job" value="<?php echo $job;	?>">
	<input type="hidden" name="eprouvette" value="<?php echo $eprouvette;	?>">
	<input type="hidden" name="poste" value="<?php echo $poste;	?>">
	<input type="hidden" name="acquisition" value="<?php echo $acquisition;	?>">
	<input type="hidden" name="date" value="<?php echo $date;	?>">
	<input type="hidden" name="operateur" value="<?php echo $operateur;	?>">
	<input type="hidden" name="controleur" value="<?php echo $controleur;	?>">
	<?php if ($ok==1)
		echo '<input type="submit" value="Enregistrement de l\'essai">';
	?>
</form>


<?php		// Affichage du Commentaire du job
    $req_commentaire = mysql_query('SELECT job_commentaire FROM jobs WHERE id_job='.$jobtemp.' ;') or die (mysql_error());
  
	if ($req_commentaire) {
		$commentaire= mysql_result($req_commentaire,0);
		if ($commentaire!="")	{
		echo '<table class="job3">	
			<tr>
				<td><b>Commentaire :</b></td>
			</tr>
			<tr>
				<td id="commentaire">'.stripslashes($commentaire).'</td>
			</tr>
		</table>';
		}
	}
?>	