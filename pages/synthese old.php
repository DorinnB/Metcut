<?php
function est_selectionne($option) {
    for ($i = 0, $c = count($_GET['client']); $i < $c; $i++) {
        if ($_GET['client'][$i] == $option) {
            return TRUE;
        }
    }
    return FALSE;
}
?>


<table>
	<FORM method="GET" name="datasynthese" action="index.php#menu">
		<tr>
			<th>Client</th>
			<th>Colonne</th>			
			<th>Debut</th>
			<th>Fin</th>
		</tr>
		<tr>
			<td><select name="client[]" size="5" multiple>
			<?php
				$req_client = mysql_query("SELECT distinct n_client FROM jobs ORDER by n_client ASC;") or die (mysql_error());
				while ($lstclient = mysql_fetch_array($req_client)){
					$client[] = $lstclient['n_client'];
				}
				echo '<option value="%">-</option>';
				foreach ($client as $k) {
					if (isset($_GET['client']) && est_selectionne($k)) {
						echo '<option selected>' . $k . '</option>';
					} else {
						echo '<option>' . $k . '</option>';
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
	$debut=(isset($_GET['debut']) AND $_GET['debut']!="")? $_GET['debut'] : date("Y-m-d", mktime(0, 0, 0, 1,  1,  date("Y")));
	$fin=(isset($_GET['fin']) AND $_GET['fin']!="")? $_GET['fin'] : date("Y-m-d", mktime(0, 0, 0, 1,  0,  date("Y")+1));
	$groupe=(isset($_GET['groupe']) AND $_GET['groupe']!="")? $_GET['groupe'] : "month";
	
	$client=(isset($_GET['client']) AND $_GET['client']!="")? $_GET['client'] : 'n_client like "%"';
	
if (is_array($client))	{
	$lclient='(n_client like "'.$client[0].'" ';
	Foreach ($client as $key => $value) {
		$lclient = $lclient.' OR n_client like "'.$value.'"'; 
	}
	$lclient = $lclient. ')';
}
Else	{
	$lclient= $client;
}



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

		jobs.id_job, n_fichier, control, type_essai, eprouvettes.temperature, n_client, n_job, indice, n_essai, nom_eprouvette, machine, acquisition, enregistrementessais.date, tech1.technicien AS op�rateur, tech2.technicien AS controleur
		FROM enregistrementessais
		LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
		LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
		LEFT JOIN type_essais ON jobs.id_type_essai = type_essais.id_type_essai
		LEFT JOIN machines ON enregistrementessais.id_machine = machines.id_machine
		LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition
		LEFT OUTER JOIN techniciens tech1 ON enregistrementessais.id_operateur = tech1.id_technicien
		LEFT OUTER JOIN techniciens tech2 ON enregistrementessais.id_controleur = tech2.id_technicien
		
		WHERE enregistrementessais.date between "'.$debut.'" and "'.$fin.'" AND '.$lclient.'
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



<?php												//bidouille pour l'url du graph
$urlclient = "";
if (is_array($client))	{
	Foreach ($client as $key => $value) {
		$urlclient = $urlclient.'&client[]='.$value; 
	}
}
Else	{
	$urlclient= (isset($_GET['client']))? $_GET['client'] : "";
}
?>



<img src="Graph/nbeprouvette.php?debut=<?php echo $debut; ?>&fin=<?php echo $fin; ?>&groupe=<?php echo $groupe; ?>&client=<?php echo $urlclient; ?>">




<?php

echo '<table class="job2"><tr><th>Date</th>';
foreach ($synthese as $key => $value)	{
	echo '<th>'.$key.'</th>';
}
echo '<th>TOTAL</th></tr><tr><th>Nombre d\'�prouvette</th>';
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