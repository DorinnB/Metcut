<script language="javascript">
function popup(fic)
// on ouvre dans une fen�tre le fichier pass� en param�tre.
// cette ouverture peut �tre am�lior�e en passant d'autres
// param�tres que la taille et la position de la fen�tre.
{ window.open(fic,'Choisir','width=400,height=350,top=50,left=50'); }
</script>

<?php
if (isset($_POST['n_fichier']) AND $_POST['n_fichier']!="" AND $_POST['n_fichier']>1)	{
							//recuperation des donnees de l'essai
	$req_essai = mysql_query('
		SELECT prefixe, nom_eprouvette, format, matiere, machine, enregistreur, compresseur, i1.ind_temp as ind_temp_top, i2.ind_temp as ind_temp_strap, i3.ind_temp as ind_temp_bot, extensometre, chauffage, type_chauffage, enregistreur, acquisition, cartouche_load, cartouche_stroke, cartouche_strain,
		n_client, n_job, indice, control, n_essai, n_fichier, DATE_FORMAT(enregistrementessais.date,"%d %b %Y") as date, t1.technicien as operateur, t2.technicien as controleur, eprouvettes.temperature, jobs.rapport, jobs.frequence, forme_cycle, STL, F_STL, Suivi_extenso, Arret_cycle
		FROM enregistrementessais
		LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
		LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
		LEFT JOIN type_essais ON type_essais.id_type_essai = jobs.id_type_essai
		LEFT JOIN acquisitions ON acquisitions.id_acquisition = enregistrementessais.id_acquisition
		LEFT JOIN techniciens t1 ON t1.id_technicien=enregistrementessais.id_operateur
		LEFT JOIN techniciens t2 ON t2.id_technicien=enregistrementessais.id_controleur
		LEFT JOIN postes ON postes.id_poste=enregistrementessais.id_poste
		LEFT JOIN machines ON machines.id_machine=postes.id_machine
		LEFT JOIN enregistreurs ON enregistreurs.id_enregistreur=postes.id_enregistreur
		LEFT JOIN extensometres ON extensometres.id_extensometre=postes.id_extensometre
		LEFT JOIN outillages o1 ON o1.id_outillage = postes.id_outillage_top
		LEFT JOIN outillages o2 ON o2.id_outillage = postes.id_outillage_bot
		LEFT JOIN chauffages ON chauffages.id_chauffage=postes.id_chauffage
		LEFT JOIN ind_temps i1 ON i1.id_ind_temp = postes.id_ind_temp_top
		LEFT JOIN ind_temps i2 ON i2.id_ind_temp = postes.id_ind_temp_strap
		LEFT JOIN ind_temps i3 ON i3.id_ind_temp = postes.id_ind_temp_bot
		WHERE n_fichier ='.$_POST['n_fichier'].'
		;') or die (mysql_error());
	$tbl_essai = mysql_fetch_assoc($req_essai);

?>

<?php			//traitement des donnees
			
if (isset($tbl_essai['indice']))		//groupement du nom du job avec ou sans indice
	$jobcomplet= $tbl_essai['n_client'].'-'.$tbl_essai['n_job'].'-'.$tbl_essai['indice'];
else
	$jobcomplet= $tbl_essai['n_client'].'-'.$tbl_essai['n_job'];
	
if (isset($tbl_essai['prefixe']))		//groupement du nom d eprouvette avec ou sans pr�fixe
	$identification= $tbl_essai['prefixe'].'-'.$tbl_essai['nom_eprouvette'];
else
	$identification= $tbl_essai['nom_eprouvette'];

if (isset($tbl_essai['compresseur']) AND $tbl_essai['compresseur']==1)
	$compresseur="n";
else
	$compresseur="o";	

$tbl_essai['ind_temp_top'] = (isset($tbl_essai['ind_temp_top']))? $tbl_essai['ind_temp_top'] : "";
$tbl_essai['ind_temp_strap'] = (isset($tbl_essai['ind_temp_strap']))? $tbl_essai['ind_temp_strap'] : "";
$tbl_essai['ind_temp_bot'] = (isset($tbl_essai['ind_temp_bot']))? $tbl_essai['ind_temp_bot'] : "";
if ($tbl_essai['ind_temp_top'] == $tbl_essai['ind_temp_bot'] )	{		//groupement des ind.temp.
	if ($tbl_essai['ind_temp_top'] == $tbl_essai['ind_temp_strap'])
		$ind_temp = $tbl_essai['ind_temp_top'];
	else
		$ind_temp = $tbl_essai['ind_temp_top'].'/'.$tbl_essai['ind_temp_strap'];
}
else
	$ind_temp = $tbl_essai['ind_temp_top'].'/'.$tbl_essai['ind_temp_strap'].'/'.$tbl_essai['ind_temp_bot'];
if (isset($tbl_essai['type_chauffage']) AND $tbl_essai['type_chauffage']=="Coil")	//chauffage coil
	$coil=$tbl_essai['chauffage'];
else
	$coil="";
if (isset($tbl_essai['type_chauffage']) AND $tbl_essai['type_chauffage']=="Four")	//chauffage coil
	$four=$tbl_essai['chauffage'];
else
	$four="";
	
if (isset($tbl_essai['STL']) AND $tbl_essai['STL']!="0")	//STL
	$STL=$tbl_essai['STL'];
else
	$STL="";
if (isset($tbl_essai['F_STL']) AND $tbl_essai['F_STL']!="0")	//STL
	$F_STL=$tbl_essai['F_STL'];
else
	$F_STL="";
?>

<?php

If (isset($tbl_essai['control']) AND $tbl_essai['control']=="LOAD")	{
	/** Error reporting */
	error_reporting(E_ALL);
	date_default_timezone_set('Europe/Paris');
	/** PHPExcel_IOFactory */
	require_once 'Excel/PHPExcel/PHPExcel/IOFactory.php';

	echo date('H:i:s') . " Load from Excel5 template\n<br/>";
	$objReader = PHPExcel_IOFactory::createReader('Excel5');
	$objPHPExcel = $objReader->load("Excel/templates/LCF HCF CTRL EFFORT Fiche Technique.xls");

	If ($tbl_essai['acquisition']=="759")
		$acquisition="Enreg. Info. 759■Dyn□MPT□793□TS□(*)";
	ElseIf ($tbl_essai['acquisition']=="Dynamic")
		$acquisition="Enreg. Info. 759□Dyn■MPT□793□TS□(*)";
	ElseIf ($tbl_essai['acquisition']=="MPT")
		$acquisition="Enreg. Info. 759□Dyn□MPT■793□TS□(*)";
	ElseIf ($tbl_essai['acquisition']=="793")
		$acquisition="Enreg. Info. 759□Dyn□MPT□793■TS□(*)";
	ElseIf ($tbl_essai['acquisition']=="TestSuite")	
		$acquisition="Enreg. Info. 759□Dyn□MPT□793□TS■(*)";
	ElseIf ($tbl_essai['acquisition']=="Strip Chart")	
		$acquisition="Enreg. Info. 759□Dyn□MPT□793□TS□(*)";

	$compresseur=($compresseur=="o")?"Compresseur ON □(*)" : "Compresseur ON ■(*)";	

	$objPHPExcel->getActiveSheet()->setCellValue('B7', $identification)->getStyle('B7')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('B8', $tbl_essai['format'])->getStyle('B8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('B9', $tbl_essai['matiere'])->getStyle('B9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('B14', $tbl_essai['machine'])->getStyle('B14')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('B16', $tbl_essai['enregistreur'])->getStyle('B16')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('A18', $compresseur);
	$objPHPExcel->getActiveSheet()->setCellValue('D17', $ind_temp)->getStyle('D17')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('B17', $tbl_essai['extensometre'])->getStyle('B17')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('D15', $coil)->getStyle('D15')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('D16', $four)->getStyle('D16')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('D18', $acquisition);

	$objPHPExcel->getActiveSheet()->setCellValue('B22', $tbl_essai['cartouche_load'])->getStyle('B22')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('B23', $tbl_essai['cartouche_stroke'])->getStyle('B23')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('B24', $tbl_essai['cartouche_strain'])->getStyle('B24')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('D22', $tbl_essai['cartouche_load']/10)->getStyle('D22')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('D23', $tbl_essai['cartouche_stroke']/10)->getStyle('D23')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('D24', $tbl_essai['cartouche_strain']/1000*12)->getStyle('D24')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('G7', $jobcomplet)->getStyle('G7')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('G8', $tbl_essai['n_essai'])->getStyle('G8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('G9', $tbl_essai['n_fichier'])->getStyle('G9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('G10', $tbl_essai['date'])->getStyle('G10')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('G11', $tbl_essai['operateur'])->getStyle('G11')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('G12', $tbl_essai['controleur'])->getStyle('G12')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('H18', $tbl_essai['operateur'])->getStyle('H18')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('H19', $tbl_essai['controleur'])->getStyle('H19')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('I21', $tbl_essai['temperature'])->getStyle('I21')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('I22', $tbl_essai['rapport'])->getStyle('I22')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('I23', $tbl_essai['frequence'])->getStyle('I23')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('G22', (1-$tbl_essai['rapport'])/(1+$tbl_essai['rapport']))->getStyle('G22')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('G23', $tbl_essai['forme_cycle'])->getStyle('G23')->getFont()->setBold(true);	
	$objPHPExcel->getActiveSheet()->setCellValue('H46', $tbl_essai['Arret_cycle'])->getStyle('H46')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H46')->getNumberFormat()->setFormatCode('# ### ### ###');
	
	
	echo date('H:i:s') . " Write to Excel5 format\n<br/>";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	//$objWriter->save(str_replace('.php', '.xls', __FILE__));
	$objWriter->save('Excel/'.$tbl_essai['n_fichier'].'.xls');
	// Echo memory peak usage
	echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n<br/>";
	// Echo done
	echo date('H:i:s') . " Done writing file.\r\n<br/>";	
}
ElseIf (isset($tbl_essai['control']) AND $tbl_essai['control']=="STRAIN")	{
	If (isset($tbl_essai['acquisition']) AND $tbl_essai['acquisition']=="TestSuite")	{

	}
	ElseIf (isset($tbl_essai['acquisition']) AND $tbl_essai['acquisition']=="759")	{
		/** Error reporting */
		error_reporting(E_ALL);
		date_default_timezone_set('Europe/Paris');
		/** PHPExcel_IOFactory */
		require_once 'Excel/PHPExcel/PHPExcel/IOFactory.php';

		echo date('H:i:s') . " Load from Excel5 template\n<br/>";
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("Excel/templates/LCF CTRL DEF Fiche Technique.xls");

			
		$objPHPExcel->getActiveSheet()->setCellValue('B6', $identification);
		$objPHPExcel->getActiveSheet()->setCellValue('B7', $tbl_essai['format']);
		$objPHPExcel->getActiveSheet()->setCellValue('B8', $tbl_essai['matiere']);
		$objPHPExcel->getActiveSheet()->setCellValue('B12', $tbl_essai['machine']);
		$objPHPExcel->getActiveSheet()->setCellValue('B14', $tbl_essai['enregistreur']);
		$objPHPExcel->getActiveSheet()->setCellValue('B15', $compresseur);
		$objPHPExcel->getActiveSheet()->setCellValue('D12', $ind_temp);
		$objPHPExcel->getActiveSheet()->setCellValue('D13', $tbl_essai['extensometre']);
		$objPHPExcel->getActiveSheet()->setCellValue('D14', $coil);
		$objPHPExcel->getActiveSheet()->setCellValue('D15', $four);

		$objPHPExcel->getActiveSheet()->setCellValue('B19', $tbl_essai['cartouche_load']);
		$objPHPExcel->getActiveSheet()->setCellValue('B20', $tbl_essai['cartouche_stroke']);
		$objPHPExcel->getActiveSheet()->setCellValue('B21', $tbl_essai['cartouche_strain']);


		$objPHPExcel->getActiveSheet()->setCellValue('G6', $jobcomplet);
		$objPHPExcel->getActiveSheet()->setCellValue('G7', $tbl_essai['n_essai']);
		$objPHPExcel->getActiveSheet()->setCellValue('G8', $tbl_essai['n_fichier']);
		$objPHPExcel->getActiveSheet()->setCellValue('G9', $tbl_essai['date']);
		$objPHPExcel->getActiveSheet()->setCellValue('G10', $tbl_essai['operateur']);
		$objPHPExcel->getActiveSheet()->setCellValue('G11', $tbl_essai['controleur']);

		$objPHPExcel->getActiveSheet()->setCellValue('H16', $tbl_essai['operateur']);
		$objPHPExcel->getActiveSheet()->setCellValue('H17', $tbl_essai['controleur']);
		$objPHPExcel->getActiveSheet()->setCellValue('I18', $tbl_essai['temperature']);
		$objPHPExcel->getActiveSheet()->setCellValue('I19', $tbl_essai['rapport']);
		$objPHPExcel->getActiveSheet()->setCellValue('I20', $tbl_essai['frequence']);
		$objPHPExcel->getActiveSheet()->setCellValue('G19', (1-$tbl_essai['rapport'])/(1+$tbl_essai['rapport']));
		$objPHPExcel->getActiveSheet()->setCellValue('G20', $tbl_essai['forme_cycle']);	
		
		$objPHPExcel->getActiveSheet()->setCellValue('C37', $STL);
		$objPHPExcel->getActiveSheet()->setCellValue('B38', $F_STL);
		$objPHPExcel->getActiveSheet()->setCellValue('G39', $tbl_essai['temperature']);		
		$objPHPExcel->getActiveSheet()->setCellValue('H40', $tbl_essai['Arret_cycle']);
		
		
		
		echo date('H:i:s') . " Write to Excel5 format\n<br/>";
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter->save(str_replace('.php', '.xls', __FILE__));
		$objWriter->save('Excel/'.$tbl_essai['n_fichier'].'.xls');
		// Echo memory peak usage
		echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n<br/>";
		// Echo done
		echo date('H:i:s') . " Done writing file.\r\n<br/>";
	}
	ElseIf (isset($tbl_essai['acquisition']) AND $tbl_essai['acquisition']=="793")	{
		/** Error reporting */
		error_reporting(E_ALL);
		date_default_timezone_set('Europe/Paris');
		/** PHPExcel_IOFactory */
		require_once 'Excel/PHPExcel/PHPExcel/IOFactory.php';

		echo date('H:i:s') . " Load from Excel5 template\n<br/>";
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("Excel/templates/LCF CTRL DEF Fiche Technique 793.xls");

			
		$objPHPExcel->getActiveSheet()->setCellValue('B6', $identification);
		$objPHPExcel->getActiveSheet()->setCellValue('B7', $tbl_essai['format']);
		$objPHPExcel->getActiveSheet()->setCellValue('B8', $tbl_essai['matiere']);
		$objPHPExcel->getActiveSheet()->setCellValue('B12', $tbl_essai['machine']);
		$objPHPExcel->getActiveSheet()->setCellValue('B14', $tbl_essai['enregistreur']);
		$objPHPExcel->getActiveSheet()->setCellValue('B15', $compresseur);
		$objPHPExcel->getActiveSheet()->setCellValue('D12', $ind_temp);
		$objPHPExcel->getActiveSheet()->setCellValue('D13', $tbl_essai['extensometre']);
		$objPHPExcel->getActiveSheet()->setCellValue('D14', $coil);
		$objPHPExcel->getActiveSheet()->setCellValue('D15', $four);

		$objPHPExcel->getActiveSheet()->setCellValue('B19', $tbl_essai['cartouche_load']);
		$objPHPExcel->getActiveSheet()->setCellValue('B20', $tbl_essai['cartouche_stroke']);
		$objPHPExcel->getActiveSheet()->setCellValue('B21', $tbl_essai['cartouche_strain']);


		$objPHPExcel->getActiveSheet()->setCellValue('G6', $jobcomplet);
		$objPHPExcel->getActiveSheet()->setCellValue('G7', $tbl_essai['n_essai']);
		$objPHPExcel->getActiveSheet()->setCellValue('G8', $tbl_essai['n_fichier']);
		$objPHPExcel->getActiveSheet()->setCellValue('G9', $tbl_essai['date']);
		$objPHPExcel->getActiveSheet()->setCellValue('G10', $tbl_essai['operateur']);
		$objPHPExcel->getActiveSheet()->setCellValue('G11', $tbl_essai['controleur']);

		$objPHPExcel->getActiveSheet()->setCellValue('H16', $tbl_essai['operateur']);
		$objPHPExcel->getActiveSheet()->setCellValue('H17', $tbl_essai['controleur']);
		$objPHPExcel->getActiveSheet()->setCellValue('I18', $tbl_essai['temperature']);
		$objPHPExcel->getActiveSheet()->setCellValue('I19', $tbl_essai['rapport']);
		$objPHPExcel->getActiveSheet()->setCellValue('I20', $tbl_essai['frequence']);
		$objPHPExcel->getActiveSheet()->setCellValue('G19', (1-$tbl_essai['rapport'])/(1+$tbl_essai['rapport']));
		$objPHPExcel->getActiveSheet()->setCellValue('G20', $tbl_essai['forme_cycle']);	
		
		$objPHPExcel->getActiveSheet()->setCellValue('C37', $STL);
		$objPHPExcel->getActiveSheet()->setCellValue('B38', $F_STL);
		$objPHPExcel->getActiveSheet()->setCellValue('G39', $tbl_essai['temperature']);		
		$objPHPExcel->getActiveSheet()->setCellValue('H40', $tbl_essai['Arret_cycle']);
		
		
		
		echo date('H:i:s') . " Write to Excel5 format\n<br/>";
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter->save(str_replace('.php', '.xls', __FILE__));
		$objWriter->save('Excel/'.$tbl_essai['n_fichier'].'.xls');
		// Echo memory peak usage
		echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n<br/>";
		// Echo done
		echo date('H:i:s') . " Done writing filed.\r\n<br/>";	
	}
}

?>
<body onLoad="popup('Excel/download.php?dwn=<?php echo $tbl_essai['n_fichier']; ?>.xls')">
<?php
}
?>




<br/>
<div id="choixessai">Choix de l'essai			<!--choix du poste(machine)-->
	<form method="post" name="choixessai" onchange="submit()">
		<INPUT type=text name="n_fichier">
	</form>
</div>