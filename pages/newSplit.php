<?php
	Require("../fonctions.php");
	Connectionsql();
?>
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

		
		
<script>
	$(function() {
		$( ".datepicker" ).datepicker({
			dateFormat: "yy-mm-dd"
		});	    
	});	    
</script>
<script type="text/javascript">	//Ajax pour gerer le la liste des contacts
	function getContactList(idclient, idcontact)
	{
		var blocListe = document.getElementById('contact');
		
		$.ajax({
			url : "contact.php?ref_customer="+ idclient+"&id_contact"+idcontact,
			type: "GET",
			dataType: 'json', // JSON
				success: function(data)
				{
					document.getElementById('contact').innerHTML = data['liste'];
					document.getElementById('compagnie').innerHTML = data['compagnie'];					
				}
			});
	}
</script>




<?php	//count split
	$req="
	SELECT max(split) 
	FROM tbljobs 
	LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
	LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai 
	WHERE info_jobs.id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob=".$_GET['id_tbljob'].") AND tbljob_actif=1
	ORDER BY split;";
//echo $req;	
	$req_split = $db->query($req);

//	$splitencours=mysqli_num_rows($req_split)+1;
	$splitencours=mysqli_fetch_array($req_split)[0]+1;

?>
<?php	//SELECT tbljobs
			$req="SELECT id_tbljob, id_statut, info_jobs.id_info_job, customer, job, split, contacts.id_contact, surname, lastname, compagnie, specification, type_essais.id_type_essai, type_essai, id_condition_temps, material, matieres.id_matiere, matiere, type_matiere, dessin, dessins.id_dessin, drawing, comments, nb_specimen, type_feuille, nb_type_feuille, tooling, MRI_req, MFG_qty, nb_MRI, sub_C, type_machine, nb_test_MRSAS, ordre, reception_eprouvette, retour_eprouvette, test_leadtime, test_start, test_end, test_leadtime, estimated_turn_over, estimated_testing, invoiced_turn_over, invoiced_testing, waveform, instructions_particulieres, tbljob_commentaire, po_number, devis, instruction 
			FROM tbljobs 
			LEFT JOIN dessins ON dessins.id_dessin=tbljobs.id_dessin
			LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai
			LEFT JOIN matieres ON matieres.id_matiere=tbljobs.id_matiere
			LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job			
			LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
			WHERE id_tbljob=".$_GET['id_tbljob'].";";
//echo $req;
		$req_tbljobs = $db->query($req);
		$tbl_tbljobs = mysqli_fetch_array($req_tbljobs);

		

		if($_GET['id_tbljob']==0)	{								//creation, avec modif
				//on supprimer "job" du 8000-00000 pour avoir un champ vide
			$tbl_tbljobs['job']="";	//trouver le max
			
			$req='select max(job) from info_jobs';
					$req_num_job = $db->query($req);
					$tbl_tbljobs['job']=mysqli_fetch_array($req_num_job)[0]+1;
			
			$tbl_tbljobs['id_info_job']="";		
			$checked=0;
		}
		elseif(isset($_GET['toBeCheck']) AND $_GET['toBeCheck']==1)	{	//check, sans modif
			$splitencours=$tbl_tbljobs['split'];
			$checked=1;
		}
		elseif(isset($_GET['toBeCheck']) AND $_GET['toBeCheck']==2)	{	//modif
			$splitencours=$tbl_tbljobs['split'];
			$checked=2;
		}
		elseif(isset($_GET['toBeCheck']) AND $_GET['toBeCheck']==3)	{		//ajout split
		
			$checked=3;
		}
		
		//var_dump($tbl_tbljobs);
?>

<?php	//Select dessins
	$req="SELECT * FROM dessins ORDER BY id_dessin;";
	$req_dessins = $db->query($req);
	while ($w_dessins = mysqli_fetch_array($req_dessins)) {
		$tbl_dessins[]=$w_dessins;
	}
?>
<?php	//Select matieres
	$req="SELECT * FROM matieres ORDER BY id_matiere;";
	$req_matieres = $db->query($req);
	while ($w_matieres = mysqli_fetch_array($req_matieres)) {
		$tbl_matieres[]=$w_matieres;
	}
?>
<?php	//Select type_essais
	$req="SELECT * FROM type_essais ORDER BY id_type_essai;";
	$req_type_essais = $db->query($req);
	while ($w_type_essais = mysqli_fetch_array($req_type_essais)) {
		$tbl_type_essais[]=$w_type_essais;
	}
?>
<?php	//Select contacts
	$req="SELECT * FROM contacts where ref_customer >= 8000 ORDER BY ref_customer, surname, lastname;";
	$req_contacts = $db->query($req);
	while ($w_contacts = mysqli_fetch_assoc($req_contacts)) {
		$tbl_contacts[]=$w_contacts;

		$n_clients[]=$w_contacts['ref_customer'];
	}
	//var_dump($tbl_contacts);
	$n_clients=array_unique($n_clients);
?>
<?php	//Select techniciens
	$req="SELECT * FROM techniciens WHERE technicien_actif=1 ORDER BY technicien;";
	$req_techniciens = $db->query($req);
	while ($w_techniciens = mysqli_fetch_array($req_techniciens)) {
		$tbl_techniciens[]=$w_techniciens;
	}
?>




	
	
	
	

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
 cursor:pointer;
 }
</style>

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
<script type="text/javascript">	//cache/pascache des check no check
	var elems3 = document.getElementsByClassName('check');
	var elems4 = document.getElementsByClassName('nocheck');
	var tag2=1;
	function checkitem(objet){
		if (objet !== undefined){
			tag2=objet;
		}
		if (tag2==1)	{		
			for(var i = 0; i < elems3.length; i++) {
				elems3[i].style.display = "none";
			}
			for(var i = 0; i < elems4.length; i++) {
				elems4[i].style.display = "block";
			}
		}
		else{
			for(var i = 0; i < elems3.length; i++) {
			elems3[i].style.display = "block";
			}
			for(var i = 0; i < elems4.length; i++) {
				elems4[i].style.display = "none";
			}
		}
		tag2 = -tag2;
	}
</script>
	
</head>
<body>
	
<div>
	

<form id="ajoutsplit" action="./ajoutsplit" method="POST">
	<table border="1" cellspacing="2" style="height:100%; width:100%">
		<tbody>
			<tr>	<!--bandeau supérieur-->
				<td style="background-color:#086a87; border-color:black; height:60px">
					<div style="padding:0px; height: 100px; ">
						<ul id="onglets2">
							<li><a><span>&nbsp;&nbsp;  Données du job  &nbsp;&nbsp;</span></a></li>
							<?php
								if ($checked!=1)	{	?>							
									<li style="float:right;">
										<a href="#"  id="myBtn" onclick="document.getElementById('ajoutsplit').submit();" class="toolbar">Ajout du nouveau job/split</a>
									</li>
									<li  style="float:right;">
										<a href="#" class="cache" onclick="checkitem()">Modification du GLOBAL</a>
									</li>
							<?php
								}
								else	{
									echo '<li style="float:right;">
											<a id="myBtn">
												<img alt="" src="../img/not-checked.png" style="height:30px; width:30px;" />
											</a>
										</li>';	
								}
							?>
						</ul>
						<div style="padding:20px; height: 60px;">
							<table class="datajob">
								<tbody>
									<tr style="font-size:24px">
										<td class="colored" style="width:15%; padding: 0px 10px 0px 10px;">
											<div class="titre">Numéro d'essai</div>
											<div class="valeur check" style="height:50%; padding-top: 5px;">											
												<?php
												$contactpresent=($tbl_tbljobs['id_contact']!="")?	', '.$tbl_tbljobs['id_contact']	:''	;
												echo '<SELECT name="global-customer" style="width:40%;" onchange="getContactList(this.value'.$contactpresent.');">
														<option>-</option>
													';
													foreach ($n_clients as $val)	{
														$selected=($val==$tbl_tbljobs['customer'])?"selected":"";
														echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>
														';	
													}
													echo '</select>
													';
												?>-<input name="global-job" style="width:40%;"type="text" value="<?php	echo $tbl_tbljobs['job'];?>"/>-<?php	echo $splitencours;?>
												<input type="hidden" name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-split" value="<?php	echo $splitencours;?>">
											</div>
											<div class="valeur nocheck" style="height:50%; padding-top: 5px;">
												<?php	echo $tbl_tbljobs['customer'];?>-<?php	echo $tbl_tbljobs['job'];?>-<?php	echo $splitencours;?>
											</div>
										</td>
										<td style="width: 15%; padding: 0px 10px 0px 10px;" class="colored">	<!--Type Essai-->
											<div class="titre">Type Essai</div>
											<div class="valeur" style="height:50%; padding-top: 5px;">
												<?php		
													$titreLigne='type_essai';	echo '<SELECT name="'.$tbl_tbljobs['id_tbljob'].'-id_'.$titreLigne.'" class="cache">
													';
													for($k=0;$k < count($tbl_type_essais);$k++)	{
														$selected=($tbl_type_essais[$k]['id_'.$titreLigne]==$tbl_tbljobs['id_'.$titreLigne])?"selected":"";
														echo '<option value="'.$tbl_type_essais[$k]['id_'.$titreLigne].'" '.$selected.'>'.$tbl_type_essais[$k][$titreLigne].'</option>
														';	
													}
													echo '</select>
													';
												?>
												<a class="pascache"><?php	echo $tbl_tbljobs['type_essai'];	?></a>
											</div>
										</td>
										<td style="text-align: center; width:15%;"></td>
										<td style="text-align:right; width:15%"></td>
										<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--leadtime-->
											<div class="titre">Lead Time (YYYY-MM-DD)</div>
											<div class="valeur" style="height:50%; padding-top: 5px;">
												<?php	$titreLigne="test_leadtime"; echo '<input form="ajoutsplit" name="'.$tbl_tbljobs['id_tbljob']."-".$titreLigne.'" class="datepicker cache" type="text" value="'.$tbl_tbljobs[$titreLigne].'"/>';	?>
												<a class="pascache"><?php	echo $tbl_tbljobs[$titreLigne];	?></a>
											</div>
										</td>
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

								<table class="datajob" >
									<tbody>
										<tr>
											<td style="width: 23%; padding: 0px 10px 0px 10px;" class="colored">	<!--Contact-->
												<div class="titre">Contact</div>
												<div class="valeur check" style="height:50%; padding-top: 5px;">
													<?php		
														$titreLigne='contact';	echo '<SELECT id="contact" name="global-id_'.$titreLigne.'">
														';
														if ($tbl_tbljobs['id_contact']!="")
															echo '<option value="'.$tbl_tbljobs['id_contact'].'">'.$tbl_tbljobs['lastname']." ".$tbl_tbljobs['surname'].'</option>';
														echo '</SELECT>
														';
													?>
												</div>
												<div class="valeur nocheck" style="height:50%; padding-top: 5px;">
													<?php	echo $tbl_tbljobs['lastname']." ".$tbl_tbljobs['surname'];?>
												</div>
											</td>
											<td style="width: 23%; padding: 0px 10px 0px 10px;" class="colored">	<!--Compagnie-->
												<div class="titre">Compagnie</div>
												<div id="compagnie" class="valeur" style="height:50%; padding-top: 5px;">
													<?php	echo $tbl_tbljobs['compagnie'];?>
												</div>											
											</td>
											<td style="width:8%">&nbsp;</td>
											<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--forme signal-->
												<div class="titre">Forme du signal</div>
												<div class="valeur" style="height:50%; padding-top: 5px;">
												<?php		
													$titreLigne='waveform';	echo '<SELECT name="'.$tbl_tbljobs['id_tbljob']."-".$titreLigne.'" class="cache">
													';
													$lst_waveform= array('Sinus','Triangle','Rampe');
													for($k=0;$k < count($lst_waveform);$k++)	{
														$selected=($lst_waveform[$k]==$tbl_tbljobs[$titreLigne])?"selected":"";
														echo '<option value="'.$lst_waveform[$k].'" '.$selected.'>'.$lst_waveform[$k].'</option>
														';	
													}
													echo '</select>
													<a class="pascache">'.$tbl_tbljobs[$titreLigne].'</a>';
												?>
												</div>	
											</td>
											<td style="width: 23%; padding: 0px 0px 0px 10px;">
											</td>
										</tr>
										<tr>
											<td style="width: 23%; padding: 0px 10px 0px 10px;" class="colored">	<!--PO number-->
												<div class="titre">PO Number</div>
												<div class="valeur" style="height:50%; padding-top: 5px; font : 12px Batang, arial, serif;">
													<INPUT name="global-po_number" value="<?php	echo $tbl_tbljobs['po_number'];	?>" class="check" style="font : 12px Batang, arial, serif;">
													<a class="nocheck"><?php	echo $tbl_tbljobs['po_number'];	?></a>
												</div>												
											</td>
											<td colspan="1" rowspan="2" style="text-align:center; vertical-align:middle">	<!--OneNote-->
												<a target="_blank" href="https://metcut.sharepoint.com/MRSAS - Metcut France/SiteAssets/Notebook-JOBS En Cours"	>
													<img alt="" src="../img/onenote-icone.png" style="height:80px; width:80px" />
												</a></td>
											<td>&nbsp;</td>
											<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--matiere-->
												<div class="titre">Matiere</div><div class="valeur" style="height:50%; padding-top: 5px;">
													<INPUT name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-material" value="<?php	echo $tbl_tbljobs['material'];	?>" class="cache" style="float:left; font : 12px Batang, arial, serif; width:60%">
													<?php		
														$titreLigne='matiere';	echo '<SELECT name="'.$tbl_tbljobs['id_tbljob']."-id_".$titreLigne.'" class="cache" style="font : 12px Batang, arial, serif; float:left; width:40%">
														';
														for($k=0;$k < count($tbl_matieres);$k++)	{
															$selected=($tbl_matieres[$k]['id_'.$titreLigne]==$tbl_tbljobs['id_'.$titreLigne])?"selected":"";
															echo '<option value="'.$tbl_matieres[$k]['id_'.$titreLigne].'" '.$selected.'>'.$tbl_matieres[$k][$titreLigne].'</option>
															';	
														}
														echo '</select>
														<a class="pascache" style="font : 12px Batang, arial, serif;">'.$tbl_tbljobs['material']."	-	(".$tbl_tbljobs['matiere'].')'.'</a>';
													?>	
													</div>
											</td>
											<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--specification-->
												<div class="titre">Specification</div>
												<div class="valeur" style="height:50%; padding-top: 5px; font : 12px Batang, arial, serif;">
													<INPUT name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-specification" value="<?php	echo $tbl_tbljobs['specification'];	?>" class="cache" style="font : 12px Batang, arial, serif;">
													<a class="pascache"><?php	echo $tbl_tbljobs['specification'];	?></a>
												</div>
											</td>
										</tr>
										<tr>
											<td style="width: 23%; padding: 0px 10px 0px 10px;" class="colored">	<!--devis-->
												<div class="titre">Devis</div>
												<div class="valeur" style="height:50%; padding-top: 5px; font : 12px Batang, arial, serif;">
													<INPUT name="global-devis" value="<?php	echo $tbl_tbljobs['devis'];	?>" class="check" style="font : 12px Batang, arial, serif;">
													<a class="nocheck"><?php	echo $tbl_tbljobs['devis'];	?></a>
												</div>													
											</td>

											
											<td>&nbsp;</td>
											<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--dessin-->
												<div class="titre">Dessin</div><div class="valeur" style="height:50%; padding-top: 5px;">
													<?php		
														$titreLigne='dessin';	echo '<SELECT name="'.$tbl_tbljobs['id_tbljob']."-id_".$titreLigne.'" class="cache">
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
											<td style="width:23%; padding: 0px 10px 0px 10px;" class="colored">	<!--Instructions Particulières-->
												<div class="titre">Instructions Particulières</div>
												<div class="valeur" style="height:50%; padding-top: 5px;">
													<INPUT class="cache" TYPE="file" style="font : 8px Batang, arial, serif;" name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-instructions_particulieres">
													<a class="pascache" href="javascript:popup('readPDF.php?pathfile=<?php	echo 'C:/Quality/IP/'.$tbl_tbljobs['instructions_particulieres'];	?>',595,842,'IP')" ><?php	echo $tbl_tbljobs['instructions_particulieres'];	?></a>
												</div>
											</td>
										</tr>
										<tr>
											<td style="width: 46%;" class="colored" colspan=2 rowspan=5>	<!--Instructions-->
												<div class="titre" style="height: 5%;">Instructions</div>
												<div class="valeur" style="height:95%; padding-top: 5px;">
													<textarea class="check" style="font : 18px Batang, arial, serif;" name="global-instruction"><?php	echo $tbl_tbljobs['instruction'];	?></textarea>													
													<textarea class="nocheck" readonly style="font : 18px Batang, arial, serif;" ><?php	echo $tbl_tbljobs['instruction'];	?></textarea>
												</div>
											</td>
											
											<td>&nbsp;</td>
											<td style="width:23%; padding: 0px 10px 0px 10px;">	<!--Instructions Particulières-->
											</td>
											<td style="width: 23%">&nbsp;</td>
										</tr>
										<tr>
										
										
											<td>&nbsp;</td>
											<td style="width: 23%;" class="colored" colspan=2 rowspan=4>	<!--commentaire-->
												<div class="titre" style="height: 5%;">Commentaire</div>
												<div class="valeur" style="height:95%; padding-top: 5px;">
													<textarea class="cache" style="font : 18px Batang, arial, serif;" name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-tbljob_commentaire"><?php	echo $tbl_tbljobs['tbljob_commentaire'];	?></textarea>
													<textarea class="pascache" readonly style="font : 18px Batang, arial, serif;" ><?php	echo $tbl_tbljobs['tbljob_commentaire'];	?></textarea>
												</div>
											</td>	
																
										</tr>
										<tr>
										
										
											<td>&nbsp;</td>
												
												
										</tr>
										<tr>
										
										
											<td>&nbsp;</td>
										
										
										</tr>
										<tr>
										
										
											<td>&nbsp;</td>
										
										
										</tr>
									</tbody>
								</table>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

		
		
</div>



<!-- The Modal -->
<div id="myModal" class="modal">
				<span class="close"></span>
  <!-- Modal content -->
  <div class="modal-content" style="margin:300px; height:100px">
	
		<table class="datajob">
			<tr>
				<td class="colored">
					<div class="titre">Checker</div>
					<div class="valeur" style="height:50%; padding-top: 5px;">
						<?php		
							$titreLigne='technicien';	echo '<SELECT name="info-'.$titreLigne.'"><option value="0">-</option>
							';
							for($k=0;$k < count($tbl_techniciens);$k++)	{
								echo '<option value="'.$tbl_techniciens[$k]['id_'.$titreLigne].'">'.$tbl_techniciens[$k][$titreLigne].'</option>
								';	
							}
							echo '</select>
							';
						?>
					</div>
				</td>
				<td class="colored">
					<div class="titre">Mot de Passe</div>
					<div class="valeur" style="height:50%; padding-top: 5px;">
						<input name="info-mdp" type="password"/>
					</div>
				</td>
				<td>
					<input type="submit" value="" style="background-image:url('../img/checked.png'); height:40px; width:35px;">
				</td>
			</tr>
		</table>
		<input type="hidden" name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-id_tbljob" value="<?php	echo $tbl_tbljobs['id_tbljob'];?>">
		<input type="hidden" name="info-checked" value="<?php	echo $checked;	?>">
		<input type="hidden" name="global-id_info_job" value="<?php	echo $tbl_tbljobs['id_info_job'];?>">
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
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>

<script type="text/javascript">	<!--affichage par defaut des valeurs modifiable ou non / input class="cache/pascache"	-->
	cachetruc();
</script>
<?php							//--affichage par defaut des valeurs modifiable ou non / input class="check/nocheck"	-->


	if ($checked==0)											//creation, avec modif
		echo '<script type="text/javascript">
				checkitem(-1);
				cachetruc();
			</script>';	
	elseif ($checked==1)										//check, sans modif
		echo '<script type="text/javascript">
				checkitem(1);

			</script>';
	elseif ($checked==2)										//modif du split
		echo '<script type="text/javascript">
				checkitem(1);
				cachetruc();
			</script>';			
	elseif	($checked==3)										//ajout split
		echo '<script type="text/javascript">
				checkitem(1);
				cachetruc();
			</script>';
	else
		echo"HEY OH !! pas de $checked ??";
?>
		