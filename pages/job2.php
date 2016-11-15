<html>
	<head>


	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>JOBS</title>
	<link rel="shortcut icon" href="../css/favicon.ico" />
	<link type="text/css" rel="stylesheet" href="../css/style.css" />

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

	<script type="text/javascript">
//		var nombresplit = 2;
		function changeSplit(numero,nombresplit)
		{
			// On commence par tout masquer
			for (var i = 0; i < nombresplit; i++)
				document.getElementById("contenuSplit" + i).style.display = "none";

			// Puis on affiche celui qui a été sélectionné
			document.getElementById("contenuSplit" + numero).style.display = "block";
		}
	</script>
	<script type="text/javascript">
		var nombreOnglets = 2;
		function changeOnglet(split,numero)
		{
			// On commence par tout masquer
			for (var i = 0; i < nombreOnglets; i++)
				document.getElementById("contenuOnglet" + split + "-" + i).style.display = "none";

			// Puis on affiche celui qui a été sélectionné
			document.getElementById("contenuOnglet" + split + "-" + numero).style.display = "block";
				}
	</script>

	<style>
	#onglets
	{
		font : bold 24px Batang, arial, serif;
		list-style-type : none;
		padding-bottom : 39px; /* à modifier suivant la taille de la police ET de la hauteur de l'onglet dans #onglets li */
		border-bottom : 1px solid #9EA0A1;
		margin-left : 0;
	}
	#onglets li
	{
		float : left;
		height : 36px; /* à modifier suivant la taille de la police pour centrer le texte dans l'onglet */
		background-color: #F4F9FD;
		margin : 2px 2px 0 2px !important;  /* Pour les navigateurs autre que IE */
		margin : 1px 2px 0 2px;  /* Pour IE  */
		border : 1px solid #9EA0A1;
	}
	#onglets a
	{
		display : block;
		color : #666;
		text-decoration : none;
		padding : 4px;
	}
	#onglets a:hover
	{
		background : #CECEF6;
	}
	</style>



	</head>
<body>
	<?php
	Require("../fonctions.php");
	Connectionsql();
	?>





	<?php

$req_split = $db->query("SELECT id_tbljob, id_statut, customer, job, split, type_essai FROM tbljobs LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai WHERE job=(select job from tbljobs where id_tbljob=".$_GET['id_job'].") ORDER BY split;");
		
		while ($tbl_split = mysqli_fetch_array($req_split)) {
			$tblsplit[]=$tbl_split;
		}
	$nombresplit=count($tblsplit)+1;
?>

<div id="menu">
	<ul id="onglets">
	<?php
	echo '<li><a><span onclick="changeSplit(0, '.$nombresplit.')">&nbsp;&nbsp;   GLOBAL  &nbsp;&nbsp;</span></a></li>
	';
	for($i=0;$i < count($tblsplit);$i++)	{
		$j=$i+1;
		echo '<li><a><span onclick="changeSplit('.$j.', '.$nombresplit.')">&nbsp;&nbsp;  '.$tblsplit[$i]['split'].' - '.$tblsplit[$i]['type_essai'].'  &nbsp;&nbsp;</span></a></li>
	';}
	?></ul>
</div>

<div id="contenuSplit0" style="display:block;">	<!--GLOBAL-->
	DONNEES globale du job:
	<br/>
	contact (nom email phone)
	<br/>
	delai
	<br/>
	onenote<a title="Partage et stockage des informations diverses liées à un Job. Requete, spécifications, Emails clients, Phonecall, croquis,...&#10;&#10;Lorsque le JOB est Clos, la section doit etre déplacé dans Notebook-JOBS Archivés" class="static menu-item ms-core-listMenu-item ms-displayInline ms-navedit-linkNode" href="https://metcut.sharepoint.com/MRSAS - Metcut France/SiteAssets/Notebook-JOBS En Cours"><span class="additional-background ms-navedit-flyoutArrow"><span class="menu-item-text">Notebook-JOBS En Cours</span></span></a>
	<br/>
	PO & Instruction
	<br/>
	Commentaire ? (si oui, faire une table id_com, job(13000), com)
</div>




<!--<div id="contenuSplit0" style="display:block;">	<!--SPLIT-->
<?php
for($splitencours=1;$splitencours <= count($tblsplit);$splitencours++)	{
?>

	<div id="contenuSplit<?php echo $splitencours;?>" style="display:none;">	<!--split <?php echo $splitencours;?>-->
		<div id="menu">
		  <ul id="onglets">
			<li><a><span onclick="changeOnglet(<?php echo $splitencours;?>,0)">&nbsp;&nbsp;   Données du job  &nbsp;&nbsp;</span></a></li>
			<li><a><span onclick="changeOnglet(<?php echo $splitencours;?>,1)">&nbsp;&nbsp;  Eprouvettes  &nbsp;&nbsp;</span></a></li>
		  </ul>
		</div>

		<div id="contenuOnglet<?php echo $splitencours; ?>-0" style="display:block;">	<!--Données du job-->

			<form action="../index.php?page=tbljobs_maj" method="POST">
				<div>
					<button type="submit">Mise à jour des données</button>
				</div>

				<?php

				$titre=array('statut',	'customer',	'job',	'split',	'PO_instructions',	'type essai',	'cond.s',	'drawing',	'comments',	'nb_specimen', 'type_feuille', 'nb_type_feuille', 'tooling',	'MRI_req',	'MFG_qty',	'nb_MRI',	'sub_C',	'type_machine',	'nb_test_MRSAS',	'ordre',	'blanks_reception',	'blanks_shipment',	'specimen_leadtime',	'specimen_reception',	'test_start',	'test_end',	'test_leadtime',	'estimated_turn_over',	'estimated_testing',	'invoiced_turn_over',	'invoiced_testing');
				$titresql=array('id_statut',	'customer',	'job',	'split',	'PO_instructions',	'id_type_essai',	'id_condition_temps',	'drawing',	'comments',	'nb_specimen', 'type_feuille', 'nb_type_feuille', 'tooling',	'MRI_req',	'MFG_qty',	'nb_MRI',	'sub_C',	'type_machine',	'nb_test_MRSAS',	'ordre',	'blanks_reception',	'blanks_shipment',	'specimen_leadtime',	'specimen_reception',	'test_start',	'test_end',	'test_leadtime',	'estimated_turn_over',	'estimated_testing',	'invoiced_turn_over',	'invoiced_testing');

					$req="SELECT id_tbljob,	id_statut,	customer,	job,	split,	PO_instructions,	id_type_essai,	id_condition_temps,	drawing,	comments,	nb_specimen, type_feuille, nb_type_feuille,	tooling,	MRI_req,	MFG_qty,	nb_MRI,	sub_C,	type_machine,	nb_test_MRSAS,	ordre,	blanks_reception,	blanks_shipment,	specimen_leadtime,	specimen_reception,	test_start,	test_end,	test_leadtime,	estimated_turn_over,	estimated_testing,	invoiced_turn_over,	invoiced_testing
					FROM tbljobs where id_tbljob=".$tblsplit[$splitencours-1]['id_tbljob'].";";
					
				$req_tbljobs = $db->query($req);
				
				$tbljobs= array();
				while ($tbl_req = mysqli_fetch_array($req_tbljobs)) {
					$tbljobs[]=$tbl_req;
				}	
				?>
				
				<table>
					<tr>
						<td>
							<table id="table_id" class="display" cellspacing="0">
								<tbody>						
									<?php
									if ($tbljobs) {
										for($k=0;$k < count($tbljobs);$k++)	{
											
											
											$color="white";
											$color=($tbljobs[$k]['id_statut']>=00)? "#FA5858" : $color;
											$color=($tbljobs[$k]['id_statut']>=10)? "#F79F81" : $color;
											$color=($tbljobs[$k]['id_statut']>=20)? "#FF8000" : $color;
											$color=($tbljobs[$k]['id_statut']>=50)? "#F3F781" : $color;
											$color=($tbljobs[$k]['id_statut']>=70)? "#9FF781" : $color;
											$color=($tbljobs[$k]['id_statut']>=80)? "#04B404" : $color;
														
											for($j=0;$j < count($titresql);$j++)	{
												echo '<tr>';
												echo '<td  bgcolor="'.$color.'">'.$titre[$j].'</td>';
												echo '<td>
												<input id="row-'.$k.$j.'1-age" name="'.$tbljobs[$k]['id_tbljob']."-".$titresql[$j].'" value="'.$tbljobs[$k][$titresql[$j]].'" type="text"></td></tr>
												';
											}
										}
									}		
									?>
								</tbody>
							</table>
						</td>

					</tr>
				</table>
			</form>
		</div>

		<div id="contenuOnglet<?php echo $splitencours; ?>-1" style="display:none;">	<!--Eprouvettes-->
			
				<?php 
				$req_ep = $db->query("SELECT 
				eprouvettes.id_eprouvette, prefixe, nom_eprouvette, temperature, n_essai, n_fichier, assigne, machine, DATE_FORMAT(enregistrementessais.date,'%d %b %y') as date, technicien, frequence, rapport, deltaepsilon, epsilonmax, niveau_max, niveau_moy, niveau_alt, niveau_min, cycle_min,c1_E_montant,	c1_E_descendant,	c1_max_strain,	c1_min_strain,	c1_max_stress,	c1_min_stress,	c1_calc_inelastic_strain,	c1_meas_inelastic_strain,	c2_E_montant,	c2_E_descendant,	c2_max_strain,	c2_min_strain,	c2_max_stress,	c2_min_stress,	c2_calc_inelastic_strain,	c2_meas_inelastic_strain,	Ni,	Nf75, check_data, flag_qualite

				FROM eprouvettes 
				LEFT JOIN enregistrementessais ON eprouvettes.id_eprouvette = enregistrementessais.id_eprouvette
				LEFT JOIN postes ON enregistrementessais.id_poste=postes.id_poste
				LEFT JOIN machines ON postes.id_machine=machines.id_machine
				LEFT JOIN techniciens ON enregistrementessais.id_operateur=techniciens.id_technicien
				WHERE id_job =".$_GET['id_job']." ORDER BY id_eprouvette ;") or die (mysql_error());
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
	</div>

<?php	}	?>


