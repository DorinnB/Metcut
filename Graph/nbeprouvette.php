<?php
	require_once "../Artichow/BarPlot.class.php";
	require_once "../Artichow/LinePlot.class.php";
	Require("../fonctions.php");
	Connectionsql();

	$graph = new Graph(800, 600);
	$graph->setAntiAliasing(TRUE);
	$graph->setBackgroundColor(new Color(240, 240, 240));
	
	$blue = new Color(0, 0, 200);
	$red = new Color(200, 0, 0);
	$green = new Color(0, 200, 0);

	$group = new PlotGroup;
	$group->setSize(0.90, 1);
	$group->setCenter(0.45, 0.5);
	$group->setPadding(35, 26, 40, 27);
	$group->setSpace(2, 2);

	$group->setBackgroundColor(new Color(240, 240, 240));
  
  

   
$group->legend->setPosition(1.09);
$group->legend->setTextFont(new Tuffy(8));
$group->legend->setSpace(10);  
?>
<?php 
   
	$debut=(isset($_GET['debut']))? $_GET['debut'] : "2008-12-21";
	$fin=(isset($_GET['fin']))? $_GET['fin'] : "2009-12-21";
	$groupe=(isset($_GET['groupe']))? $_GET['groupe'] : "month";
	$client=(isset($_GET['client']) AND $_GET['client']!="")? $_GET['client'] : 'n_client like "%"';
	
if (is_array($client))	{
$clienttitre = ' pour';
	$lclient='(n_client like "'.$client[0].'" ';
	Foreach ($client as $key => $value) {
		$lclient = $lclient.' OR n_client like "'.$value.'"';
		$clienttitre = $clienttitre.' '.$value;
	}
	$lclient = $lclient. ')';
}
Else	{
	$lclient= $client;
	$clienttitre = ' tous clients';
}



	//$clienttitre=($client=="%")? "" : " pour ".$client;
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

		jobs.id_job, n_fichier, control, type_essai, eprouvettes.temperature, n_client, n_job, indice, n_essai, nom_eprouvette, machine, acquisition, enregistrementessais.date, tech1.technicien AS opérateur, tech2.technicien AS controleur
		FROM enregistrementessais
		LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
		LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
		LEFT JOIN type_essais ON jobs.id_type_essai = type_essais.id_type_essai
		LEFT JOIN postes ON enregistrementessais.id_poste = postes.id_poste
		LEFT JOIN machines ON postes.id_machine = machines.id_machine
		LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition
		LEFT OUTER JOIN techniciens tech1 ON enregistrementessais.id_operateur = tech1.id_technicien
		LEFT OUTER JOIN techniciens tech2 ON enregistrementessais.id_controleur = tech2.id_technicien
		
		WHERE enregistrementessais.date between "'.$debut.'" and "'.$fin.'" AND n_client LIKE "'.$lclient.'"
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
<?php  
						//Axes
	$graph->title->set("Nombre d'eprouvettes de ".$debut.' à '.$fin.$clienttitre);
	$group->axis->left->title->set("nombre d'eprouvettes");  

	$group->axis->bottom->setLabelText($interval);
	$group->axis->bottom->hideTicks(TRUE);
	
	
	
	


						// DONNEES 1  
	$plot = new BarPlot($STRAIN, 1, 3);
	$plot->setBarColor($blue);
	$plot->label->set($STRAIN);
	$plot->label->move(0, -10);
	$plot->setYAxis(Plot::LEFT);
	$group->legend->add($plot, "STRAIN", Legend::BACKGROUND);
	$group->add($plot);

						// DONNEES 2  
	$plot = new BarPlot($LOAD, 3, 3);
	$plot->label->set($LOAD);
	$plot->label->move(0, -10);
	$plot->setBarColor($red);
	$group->legend->add($plot, "LOAD", Legend::BACKGROUND);
	$group->add($plot);  

						// TOTAL
	$plot = new LinePlot($TOTAL, LinePlot::MIDDLE);
	$plot->setColor($green);
	$plot->setThickness(2);
	$plot->label->set($TOTAL);
	$plot->label->move(0, -10);
	$plot->mark->setType(Mark::CIRCLE);
	$plot->mark->setSize(6);
	$plot->mark->setFill($green);
	$plot->mark->border->show();
	$group->legend->add($plot, "TOTAL", Legend::MARK);
	$group->add($plot); 
	

	
	
	$graph->add($group);
	$graph->draw();

?>