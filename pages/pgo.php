<table>
	<FORM method="GET" name="datasynthese" action="index.php#menu">
		<tr>
			<th>Client</th>
			<th>Colonne</th>			
			<th>Debut</th>
			<th>Fin</th>
		</tr>
		<tr>
			<td><select name="client">		
				<?php
					$req_client = mysql_query("SELECT distinct n_client FROM jobs ORDER by n_client ASC;") or die (mysql_error());
				  if ($req_client) {
					echo '<option value="%">-</option>';
					while ($tbl_client = mysql_fetch_assoc($req_client)) {
						if ($_GET['client'] == $tbl_client['n_client']) {
							echo '<option value="'.$tbl_client['n_client'].'" selected>'.$tbl_client['n_client'].'</option>';
						} else {
							echo '<option value="'.$tbl_client['n_client'].'">'.$tbl_client['n_client'].'</option>';
						}
					}
				  }
				?>
				</select>
			</td>
			<td><select name="groupe">	
					<option value="month">-</option>
				<?php
					$list=array("year","month","week","day");
					foreach ($list as $value)	{
						if($_GET['groupe']==$value)
							echo '<option value="'.$value.'" selected>'.$value.'</option>';
						else
							echo '<option value="'.$value.'">'.$value.'</option>';		
					}
				?>
				</select>
			</td>
			<td><INPUT type=text id="calendar-field" name="debut" size=10 value="
				<?php	
					if (isset($_GET['debut']))
						echo $_GET['debut'];
				?>
				">
			</td>
			<td><INPUT type=text id="calendar-field2" name="fin" size=10 value="
				<?php	
					if (isset($_GET['fin']))
						echo $_GET['fin'];
				?>
				">
			</td>		
			<td><input type="submit" value="Afficher">
			<input type="hidden" name="page" value="synthese">				
			</td>
		</tr>
	</FORM>
			<tr align=center>
				<td></td>
				<td></td>
				<td><button id="calendar-trigger">...</button>
					<script>
					Calendar.setup({
						inputField : "calendar-field",
						trigger    : "calendar-trigger",
						onSelect   : function() { this.hide() }
					});
					</script></td>
				<td><button id="calendar-trigger2">...</button>
					<script>
					Calendar.setup({
						inputField : "calendar-field2",
						trigger    : "calendar-trigger2",
						onSelect   : function() { this.hide() }
					});
					</script></td>
			</tr>
</table>





<?php
	$debut=(isset($_GET['debut']) AND $_GET['debut']!="")? $_GET['debut'] : date("Y-m-d", mktime(0, 0, 0, date("m"),  1,  date("Y")-1));
	$fin=(isset($_GET['fin']) AND $_GET['fin']!="")? $_GET['fin'] : date("Y-m-d", mktime(0, 0, 0, date("m")+1,  0,  date("Y")));
	$groupe=(isset($_GET['groupe']) AND $_GET['groupe']!="")? $_GET['groupe'] : "month";
	$client=(isset($_GET['client']) AND $_GET['client']!="")? $_GET['client'] : "%";

	$clienttitre=($client=="%")? "" : " pour ".$client;
	//utilisation de 'where n_client like '.$client.'


	if ($groupe=="year")	{
		$groupe1="date_format(enregistrementessais.date,'%y') as year,";
		$groupe2="year( enregistrementessais.date )";
	}
	if ($groupe=="month")	{
		$groupe1="date_format(enregistrementessais.date,'%y') as year,
			date_format(enregistrementessais.date,'%b %y') as month,";
		$groupe2="year( enregistrementessais.date ), month( enregistrementessais.date )";
	}
	if ($groupe=="week")	{
		$groupe1="date_format(enregistrementessais.date,'%y') as year,
			date_format(enregistrementessais.date,'%u %y') as week, ";
		$groupe2="year( enregistrementessais.date ), week( enregistrementessais.date )";
	}
	if ($groupe=="day")	{
		$groupe1="date_format(enregistrementessais.date,'%y') as year,
			date_format(enregistrementessais.date,'%b %y') as month,
			date_format(enregistrementessais.date,'%d %b %y') as day,";
		$groupe2="year( enregistrementessais.date ), month( enregistrementessais.date ), day(enregistrementessais.date)";
	}

	
	$sql_synthese='
	SELECT '.$groupe1.'
		sum( if (control = "STRAIN",1,0)) as "STRAIN",
		sum( if (control= "LOAD",1,0)) as "LOAD",

		jobs.id_job, n_fichier, control, type_essai, eprouvettes.temperature, n_client, n_job, indice, n_essai, nom_eprouvette, machine, acquisition, enregistrementessais.date, tech1.technicien AS operateur, tech2.technicien AS controleur
		FROM enregistrementessais
		LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
		LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
		LEFT JOIN type_essais ON jobs.id_type_essai = type_essais.id_type_essai
		LEFT JOIN postes ON postes.id_poste=enregistrementessais.id_poste
		LEFT JOIN machines ON postes.id_machine = machines.id_machine
		LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition
		LEFT OUTER JOIN techniciens tech1 ON enregistrementessais.id_operateur = tech1.id_technicien
		LEFT OUTER JOIN techniciens tech2 ON enregistrementessais.id_controleur = tech2.id_technicien
		
		WHERE enregistrementessais.date between "'.$debut.'" and "'.$fin.'" AND n_client LIKE "'.$client.'"
		GROUP BY '.$groupe2;

    $req_synthese = mysql_query($sql_synthese) or die (mysql_error());
	if ($req_synthese) {
		while ($tbl_synthese = mysql_fetch_assoc($req_synthese)) {
			$synthese[$tbl_synthese[$groupe]]['STRAIN']=$tbl_synthese['STRAIN'];
			$synthese[$tbl_synthese[$groupe]]['LOAD']=$tbl_synthese['LOAD'];
		}
	}

	$j=0;
foreach ($synthese as $key => $value) {
    $STRAIN[$j] = $value['STRAIN'];
	$LOAD[$j] = $value['LOAD'];
	$TOTAL[$j] = $value['STRAIN'] + $value['LOAD'];
	$interval[$j]=$key;
	$j++;
}		
?>
<?php echo $client; ?>
<table><TR VALIGN=TOP><td>
<img src="Graph/pgo.php?debut=<?php echo $debut; ?>&fin=<?php echo $fin; ?>&groupe=<?php echo $groupe; ?>&client=<?php echo $client; ?>">
</td><td>
<!-- Bouton pour status snecma -->
<input type="button" name="StatusSnecma" value="Status Snecma" onclick="self.location.href='index.php?page=statussnecma'" style="background-color:#3cb371" style="color:white; font-weight:bold; font-size:250%"onclick> 
</td></tr>
</table>

<?php

echo '<table class="job2"><tr><th>Date</th>';
foreach ($synthese as $key => $value)	{
	echo '<th>'.$key.'</th>';
}
echo '<th>TOTAL</th></tr><tr><th>Nombre d\'éprouvette</th>';
$somme=0;
foreach ($synthese as $key => $value)	{
	echo '<td>'.($value['STRAIN']+$value['LOAD']).'</td>';
	$somme=$somme+($value['STRAIN']+$value['LOAD']);
}
echo '<th>'.$somme.'</th></tr></table>';	
?>

<div class="todo">TODO :<br/>
bouton envoi excel</br>
</div>