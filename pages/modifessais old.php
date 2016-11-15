<?php		//mémorisation du job en temporaire pour les requetes liées (machines, acquisitions ...)

if(isset($_POST['edit']))	{
	$req_essai = mysql_query('
		SELECT n_fichier, id_type_essai, eprouvettes.temperature, eprouvettes.id_job, n_essai, eprouvettes.id_eprouvette, id_machine, id_acquisition, enregistrementessais.date, id_operateur, id_controleur
		FROM enregistrementessais
		LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
		LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
		WHERE n_fichier ='.$_POST['edit'].'
		;') or die (mysql_error());
	while ($tbl_essai = mysql_fetch_assoc($req_essai)) {

		$_POST['job']=$tbl_essai['id_job'];
		$_POST['eprouvette']=$tbl_essai['id_eprouvette'];
		$_POST['machine']=$tbl_essai['id_machine'];
		$_POST['acquisition']=$tbl_essai['id_acquisition'];
		$_POST['operateur']=$tbl_essai['id_operateur'];
		$_POST['controleur']=$tbl_essai['id_controleur'];
		$_POST['modif']=$_POST['edit'];
		
		list($annee, $moisUS, $jour) = explode('-', $tbl_essai['date']);	
		$_POST['dateUS']= date("d M Y",mktime(0, 0, 0, $moisUS, $jour, $annee));
	}
}

(isset($_POST['job']) AND $_POST['job']!='-') ?	$jobtemp=$_POST['job'] :	$jobtemp=0;
(isset($_POST['eprouvette']) AND $_POST['eprouvette']!='-') ?	$eprouvettetemp=$_POST['eprouvette'] :	$eprouvettetemp=0;





?>


<div id="ErreurFormulaire">
/!\ vous etes en train de modifier l'essai n° <?php echo $_POST['modif']; ?> !<br/><br/>
</div>

<form method="post" name="modifessais"> 
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
			echo 'Ambiant';
		else
			echo $tempe.'°C';
	}
?>		
		</td>
		<td><select name="job" onchange="document.modifessais.submit()">		
<?php
    $req_job = mysql_query("SELECT id_job, n_client, n_job, indice, id_type_essai FROM jobs WHERE job_actif =1 AND termine IS NULL;") or die (mysql_error());
  if ($req_job) {
    echo '<option value="-">-</option>';
    while ($tbl_job = mysql_fetch_assoc($req_job)) {
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
		</select></td>
		<td>	<!--Préfixe de l'eprouvette-->
<?php
    $req_prefixe = mysql_query('SELECT prefixe FROM eprouvettes WHERE id_eprouvette = '.$eprouvettetemp.';') or die (mysql_error());
	if ($req_prefixe) {
		$prefixe= mysql_result($req_prefixe,0);
		echo $prefixe;
	}
?>		
		</td>		
		<td><select name="eprouvette" onchange="document.modifessais.submit()">
<?php
    $req_ep = mysql_query('SELECT id_eprouvette,nom_eprouvette FROM eprouvettes LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job WHERE eprouvette_actif =1 AND assigne IS NULL AND jobs.id_job ='.$jobtemp.' OR id_eprouvette='.$eprouvettetemp.' ;') or die (mysql_error());
  
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
		<td><select name="machine" onchange="document.modifessais.submit()">
<?php
    $req_machine = mysql_query('SELECT id_machine, machine FROM machines WHERE machine_actif=1;') or die (mysql_error());
	if ($req_machine) {
		echo '<option value="-">-</option>';
		
		$req_machinejob = mysql_query('SELECT machines.id_machine, machines.machine FROM enregistrementessais LEFT JOIN machines ON enregistrementessais.id_machine = machines.id_machine LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job WHERE jobs.id_job ='.$jobtemp.' GROUP BY machines.id_machine;') or die (mysql_error());
		while ($tbl_machinejob = mysql_fetch_assoc($req_machinejob)) {
			echo '<option class="formdefaut" value="'.$tbl_machinejob['id_machine'].'">'.$tbl_machinejob['machine'].'</option>';
		}
		echo '<option value="-">---------</option>';

		while ($tbl_machine = mysql_fetch_assoc($req_machine)) {
			
			if ($_POST['machine'] == $tbl_machine['id_machine']) {
				echo '<option value="'.$tbl_machine['id_machine'].'" selected>'.$tbl_machine['machine'].'</option>';
			} else {
				echo '<option value="'.$tbl_machine['id_machine'].'">'.$tbl_machine['machine'].'</option>';
			}
		}
	}
?>		
		</select></td>
		<td><select name="acquisition" onchange="document.modifessais.submit()">
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
		<td><INPUT type=text name="dateUS" onchange="document.modifessais.submit()"
		size=10 value="<?php	
		if (isset($_POST['dateUS']))
			echo $_POST['dateUS'];
		else
			echo date("d M Y");	
		?>">
		</td>
		<td><select name="operateur" onchange="document.modifessais.submit()">
<?php
    $req_operateur = mysql_query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1;') or die (mysql_error());
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
		<td><select name="controleur" onchange="document.modifessais.submit()">
<?php
    $req_controleur = mysql_query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1;') or die (mysql_error());
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
	<input type="hidden" name="modif" value="<?php echo $_POST['modif'];	?>">
</form>


<?php	//Verification des données avant envoi
extract($_POST);

$ok=1;
(isset($job) and $job!="-")	?	$ok=$ok*1	:	$ok=0;
(isset($eprouvette) and $eprouvette!="-")	?	$ok=$ok*1	:	$ok=0;
(isset($machine) and $machine!="-")	?	$ok=$ok*1	:	$ok=0;
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
			echo 'Date antérieur à aujourdhui<br/>';

		}
		if($ecart>15)	{
			echo 'Attention, date supérieur à 15 jours<br/>';

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
	<input type="hidden" name="machine" value="<?php echo $machine;	?>">
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