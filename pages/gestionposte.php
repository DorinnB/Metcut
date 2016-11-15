<?php			//insertion modification
if (isset($_POST['modifposte']) AND $_POST['modifposte']="1")	{
	extract($_POST);
	$compresseur=(isset($compresseur))? "1" : "0";
			//Enregistrement du nouveau poste
	$ajoutposte='INSERT INTO postes (id_machine, id_enregistreur, id_outillage_top, id_outillage_bot, id_extensometre, cartouche_load, cartouche_stroke, cartouche_strain, id_chauffage, id_ind_temp_top, id_ind_temp_strap, id_ind_temp_bot, compresseur)
	VALUES ('.$id_machine.', '.$id_enregistreur.', '.$id_outillage_top.', '.$id_outillage_bot.', '.$id_extensometre.', "'.$cartouche_load.'", "'.$cartouche_stroke.'", "'.$cartouche_strain.'", '.$id_chauffage.', '.$id_ind_temp_top.', '.$id_ind_temp_strap.', '.$id_ind_temp_bot.', '.$compresseur.')';
	mysql_query($ajoutposte);
	header("Location: index.php?page=gestionposte");
}
?>

<div id="choixmachine">Choix poste			<!--choix du poste(machine)-->
<form method="post" name="choixmachine">
	<select name="id_poste" onchange="document.choixmachine.submit()">
	<?php
		$req_machine = mysql_query('SELECT t1.id_poste, t1.id_machine, machine
								FROM postes t1
								LEFT JOIN machines ON machines.id_machine = t1.id_machine
								WHERE t1.id_poste = ( 
								SELECT MAX( t2.id_poste ) 
								FROM postes t2
								WHERE t2.id_machine = t1.id_machine ) 
								ORDER BY t1.id_machine
								;') or die (mysql_error());
	if ($req_machine) {
		echo '<option value="-">-</option>';
		while ($tbl_machine = mysql_fetch_assoc($req_machine)) {
			if ($_POST['id_poste'] == $tbl_machine['id_poste']) {
				echo '<option value="'.$tbl_machine['id_poste'].'" selected>'.$tbl_machine['machine'].'</option>';
			} else {
				echo '<option value="'.$tbl_machine['id_poste'].'">'.$tbl_machine['machine'].'</option>';
			}
		}
	  }
	?>
	</select>
</form>
</div>


<?php										//info du poste
if (isset($_POST['id_poste']) AND $_POST['id_poste']!="-")	{
																	//recuperation des données de la machine
	$req_poste = mysql_query("
		SELECT * 
		FROM postes
		WHERE id_poste =".$_POST['id_poste']) or die (mysql_error());
	$tbl_poste = mysql_fetch_assoc($req_poste);
?>
	<div id="machine">
		<form method="post" name="modifposte">
		<table>
			<tr valign=top>
					<td>
					<table class="job2">			<!--Pleine echelle-->
						<th colspan="2">Pleine echelle</th>
						<?php 
							$titre=array('Stroke (mm)', 'Load (kN)', 'Strain (%)');
							$titresql=array('cartouche_stroke', 'cartouche_load', 'cartouche_strain');
							for($i=0; $i<count($titre); $i++){
								echo '
									<tr>
										<td>'.$titre[$i].'</td><td>';
								$req_enum = mysql_query('SHOW COLUMNS FROM postes LIKE "'.$titresql[$i].'" ;') or die (mysql_error());							
								$cartouchearray=mysql_fetch_assoc($req_enum);
								$cartouche=explode(',',str_replace(array ("'", "enum(",')'),"",$cartouchearray['Type']));

								echo '<select name="'.$titresql[$i].'"><option value="-">-</option>';
								Foreach ($cartouche as $c) {
									if ($tbl_poste[$titresql[$i]] == $c) {
										echo '<option value="'.$c.'" selected>'.$c.'</option>';
									} else {
										echo '<option value="'.$c.'">'.$c.'</option>';
									}
								}
								echo '</select></td></tr>';

							}
						?>
					</table>
				</td>
				<td>
					<table class="job2">			<!--	liste des differents equipements-->
						<th colspan="2">Equipements</th>
						<tr><td>Enregistreur</td><td><select name="id_enregistreur">
						<?php
						$req_enregistreur = mysql_query('SELECT id_enregistreur, enregistreur FROM enregistreurs WHERE enregistreur_actif=1 ORDER BY enregistreur;') or die (mysql_error());
						if ($req_enregistreur) {
							echo '<option value="NULL">-</option>';
							while ($tbl_enregistreur = mysql_fetch_assoc($req_enregistreur)) {
								
								if ($tbl_poste['id_enregistreur'] == $tbl_enregistreur['id_enregistreur']) {
									echo '<option value="'.$tbl_enregistreur['id_enregistreur'].'" selected>'.$tbl_enregistreur['enregistreur'].'</option>';
								} else {
									echo '<option value="'.$tbl_enregistreur['id_enregistreur'].'">'.$tbl_enregistreur['enregistreur'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>
						<tr><td>Extensomètre</td><td><select name="id_extensometre">
						<?php
						$req_extensometre = mysql_query('SELECT id_extensometre, extensometre FROM extensometres WHERE extensometre_actif=1 ORDER BY extensometre;') or die (mysql_error());
						if ($req_extensometre) {
							echo '<option value="NULL">-</option>';
							while ($tbl_extensometre = mysql_fetch_assoc($req_extensometre)) {
								
								if ($tbl_poste['id_extensometre'] == $tbl_extensometre['id_extensometre']) {
									echo '<option value="'.$tbl_extensometre['id_extensometre'].'" selected>'.$tbl_extensometre['extensometre'].'</option>';
								} else {
									echo '<option value="'.$tbl_extensometre['id_extensometre'].'">'.$tbl_extensometre['extensometre'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>						
						</table>
				</td>
				<td>
					<table class="job2">			<!--	liste des differents outillages-->
						<th colspan="2">Outillages</th>
						<tr><td>Top</td><td><select name="id_outillage_top">
						<?php
						$req_outillage = mysql_query("SELECT id_outillage, outillage
							FROM outillages
							WHERE outillage_actif =1
							ORDER BY SUBSTRING_INDEX( outillage,  '-', 1 ) ASC , SUBSTRING_INDEX( outillage,  '-', 2 ) ASC , 00+SUBSTRING_INDEX( outillage,  '-', -1 ) ASC") or die (mysql_error());
						if ($req_outillage) {
							echo '<option value="NULL">-</option>';
							while ($tbl_outillage = mysql_fetch_assoc($req_outillage)) {
								
								if ($tbl_poste['id_outillage_top'] == $tbl_outillage['id_outillage']) {
									echo '<option value="'.$tbl_outillage['id_outillage'].'" selected>'.$tbl_outillage['outillage'].'</option>';
								} else {
									echo '<option value="'.$tbl_outillage['id_outillage'].'">'.$tbl_outillage['outillage'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>
						<tr><td>Bot</td><td><select name="id_outillage_bot">
						<?php
						$req_outillage = mysql_query("SELECT id_outillage, outillage
							FROM outillages
							WHERE outillage_actif =1
							ORDER BY SUBSTRING_INDEX( outillage,  '-', 1 ) ASC , SUBSTRING_INDEX( outillage,  '-', 2 ) ASC , 00+SUBSTRING_INDEX( outillage,  '-', -1 ) ASC") or die (mysql_error());
						if ($req_outillage) {
							echo '<option value="NULL">-</option>';
							while ($tbl_outillage = mysql_fetch_assoc($req_outillage)) {
								
								if ($tbl_poste['id_outillage_bot'] == $tbl_outillage['id_outillage']) {
									echo '<option value="'.$tbl_outillage['id_outillage'].'" selected>'.$tbl_outillage['outillage'].'</option>';
								} else {
									echo '<option value="'.$tbl_outillage['id_outillage'].'">'.$tbl_outillage['outillage'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>
					</table>
				</td>
				<td>
					<table class="job2">			<!--	liste des differents equipements de temperature-->
						<th colspan="2">Chauffage</th>
						<tr><td>Chauffage</td><td><select name="id_chauffage">
						<?php
						$req_chauffage = mysql_query('SELECT id_chauffage, chauffage FROM chauffages WHERE chauffage_actif=1 ORDER BY chauffage;') or die (mysql_error());
						if ($req_chauffage) {
							echo '<option value="NULL">-</option>';
							while ($tbl_chauffage = mysql_fetch_assoc($req_chauffage)) {
								
								if ($tbl_poste['id_chauffage'] == $tbl_chauffage['id_chauffage']) {
									echo '<option value="'.$tbl_chauffage['id_chauffage'].'" selected>'.$tbl_chauffage['chauffage'].'</option>';
								} else {
									echo '<option value="'.$tbl_chauffage['id_chauffage'].'">'.$tbl_chauffage['chauffage'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>
						<tr><td>Ind. Temp Top</td><td><select name="id_ind_temp_top">
						<?php
						$req_ind_temp = mysql_query('SELECT id_ind_temp, ind_temp FROM ind_temps WHERE ind_temp_actif=1 ORDER BY ind_temp;') or die (mysql_error());
						if ($req_ind_temp) {
							echo '<option value="NULL">-</option>';
							while ($tbl_ind_temp = mysql_fetch_assoc($req_ind_temp)) {
								
								if ($tbl_poste['id_ind_temp_top'] == $tbl_ind_temp['id_ind_temp']) {
									echo '<option value="'.$tbl_ind_temp['id_ind_temp'].'" selected>'.$tbl_ind_temp['ind_temp'].'</option>';
								} else {
									echo '<option value="'.$tbl_ind_temp['id_ind_temp'].'">'.$tbl_ind_temp['ind_temp'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>
						<tr><td>Ind. Temp Strap</td><td><select name="id_ind_temp_strap">
						<?php
						$req_ind_temp = mysql_query('SELECT id_ind_temp, ind_temp FROM ind_temps WHERE ind_temp_actif=1 ORDER BY ind_temp;') or die (mysql_error());
						if ($req_ind_temp) {
							echo '<option value="NULL">-</option>';
							while ($tbl_ind_temp = mysql_fetch_assoc($req_ind_temp)) {
								
								if ($tbl_poste['id_ind_temp_strap'] == $tbl_ind_temp['id_ind_temp']) {
									echo '<option value="'.$tbl_ind_temp['id_ind_temp'].'" selected>'.$tbl_ind_temp['ind_temp'].'</option>';
								} else {
									echo '<option value="'.$tbl_ind_temp['id_ind_temp'].'">'.$tbl_ind_temp['ind_temp'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>
						<tr><td>Ind. Temp Bot</td><td><select name="id_ind_temp_bot">
						<?php
						$req_ind_temp = mysql_query('SELECT id_ind_temp, ind_temp FROM ind_temps WHERE ind_temp_actif=1 ORDER BY ind_temp;') or die (mysql_error());
						if ($req_ind_temp) {
							echo '<option value="NULL">-</option>';
							while ($tbl_ind_temp = mysql_fetch_assoc($req_ind_temp)) {
								
								if ($tbl_poste['id_ind_temp_bot'] == $tbl_ind_temp['id_ind_temp']) {
									echo '<option value="'.$tbl_ind_temp['id_ind_temp'].'" selected>'.$tbl_ind_temp['ind_temp'].'</option>';
								} else {
									echo '<option value="'.$tbl_ind_temp['id_ind_temp'].'">'.$tbl_ind_temp['ind_temp'].'</option>';
								}
							}
						}
						?>
						</select></td></tr>
						<tr><td>Compresseur</td><td>
						<?php
						if ($tbl_poste['compresseur'] == 1)	{
							echo '<input type="checkbox" name="compresseur" checked >';
						} Else	{
							echo '<input type="checkbox" name="compresseur" >';
						}
						?>
						</td></tr>	
						</table>
				</td>
				<td>
					<table class="job2">			<!--	Date de la modif -->
						<th>Date de la modif</th>
						<tr><td><?php echo $tbl_poste['date']; ?></td></tr>
					</table>
				</td>				
			</tr>
		</table>
		<input type="hidden" name="id_poste" value="<?php echo $_POST['id_poste'];	?>">
		<input type="hidden" name="id_machine" value="<?php echo $tbl_poste['id_machine'];	?>">
		<input type="hidden" name="modifposte" value="1">
		<input type="submit" value="Modification du poste">
		</form>
	</div>

	
	
	<!--historique-->
	<div id="historique">
		<table class="job2">
			<CAPTION>Historique des dernières modifications</CAPTION>
			<tr>
				<th>Date</th><th>Cartouche Stroke (mm)</th><th>Cartouche Load (kN)</th><th>Cartouche Strain (%)</th><th>Enregistreur</th><th>Extensometre</th><th>Outillage Top</th><th>Outillage Bot</th><th>Chauffage</th><th>Ind. Temp Top</th><th>Ind. Temp Strap</th><th>Ind. Temp Bot</th><th>Compresseur</th>
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
				WHERE id_machine='.$tbl_poste['id_machine'].' ORDER BY date DESC LIMIT 1,5') or die (mysql_error());
				if ($req_historique) {
					while ($tbl_historique = mysql_fetch_assoc($req_historique)) {
						$titresql=array('cartouche_stroke', 'cartouche_load', 'cartouche_strain', 'enregistreur', 'extensometre','outillage_top', 'outillage_bot', 'chauffage', 'ind_temp_top', 'ind_temp_strap', 'ind_temp_bot', 'compresseur');
						echo'<tr><td>'.$tbl_historique['date'];
						for($i=0; $i<count($titresql); $i++){
							echo '</td><td>'.$tbl_historique[$titresql[$i]];
						}
						echo '</td></tr>';
					}
				}
			?>
		</table>
	</div>

<?php
}
?>