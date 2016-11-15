<Script type="Text/JavaScript">
function cacher(lst)
{
	var d=document.getElementById("load");
	var e=document.getElementById("strain");
	if ( lst.selectedIndex==1)	{
		d.style.visibility="hidden";
		e.style.visibility="visible";
	}
	else	{
		e.style.visibility="hidden";
		d.style.visibility="visible";
	}
}
</Script>


<?php		
if (isset($_GET['job']))	{		//recuperation des données du job
	
	$req_job = mysql_query("
			SELECT id_job, n_client, n_job, indice, control, jobs.id_type_essai, type_essai, temperature, rapport, frequence, format, matiere, forme_cycle, DATE_FORMAT( date, '%d %b %y' ) AS date, STL, F_STL, suivi_extenso, arret, arret_cycle, termine, job_commentaire, tech1.technicien AS preparateur, tech2.technicien AS controleur
			FROM jobs
			LEFT JOIN type_essais ON jobs.id_type_essai = type_essais.id_type_essai
			LEFT OUTER JOIN techniciens tech1 ON jobs.preparateur = tech1.id_technicien
			LEFT OUTER JOIN techniciens tech2 ON jobs.controleur = tech2.id_technicien
			WHERE id_job =".$_GET['job'].";") or die (mysql_error());
	$tbl_job = mysql_fetch_assoc($req_job);
	if (isset($tbl_job['indice']))
		$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'].'-'.$tbl_job['indice'];
	else
		$jobcomplet= $tbl_job['n_client'].'-'.$tbl_job['n_job'];

?>












<?php			//action si modif
if (isset($_POST['modif']))	{

				// $niveaumax=(isset($_POST['niveaumax']) AND $_POST['niveaumax']!="")? '"'.$_POST['niveaumax'].'"' : 'NULL';
	$id_type_essai=(isset($_POST['type_essai']) AND $_POST['type_essai']!="") ? '"'.$_POST['type_essai'].'"' : 'NULL';
	$control=(isset($_POST['control']) AND $_POST['control']!="")? '"'.$_POST['control'].'"' :'NULL';
	$temperature=(isset($_POST['temperature']) AND $_POST['temperature']!="")? '"'.$_POST['temperature'].'"' :'NULL';
	$type_essai=(isset($_POST['type_essai']) AND $_POST['type_essai']!="")? '"'.$_POST['type_essai'].'"' :'NULL';
	$date=(isset($_POST['date']) AND $_POST['date']!="")? 'STR_TO_DATE("'.$_POST['date'].'", "%d %b %y")' :'NULL';
	$rapport=(isset($_POST['rapport']) AND $_POST['rapport']!="")? '"'.$_POST['rapport'].'"' :'NULL';
	$matiere=(isset($_POST['matiere']) AND $_POST['matiere']!="")? '"'.$_POST['matiere'].'"' :'NULL';
	$preparateur=(isset($_POST['preparateur']) AND $_POST['preparateur']!="")? '"'.$_POST['preparateur'].'"' :'NULL';
	$frequence=(isset($_POST['frequence']) AND $_POST['frequence']!="")? '"'.$_POST['frequence'].'"' :'NULL';
	$format=(isset($_POST['format']) AND $_POST['format']!="")? '"'.$_POST['format'].'"' :'NULL';
	$forme=(isset($_POST['forme']) AND $_POST['forme']!="")? '"'.$_POST['forme'].'"' :'NULL';
	$controleur=(isset($_POST['controleur']) AND $_POST['controleur']!="")? '"'.$_POST['controleur'].'"' :'NULL';
	$stl=(isset($_POST['stl']) AND $_POST['stl']!="")? '"'.$_POST['stl'].'"' :'NULL';
	$fstl=(isset($_POST['fstl']) AND $_POST['fstl']!="")? '"'.$_POST['fstl'].'"' :'NULL';
	$suivi=(isset($_POST['suivi']) AND $_POST['suivi']!="")? '"'.$_POST['suivi'].'"' :'NULL';
	$arret=(isset($_POST['arret']) AND $_POST['arret']!="")? '"'.$_POST['arret'].'"' :'NULL';
	$rupture=(isset($_POST['rupture']) AND $_POST['rupture']!="")? '"'.$_POST['rupture'].'"' :'NULL';
	$job_commentaire=(isset($_POST['job_commentaire']) AND $_POST['job_commentaire']!="")? '"'.mysql_escape_string(addslashes($_POST['job_commentaire'])).'"' :'NULL';




				


	echo '<br/><br/>';
	
	$modifjob='UPDATE metcut.jobs SET
	id_type_essai='.$type_essai.', 
	control='.$control.', 
	temperature='.$temperature.', 
	id_type_essai='.$type_essai.', 
	date='.$date.', 
	rapport='.$rapport.', 
	matiere='.$matiere.', 
	preparateur='.$preparateur.', 
	frequence='.$frequence.', 
	format='.$format.', 
	forme_cycle='.$forme.', 
	controleur='.$controleur.', 
	stl='.$stl.', 
	f_stl='.$fstl.', 
	suivi_extenso='.$suivi.', 
	arret='.$arret.', 
	arret_cycle='.$rupture.',
	job_commentaire='.$job_commentaire.'

		WHERE jobs.id_job ='.$_GET['job'];


	envoilog('jobs','id_job',$_GET['job'],$modifjob);
//----mysql_query($modifjob);
	
echo	'<form method=POST id="test" action="index.php?page=listejob">
<input type="hidden" name="job" value="'.$_GET['job'].'"/>
</form>
<script type="text/javascript">
	document.getElementById("test").submit()
</script>';
}
?>





	<FORM method="POST" name="modifjob">
		<div id="jobTop">
			<table class="job1">
				<tr>
					<td colspan="4" align="right">FICHE DE DONNEES D'ESSAIS 
						<select name="type_essai">
							<?php 
							$req_type_essai = mysql_query('SELECT id_type_essai, type_essai FROM type_essais WHERE type_essai_actif=1;') or die (mysql_error());
							if ($req_type_essai) {
								while ($tbl_type_essai = mysql_fetch_assoc($req_type_essai)) {									
									if($tbl_job['type_essai'] == $tbl_type_essai['type_essai'])
										echo '<option value="'.$tbl_type_essai['id_type_essai'].'" selected>'.$tbl_type_essai['type_essai'].'</option>';
									else
										echo '<option value="'.$tbl_type_essai['id_type_essai'].'">'.$tbl_type_essai['type_essai'].'</option>';
								}
								
							}		
							?>
						</select>
						en 
						<select name="control" onchange="cacher(this);"><?php echo ($tbl_job['control']=='LOAD') ? '<option value="LOAD" selected>LOAD</option><option value="STRAIN">STRAIN</option>'	: '<option value="LOAD">LOAD</option><option value="STRAIN" selected>STRAIN</option>'; ?></select>
						Control</td>
					<td align="right">n° Travail :</td>
					<td><?php echo $jobcomplet;?></td>
				</tr>
				<tr>
					<td width="15%">Température</td>
					<td width="15%"><input name="temperature" type="text" size="10" value="<?php echo $tbl_job['temperature'];?>"></td>
					<td width="15%">type éprouvette</td>
					<td width="15%"><input name="format" type="text" size="10" value="<?php echo $tbl_job['format'];?>"></td>
					<td width="15%">Date</td>
					<td width="15%"><input name="date" type="text" size="10" value="<?php echo $tbl_job['date'];?>"></td>
				</tr>
				<tr>
					<td width="15%">Rapport R</td>
					<td width="15%"><input name="rapport" type="text" size="10" value="<?php echo $tbl_job['rapport'];?>"></td>
					<td width="15%">Matière</td>
					<td width="15%"><input name="matiere" type="text" size="10" value="<?php echo $tbl_job['matiere'];?>"></td>
					<td width="15%">Préparateur</td>
					<td width="15%">
						<select name="preparateur">
							<?php 
							$req_operateur = mysql_query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1;') or die (mysql_error());
							if ($req_operateur) {
								echo '<option value="-">-</option>';
								while ($tbl_operateur = mysql_fetch_assoc($req_operateur)) {
									
									if($tbl_job['preparateur'] == $tbl_operateur['technicien'])
										echo '<option value="'.$tbl_operateur['id_technicien'].'" selected>'.$tbl_operateur['technicien'].'</option>';
									
									if ($_POST['preparateur'] == $tbl_operateur['id_technicien']) {
										echo '<option value="'.$tbl_operateur['id_technicien'].'" selected>'.$tbl_operateur['technicien'].'</option>';
									} else {
										echo '<option value="'.$tbl_operateur['id_technicien'].'">'.$tbl_operateur['technicien'].'</option>';
									}
								}
							}		
							?>
						</select></td>
				</tr>
				<tr>
					<td width="15%">Fréquence (Hz)</td>
					<td width="15%"><input name="frequence" type="text" size="10" value="<?php echo $tbl_job['frequence'];?>"></td>
					<td width="15%">Forme Cycle</td>
					<td width="15%"><input name="forme" type="text" size="10" value="<?php echo $tbl_job['forme_cycle'];?>"></td>
					<td width="15%">Contrôleur</td>
					<td width="15%">
						<select name="controleur">
							<?php 
							$req_operateur = mysql_query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1;') or die (mysql_error());
							if ($req_operateur) {
								echo '<option value="-">-</option>';
								while ($tbl_operateur = mysql_fetch_assoc($req_operateur)) {
									
									if($tbl_job['controleur'] == $tbl_operateur['technicien'])
										echo '<option value="'.$tbl_operateur['id_technicien'].'" selected>'.$tbl_operateur['technicien'].'</option>';
									
									if ($_POST['controleur'] == $tbl_operateur['id_technicien']) {
										echo '<option value="'.$tbl_operateur['id_technicien'].'" selected>'.$tbl_operateur['technicien'].'</option>';
									} else {
										echo '<option value="'.$tbl_operateur['id_technicien'].'">'.$tbl_operateur['technicien'].'</option>';
									}
								}
							}						
							?>
						</select></td>
				</tr>
			</table>
		</div>
		
		<!--				style="visibility:hidden"	-->
		<div id="jobBottom">
			<table class="job3">

				<tr id="strain" <?php echo ($tbl_job['control']=='LOAD') ? 'style="visibility:hidden"' : '' ;?>>
					<td width="25%">Passage Contrôle Effort :</td>
					<td width="25%" align="center"><input name="stl" type="text" size="10" value="<?php echo ($tbl_job['STL']!=0)? $tbl_job['STL'] : ""; ?>"> (Cycles)</td>
					<td width="25%" align="center">Fréquence :</td>
					<td width="25%"><input name="fstl" type="text" size="10" value="<?php echo ($tbl_job['F_STL']!=0)? $tbl_job['F_STL'] : ""; ?>"> Hz</td>
				</tr>

				<tr id="load" <?php echo ($tbl_job['control']=='STRAIN') ? 'style="visibility:hidden"' : '' ;?>>
					<td width="25%">Suivi extensométrique :</td>
					<td align="center"><input type="radio" name="suivi" value="0" checked> NON</td>
					<td align="center"><input type="radio" name="suivi" value="1"<?php echo ($tbl_job['suivi_extenso']==1)? "checked" : ""; ?>> OUI</td>
				</tr>

				<tr>
					<td>Arrêt des essais :</td>
					<td align="center"><input type="radio" name="arret" value="0" checked>Rupture</td>
					<td align="center"><input type="radio" name="arret" value="1"<?php echo ($tbl_job['arret']==1)? "checked" : ""; ?>>Après :</td>
					<td><input name="rupture" type="text" size="10" value="<?php echo ($tbl_job['arret']==1)? $tbl_job['arret_cycle'] : ""; ?>"> cycles</td>
				</tr>
			</table>
		
			<table class="job3">	
				<tr>
					<td><b>Commentaire :</b></td>
				</tr>
				<tr>
					<td id="commentaire"><textarea name="job_commentaire" COLS=80 ROWS=4><?php echo stripslashes($tbl_job['job_commentaire']); ?></TEXTAREA></td>
				</tr>
			</table>
		
		</div>		
		
		<input type="hidden" value=1 name="modif">
		<input type="submit" value="modif">
	</FORM>
<?php
}
?>


<!--<a href="javascript:if(confirm('Etes vous sur ?')) document.location.href='mon_lien.htm'">Mon lien</a> -->

