<?php
$Fnm = "Graph/statussnecma.csv";
$inF = fopen($Fnm,"w");

$ligne= 'YQEM;n° client; n° Job; Indice; Matiere; Type d\'essais; Fin prévue; Nb ep. à faire; Qté testé; Date 1er essais; Commentaire';

fputs($inF,$ligne."\n");	

$sql_liste='SELECT  " ", n_client, n_job, indice, matiere, type_essai,  " ", COUNT( eprouvettes.id_eprouvette ) AS total, SUM( IF( eprouvettes.assigne =1, 1, 0 ) ) AS tested, MIN( enregistrementessais.date ) AS date_debut
		FROM jobs
		LEFT JOIN eprouvettes ON eprouvettes.id_job = jobs.id_job
		LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
		LEFT JOIN type_essais ON type_essais.id_type_essai = jobs.id_type_essai
		WHERE (
		n_client =  "8003"
		OR n_client =  "8002"
		)
		AND termine IS NULL 
		AND job_actif =1
		GROUP BY jobs.id_job';

    $req_liste = mysql_query($sql_liste) or die (mysql_error());
	if ($req_liste) {
		while ($tbl_liste = mysql_fetch_assoc($req_liste)) {
		$job=$tbl_liste['n_client'].'-'.$tbl_liste['n_job'].((isset($tbl_liste['indice']))? '-'.$tbl_liste['indice'] : "");
		$ligne= ";".
				$tbl_liste['n_client'].';'.
				$tbl_liste['n_job'].';'.
				$tbl_liste['indice'].';'.
				$tbl_liste['matiere'].';'.
				$tbl_liste['type_essai'].';'.
				';'.
				$tbl_liste['total'].';"'.
				$tbl_liste['tested'].'";'.
				$tbl_liste['date_debut'].';'.
				';';
			fputs($inF,$ligne."\n");	
		}
	}
fclose($inF);
?>	

<?php
redirection("./pages/download.php?dwn=../Graph/statussnecma.csv");
?>