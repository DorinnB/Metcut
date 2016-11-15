
<?php		//gestion tri/filtre
$SearchField=(isset($_GET['SearchField'])) ? $_GET['SearchField'] : '1';
$FilterType=(isset($_GET['FilterType'])) ? $_GET['FilterType'] : '=';
$FilterText2=(isset($_GET['FilterText'])) ? $_GET['FilterText'] : '1';
	$FilterText=($FilterType=="LIKE") ? "%".$FilterText2."%" : $FilterText2;
$filtreavance = 'AND '.$SearchField." ".$FilterType." '".$FilterText."'";
$avance='&SearchField='.$SearchField.'&FilterType='.$FilterType.'&FilterText='.$FilterText2;


$debut=(isset($_GET['debut'])) ? $_GET['debut'] : '1999-01-01';
$fin=(isset($_GET['fin'])) ? $_GET['fin'] : '2999-01-01';
$temps1='&debut='.$debut.'&fin='.$fin;
$temps2=' WHERE enregistrementessais.date > "'.$debut.'" AND enregistrementessais.date < "'.$fin.'"';

$filtre=(isset($_GET['cat']) AND isset($_GET['val']))? $temps2.' AND '.$_GET['cat'].'="'.$_GET['val'].'"' : $temps2;
$range=(isset($_GET['tri']) AND isset($_GET['sens']))? $_GET['tri'].' '.$_GET['sens'] : "n_fichier DESC";


$cat=(isset($_GET['cat'])) ? '&cat='.$_GET['cat'] : '';
$val=(isset($_GET['val'])) ? '&val='.$_GET['val'] : '';

$tri=(isset($_GET['tri'])) ? '&tri='.$_GET['tri'] : '';
$sens=(isset($_GET['sens']) AND $_GET['sens']=='ASC') ? '&sens=ASC' : '&sens=DESC';
$sensinv=(isset($_GET['sens']) AND $_GET['sens']=='ASC') ? '&sens=DESC' : '&sens=ASC';


$nb=(isset($_GET['nb'])) ? $_GET['nb'] : 15;
$nburl='&nb='.$nb;



?>

<?php
$Fnm = "Graph/listeessais.csv";
$inF = fopen($Fnm,"w");

$ligne= 'n° Fichier;Control;Type d\'Essai;Température;n° du Job;n° Essais;Eprouvette;Machine;Acquisition;Date;operateur;Controleur';

fputs($inF,$ligne."\n");	

$sql_liste='	
	SELECT jobs.id_job, n_fichier, control, type_essai, eprouvettes.temperature, n_client, n_job, indice, n_essai, nom_eprouvette, machine, acquisition, DATE_FORMAT(enregistrementessais.date , "%d/%m/%Y") as date, tech1.technicien AS operateur, tech2.technicien AS controleur
	FROM enregistrementessais
	LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
	LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
	LEFT JOIN type_essais ON jobs.id_type_essai = type_essais.id_type_essai
	LEFT JOIN postes ON postes.id_poste = enregistrementessais.id_poste
	LEFT JOIN machines ON postes.id_machine = machines.id_machine
	LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition
	left outer join techniciens tech1 on enregistrementessais.id_operateur = tech1.id_technicien
	left outer join techniciens tech2 on enregistrementessais.id_controleur = tech2.id_technicien
	'.$filtre.' '.$filtreavance.'
	ORDER BY '.$range.'
	LIMIT '.$nb
	;

    $req_liste = mysql_query($sql_liste) or die (mysql_error());
	if ($req_liste) {
		while ($tbl_liste = mysql_fetch_assoc($req_liste)) {
		$job=$tbl_liste['n_client'].'-'.$tbl_liste['n_job'].((isset($tbl_liste['indice']))? '-'.$tbl_liste['indice'] : "");
		$ligne= 
				$tbl_liste['n_fichier'].';'.
				$tbl_liste['control'].';'.
				$tbl_liste['type_essai'].';'.
				$tbl_liste['temperature'].';'.
				$tbl_liste['n_client'].';'.
				$job.';'.
				$tbl_liste['n_essai'].';="'.
				$tbl_liste['nom_eprouvette'].'";'.
				$tbl_liste['machine'].';'.
				$tbl_liste['acquisition'].';'.
				$tbl_liste['date'].';'.
				$tbl_liste['operateur'].';'.
				$tbl_liste['controleur'];
			fputs($inF,$ligne."\n");	
		}
	}
fclose($inF);
?>	

<?php
redirection("./pages/download.php?dwn=../Graph/listeessais.csv");
?>