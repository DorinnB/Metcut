<html>
	<head>


	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>JOBS</title>
	<link rel="shortcut icon" href="../css/favicon.ico" />
	<link rel="stylesheet" href="../css/style.css" type="text/css" />

	<!--Calendrier	http://api.jqueryui.com/datepicker/	-->

		<link rel="stylesheet" href="../css/jquery-ui.css" type="text/css" media="all" />
		<link rel="stylesheet" href="../css/ui.theme.css" type="text/css" media="all" />
		<script src="../jquery/jquery.min.js" type="text/javascript"></script>
		<script src="../jquery/jquery-ui.min.js" type="text/javascript"></script>
		<script>    $(function() {	        $( ".datepicker" ).datepicker();	    });	    </script>
	   
<!--ancien jquery avant calendrier	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>	-->
	
	<script language="javascript">	//popup (a disparaitre ?)
	function popup(fic)
	// on ouvre dans une fenêtre le fichier passé en paramètre.
	// cette ouverture peut être améliorée en passant d'autres
	// paramètres que la taille et la position de la fenêtre.
	{ window.open(fic,'NewSplit','toolbar=yes, width=1300,height=800,top=50,left=50,scrollbars=yes'); }
	</script>
	
	<script type="text/javascript">	//affichage contenu split
//		var nombresplit = 2;
		function changeSplit(numero,nombresplit)
		{
			// On commence par tout masquer
			for (var i = 0; i < nombresplit; i++)	{
				document.getElementById("contenuSplit" + i).style.display = "none";
				document.getElementById("OngletSplit" + i).style.color = "#666";	
			}
			// Puis on affiche celui qui a été sélectionné
			document.getElementById("contenuSplit" + numero).style.display = "block";
			document.getElementById("OngletSplit" + numero).style.color = "#0000FF";	
		}
	</script>
	<script type="text/javascript">	//affichage contenut onglet
		var nombreOnglets = 3;
		function changeOnglet(split,numero)
		{
			// On commence par tout masquer
			for (var i = 0; i < nombreOnglets; i++)	{
				document.getElementById("contenuOnglet" + split + "-" + i).style.display = "none";
				document.getElementById("BoutonOnglet" + split + "-" + i).style.display = "none";
			}
			// Puis on affiche celui qui a été sélectionné
			document.getElementById("contenuOnglet" + split + "-" + numero).style.display = "block";
			document.getElementById("BoutonOnglet" + split + "-" + numero).style.display = "block";
		}
	</script>
	<script type="text/javascript">	//cache/pascache des inputs/valeurs
	var elems = document.getElementsByClassName('cache');
	var elems2 = document.getElementsByClassName('pascache');
	var tag=1;
	function cachetruc(objet){
		if (objet !== undefined){
			tag=objet;
		}
		if (tag==1)	{		
			for(var i = 0; i < elems.length; i++) {
				elems[i].style.display = "none";
			}
			for(var i = 0; i < elems2.length; i++) {
				elems2[i].style.display = "block";
			}
		}
		else{
			for(var i = 0; i < elems.length; i++) {
			elems[i].style.display = "block";
			}
			for(var i = 0; i < elems2.length; i++) {
				elems2[i].style.display = "none";
			}
		}
		tag = -tag;
	}
	</script>
	<script type="text/javascript">	//Append column
	function appendColumn(table){
		var tbl=document.getElementById('my-table'+table);
		createCell(tbl.rows[0].insertCell(tbl.rows[0].cells.length),tbl.rows[0].cells.length,tbl.rows[0].cells[0].getAttribute("name"));
		for(var i=0;i<tbl.rows.length;i++)	{
			if (tbl.rows[i].className=="consigne")
				createCell(tbl.rows[i].insertCell(tbl.rows[i].cells.length),tbl.rows[i].cells.length,tbl.rows[i].cells[0].getAttribute("name"));
		}
	}

	function createCell(cell,text,style){
		var input=document.createElement('input');
		var txt=document.createTextNode(text);
		input.appendChild(txt);
	//	input.setAttribute('class',style);
		input.name = text+"-"+style;
		input.type= "text";
		cell.appendChild(input);
		}

	function deleteColumns(){
		var tbl=document.getElementById('my-table');
		var lastCol=tbl.rows[0].cells.length-1;
		for(var i=0;i<tbl.rows.length;i++)
			for(var j=lastCol;j>0;j--)tbl.rows[i].deleteCell(j);
		}
	</script>
	<script type="text/javascript">	//checkUser - ajout hidden field form selon user/password
		var user="";
		var password="";

		function checkUser(){
			if (user=="")	{
				modal.style.display = "block";			
			}
			else
			 cachetruc();
		}
	</script>
	<script type="text/javascript">	//Ajax pour gerer le login utilisateur
	$(document).ready(function() {

		$('#login').click(function() {

			$.ajax({
				type: "POST",
				url: 'checkusers.php',
				dataType: "json",
				data: {
					username: $("#username").val(),
					password: $("#password").val()
				},
				success: function(data)
				{
	

				if (typeof data.technicien !== 'undefined') {
						user = data['technicien'];
						iduser = data['id_technicien'];
						
						//on affiche en haut le nom de l'opérateur
					document.getElementById('affichage').innerHTML = user;
					
						//on change la valeur des input hidden de toute la page avec id_technicien = user
					var classuser = document.getElementsByClassName('user');
					for(var i = 0; i < classuser.length; i++) {
						classuser[i].getAttributeNode("value").value = data['id_technicien'];
					}
					
					cachetruc();
									
					
				}
				else{
					document.getElementById('affichage').innerHTML = data['result'];
				}

					
				}
			});

		});

	});
	</script>
	<script type="text/javascript">	//updateEp - Ajax pour gerer le check des ep
	function updateEp(id){
		src_checked="../img/checked.png"
		src_not_checked="../img/not-checked.png"
		
		
		if (user=="")	{
			modal.style.display = "block";	
		}
//attendre la fin du modal



		$.ajax({
			type: "POST",
			url: 'checkEp.php',
			dataType: "json",
			data: {
					id_ep: id,
					tech: iduser
				},
				success: function(data)
				{
	

				if (data['result'] === 'ok') {
					//on affiche en haut le nom de l'opérateur
					document.getElementById(id).src = src_checked;
				}
				else{
					alert('ERREUR LORS DE L\'ENVOI' + data['req'])
					document.getElementById(id).src = src_not_checked;
				}

					
			}
		});
	}


	</script>
	<script type="text/javascript">	//delEp - Ajax pour supprimer les ep
	function delEp(id, numero, table){
	
		$.ajax({
			type: "POST",
			url: 'delEp.php',
			dataType: "json",
			data: {
					id_ep: id,
					tech: iduser
				},
				success: function(data)
				{

				if (data['result'] === 'ok') {
					//on supprime la colonne du tableau
	
					var tb=document.getElementById(table);

					for (var x = 0; x < tb.rows.length; x++) {	//pour chaque tr tu tableau, on cache la cellule (td)
						tb.getElementsByTagName('tr')[x].cells[numero+1].style.display = "none";
					}
						
				}
					
			}
		});
	}


	</script>
	<script type="text/javascript">	//majConsigne - Ajax pour gerer la maj des consignes
	function majConsigne(form){
	src_not_checked="../img/not-checked.png"	
		
		var str = $("#"+form).serialize();

			$.ajax({
				type: "POST",
				url: 'newEp.php',
                data: str,
                dataType: 'json', // JSON
                success: function(json) {
					for(var i in json){
						obj = json[i];

						if (obj=="update"){
							//modifier l'icone de check en bas
							document.getElementById(i).src = src_not_checked;
						}
						else if (obj=="no_update")	{
							//on ne fait rien

						}
						else	{	//insertion d'ep
							
						}
						
					}									



                }
            });

		

	}
	</script>

	
	<style>
	#onglets
	{
		font : bold 24px Batang, arial, serif;
		list-style-type : none;
		padding-bottom : 0px; /* à modifier suivant la taille de la police ET de la hauteur de l'onglet dans #onglets li */
		border : 0px solid #9EA0A1;
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
		color : red;
	}
		#onglets2
	{
		font : bold 18px Batang, arial, serif;
		list-style-type : none;
		padding-bottom : 0px; /* à modifier suivant la taille de la police ET de la hauteur de l'onglet dans #onglets li */
		margin-left : 0;
	}
	#onglets2 li
	{
		float : left;
		height : 28px; /* à modifier suivant la taille de la police pour centrer le texte dans l'onglet */
		background-color: #F4F9FD;
		margin : 2px 2px 0 2px !important;  /* Pour les navigateurs autre que IE */
		margin : 1px 2px 0 2px;  /* Pour IE  */
	}
	#onglets2 li.check
	{
		float : right;
		padding: 0 30 0 30;
		background-color: #086a87;
	}
	#onglets2 a
	{
		display : block;
		color : #666;
		text-decoration : none;
		padding : 2px;
	}
	#onglets2 a:hover
	{
		background : #CECEF6;
		color : red;
	}
	table tbody .colored {
		background : rgb(206, 227, 246);
	}

	table.datajob{		
		font : 24px Batang, arial, serif;
		color : #666;
		border-spacing: 10px;
		border=0px;
		height:100%;
		width:100%;
	}
	table.datajob td{
		padding: 10px;
	}
	table div.titre{
		font : 12px Batang, arial, serif;
		color : black;
		height : 30%;
	}
	table div.valeur{
		font : 24px Batang, arial, serif;
		color : #666;
		height : 50%;
		padding-top: 5px;
	}
	
	input, textarea, select, option {
		background-color:rgb(206, 227, 246);
	}
	input, textarea, select {
		padding:3px;
		border:0px solid #F5C5C5;
		border-radius:5px;
		width:100%;
		height:100%;
		box-shadow:1px 1px 2px #C0C0C0 inset;
		font : 24px Batang, arial, serif;
	}
	table.job2 select, 
	table.job2 input {
		margin-top:-0px;
		padding:0px;
		font : 14px Batang, arial, serif;
		text-align:center;
	}
	select {
		margin-top:0px;
	}
	input[type=radio] {
		background-color:transparent;
		border:none;
		width:10px;
	}
	input[type=submit], input[type=reset] {
		width:100px;
		margin-left:5px;
		box-shadow:1px 1px 1px #D83F3D;
		cursor:pointer;
	}
	</style>



	</head>
<body>
	<?php
	Require("../fonctions.php");
	Connectionsql();
	?>

<?php	//Select type_essais
	$req="SELECT * FROM type_essais;";
	$req_type_essais = $db->query($req);
	while ($w_type_essais = mysqli_fetch_array($req_type_essais)) {
		$tbl_type_essais[]=$w_type_essais;
	}
?>
<?php	//Select statuts
	$req="SELECT * FROM statuts;";
	$req_statuts = $db->query($req);
	while ($w_statuts = mysqli_fetch_array($req_statuts)) {
		$tbl_statuts[]=$w_statuts;
	}
?>	
<?php	//Select dessins
	$req="SELECT * FROM dessins ORDER BY id_dessin;";
	$req_dessins = $db->query($req);
	while ($w_dessins = mysqli_fetch_array($req_dessins)) {
		$tbl_dessins[]=$w_dessins;
	}
?>	
<?php	//Select tbljob
	$req="
	SELECT * 
	FROM tbljobs 
	LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai 
	WHERE job=(select job from tbljobs where id_tbljob=".$_GET['id_tbljob'].") 
	ORDER BY split;";
	$req_split = $db->query($req);

	while ($tbl_split = mysqli_fetch_array($req_split)) {
		$tblsplit[]=$tbl_split;
	}
	$nombresplit=count($tblsplit)+1;
?>
<?php	//Select techniciens
	$req="SELECT * FROM techniciens ORDER BY technicien;";
	$req_techniciens = $db->query($req);
	while ($w_techniciens = mysqli_fetch_array($req_techniciens)) {
		$tbl_techniciens[]=$w_techniciens;
	}
?>

<?php	//ConvTitre
		$convTitre = [
			"id_consigne_eprouvette" => "id_consigne_eprouvette",
			"assigne" => "assigne",
			"blanks_reception" => "blanks_reception",
			"blanks_shipment" => "blanks_shipment",
			"c1_calc_inelastic_strain" => "c1_calc_inelastic_strain",
			"c1_E_descendant" => "Module Descendant",
			"c1_E_montant" => "Module Montant",
			"c1_max_strain" => "c1_max_strain",
			"c1_max_stress" => "c1_max_stress",
			"c1_meas_inelastic_strain" => "c1_meas_inelastic_strain",
			"c1_min_strain" => "c1_min_strain",
			"c1_min_stress" => "c1_min_stress",
			"c2_calc_inelastic_strain" => "c2_calc_inelastic_strain",
			"c2_cycle" => "c2_cycle",
			"c2_E_descendant" => "c2_E_descendant",
			"c2_E_montant" => "c2_E_montant",
			"c2_max_strain" => "c2_max_strain",
			"c2_max_stress" => "c2_max_stress",
			"c2_meas_inelastic_strain" => "c2_meas_inelastic_strain",
			"c2_min_strain" => "c2_min_strain",
			"c2_min_stress" => "c2_min_stress",
			"check_data" => "check_data",
			"comments" => "comments",
			"consigne_1" => "Consigne client",
			"consigne_1" => "consigne_1",
			"consigne_1_val" => "consigne_1_val",
			"consigne_2" => "Consigne client",
			"consigne_2" => "consigne_2",
			"consigne_2_val" => "consigne_2_val",
			"consigne_temperature" => "consigne_temperature",
			"consigne_temperature" => "Température",
			"consigne_unite" => "consigne_unite",
			"customer" => "customer",
			"cycle_en_cours" => "cycle_en_cours",
			"Cycle_final" => "Cycle_final",
			"Cycle_min" => "Cycle_min",
			"Cycle_STL" => "Cycle STL",
			"c_frequence" => "Frequence",
			"c_frequence_STL" => "Frequence STL",
			"date" => "date",
			"Deltaepsilon" => "Deltaepsilon",
			"dessin" => "dessin",
			"diam_trou" => "diam_trou",
			"Diametre" => "Diametre",
			"dilatation" => "dilatation",
			"dim_1" => "dim_1",
			"dim_2" => "dim_2",
			"dim_3" => "dim_3",
			"dim_format" => "dim_format",
			"drawing" => "drawing",
			"E_ht" => "E_ht",
			"E_RT" => "E_RT",
			"Epaisseur" => "Epaisseur",
			"eprouvette_actif" => "eprouvette_actif",
			"Epsilonmax" => "Epsilonmax",
			"estimated_testing" => "estimated_testing",
			"estimated_turn_over" => "estimated_turn_over",
			"flag_qualite" => "flag_qualite",
			"Fracture" => "Fracture",
			"Frequence" => "Frequence",
			"Frequence_STL" => "Frequence_STL",
			"id_condition_temps" => "id_condition_temps",
			"id_dessin" => "id_dessin",
			"id_eprouvette" => "id_eprouvette",
			"id_job" => "id_job",
			"id_material" => "id_material",
			"id_statut" => "id_statut",
			"id_type_essai" => "id_type_essai",
			"inner_diam" => "inner_diam",
			"invoiced_testing" => "invoiced_testing",
			"invoiced_turn_over" => "invoiced_turn_over",
			"job" => "job",
			"Largeur" => "Largeur",
			"machine" => "Machine",
			"matiere" => "matiere",
			"MFG_qty" => "MFG_qty",
			"MRI_req" => "MRI_req",
			"n_essai" => "n_essai",
			"n_fichier" => "N° Fichier",
			"nb_MRI" => "nb_MRI",
			"nb_specimen" => "nb_specimen",
			"nb_test_MRSAS" => "nb_test_MRSAS",
			"nb_type_feuille" => "nb_type_feuille",
			"Nf75" => "Nf75",
			"Ni" => "Ni",
			"Niveau_alt" => "Niveau_alt",
			"Niveau_max" => "Max",
			"Niveau_max" => "Niveau_max",
			"Niveau_min" => "Min",
			"Niveau_min" => "Niveau_min",
			"Niveau_moy" => "Niveau_moy",
			"niveau_unite" => "niveau_unite",
			"nom_eprouvette" => "nom_eprouvette",
			"Occupation_machine" => "Occupation_machine",
			"ordre" => "ordre",
			"outer_diam" => "outer_diam",
			"OVST_load_max" => "OVST_load_max",
			"OVST_load_min" => "OVST_load_min",
			"OVST_strain_max" => "OVST_strain_max",
			"OVST_strain_min" => "OVST_strain_min",
			"PO_instructions" => "PO_instructions",
			"prefixe" => "prefixe",
			"Rapport" => "Rapport",
			"report_creation_date" => "report_creation_date",
			"report_creation_time" => "report_creation_time",
			"report_TR_creation_date" => "report_TR_creation_date",
			"report_TR_creation_time" => "report_TR_creation_time",
			"Rupture" => "Rupture",
			"specimen_leadtime" => "specimen_leadtime",
			"specimen_reception" => "specimen_reception",
			"split" => "split",
			"staircase" => "staircase",
			"sub_C" => "sub_C",
			"temperature" => "temperature",
			"temps_essais" => "temps_essais",
			"temps_machine" => "temps_machine",
			"test_end" => "test_end",
			"test_leadtime" => "test_leadtime",
			"test_start" => "test_start",
			"tooling" => "tooling",
			"type_essai" => "type_essai",
			"type_feuille" => "type_feuille",
			"type_machine" => "type_machine",
			"UDST_load_max" => "UDST_load_max",
			"UDST_load_min" => "UDST_load_min",
			"UDST_strain_max" => "UDST_strain_max",
			"UDST_strain_min" => "UDST_strain_min",
			"waveform" => "waveform",
			"consigne_eprouvette_creation" => "Created by",
			"consigne_eprouvette_modif" => "Updated by",
			"consigne_eprouvette_checked" => "Checked by"			
		];				
?>


<div id="affichage" style="float:right"></div> 

<div id="menu">
	<ul id="onglets">
	<?php
	echo '<li><a><span onclick="changeSplit(0, '.$nombresplit.')" id="OngletSplit0">&nbsp;&nbsp;   GLOBAL  &nbsp;&nbsp;</span></a></li>
	';
	for($i=0;$i < count($tblsplit);$i++)	{
		$j=$i+1;
		$toBeCheck=($tblsplit[$i]['checked']==0)?'background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,.5) 5px, rgba(255,255,255,.5) 10px);':'';
		$color=colorstatut($tblsplit[$i]['id_statut']);
		echo '<li><a style="background-color:'.$color.';'.$toBeCheck.'"><span onclick="cachetruc(1); changeSplit('.$j.', '.$nombresplit.')" id="OngletSplit'.$j.'">&nbsp;&nbsp;  '.$tblsplit[$i]['split'].' - '.$tblsplit[$i]['type_essai'].'  &nbsp;&nbsp;</span></a></li>
	';}
	?></ul>
</div>


<div id="contenuSplit0" style="display:block;">	<!--GLOBAL-->
	
	<table border="1" cellspacing="2" style="height:90%; width:100%">
		<tbody>
			<tr>
				<td style="background-color:#086a87; border-color:black; height:60px">
					<div style="padding:00px; height: 100px;">
						<div>
						</div>
						<div style="padding:20px; height: 60px;">
							<table border="0" cellpadding="10" style="height:100%; width:100%;">
								<tbody>
									<tr style="font-size:24px">
										<td style="text-align:center; width:24%" class="colored"><?php	echo $tblsplit[0]['customer'].' - '.$tblsplit[0]['job'];	?></td>
										<td style="text-align: center; width: 25%;" class="colored">client</td>
										<td style="text-align:center">&nbsp;</td>
										<td style="text-align:center">&nbsp;</td>
										<td style="text-align: center; width: 20%; padding:0px;" class="colored"><a href="javascript:popup('newSplit.php?id_tbljob=<?php	echo $tblsplit[0]['id_tbljob'];	?>')" style="display:block;">Add Split</a></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="background-color:#086A87; border-color:black">
					<div style="margin:20px; height:95%">
						<table border="0" cellpadding="10" cellspacing="10" style="height:100%; width:100%">
							<tbody>
								<tr>
									<td style="width: 22px;" class="colored">contact</td>
									<td style="width:27%">&nbsp;</td>
									<td style="width:7%">&nbsp;</td>
									<td style="width: 22%;" class="colored"><?php	for($i=0;$i < count($tblsplit);$i++)	{	if (empty($blanks_reception))	$blanks_reception=$tblsplit[$i]['blanks_reception'];	else	$blanks_reception=($blanks_reception < $tblsplit[$i]['blanks_reception'])?$blanks_reception:$tblsplit[$i]['blanks_reception'];	}	echo $blanks_reception;	?></td>
									<td style="width: 22%;" class="colored">est complete job &euro;</td>
								</tr>
								<tr>
									<td style="background-color:" class="colored">po number</td>
									<td colspan="1" rowspan="2" style="text-align:center; vertical-align:middle">
									<a target="_blank" href="https://metcut.sharepoint.com/MRSAS - Metcut France/SiteAssets/Notebook-JOBS En Cours"	>
										<img alt="" src="../img/onenote-icone.png" style="height:80px; width:80px" />
									</a>
									
									</td>
									<td>&nbsp;</td>
									<td style="background-color:" class="colored"><?php	echo $tblsplit[0]['blanks_shipment'];	?></td>
									<td style="background-color:" class="colored"><?php	echo $tblsplit[0]['estimated_testing'];	?> &euro;</td>
								</tr>
								<tr>
									<td style="background-color:" class="colored">devis</td>
									<td>&nbsp;</td>
									<td style="background-color:" class="colored">% reception spec</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2" rowspan="5" style="background-color: rgb(206, 227, 246); text-align: left; vertical-align: top;">Instruction</td>
									<td>&nbsp;</td>
									<td style="background-color:" class="colored">% reception ext test</td>
									<td style="background-color:" class="colored">invoiced &euro;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td style="background-color:" class="colored">% expedition</td>
									<td style="background-color:" class="colored"><?php	echo $tblsplit[0]['invoiced_testing'];	?> &euro;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td style="background-color:" class="colored">n&deg; Facture</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

</div>




<!--<div id="contenuSplit0" style="display:block;">	<!--SPLIT-->
<?php
	for($splitencours=1;$splitencours <= count($tblsplit);$splitencours++)	{
	?>

	<div id="contenuSplit<?php echo $splitencours;?>" style="display:none;">	<!--split <?php echo $splitencours;?>-->
	
		<?php	//SELECT tbljobs
			$req="SELECT id_tbljob, id_statut, customer, job, split, PO_instructions, type_essais.id_type_essai, type_essai, id_condition_temps, matieres.id_matiere, matiere, generique, dessin, dessins.id_dessin, drawing, comments, nb_specimen, type_feuille, nb_type_feuille, tooling, MRI_req, MFG_qty, nb_MRI, sub_C, type_machine, nb_test_MRSAS, ordre, blanks_reception, blanks_shipment, specimen_leadtime, specimen_reception, test_start, test_end, test_leadtime, estimated_turn_over, estimated_testing, invoiced_turn_over, invoiced_testing, checked, crea.technicien as createur, chec.technicien as checker 
				FROM tbljobs 
				LEFT JOIN techniciens as crea ON crea.id_technicien=tbljobs.createur
				LEFT JOIN techniciens as chec ON chec.id_technicien=tbljobs.checked 
				LEFT JOIN dessins ON dessins.id_dessin=tbljobs.id_dessin 
				LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai 
				LEFT JOIN matieres ON matieres.id_matiere=tbljobs.id_matiere 
				WHERE id_tbljob=".$tblsplit[$splitencours-1]['id_tbljob'].";";

		$req_tbljobs = $db->query($req);
		$tbl_tbljobs = mysqli_fetch_assoc($req_tbljobs);
		?>
		<?php 	//SELECT eprouvettes
			$req="SELECT *, enregistrementessais.date, crea.technicien as createur, modif.technicien as modif, chec.technicien as checker 
			FROM consigne_eprouvettes 
			LEFT JOIN data_eprouvettes ON consigne_eprouvettes.id_consigne_eprouvette = data_eprouvettes.id_data_eprouvette
			LEFT JOIN enregistrementessais ON consigne_eprouvettes.id_consigne_eprouvette = enregistrementessais.id_eprouvette
			LEFT JOIN postes ON enregistrementessais.id_poste=postes.id_poste
			LEFT JOIN machines ON postes.id_machine=machines.id_machine
			LEFT JOIN techniciens ON enregistrementessais.id_operateur=techniciens.id_technicien
			LEFT JOIN techniciens as crea ON crea.id_technicien=consigne_eprouvettes.consigne_eprouvette_creation 
			LEFT JOIN techniciens as modif ON modif.id_technicien=consigne_eprouvettes.consigne_eprouvette_modif
			LEFT JOIN techniciens as chec ON chec.id_technicien=consigne_eprouvettes.consigne_eprouvette_checked		
			WHERE id_job = ".$tblsplit[$splitencours-1]['id_tbljob']." AND consigne_eprouvette_actif = 1 ORDER BY consigne_eprouvettes.id_consigne_eprouvette ;";
//echo $req;
			$req_ep = $db->query($req) or die (mysql_error());
			$liste_ep = array();
			$mindate = array();			
			while ($tbl_ep = mysqli_fetch_array($req_ep)) {
				$liste_ep[]=$tbl_ep;
				$mindate[]=$tbl_ep['date'];
			}
		?>	
		<?php	//SELECT consigne_temperature
			$req='SELECT DISTINCT round(consigne_temperature,0) as temperature FROM `eprouvettes` where id_job='.$tblsplit[$splitencours-1]['id_tbljob'];
			$req_constemp = $db->query($req) or die (mysql_error());
			$cons_temp="";
			while ($tbl_constemp = mysqli_fetch_array($req_constemp)) {
				$cons_temp.=$tbl_constemp['temperature']."&nbsp;&nbsp;&nbsp;";
			}
		?>			
	
		
		<table border="1" cellspacing="2" style="height:90%; width:100%">
			<tbody>
				<tr>	<!--bandeau supérieur-->
					<td style="background-color:#086a87; border-color:black; height:60px">
						<div style="padding:0px; height: 100px; ">
							<ul id="onglets2">
								<li><a><span onclick="cachetruc(1);changeOnglet(<?php echo $splitencours;?>,0)">&nbsp;&nbsp;  Données du job  &nbsp;&nbsp;</span></a></li>
								<li><a><span onclick="cachetruc(1);changeOnglet(<?php echo $splitencours;?>,1)">&nbsp;&nbsp;  Consignes  &nbsp;&nbsp;</span></a></li>
								<li><a><span onclick="cachetruc(1);changeOnglet(<?php echo $splitencours;?>,2)">&nbsp;&nbsp;  Eprouvettes  &nbsp;&nbsp;</span></a></li>
								<li id="BoutonOnglet<?php echo $splitencours; ?>-0" style="float:right; display:block;">
									<a href="#" class="pascache" onclick="checkUser();">Modification des données</a>
									<a href="#" class="cache" onclick="document.getElementById('FormcontenuaOnglet<?php echo $splitencours; ?>-0').submit();" class="toolbar">Maj Données</a>
								</li>
								<li id="BoutonOnglet<?php echo $splitencours; ?>-1" style="float:right; display:none;">
									<a href="#" class="pascache" onclick="checkUser();">Modification des Consignes</a>
									<a href="#" class="cache" onclick="majConsigne('FormcontenuaOnglet<?php echo $splitencours; ?>-1');" class="toolbar">Maj Consignes</a>
								</li>
								<li id="BoutonOnglet<?php echo $splitencours; ?>-2" style="float:right; display:none;">
									<a href="#" class="pascache" onclick="checkUser();">Modification des Eprouvettes</a>
									<a href="#" class="cache" onclick="document.getElementById('FormcontenuaOnglet<?php echo $splitencours; ?>-2').submit();" class="toolbar">Maj Ep</a>
								</li>
								<li class="check">
									<?php					
										if ($tbl_tbljobs['checked']==0)	{
											echo '<a href="javascript:popup(\'newSplit.php?id_tbljob='.$tbl_tbljobs['id_tbljob'].'&toBeCheck=1\')">
												<img alt="" src="../img/not-checked.png" style="height:30px; width:30px;" title="Created by : '.$tbl_tbljobs['createur'].'"/>
											</a>';										
										}
										else	{
											echo '<a>
												<img alt="" src="../img/checked.png" style="height:30px; width:30px;" title="Created by : '.$tbl_tbljobs['createur'].' &#13;Approved by : '.$tbl_tbljobs['checker'].'"/>
											</a>';
										}
									?>
								</li>
							</ul>
							<div style="padding:20px; height: 60px;">
								<table border="0" cellpadding="10" cellspacing="10" style="height:100%; width:100%">
									<tbody>
										<tr style="font-size:24px">
											<td class="colored" style="width:15%"><?php	echo $tblsplit[0]['customer'].'-'.$tblsplit[0]['job'].'-'.$tblsplit[$splitencours-1]['split'];	?></td>
											<td class="colored" style="width:15%">
												<?php		
													$titreLigne='id_statut';	echo '<SELECT id="row-0'.$splitencours.'" name="'.$tbl_tbljobs['id_tbljob']."-".$titreLigne.'" form="contenuOnglet'.$splitencours.'-0">';
													for($k=0;$k < count($tbl_statuts);$k++)	{
														$selected=($tbl_statuts[$k]['id_statut']==$tbl_tbljobs['id_statut'])?"selected":"";
														echo '<option value="'.$tbl_statuts[$k]['id_statut'].'" '.$selected.'>'.$tbl_statuts[$k]['id_statut'].' - '.$tbl_statuts[$k]['statut'].'</option>';	
													}
													echo '</select>';									
												?>	
											</td>
											<td style="text-align: center; width:15%;" class="colored"><?php	echo count($liste_ep);	?> specimens</td>
											<td style="text-align:right; width:15%" class="colored">Est nb jour</td>
											<td style="text-align:right; width:15%" class="colored"><?php	$titreLigne="test_leadtime"; echo '<input name="'.$tblsplit[$splitencours-1]['id_tbljob']."-".$titreLigne.'" class="datepicker" type="text" value="'.$tblsplit[0][$titreLigne].'"/>';	?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td style="background-color:#086A87; border-color:black">
						<div style="margin:20px; height:95%">

						<!--Données du job-->
							<div id="contenuOnglet<?php echo $splitencours; ?>-0" style="display:block;">	<!--Données du job-->

								<form id="FormcontenuaOnglet<?php echo $splitencours; ?>-0" action="../index.php?page=tbljobs_maj" method="POST">
									<input type="hidden" class="user" name="<?php echo $tblsplit[$splitencours-1]['id_tbljob'];	?>-id_user" value="">
									<table class="datajob" >
										<tbody>
											<tr>
												<td style="width: 23%;" class="colored">Specification</td>
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">
													<div class="titre">Temperature</div>
													<div class="valeur" style="height:50%; padding-top: 5px;"><?php	echo $cons_temp;	?></div>
												</td>
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%" class="colored"><?php	echo $tbl_tbljobs['specimen_reception'];	?></td>									
											</tr>
											<tr>
												<td style="width: 23%;" class="colored"><?php	echo $tbl_tbljobs['matiere']."	-	(".$tbl_tbljobs['generique'].')';	?></td>
												<td style="width: 23%" class="colored">forme signal</td>
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%" class="colored"><?php	echo ((empty($mindate))?"":min($mindate));	?></td>									
											</tr>
											<tr>
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">
													<div class="titre">Dessin</div><div class="valeur" style="height:50%; padding-top: 5px;">
														<?php		
															$titreLigne='dessin';	echo '<SELECT name="'.$tblsplit[$splitencours-1]['id_tbljob']."-id_".$titreLigne.'" class="cache">
															';
															for($k=0;$k < count($tbl_dessins);$k++)	{
																$selected=($tbl_dessins[$k]['id_'.$titreLigne]==$tbl_tbljobs['id_'.$titreLigne])?"selected":"";
																echo '<option value="'.$tbl_dessins[$k]['id_'.$titreLigne].'" '.$selected.'>'.$tbl_dessins[$k][$titreLigne].'</option>
																';	
															}
															echo '</select>
															<a class="pascache">'.$tbl_tbljobs['dessin'].'</a>';
														?>	
														</div>
													</td>
												<td style="width: 23%" class="colored">taille machine</td>
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%" class="colored">test end</td>									
											</tr>
											<tr>
												<td style="width: 23%;" class="colored">format grip</td>
												<td style="width: 23%">&nbsp;</td>
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%" class="colored">nb test inf 24h</td>
												<td style="width: 16%" class="colored">nb machine</td>
												<td style="width: 16%" class="colored">first yield pass</td>
											</tr>
											<tr>
												<td style="width: 23%;" class="colored" colspan=2 rowspan=4>commentaire</td>
												
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%" class="colored">hrs test w24h</td>
												<td style="width: 16%" class="colored">ratio running/total</td>
												<td style="width: 16%" class="colored">tests ok avec nc</td>									
											</tr>
											<tr>

											
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%" class="colored">hrs total</td>
												<td style="width: 16%" class="colored">avance/Retard</td>
												<td style="width: 16%" class="colored">tests void</td>									
											</tr>
											<tr>

											
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%" class="colored">hrs supp</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%" class="colored">reclamation</td>									
											</tr>
											<tr>

											
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%" class="colored">ratio inf 24h</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%" class="colored">nc client</td>									
											</tr>
										</tbody>
									</table>
								</form>
							</div>

						<!--Consignes-->		
							<div id="contenuOnglet<?php echo $splitencours; ?>-1" style="display:none;">	<!--Consignes-->
								<form id="FormcontenuaOnglet<?php echo $splitencours; ?>-1" action="../index.php?page=newEp" method="POST">
									<input type="hidden" name="job-idjob" value="<?php	echo $tblsplit[$splitencours-1]['id_tbljob'];	?>">
									<input type="hidden" class="user" name="job-id_user" value="">
									<?php if(!empty($liste_ep))	{	?>							
										<table>
											<tr>
												<td>
													<table id="my-table<?php echo $splitencours; ?>-1" class="job2">
														<?php
															echo '<tr><td style="max-width:200px;" name="nom_eprouvette"">Nom éprouvette</td>';
															for($k=0;$k < count($liste_ep);$k++)	{
																
													
																echo '<td bgcolor="'.est_assigne($liste_ep[$k]['assigne']).'">
																<INPUT name="'.$k.'-nom_eprouvette" value="'.$liste_ep[$k]['nom_eprouvette'].'" class="cache">
																<a class="pascache" href="file:///[NE MARCHE PAS]I:/Trans/'.$liste_ep[$k]['id_consigne_eprouvette'].'">'.$liste_ep[$k]['nom_eprouvette'].'</a>
																<input type="hidden" name="'.$k.'-id_consigne_eprouvette" value="'.$liste_ep[$k]['id_consigne_eprouvette'].'">
																</td>';
															}
															echo '</tr>';



															$titreLigne='n_essai';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															$titreLigne='n_fichier';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															//Operateur
															$titreLigne='machine';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															$titreLigne='date';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

															$titreLigne='consigne_temperature';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.'"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
															$titreLigne='c_frequence';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.'"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
															$titreLigne='c_frequence_STL';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.'"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
													
		

															for($k=1;$k <= nb_dim($liste_ep[0]['dim_format']);$k++)	{
																$titreLigne='dim_'.$k;	echo '<tr><td>'.$denomination[$k-1].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															}

															$titreLigne='waveform';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';



															$titreLigne='consigne_1';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.(($liste_ep[0][$titreLigne]==("R" OR "A"))?	$liste_ep[0][$titreLigne]	:	$liste_ep[0][$titreLigne]." (".$liste_ep[0]['consigne_unite'].")").'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne."_val"]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne."_val"].'" class="'.$cache1.'"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne."_val"].'</a></td>';}	echo '</tr>';
															$titreLigne='consigne_2';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.(($liste_ep[0][$titreLigne]==("R" OR "A"))?	$liste_ep[0][$titreLigne]	:	$liste_ep[0][$titreLigne]." (".$liste_ep[0]['consigne_unite'].")").'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne."_val"]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne."_val"].'" class="'.$cache1.'"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne."_val"].'</a></td>';}	echo '</tr>';
															
																
															
															$titreLigne='Niveau_max';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															$titreLigne='Niveau_min';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

															$titreLigne='Cycle_STL';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															$titreLigne='Cycle_final';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															$titreLigne='Rupture';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															$titreLigne='Fracture';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
															$titreLigne='temps_essais';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

															$titreLigne='Cycle_min';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.'"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
															
															
															
															
															
															
															echo '<tr><td>Checked by</td>';	
															FOR($j=0;$j < count($liste_ep);$j++){
																echo '<td>';
																if ($liste_ep[$j]['consigne_eprouvette_checked']==0)	{

																	echo '<a class="pascache" href="javascript:updateEp(\''.$liste_ep[$j]['id_consigne_eprouvette'].'\')">
																		<img id="'.$liste_ep[$j]['id_consigne_eprouvette'].'" alt="" src="../img/not-checked.png" style="height:30px; width:30px;" title="Created by : '.$liste_ep[$j]['createur'].' &#13; Updated by : '.$liste_ep[$j]['modif'].'"/>
																	</a>';
																}
																else	{
																	echo '<a>
																		<img class="pascache" id="'.$liste_ep[$j]['id_consigne_eprouvette'].'" alt="" src="../img/checked.png" style="height:30px; width:30px;" title="Created by : '.$liste_ep[$j]['createur'].' &#13; Updated by : '.$liste_ep[$j]['modif'].' &#13;Approved by : '.$liste_ep[$j]['checker'].'"/>
																	</a>';
																}
																					
																echo' <a class="cache" href="javascript:delEp('.$liste_ep[$j]['id_consigne_eprouvette'].', '.$j.', \'my-table'.$splitencours.'-1\')">
																	<img alt="" src="../img/unchecked.png" style="height:30px; width:30px;" title="Created by : '.$liste_ep[$j]['createur'].' &#13; Updated by : '.$liste_ep[$j]['modif'].' &#13;Approved by : '.$liste_ep[$j]['checker'].'"/>
																</a>';
																echo '</td>';										
															}	
															echo '</tr>';

														?>
													</table>
												</td>
												<td align="left" valign="top" style="padding:7">
													<button value="Add column" onclick="javascript:appendColumn('<?php echo $splitencours; ?>-1')" class="append_column" type="button">
														<img src="../css/croix.png">
													</button>		
												</td>
											</tr>
										</table>
									<?php	}	?>
								</form>
							</div>

						<!--Eprouvettes-->
							<div id="contenuOnglet<?php echo $splitencours; ?>-2" style="display:none;">	<!--Eprouvettes-->
								<?php if(!empty($liste_ep))	{	?>	
										<table class="job2">
										
										<?php
											
											echo '<tr><td>Nom éprouvette</td>';
											for($k=0;$k < count($liste_ep);$k++)	{
												$color=($liste_ep[$k]['assigne']==1)? "#E0E0E0" : "";
												echo '<td bgcolor="'.$color.'"><a href="javascript:popup(\'pages/modifep.php?ep='.$liste_ep[$k]['id_eprouvette'].'\')">'.$liste_ep[$k]['nom_eprouvette'].'</a></td>';
											}
											echo '</tr>';




											$titreLigne='n_essai';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='n_fichier';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='machine';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='date';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='Frequence';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='Frequence_STL';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.$color.'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='Rapport';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='waveform';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_1';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_2';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='consigne_temperature';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';



											//FAIRE en sorte que le titre ai : consigne client MPA et les td suivant aient les valeurs
											echo '<tr><td>'.$convTitre['consigne_1'].'</td>';FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j]['consigne_1_val'].'</td>';}	echo '</tr>';
											echo '<tr><td>'.$convTitre['consigne_2'].'</td>';FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j]['consigne_2_val'].'</td>';}	echo '</tr>';


											$titreLigne='consigne_1';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='consigne_2';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='consigne_1_val';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='consigne_2_val';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='consigne_unite';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';



											$titreLigne='temperature';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

											$titreLigne='c1_E_montant';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											//diametre HT
											echo '</tr><td>øchaud</td><td> </td></tr>';
											//section a chaud
											echo '</tr><td>section chaud</td><td> </td></tr>';
											//gage length a chaud
											echo '</tr><td>gage length chaud</td><td> </td></tr>';
											echo '</tr><td> </td></tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

											echo '<tr><td>a</td></tr>';

										?>
										</table>

								<?php	}	?>						
							</div>
				
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		
		
	</div>

<?php	}	?>




<!-- The Modal -->
<div id="myModal" class="modal">
				<span class="close"></span>
  <!-- Modal content -->
  <div class="modal-content" style="margin:300px; height:100px">
	  <form>	
		<table class="datajob">
			<tr>
				<td class="colored">
					<div class="titre">Checker</div>
					<div class="valeur" style="height:50%; padding-top: 5px;">
						<?php		
							$titreLigne='technicien';	echo '<SELECT id="username" name="'.$titreLigne.'"><option value="0">-</option>
							';
							for($k=0;$k < count($tbl_techniciens);$k++)	{
								if($tbl_techniciens[$k]['technicien_actif']==1)	{
									echo '<option value="'.$tbl_techniciens[$k]['id_'.$titreLigne].'">'.$tbl_techniciens[$k][$titreLigne].'</option>
									';	
								}
							}
							echo '</select>
							';
						?>
					</div>
				</td>
				<td class="colored">
					<div class="titre">Mot de Passe</div>
					<div class="valeur" style="height:50%; padding-top: 5px;">
						<input id="password" name="password" type="password"/>
					</div>
				</td>
				<td>
					<a id="login" class="valid">
						<img alt="" src="../img/checked.png" style="height:40px; width:35px;" />
					</a>
				</td>
			</tr>
			<tr>
				<span class="close">CLOSE</span>
			</tr>
		</table>
	</form>
  </div>

</div>

<style>
 /* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
    background-color: #086a87;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 40%; /* Could be more or less, depending on screen size */
}
</style>
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementsByClassName("myBtn");
var valid = document.getElementsByClassName("valid");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
for(var i = 0; i < btn.length; i++) {
	btn[i].onclick= function() {
		modal.style.display = "block";
	}
}

for(var i = 0; i < valid.length; i++) {
	valid[i].onclick= function() {
		modal.style.display = "none";
		user=document.getElementById("username").value;
		password=document.getElementById("password").value;
	}
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
document.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>









<!--Au chargement de la page-->
<?php	//Affichage du split selectionné du tableau des jobs
$req="select split from tbljobs where id_tbljob=".$_GET['id_tbljob'];
$req_split = $db->query($req);
	$split = mysqli_fetch_array($req_split);

echo '<script type="text/javascript">
    changeSplit('.$split['split'].', '.$nombresplit.');
</script>';
?>
<script type="text/javascript">	<!--affichage par defaut des valeurs modifiable ou non / input class="cache/pascache"	-->
	cachetruc();
</script>
