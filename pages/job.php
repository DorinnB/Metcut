<html>
	<head>


	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>JOBS</title>
	<link rel="shortcut icon" href="../css/favicon.ico" />
	<link rel="stylesheet" href="../css/style.css" type="text/css" />

	<!--Calendrier	http://api.jqueryui.com/datepicker/	-->

		<link rel="stylesheet" href="../jquery/jquery-ui-1.12.1.custom/jquery-ui.css" type="text/css" media="all" />
		<link rel="stylesheet" href="../jquery/jquery-ui-1.12.1.custom/jquery-ui.theme.css" type="text/css" media="all" />
		<script src="../jquery/jquery-3.1.1.min.js" type="text/javascript"></script>
		<script src="../jquery/jquery-ui-1.12.1.custom/jquery-ui.min.js" type="text/javascript"></script>
	   
<!--ancien jquery avant calendrier	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>	-->
	
	
	<script>		//datepicker
		$(function() {
			$( ".datepicker" ).datepicker({
				dateFormat: "yy-mm-dd"
			});
		});	    
	</script>
	<?php			//connection sql
	Require("../fonctions.php");
	Connectionsql();
	?>
	<?php			//Select dessins
	$req="SELECT * FROM dessins WHERE dessin_actif=1 ORDER BY id_dessin;";
	$req_dessins = $db->query($req);
	while ($w_dessins = mysqli_fetch_array($req_dessins)) {
		$tbl_dessins[]=$w_dessins;
		$dessins[$w_dessins['id_dessin']]=$w_dessins['dessin'];
	}
?>	
	
	<script language="javascript">	//popup
	function popup(fic,w,h,name)
	// on ouvre dans une fenêtre le fichier passé en paramètre.
	// cette ouverture peut être améliorée en passant d'autres
	// paramètres que la taille et la position de la fenêtre.
	{ window.open(fic,name,'toolbar=yes, width='+w+',height='+h+',top=50,left=50,scrollbars=yes'); }
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
		function changeOnglet(split,numero,nombreOnglets)
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
		createCell(tbl.rows[0].insertCell(tbl.rows[0].cells.length),tbl.rows[0].cells.length,tbl.rows[0].cells[0].getAttribute("name"),table);
		createCell(tbl.rows[1].insertCell(tbl.rows[1].cells.length),tbl.rows[1].cells.length,tbl.rows[1].cells[0].getAttribute("name"),table);
		for(var i=2;i<tbl.rows.length;i++)	{
			if (tbl.rows[i].className=="consigne")
				createCell(tbl.rows[i].insertCell(tbl.rows[i].cells.length),tbl.rows[i].cells.length,tbl.rows[i].cells[0].getAttribute("name"),table);
		}
	}

	function createCell(cell,text,style,table){
		var input=document.createElement('input');
		var txt=document.createTextNode(text);
		input.appendChild(txt);
	//	input.setAttribute('class',style);
		ncol=text-2;
		input.name = ncol+"-"+style;
		input.id =  table+"_"+ncol+"-"+style;		
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
	<script type="text/javascript">	//Append column2 (ajout valeur precedente)
	function appendColumn2(table){
		var tbl=document.getElementById('my-table'+table);
		createCell2(tbl.rows[0].insertCell(tbl.rows[0].cells.length),tbl.rows[0].cells.length,tbl.rows[0].cells[0].getAttribute("name"),table);
		createCell(tbl.rows[1].insertCell(tbl.rows[1].cells.length),tbl.rows[1].cells.length,tbl.rows[1].cells[0].getAttribute("name"),table);
		for(var i=2;i<tbl.rows.length;i++)	{
			if (tbl.rows[i].className=="consigne")
				createCell2(tbl.rows[i].insertCell(tbl.rows[i].cells.length),tbl.rows[i].cells.length,tbl.rows[i].cells[0].getAttribute("name"),table);
		}
	}

	function createCell2(cell,text,style,table){
		var input=document.createElement('input');
		var txt=document.createTextNode(text);
		input.appendChild(txt);
		input.setAttribute('class',"modified");
		ncol=text-2;
		input.name = ncol+"-"+style;
		input.id =  table+"_"+ncol+"-"+style;
		input.type= "text";
		
		ncolprecedent=ncol-1;

		if($(document.getElementById(table+"_"+ncolprecedent+"-"+style)).val()){ 
				input.value = document.getElementById(table+"_"+ncolprecedent+"-"+style).value;
			}

		cell.appendChild(input);
		
//		a=document.getElementsByName("0-"+style);
//		alert(document.getElementsByName("0-"+style).item(0).value);
		
		}
	</script>
	<script type="text/javascript">	//checkUser - ajout hidden field form selon user/password
		var user="";
		var password="";

		function checkUser(chgt){
			if (user=="")
				modal.style.display = "block";			
			else if (chgt==1)
				modal.style.display = "block";	
			else
				cachetruc();
		}
	</script>
	<script type="text/javascript">	//newTest - affichage modal pour new test
		function newTest(id,nomep){
				modalNewTest.style.display = "block";
				document.getElementById('eprouvettenewtest').innerHTML=nomep;
				document.getElementById('id_eprouvettenewTest').value=id;
				lstPoste(id);
				
		}
	</script>
	<script type="text/javascript">	//showLogged
	var elemsLogged = document.getElementsByClassName('logged');
	function showLogged(yn){
		if (yn=="y")	{
			for(var i = 0; i < elemsLogged.length; i++) {
				elemsLogged[i].style.display = "block";
			}
		}
		else{
			for(var i = 0; i < elemsLogged.length; i++) {
				elemsLogged[i].style.display = "none";
			}			
		}
			
	}
	</script>	
	<script type="text/javascript">	//Ajax pour gerer le login utilisateur
	//$(document).ready(function() {

	//	$('#login').click(function() {
	function logon(){

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
						iduser = data['id_technicien'];				;
						//on affiche en haut le nom de l'opérateur
					document.getElementById('affichage').innerHTML = user;
					document.getElementById('usernewTest').innerHTML = user;
						
						modal.style.display = "none";
						
						//on change la valeur des input hidden de toute la page avec id_technicien = user
					var classuser = document.getElementsByClassName('user');
					for(var i = 0; i < classuser.length; i++) {
						classuser[i].getAttributeNode("value").value = data['id_technicien'];
					}
					
					//cachetruc();
					showLogged("y");
									
					
				}
				else{
					document.getElementById('affichage').innerHTML = data['result'];
					user="";
					showLogged("n");
				}

					
				}
			});

}	//	});

	//});
	</script>
	<script type="text/javascript">	//updateEp - Ajax pour gerer le check des ep
	function updateEp(id,type){
		src_checked="../img/checked.png"
		src_not_checked="../img/not-checked.png"

		$.ajax({
			type: "POST",
			url: 'checkEp.php',
			dataType: "json",
			data: {
					id_ep: id,
					tech: iduser,
					type : type
				},
				success: function(data)
				{
	
				if (type=="consigne")	{
					if (data['result'] === 'ok') {
						//on affiche en haut le nom de l'opérateur
						document.getElementById("consigne_"+id).src = src_checked;
					}
					else{
						alert('ERREUR LORS DE L\'ENVOI' + data['req'])
						document.getElementById("consigne_"+id).src = src_not_checked;
					}
				}
				else if (type=="data")	{
					if (data['result'] === 'ok') {
						//on affiche en haut le nom de l'opérateur
						document.getElementById("data_"+id).src = src_checked;
					}
					else{
						alert('ERREUR LORS DE L\'ENVOI' + data['req'])
						document.getElementById("data_"+id).src = src_not_checked;
					}
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
			success: function(data)	{

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
	<script type="text/javascript">	//affichage ep soustraitances
		function changeSTSplit(ST,id_job)
		{		
			var statut=document.getElementById("STSplit_"+ST+"-"+ id_job);

			var elems = document.getElementsByClassName("ST_"+ST+"-"+ id_job);
					
			
			if (statut.classList.contains("statut")==true)	{
				visibility="collapse";
				color="";
				statut.className =  statut.className.replace(new RegExp('(?:^|\\s)'+ 'statut' + '(?:\\s|$)'), ' ');
			}
			else	{
				visibility="visible";
				color="blue";
				statut.className +=  " statut";
			}
			

			for(var i = 0; i < elems.length; i++) {
				elems[i].style.visibility = visibility;
				elems[i].getElementsByClassName("ST_"+ST)[0].style.visibility=visibility;
				if(elems[i].getElementsByTagName("input")[1].checked){
					elems[i].style.visibility = "visible";
					color="pink";
					elems[i].getElementsByClassName("ST_"+ST)[0].style.visibility="visible";				
				}
				
				document.getElementById("STSplit_"+ST+"-"+id_job).style.backgroundColor = color;
			}
		
		}
	</script>
	<script type="text/javascript">	//checkbox ep soustraitances
		function toggleCheckbox(btn,ST)
		{
			var elems = document.getElementsByClassName('ST_'+ST);

			for(var i = 0; i < elems.length; i++) {
				if(elems[i].style.visibility == "visible")	{
					elems[i].checked=btn.checked;
				}
			}
		}
	</script>
	<script type="text/javascript">	//Append row soustraitance
		numInsertRow=0;	//index d'insertion pour les id
		
		function newRow(tbl,idsoustraitant){

			var table = document.getElementById(tbl);

			// Create an empty <tr> element and add it to the 1st position of the table:
			var row = table.insertRow(1);
			row.setAttribute('class',tbl+"-"+idsoustraitant);

			// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			
			
				//Cell1
			var checkbox = document.createElement('input');
			checkbox.type = "checkbox";
			checkbox.name= "newep-"+numInsertRow+"-checkbox";
			checkbox.value="value";
			checkbox.id = tbl+"_"+numInsertRow;
			checkbox.setAttribute('class',tbl);
			checkbox.setAttribute('style','visibility:visible');
			
			cell1.appendChild(checkbox);

		
				//Cell2
			var nomep = document.createElement('input');
			nomep.type = "text";
			nomep.name= "newep-"+numInsertRow+"-nom_eprouvette";
			nomep.value="";
			nomep.id = tbl+"_"+numInsertRow+"_"+numInsertRow;
			nomep.setAttribute('class','modified');
			
			cell2.appendChild(nomep);
			
				//Cell3
			var dessin = document.createElement('select');
			//dessin.type = "text";
			dessin.name= "newep-"+numInsertRow+"-id_dessin";
			//dessin.value="";
			dessin.id = tbl+"_"+numInsertRow+"_"+numInsertRow;
			var dessins = <?php echo json_encode($dessins); ?>;
			for (key in dessins){
				var option = document.createElement("option");
				option.setAttribute("value", key);
				option.text = dessins[key];
				dessin.appendChild(option);
			}
			//	dessin.setAttribute('class','modified');
			
			cell3.appendChild(dessin);
			
				//Cell4
			var specif = document.createElement('input');
			specif.type = "text";
			specif.name= "newep-"+numInsertRow+"-d_commentaire";
			specif.value="";
			specif.id = tbl+"_"+numInsertRow+"_"+numInsertRow;
			specif.setAttribute('class','modified');
			
			cell4.appendChild(specif);
						
			
			numInsertRow=numInsertRow+1;	//numero d'ep inséré
		}
	</script>
	<script type="text/javascript">	//lstPoste - Ajax pour récupérer les postes / split
	function lstPoste(id_ep){
	
		$.ajax({
			type: "POST",
			url: 'lstPoste.php',
			dataType: "json",
			data: {
				id_ep: id_ep,
			},
			success: function(data)	{
				document.getElementById('poste').innerHTML = data['liste'];
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
	
	div.onglets3
	{
		float : left;
		font : bold 18px Batang, arial, serif;
		background-color: #F4F9FD;
		margin : 2px 2px 0 2px !important;  /* Pour les navigateurs autre que IE */
		margin : 1px 2px 0 2px;  /* Pour IE  */
	}	
	div.onglets3 a
	{
		display : block;
		color : #666;
		text-decoration : none;
	}
	div.onglets3 a:hover
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
	table div.valeur a{
		color : #666;
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
	#login2{
		border:1px solid #666;
		border-radius:5px;
		background-color:rgb(206, 227, 246);
		padding:5px;
	}


	</style>



	</head>
<body>


<?php	//Select type_essais
	$req="SELECT * FROM type_essais WHERE type_essai_actif=1;";
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

<?php	//Select tbljob
	$req='
	SELECT *, contacts.id_contact, surname, lastname, compagnie
	FROM tbljobs 
	LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai 
	LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
	LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact	
	WHERE tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$_GET['id_tbljob'].') 
		AND tbljob_actif = 1
		AND ((split >= 0) OR (split is null))
	ORDER BY split;';
 //echo $req;	
	$req_split = $db->query($req);
	while ($tbl_split = mysqli_fetch_array($req_split)) {
		$tblsplit[]=$tbl_split;
	}
	$nombresplit=count($tblsplit)+1;
?>
<?php	//Select soustraitances
	$req='
	SELECT *, contacts.id_contact, surname, lastname, compagnie, crea.technicien as createur, chec.technicien as checker
	FROM tbljobs 
	LEFT JOIN type_soustraitances ON type_soustraitances.id_type_soustraitance=tbljobs.id_type_essai 
	LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
	LEFT JOIN dessins ON dessins.id_dessin=tbljobs.id_dessin
	LEFT JOIN matieres ON matieres.id_matiere=tbljobs.id_matiere 
	LEFT JOIN contacts ON contacts.id_contact=tbljobs.id_contactST
	LEFT JOIN techniciens as crea ON crea.id_technicien=tbljobs.createur
	LEFT JOIN techniciens as chec ON chec.id_technicien=tbljobs.checked 
	WHERE tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$_GET['id_tbljob'].') 
		AND tbljob_actif = 1
		AND split < 0
	ORDER BY split DESC;';

	//echo $req;
	$req_soustraitances = $db->query($req);
	$soustraitance_checked=1;	//init 
	while ($tbl = mysqli_fetch_assoc($req_soustraitances)) {
		$tbl_soustraitances[] = $tbl;
		$soustraitance_checked=($tbl['checked']==0)?0:$soustraitance_checked;
	}
//var_dump($tbl_soustraitances);

	if (isset($tbl_soustraitances)){
		$nombreST=count($tbl_soustraitances)+1;				//inutile ??
		$nombresplit++;
}
?>
<?php	//Select techniciens
	$req="SELECT * FROM techniciens ORDER BY technicien;";
	$req_techniciens = $db->query($req);
	while ($w_techniciens = mysqli_fetch_array($req_techniciens)) {
		$tbl_techniciens[]=$w_techniciens;
	}
?>
<?php	//Select consigne_types
	$req="SELECT * FROM consigne_types;";
	$req_consigne_types = $db->query($req);
	while ($w_consigne_types = mysqli_fetch_array($req_consigne_types)) {
		$tbl_consigne_types[]=$w_consigne_types;
	}
?>
<?php	//Select matiere
	$req="SELECT * FROM matieres ORDER BY matiere;";
	$req_matieres = $db->query($req);
	while ($w_matieres = mysqli_fetch_array($req_matieres)) {
		$tbl_matieres[]=$w_matieres;
	}
?>
<?php	//Select contacts
	$req="SELECT * FROM contacts ORDER BY surname, lastname;";
	$req_contacts = $db->query($req);
	while ($w_contacts = mysqli_fetch_array($req_contacts)) {
		$tbl_contacts[]=$w_contacts;
	}
?>
<?php	//Select count eprouvettes
	$req='	SELECT COUNT("id_eprouvette") AS nbtest, 
				SUM(CASE WHEN heritage is null THEN 1 ELSE 0 END) nbep
			FROM eprouvettes 
			LEFT JOIN tbljobs ON tbljobs.id_tbljob = eprouvettes.id_job 
			LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
			WHERE eprouvette_actif = 1 
				AND info_jobs.id_info_job = ( 
					SELECT id_info_job 
					FROM tbljobs 
					WHERE id_tbljob='.$_GET['id_tbljob'].' 
						AND tbljob_actif = 1)
			AND split>0
	';
	//echo $req;
	$query_nbep = $db->query($req);
	$nb=mysqli_fetch_assoc($query_nbep)
?>
<?php	//Select postes
	$req='SELECT t1.id_poste, t1.id_machine, machines.machine
		FROM postes t1
		LEFT JOIN machines ON machines.id_machine = t1.id_machine
		WHERE t1.id_poste = ( 
		SELECT MAX( t2.id_poste ) 
		FROM postes t2
		WHERE t2.id_machine = t1.id_machine ) 
		AND machines.machine_actif =1
		ORDER BY machines.id_machine;';
		
	$req_postes = $db->query($req);
	while ($w_postes = mysqli_fetch_array($req_postes)) {
		$tbl_postes[]=$w_postes;
	}
?>
			
<?php	//ConvTitre
		$convTitre = [
			"id_eprouvette" => "id_eprouvette",
			"assigne" => "assigne",
			"reception_eprouvette" => "reception_eprouvette",
			"retour_eprouvette" => "retour_eprouvette",
			"c1_calc_inelastic_strain" => "c1_calc_inelastic_strain",
			"c1_E_descendant" => "Module Descendant",
			"c1_E_montant" => "Module Montant",
			"c1_max_strain" => "Max Strain",
			"c1_max_stress" => "Max Stress",
			"c1_meas_inelastic_strain" => "Meas Inelastic Strain",
			"c1_min_strain" => "Min Strain",
			"c1_min_stress" => "Min Stress",
			"c2_calc_inelastic_strain" => "Calc Inelastic Strain",
			"c2_cycle" => "Cycle mi-vie",
			"c2_E_descendant" => "E descendant",
			"c2_E_montant" => "E montant",
			"c2_max_strain" => "Max Strain",
			"c2_max_stress" => "Max Stress",
			"c2_meas_inelastic_strain" => "Meas Inelastic Strain",
			"c2_min_strain" => "Min Strain",
			"c2_min_stress" => "Min Stress",
			"check_data" => "check_data",
			"comments" => "comments",
			"c_1" => "Consigne client",
			"c_1" => "c_1",
			"c_1_val" => "c_1_val",
			"c_2" => "Consigne client",
			"c_2" => "c_2",
			"c_2_val" => "c_2_val",
			"c_temperature" => "Température",
			"c_unite" => "c_unite",
			"customer" => "customer",
			"cycle_en_cours" => "cycle_en_cours",
			"Cycle_final" => "Cycle_final",
			"Cycle_min" => "Cycle_min",
			"Cycle_STL" => "Cycle STL",
			"c_frequence" => "Frequence",
			"c_frequence_STL" => "Frequence STL",
			"d_frequence" => "Frequence",
			"d_frequence_STL" => "Frequence STL",			
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
			"Niveau_min" => "Min",
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
			"Rapport" => "Rapport",
			"report_creation_date" => "report_creation_date",
			"report_creation_time" => "report_creation_time",
			"report_TR_creation_date" => "report_TR_creation_date",
			"report_TR_creation_time" => "report_TR_creation_time",
			"Rupture" => "Rupture",
			"specimen_reception" => "specimen_reception",
			"split" => "split",
			"staircase" => "staircase",
			"sub_C" => "sub_C",
			"temperature" => "temperature",
			"temps_essais" => "Temps d'essais",
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
			"c_eprouvette_creation" => "Created by",
			"c_eprouvette_modif" => "Updated by",
			"c_checked" => "Checked by",
			"cycle_estime"	=> "Cycle estimé",
			"c_cycle_STL" => "Cycle_STL",
			"contact" => "contact",
			"runout" => "Runout",
			"d_commentaire" => "Commentaire",
			"prefixe" => "Prefixe"
		];				
?>

<div id="login2" style="float:right; margin:0 10 0 0;">
	<a href="#" onclick="checkUser(1);">
		<div id="affichage" style="float:right">Cliquez ici</div>
		<div style="float:right">LOGIN :&nbsp;</div> 
	</a>
</div>


<div id="menu">
	<ul id="onglets">
		<?php	
		echo '<li><a><span onclick="changeSplit(0, '.$nombresplit.')" id="OngletSplit0">&nbsp;&nbsp;   GLOBAL  &nbsp;&nbsp;</span></a></li>
		';
		for($i=0;$i < count($tblsplit);$i++)	{
			$toBeCheck=($tblsplit[$i]['checked']==0)?'background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,.5) 5px, rgba(255,255,255,.5) 10px);':'';
			$color=colorstatut($tblsplit[$i]['id_statut']);
			echo '<li>
					<a id="'.$tblsplit[$i]['id_tbljob'].'" style="background-color:'.$color.';'.$toBeCheck.'">
						<span onclick="changeSplit('.($i+1).', '.$nombresplit.')" id="OngletSplit'.($i+1).'">
							&nbsp;&nbsp;  '.$tblsplit[$i]['split'].' - '.$tblsplit[$i]['type_essai'].'  &nbsp;&nbsp;
						</span>
					</a>
				</li>
		';}
		
		if (isset($tbl_soustraitances)){
			$toBeCheck=($soustraitance_checked==0)?'background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,.5) 5px, rgba(255,255,255,.5) 10px);':'';
			$color='pink';
			$i++;
			echo '<li><a id="a" style="background-color:'.$color.';'.$toBeCheck.'"><span onclick="changeSplit('.$i.', '.$nombresplit.')" id="OngletSplit'.$i.'">&nbsp;&nbsp; S.T.  &nbsp;&nbsp;</span></a></li>';
		
		}	

		?>
	</ul>
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
										<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--job-->
											<div class="titre">Numero du job</div>
											<div class="valeur" style="height:50%;"><?php	echo $tblsplit[0]['customer'].' - '.$tblsplit[0]['job'];	?></div>
										</td>									
										<td style="width:15%; padding: 0px 10px 0px 10px;">
										</td>										
										<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--nb eprouvette-->
											<div class="titre">Nombre d'eprouvette(s)</div>
											<div class="valeur" style="height:50%;">
											<?php	echo $nb['nbep'].' ep. - '.$nb['nbtest'].' tests';	?></div>
										</td>
										
										
										<td style="text-align:center; width: 15%;">&nbsp;</td>
										<td style="text-align: center; width: 15%; padding:0px;" class="colored">
											<a href="newSplit.php?id_tbljob=<?php	echo $tblsplit[0]['id_tbljob'].'&toBeCheck=3';	?>" style="display:block;">
												Add Split
											</a>
											<a href="newST.php?id_tbljob=<?php	echo $tblsplit[0]['id_tbljob'].'&toBeCheck=3';	?>" style="display:block;">
												Add ST
											</a>											
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
						<table border="0" cellpadding="10" cellspacing="10" style="height:100%; width:100%">
							<tbody>
								<tr>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--Contact-->
										<div class="titre">Contact</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	echo $tblsplit[0]['lastname']." ".$tblsplit[0]['surname'];?>
										</div>
									</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--Compagnie-->
										<div class="titre">Compagnie</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	echo $tblsplit[0]['compagnie'];?>
										</div>											
									</td>
									<td style="width:7%">&nbsp;</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--date blank reception-->
										<div class="titre">First Blank reception (YYYY-MM-DD)</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	for($i=0;$i < count($tblsplit);$i++)	{	if (empty($reception_eprouvette))	$reception_eprouvette=$tblsplit[$i]['reception_eprouvette'];	else	$reception_eprouvette=($reception_eprouvette < $tblsplit[$i]['reception_eprouvette'])?$reception_eprouvette:$tblsplit[$i]['reception_eprouvette'];	}	echo $reception_eprouvette;	?>
										</div>
									</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--est complete job-->
										<div class="titre">estimation facturation job</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
										 <a style="float:right;">&euro;</a>
										</div>
									</td>
								</tr>
								<tr>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--PO number-->
										<div class="titre">PO Number</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	echo $tblsplit[0]['po_number'];?>
										</div>
									</td>
									<td colspan="1" rowspan="2" style="text-align:center; vertical-align:middle">	<!--OneNote-->
										<a target="_blank" href="https://metcut.sharepoint.com/MRSAS - Metcut France/SiteAssets/Notebook-JOBS En Cours"	>
											<img alt="" src="../img/onenote-icone.png" style="height:80px; width:80px" />
										</a>
									</td>
									<td>&nbsp;</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--retour_eprouvette-->
										<div class="titre">retour_eprouvette</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	echo $tblsplit[0]['retour_eprouvette'];	?>
										</div>
									</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--estimated_testing-->
										<div class="titre">estimated_testing</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	echo $tblsplit[0]['estimated_testing'];	?>
										</div>
									</td>									
								</tr>
								<tr>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--devis-->
										<div class="titre">Devis</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	echo $tblsplit[0]['devis'];	?>
										</div>
									</td>

									<td>&nbsp;</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--% reception spec-->
										<div class="titre">% reception spec</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											%
										</div>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td style="width: 44px;" class="colored" colspan=2 rowspan=5>	<!--Instructions-->
										<div class="titre" style="height: 5%;">Instructions</div>
										<div class="valeur" style="height:95%; padding-top: 5px;">
											<textarea readonly style="font : 18px Batang, arial, serif;" ><?php	echo $tblsplit[0]['instruction'];	?></textarea>
										</div>
									</td>
									<td>&nbsp;</td>
									
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--% reception ext test-->
										<div class="titre">% reception ext test</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											
										</div>
									</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--invoiced &euro;-->
										<div class="titre">invoiced &euro;</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											&euro;
										</div>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--% expedition-->
										<div class="titre">% expedition</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											
										</div>
									</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--invoiced_testing-->
										<div class="titre">invoiced_testing</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											<?php	echo $tblsplit[0]['invoiced_testing'];	?> &euro;
										</div>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--n&deg; Facture-->
										<div class="titre">n&deg; Facture</div>
										<div class="valeur" style="height:50%; padding-top: 5px;">
											
										</div>
									</td>
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




		<!--<div id="contenuSplit " style="display:none;">	<!--SPLIT-->
<?php
	for($splitencours=1;$splitencours <= count($tblsplit);$splitencours++)	{
	?>

	<div id="contenuSplit<?php echo $splitencours;?>" style="display:none;">	<!--split <?php echo $splitencours;?>-->
	
		<?php	//SELECT tbljobs
			$req="SELECT id_tbljob, id_statut, customer, job, split, specification, type_essais.id_type_essai, type_essai, id_condition_temps, matieres.id_matiere, material, matiere, type_matiere, dessin, dessins.id_dessin, drawing, comments, nb_specimen, type_feuille, nb_type_feuille, tooling, MRI_req, MFG_qty, nb_MRI, sub_C, type_machine, nb_test_MRSAS, ordre, reception_eprouvette, retour_eprouvette, test_start, test_end, test_leadtime, estimated_turn_over, estimated_testing, invoiced_turn_over, invoiced_testing, checked, crea.technicien as createur, chec.technicien as checker, type1.consigne_type as c_type_1, type2.consigne_type as c_type_2, type1.id_consigne_type as id_c_type_1, type2.id_consigne_type as id_c_type_2, c_unite, tbljob_commentaire, waveform, instructions_particulieres, type
				FROM tbljobs 
				LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				LEFT JOIN techniciens as crea ON crea.id_technicien=tbljobs.createur
				LEFT JOIN techniciens as chec ON chec.id_technicien=tbljobs.checked 
				LEFT JOIN consigne_types as type1 ON type1.id_consigne_type=tbljobs.c_1 
				LEFT JOIN consigne_types as type2 ON type2.id_consigne_type=tbljobs.c_2 							
				LEFT JOIN dessins ON dessins.id_dessin=tbljobs.id_dessin 
				LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai 
				LEFT JOIN matieres ON matieres.id_matiere=tbljobs.id_matiere 
				WHERE id_tbljob=".$tblsplit[$splitencours-1]['id_tbljob'].";";
		//echo $req;
		$req_tbljobs = $db->query($req);
		$tbl_tbljobs = mysqli_fetch_assoc($req_tbljobs);
		?>
		<?php 	//SELECT eprouvettes
			$req="SELECT *, eprouvettes.id_eprouvette, enregistrementessais.date, crea.technicien as createur, modif.technicien as modif, chec.technicien as checker, checkdata.technicien as data_check
			FROM eprouvettes 
			LEFT JOIN enregistrementessais ON eprouvettes.id_eprouvette = enregistrementessais.id_eprouvette
			LEFT JOIN postes ON enregistrementessais.id_poste=postes.id_poste
			LEFT JOIN machines ON postes.id_machine=machines.id_machine
			LEFT JOIN techniciens ON enregistrementessais.id_operateur=techniciens.id_technicien
			LEFT JOIN techniciens as crea ON crea.id_technicien=c_creation 
			LEFT JOIN techniciens as modif ON modif.id_technicien=c_modif
			LEFT JOIN techniciens as chec ON chec.id_technicien=c_checked
			LEFT JOIN techniciens as checkdata ON checkdata.id_technicien=d_checked	
			WHERE id_job = ".$tblsplit[$splitencours-1]['id_tbljob']." AND eprouvette_actif = 1 ORDER BY eprouvettes.id_eprouvette ;";
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
			$req='SELECT DISTINCT round(c_temperature,0) as temperature FROM `eprouvettes` where id_job='.$tblsplit[$splitencours-1]['id_tbljob'];
			//echo $req;
			$req_constemp = $db->query($req) or die (mysql_error());
			$cons_temp="";
			while ($tbl_constemp = mysqli_fetch_array($req_constemp)) {
				$cons_temp.=$tbl_constemp['temperature']."&nbsp;&nbsp;&nbsp;";
			}
		?>			
		<?php	//Select count eprouvettes
			$req='	SELECT COUNT("id_eprouvette") AS nbtest, 
						SUM(CASE WHEN heritage is null THEN 1 ELSE 0 END) nbep
					FROM eprouvettes 
					LEFT JOIN tbljobs ON tbljobs.id_tbljob = id_job 
					WHERE eprouvette_actif = 1 
						AND id_job = '.$tblsplit[$splitencours-1]['id_tbljob'].'
			';
			//echo $req;
			$query_nbep = $db->query($req);
			$nb=mysqli_fetch_assoc($query_nbep)
		?>
		
		<table border="1" cellspacing="2" style="height:90%; width:100%">
			<tbody>
				<tr>	<!--bandeau supérieur-->
					<td style="background-color:#086a87; border-color:black; height:60px">
						<div style="padding:0px; height: 100px; ">
							<ul id="onglets2">
								<li><a><span onclick="changeOnglet(<?php echo $splitencours;?>,0,3)">&nbsp;&nbsp;  Données du job  &nbsp;&nbsp;</span></a></li>
								<li><a><span onclick="changeOnglet(<?php echo $splitencours;?>,1,3)">&nbsp;&nbsp;  Consignes  &nbsp;&nbsp;</span></a></li>
								<li><a><span onclick="changeOnglet(<?php echo $splitencours;?>,2,3)">&nbsp;&nbsp;  Data  &nbsp;&nbsp;</span></a></li>
								<li id="BoutonOnglet<?php echo $splitencours; ?>-0" style="float:right; display:block;">
									<a href="#" class="logged pascache" onclick="checkUser();">Modification des données</a>
									<a href="#" class="cache" onclick="document.getElementById('FormcontenuaOnglet<?php echo $splitencours; ?>-0').submit();" class="toolbar">Maj Données</a>
								</li>
								<li id="BoutonOnglet<?php echo $splitencours; ?>-1" style="float:right; display:none;">
									<a href="#" class="logged pascache" onclick="checkUser();">Modification des Consignes</a>
									<a href="#" class="cache" onclick="document.getElementById('FormcontenuaOnglet<?php echo $splitencours; ?>-1').submit();" class="toolbar">Maj Consignes</a>
								</li>
								<li id="BoutonOnglet<?php echo $splitencours; ?>-2" style="float:right; display:none;">
									<a href="#" class="logged pascache" onclick="checkUser();">Modification des Eprouvettes</a>
									<a href="#" class="cache" onclick="document.getElementById('FormcontenuaOnglet<?php echo $splitencours; ?>-2').submit();" class="toolbar">Maj Ep</a>
								</li>
								<li class="check">
									<?php					
										if ($tbl_tbljobs['checked']==0)	{
											echo '<a href="newSplit.php?id_tbljob='.$tbl_tbljobs['id_tbljob'].'&toBeCheck=1\">
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
											<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--job-->
												<div class="titre">Numero du job</div>
												<div class="valeur" style="height:50%;"><?php	echo $tblsplit[0]['customer'].'-'.$tblsplit[0]['job'].'-'.$tblsplit[$splitencours-1]['split'];	?></div>
												</td>
											<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--statut-->
												<div class="titre">Statut</div>
												<div class="valeur" style="height:50%;">
												<?php		
													$titreLigne='id_statut';	echo '<SELECT form="FormcontenuaOnglet'.$splitencours.'-0" id="row-0'.$splitencours.'" name="'.$tbl_tbljobs['id_tbljob']."-".$titreLigne.'" class=" modified" style="font : 18px Batang, arial, serif;">';
													for($k=0;$k < count($tbl_statuts);$k++)	{
														$selected=($tbl_statuts[$k]['id_statut']==$tbl_tbljobs['id_statut'])?"selected":"";
														echo '<option value="'.$tbl_statuts[$k]['id_statut'].'" '.$selected.'>'.$tbl_statuts[$k]['id_statut'].' - '.$tbl_statuts[$k]['statut'].'</option>';	
													}
													echo '</select>';									
												?>	
												</div>
											</td>
											<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--nb eprouvette-->
												<div class="titre">Nombre d'eprouvette(s)</div>
												<div class="valeur" style="height:50%;">
												<?php	echo $nb['nbep'].' ep. - '.$nb['nbtest'].' tests';	?></div>
											</td>
											<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--est nb jour-->
												<div class="titre">Estimation du nombre de jour</div>
												<div class="valeur" style="height:50%;">
													<?php
														$duree=0;
														$sum=0;
														FOR($j=0;$j < count($liste_ep);$j++){
															if ($liste_ep[$j]['c_frequence']>0) {
																if (($liste_ep[$j]['c_cycle_STL']>0) AND ($liste_ep[$j]['cycle_estime']>$liste_ep[$j]['c_cycle_STL']))	{
																	$duree=(($liste_ep[$j]['cycle_estime']-$liste_ep[$j]['c_cycle_STL'])/$liste_ep[$j]['c_frequence_STL'] + $liste_ep[$j]['cycle_estime']/$liste_ep[$j]['c_frequence'])/3600/24;
																}
																else	{
																	$duree=$liste_ep[$j]['cycle_estime']/$liste_ep[$j]['c_frequence']/3600/24+3/24;	//Ajout de 3h par essai pour montage/demontage. Améliorer en fonction temps essai/ouverture labo
																}
															}
															$sum=$sum+$duree;
														}	
														echo (int) $sum.' jours/machine';
													?>
												</div>
											</td>
											<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--leadtime-->
												<div class="titre">Lead Time (YYYY-MM-DD)</div>
												<div class="valeur" style="height:50%;"><?php	$titreLigne="test_leadtime"; echo '<input form="FormcontenuaOnglet'.$splitencours.'-0" name="'.$tblsplit[$splitencours-1]['id_tbljob']."-".$titreLigne.'" class="datepicker  modified" type="text" value="'.$tblsplit[$splitencours-1][$titreLigne].'"/>';	?></div>	
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<tr>	<!--corps inférieur-->
					<td style="background-color:#086A87; border-color:black">
						<div style="margin:20px; height:95%">

						<!--Données du job-->
							<div id="contenuOnglet<?php echo $splitencours; ?>-0" style="display:block;">	<!--Données du job-->

								<form id="FormcontenuaOnglet<?php echo $splitencours; ?>-0" action="../index.php?page=tbljobs_maj" method="POST" ENCTYPE="multipart/form-data">
									<input type="hidden" class="user" name="<?php echo $tbl_tbljobs['id_tbljob'];	?>-createur" value="">
									<table class="datajob" >
										<tbody>
											<tr>
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--specification-->
													<div class="titre">Specification</div>
													<div class="valeur" style="height:50%; padding-top: 5px; font : 12px Batang, arial, serif;">
														<INPUT name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-specification" value="<?php	echo $tbl_tbljobs['specification'];	?>" class="cache  modified" style="font : 12px Batang, arial, serif;">
														<a class="pascache"><?php	echo $tbl_tbljobs['specification'];	?></a>
													</div>
												</td>
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--temperature-->
													<div class="titre">Temperature</div>
													<div class="valeur" style="height:50%; padding-top: 5px;"><?php	echo $cons_temp;	?></div>
												</td>
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--reception eprouvette-->
													<div class="titre">Reception d'eprouvette</div>
													<div class="valeur" style="height:50%;"><?php	echo $tbl_tbljobs['reception_eprouvette'];	?></div>
												</td>
											</tr>
											<tr>
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--matiere-->
													<div class="titre">Matiere</div><div class="valeur" style="height:50%; padding-top: 5px;">
														<INPUT name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-material" value="<?php	echo $tbl_tbljobs['material'];	?>" class="cache modified" style="float:left; font : 12px Batang, arial, serif; width:60%">
														<?php		
															$titreLigne='matiere';	echo '<SELECT name="'.$tbl_tbljobs['id_tbljob']."-id_".$titreLigne.'" class="cache modified" style="font : 12px Batang, arial, serif; float:left; width:40%">
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
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--forme signal-->
													<div class="titre">Forme du signal</div>
													<div class="valeur" style="height:50%; padding-top: 5px;">
													<?php		
														$titreLigne='waveform';	echo '<SELECT name="'.$tbl_tbljobs['id_tbljob']."-".$titreLigne.'" class="cache modified">
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
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--demarrage 1ere eprouvette-->
													<div class="titre">Demarrage 1ere eprouvette</div>
													<div class="valeur" style="height:50%;"><?php	echo ((empty($mindate))?"":min($mindate));	?></div>
												</td>
											</tr>
											<tr>
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--dessin-->
													<div class="titre">Dessin</div><div class="valeur" style="height:50%; padding-top: 5px;">
														<?php		
															$titreLigne='dessin';	echo '<SELECT name="'.$tbl_tbljobs['id_tbljob']."-id_".$titreLigne.'" class="cache modified">
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
												<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--taille machine-->
													<div class="titre">Taille machine (ToDo)</div>
													<div class="valeur" style="height:50%; padding-top: 5px;text-align: center;">
														<?php	//check des considtions d'essais pour determiner quels tranches de valeur d'effort contient le job. Coloration de chaque element si un essai le contient	?>
														<div style="float:left; border:1px solid #666; border-radius:5px; padding:0 10 0 10px; margin:0 10 0 10px;">10</div>
														<div style="float:left; border:1px solid #666; border-radius:5px; padding:0 10 0 10px; margin:0 10 0 10px;">100</div>
														<div style="float:left; border:1px solid #666; border-radius:5px; padding:0 10 0 10px; margin:0 10 0 10px;">250</div> 
													</div>
												</td>
												<td style="width: 4%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--test end-->
													<div class="titre">test end</div>
													<div class="valeur" style="height:50%;">test end</div>
												</td>
											</tr>
											<tr>
												<td style="width:23%; padding: 0px 10px 0px 10px;" class="colored">	<!--Instructions Particulières-->
													<div class="titre">Instructions Particulières</div>
													<div class="valeur" style="height:50%; padding-top: 5px;">
														<INPUT class="cache modified" TYPE="file" style="font : 8px Batang, arial, serif;" name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-instructions_particulieres">
														<a class="pascache" href="javascript:popup('readPDF.php?pathfile=<?php	echo 'C:/Quality/IP/'.$tbl_tbljobs['instructions_particulieres'];	?>',595,842,'IP')" ><?php	echo $tbl_tbljobs['instructions_particulieres'];	?></a>
													</div>
												</td>
												<td style="width: 23%">&nbsp;</td>
												<td style="width: 4%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--nb test inf 24h-->
													<div class="titre">nb test inf 24h</div>
													<div class="valeur" style="height:50%;">nb test inf 24h</div>
												</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--nb machine-->
													<div class="titre">nb machine</div>
													<div class="valeur" style="height:50%;">nb machine</div>
												</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--first yield pass-->
													<div class="titre">first yield pass</div>
													<div class="valeur" style="height:50%;">first yield pass</div>
												</td>
											</tr>
											<tr>
												<td style="width: 23%;" class="colored" colspan=2 rowspan=4>	<!--commentaire-->
													<div class="titre" style="height: 5%;">Commentaire</div>
													<div class="valeur" style="height:95%; padding-top: 5px;">
														<textarea class="cache modified" style="font : 18px Batang, arial, serif;" name="<?php	echo $tbl_tbljobs['id_tbljob'];	?>-tbljob_commentaire"><?php	echo $tbl_tbljobs['tbljob_commentaire'];	?></textarea>
														<textarea class="pascache" readonly style="font : 18px Batang, arial, serif;" ><?php	echo $tbl_tbljobs['tbljob_commentaire'];	?></textarea>
													</div>
												</td>												
												<td style="width: 4%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--hrs test w24h-->
													<div class="titre">hrs test w24h</div>
													<div class="valeur" style="height:50%;">hrs test w24h</div>
												</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--ratio running/total-->
													<div class="titre">ratio-running/total</div>
													<div class="valeur" style="height:50%;">ratio</div>
												</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--tests ok avec nc-->
													<div class="titre">tests ok avec nc</div>
													<div class="valeur" style="height:50%;">tests</div>
												</td>
											</tr>
											<tr>

											
												<td style="width: 4%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--hrs total-->
													<div class="titre">hrs total</div>
													<div class="valeur" style="height:50%;">hrs total</div>
												</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--avance/Retard-->
													<div class="titre">avance/Retard</div>
													<div class="valeur" style="height:50%;">avance/Retard</div>
												</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--tests void-->
													<div class="titre">tests void</div>
													<div class="valeur" style="height:50%;">tests void</div>
												</td>																				
											</tr>
											<tr>

											
												<td style="width: 4%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--hrs supp-->
													<div class="titre">hrs supp</div>
													<div class="valeur" style="height:50%;">heures supp</div>
												</td>												
												<td style="width: 16%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--reclamation-->
													<div class="titre">reclamation</div>
													<div class="valeur" style="height:50%;">reclamation</div>
												</td>							
											</tr>
											<tr>

											
												<td style="width: 4%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--ratio inf 24h-->
													<div class="titre">ratio inf 24h</div>
													<div class="valeur" style="height:50%;">ratio</div>
												</td>
												<td style="width: 16%">&nbsp;</td>
												<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--nc client-->
													<div class="titre">nc client</div>
													<div class="valeur" style="height:50%;">nc client</div>
												</td>								
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
									<?php if(empty($liste_ep))	{	
									$list_ep=array();
									}	?>
					
									<table>
										<tr>
											<td>
												<table id="my-table<?php echo $splitencours; ?>-1" class="job2">
													<?php
														$titreLigne='prefixe';	echo '<tr><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'"><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="cache modified"><a class="pascache">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
														
													
														echo '<tr><td style="max-width:200px;" name="nom_eprouvette">Nom éprouvette</td>';
														for($k=0;$k < count($liste_ep);$k++)	{
															echo '<td bgcolor="'.est_assigne($liste_ep[$k]['assigne']).'">';
															if($liste_ep[$k]['n_fichier']==""){
																echo '<a class="pascache" href="file:///[ENREGISTREMENT ESSAI]'.$liste_ep[$k]['id_eprouvette'].'" onclick="newTest('.$liste_ep[$k]['id_eprouvette'].',\''.$liste_ep[$k]['nom_eprouvette'].'\');">'.$liste_ep[$k]['nom_eprouvette'].'</a>';
															}
															else{
																	echo '<a class="pascache" href="file:///[NE MARCHE PAS]I:/Trans/'.$liste_ep[$k]['id_eprouvette'].'">'.$liste_ep[$k]['nom_eprouvette'].'</a>';
															}
															echo '
															<INPUT id="'.$splitencours.'-1_'.$k.'-nom_eprouvette" name="'.$k.'-nom_eprouvette" value="'.$liste_ep[$k]['nom_eprouvette'].'" class="cache">
															<input type="hidden" name="'.$k.'-id_eprouvette" value="'.$liste_ep[$k]['id_eprouvette'].'">
															</td>';
														}
														echo '</tr>';



														$titreLigne='n_essai';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
														$titreLigne='n_fichier';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
														//Operateur
														$titreLigne='machine';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
														$titreLigne='date';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

														$titreLigne='c_temperature';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.' modified"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
														$titreLigne='c_frequence';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.' modified"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
														$titreLigne='c_frequence_STL';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.' modified"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
														$titreLigne='c_cycle_STL';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.' modified"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
												
	

														for($k=1;$k <= nb_dim($tbl_tbljobs['type']);$k++)	{
															$titreLigne='dim_'.$k;	echo '<tr><td>'.$denomination[$k-1].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
														}

														$titreLigne='waveform';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';


														
														
														
														
														$titreLigne='c_type_1';	$units=(($tbl_tbljobs[$titreLigne]=="R") OR ($tbl_tbljobs[$titreLigne]=="A"))?		$tbl_tbljobs[$titreLigne]	:	$tbl_tbljobs[$titreLigne]." (".$tbl_tbljobs['c_unite'].")";
														echo '
														<tr class="consigne">
															<td name="'.$titreLigne.'_val">';
														echo '<SELECT name="job-id_'.$titreLigne.'" class="cache modified">
														';
														for($k=0;$k < count($tbl_consigne_types);$k++)	{
															$selected=($tbl_consigne_types[$k]['id_consigne_type']==$tbl_tbljobs['id_'.$titreLigne])?"selected":"";
															echo '<option value="'.$tbl_consigne_types[$k]['id_consigne_type'].'" '.$selected.'>'.$tbl_consigne_types[$k]['consigne_type'].'</option>
															';	
														}
														echo '</select>	';
															echo '	
																<a class="pascache">'.$units.'</a>
															</td>';	
															FOR($j=0;$j < count($liste_ep);$j++){	
																if ($liste_ep[$j][$titreLigne.'_val']==""){	
																	$cache1="";	$cache2="";	
																}	
																else	{
																	$cache1="cache";	$cache2="pascache";	
																}	
																echo '
																	<td>
																		<INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'_val" name="'.$j."-".$titreLigne.'_val" value="'.$liste_ep[$j][$titreLigne."_val"].'" class="'.$cache1.' modified">
																			<a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne."_val"].'
																			</a>
																		</td>';
															}	echo '
														</tr>';





														$titreLigne='c_type_2';	$units=(($tbl_tbljobs[$titreLigne]=="R") OR ($tbl_tbljobs[$titreLigne]=="A"))?		$tbl_tbljobs[$titreLigne]	:	$tbl_tbljobs[$titreLigne]." (".$tbl_tbljobs['c_unite'].")";
														echo '
														<tr class="consigne">
															<td name="'.$titreLigne.'_val">';
														echo '<SELECT name="job-id_'.$titreLigne.'" class="cache  modified" style="width:60%; float: left;">
														';
														for($k=0;$k < count($tbl_consigne_types);$k++)	{
															$selected=($tbl_consigne_types[$k]['id_consigne_type']==$tbl_tbljobs['id_'.$titreLigne])?"selected":"";
															echo '<option value="'.$tbl_consigne_types[$k]['id_consigne_type'].'" '.$selected.'>'.$tbl_consigne_types[$k]['consigne_type'].'</option>
															';	
														}
														echo '</select>	';
														echo'<SELECT name="job-c_unite" class="cache  modified" style="width:40%;float: left;">
															<option value="%" '.(($tbl_tbljobs['c_unite']=="%")?"selected":"").'>%</option>
															<option value="kN" '.(($tbl_tbljobs['c_unite']=="kN")?"selected":"").'>kN</option>
															<option value="MPa" '.(($tbl_tbljobs['c_unite']=="MPa")?"selected":"").'>MPa</option>
														</select>';
															echo '	
																<a class="pascache">'.$units.'</a>

															</td>';	
															FOR($j=0;$j < count($liste_ep);$j++){	
																if ($liste_ep[$j][$titreLigne.'_val']==""){	
																	$cache1="";	$cache2="";	
																}	
																else	{
																	$cache1="cache";	$cache2="pascache";	
																}	
																echo '
																	<td>
																		<INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'_val" name="'.$j."-".$titreLigne.'_val" value="'.$liste_ep[$j][$titreLigne."_val"].'" class="'.$cache1.' modified">
																			<a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne."_val"].'
																			</a>
																		</td>';
															}	echo '
														</tr>';
														
															
														
														$titreLigne='Niveau_max';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	niveaumaxmin($tbl_tbljobs['c_type_1'], $tbl_tbljobs['c_type_2'], $liste_ep[$j]['c_type_1_val'], $liste_ep[$j]['c_type_2_val']);		echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$MAX.'</td>';}	echo '</tr>';
														$titreLigne='Niveau_min';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	niveaumaxmin($tbl_tbljobs['c_type_1'], $tbl_tbljobs['c_type_2'], $liste_ep[$j]['c_type_1_val'], $liste_ep[$j]['c_type_2_val']);		echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$MIN.'</td>';}	echo '</tr>';

														$titreLigne='Cycle_STL';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.(($liste_ep[$j][$titreLigne]==0)?"":$liste_ep[$j][$titreLigne]).'</td>';}	echo '</tr>';
														$titreLigne='Cycle_final';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
														$titreLigne='Rupture';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
														$titreLigne='Fracture';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
														$titreLigne='temps_essais';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

														$titreLigne='Cycle_min';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.' modified"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
														$titreLigne='runout';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.' modified"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';

														$titreLigne='cycle_estime';	echo '<tr class="consigne"><td name="'.$titreLigne.'">'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	if ($liste_ep[$j][$titreLigne]==""){	$cache1="";	$cache2="";	}	else	{	$cache1="cache";	$cache2="pascache";	}	echo '<td><INPUT id="'.$splitencours.'-1_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="'.$cache1.' modified"><a class="'.$cache2.'">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';
														
														
														
										
														
														
														echo '<tr><td>Checked by</td>';	
														FOR($j=0;$j < count($liste_ep);$j++){
															echo '<td>';
															if ($liste_ep[$j]['c_checked']==0)	{

																echo '<a class="pascache" href="javascript:updateEp(\''.$liste_ep[$j]['id_eprouvette'].'\',\'consigne\')">
																	<img id="consigne_'.$liste_ep[$j]['id_eprouvette'].'" alt="" src="../img/not-checked.png" style="height:30px; width:30px;" title="Created by : '.$liste_ep[$j]['createur'].' &#13; Updated by : '.$liste_ep[$j]['modif'].'"/>
																</a>';
															}
															else	{
																echo '<a class="pascache">
																	<img id="consigne_'.$liste_ep[$j]['id_eprouvette'].'" alt="" src="../img/checked.png" style="height:30px; width:30px;" title="Created by : '.$liste_ep[$j]['createur'].' &#13; Updated by : '.$liste_ep[$j]['modif'].' &#13;Approved by : '.$liste_ep[$j]['checker'].'"/>
																</a>';
															}
																				
															echo' <a class="cache" href="javascript:delEp('.$liste_ep[$j]['id_eprouvette'].', '.$j.', \'my-table'.$splitencours.'-1\')">
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
												<button value="Add column" onclick="javascript:appendColumn2('<?php echo $splitencours; ?>-1')" class="append_column" type="button">
													<img src="../css/croix.png">
												</button>													
											</td>
										</tr>
									</table>

								</form>
							</div>

						<!--Data-->
							<div id="contenuOnglet<?php echo $splitencours; ?>-2" style="display:none;">	<!--Eprouvettes-->
								<?php if(!empty($liste_ep))	{	?>	
										<table class="job2">
										
										<?php
											
											echo '<tr>
											<td rowspan=18 style="writing-mode: sideways-lr; width:30px;">Conditions d\'Essais
											</td><td style="max-width:200px;" name="nom_eprouvette"">Nom éprouvette</td>';
											for($k=0;$k < count($liste_ep);$k++)	{
												echo '<td bgcolor="'.est_assigne($liste_ep[$k]['assigne']).'"><a href="javascript:popup(\'pages/modifep.php?ep='.$liste_ep[$k]['id_eprouvette'].'\',1300,800,"newep")">'.$liste_ep[$k]['nom_eprouvette'].'</a></td>';
											}
											echo '</tr>';




											$titreLigne='n_essai';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='n_fichier';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='machine';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='date';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='d_frequence';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='d_frequence_STL';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='Rapport';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='waveform';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='DIMENSIONS';	for($k=1;$k <= nb_dim($tbl_tbljobs['type']);$k++)	{ $titreLigne='dim_'.$k;	echo '<tr><td>'.$denomination[$k-1].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';}
											$titreLigne='c_temperature';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

											
											$titreLigne='c_type_1';	$units=(($tbl_tbljobs[$titreLigne]=="R") OR ($tbl_tbljobs[$titreLigne]=="A"))?		$tbl_tbljobs[$titreLigne]	:	$tbl_tbljobs[$titreLigne]." (".$tbl_tbljobs['c_unite'].")";	echo '<tr class="consigne"><td name="'.$titreLigne.'_val">'.$units.'</td>';		FOR($j=0;$j < count($liste_ep);$j++){	echo '<td><a>'.$liste_ep[$j][$titreLigne."_val"].'</a></td>';	}	echo '</tr>';
											$titreLigne='c_type_2';	$units=(($tbl_tbljobs[$titreLigne]=="R") OR ($tbl_tbljobs[$titreLigne]=="A"))?		$tbl_tbljobs[$titreLigne]	:	$tbl_tbljobs[$titreLigne]." (".$tbl_tbljobs['c_unite'].")";	echo '<tr class="consigne"><td name="'.$titreLigne.'_val">'.$units.'</td>';		FOR($j=0;$j < count($liste_ep);$j++){	echo '<td><a>'.$liste_ep[$j][$titreLigne."_val"].'</a></td>';	}	echo '</tr>';
															
	


											$titreLigne='temperature';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

											$titreLigne='c1_E_montant';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											//diametre HT
											echo '</tr><td>øchaud</td><td> </td></tr>';
											//section a chaud
											echo '</tr><td>section chaud</td><td> </td></tr>';
											//gage length a chaud
											echo '</tr><td>gage length chaud</td><td> </td></tr>';
											
											$titreLigne='c1_E_montant';	echo '<tr style="border-top:thick double #6495ed;">
											<td rowspan=5 style="writing-mode: sideways-lr;">Cycle 1
											</td><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c1_max_strain';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c1_min_strain';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c1_max_stress';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c1_min_stress';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c2_cycle';	echo '<tr style="border-top:thick double #6495ed;">
											<td rowspan=10 style="writing-mode: sideways-lr;">Mi-Vie
											</td><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
										$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c2_max_stress';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c2_min_stress';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c2_E_montant';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
										$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
										$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c2_calc_inelastic_strain';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='c2_meas_inelastic_strain';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
										$titreLigne='dim_3';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											
											$titreLigne='Cycle_STL';	echo '<tr style="border-top:thick double #6495ed;">
											<td rowspan=7 style="writing-mode: sideways-lr;">Fin d\'Essais
											</td>
											<td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.(($liste_ep[$j][$titreLigne]==0)?"":$liste_ep[$j][$titreLigne]).'</td>';}	echo '</tr>';
											$titreLigne='Ni';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='Nf75';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='Cycle_final';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											$titreLigne='Rupture';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

		

											$titreLigne='Fracture';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'"><INPUT id="'.$splitencours.'-2_'.$j.'-'.$titreLigne.'" name="'.$j."-".$titreLigne.'" value="'.$liste_ep[$j][$titreLigne].'" class="cache"><a class="pascache">'.$liste_ep[$j][$titreLigne].'</a></td>';}	echo '</tr>';









		
											$titreLigne='temps_essais';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';

											$titreLigne='flag_qualite';	echo '<tr style="border-top:thick double #6495ed;">
											<td rowspan=3 style="writing-mode: sideways-lr;">Rmq.
											</td><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.$liste_ep[$j][$titreLigne].'</td>';}	echo '</tr>';
											
											
											$titreLigne='d_commentaire';	echo '<tr><td>'.$convTitre[$titreLigne].'</td>';	FOR($j=0;$j < count($liste_ep);$j++){	echo '<td bgcolor="'.est_assigne($liste_ep[$j]['assigne']).'">'.(($liste_ep[$j][$titreLigne]=="")?"":"X").'</td>';	}	echo '</tr>';

											

										
											
											
											
											

											echo '<tr><td>Checked by</td>';	
											FOR($j=0;$j < count($liste_ep);$j++){
												echo '<td>';
												if ($liste_ep[$j]['d_checked']==0)	{

													echo '<a class="pascache" href="javascript:updateEp(\''.$liste_ep[$j]['id_eprouvette'].'\',\'data\')">
														<img id="data_'.$liste_ep[$j]['id_eprouvette'].'" alt="" src="../img/not-checked.png" style="height:30px; width:30px;" title=""/>
													</a>';
												}
												else	{
													echo '<a class="pascache">
														<img id="data_'.$liste_ep[$j]['id_eprouvette'].'" alt="" src="../img/checked.png" style="height:30px; width:30px;" title="Approved by : '.$liste_ep[$j]['data_check'].'"/>
													</a>';
												}
												
											}	
											echo '</tr>';
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


		<!--<div id="contenuSplit0" style="display:block;">	<!--SOUS TRAITANCES-->
<?php
	if (isset($tbl_soustraitances)){	?>
		<div id="contenuSplit<?php	echo $splitencours;	?>" style="display:none;">	
			<?php		//pour chaque ST
			for($l=0;$l < count($tbl_soustraitances);$l++)	{
			?>
				<?php	//Select count eprouvettes
					$req='	SELECT COUNT("id_eprouvette") AS nbtest, 
								SUM(CASE WHEN heritage is null THEN 1 ELSE 0 END) nbep
							FROM eprouvettes 
							LEFT JOIN tbljobs ON tbljobs.id_tbljob = id_job 
							WHERE eprouvette_actif = 1 
								AND id_job = '.$tbl_soustraitances[$l]['id_tbljob'].'
					';
					$req;
					$query_nbep = $db->query($req);
					$nb=mysqli_fetch_assoc($query_nbep);
				?>
				<div id="contenuOnglet<?php echo $splitencours.'-'.$l; ?>" style="<?php	echo ($l==0)?'display:block;':'display:none;';	?>">	<!--Données du job-->					
					<table border="1" cellspacing="2" style="height:90%; width:100%">
						<tbody>
							<tr>	<!--bandeau supérieur-->
								<td style="background-color:#086a87; border-color:black; height:60px">
									<div style="padding:0px; height: 100px; ">
										<ul id="onglets2">
											<?php
												for($k=0;$k < count($tbl_soustraitances);$k++)	{
													$toBeCheck=($tbl_soustraitances[$k]['checked']==0)?'background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,.5) 5px, rgba(255,255,255,.5) 10px);':'';
													$color="pink";
													echo '								
													<li><a><span style="background-color:'.$color.';'.$toBeCheck.'" onclick="changeOnglet('.$splitencours.','.$k.','.count($tbl_soustraitances).')">&nbsp;&nbsp;  '.-$tbl_soustraitances[$k]['split'].' - '.$tbl_soustraitances[$k]['type_soustraitance'].'  &nbsp;&nbsp;</span></a></li>';
												}
											?>
											
											<li id="BoutonOnglet<?php	echo $splitencours.'-'.$l;	?>" style="float:right; display:block;">
												<a href="#" class="logged pascache" onclick="checkUser();">Modification des données</a>
												<a href="#" class="cache" onclick="document.getElementById('FormcontenuaOnglet<?php	echo $splitencours.'-'.$l;	?>').submit();" class="toolbar">Maj Données</a>
											</li>
											<li class="check">
												<?php					
													if ($tbl_soustraitances[$l]['checked']==0)	{
														echo '<a href="newST.php?id_tbljob='.$tbl_soustraitances[$l]['id_tbljob'].'&toBeCheck=1\">
															<img alt="" src="../img/not-checked.png" style="height:30px; width:30px;" title="Created by : '.$tbl_soustraitances[$l]['createur'].'"/>
														</a>';										
													}
													else	{
														echo '<a>
															<img alt="" src="../img/checked.png" style="height:30px; width:30px;" title="Created by : '.$tbl_soustraitances[$l]['createur'].' &#13;Approved by : '.$tbl_soustraitances[$l]['checker'].'"/>
														</a>';
													}
												?>
											</li>
										</ul>
										<div style="padding:20px; height: 60px;">
											<table border="0" cellpadding="10" cellspacing="10" style="height:100%; width:100%">
												<tbody>
													<tr style="font-size:24px">
														<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--job-->
															<div class="titre">Numero du job</div>
															<div class="valeur" style="height:50%;"><?php	echo $tblsplit[0]['customer'].'-'.$tblsplit[0]['job'];	?></div>
															</td>
														<td style="width:15%; padding: 0px 10px 0px 10px;">	
															
														</td>
														<td class="colored" style="width:15%; padding: 0px 10px 0px 10px;">
															<div class="titre">Nombre d'eprouvette(s)</div>
															<div class="valeur" style="height:50%;" id="<?php echo $l.'-nbep';	?>"> <?php	echo $nb['nbep'].' ep - '.$nb['nbtest'].' tests';	?></div>
														</td>
														<td style="width:15%; padding: 0px 10px 0px 10px;" >												
															
														</td>
														<td style="width:15%; padding: 0px 10px 0px 10px;" class="colored">	<!--leadtime-->
															<div class="titre">Lead Time (YYYY-MM-DD)</div>
															<div class="valeur" style="height:50%;"><?php	$titreLigne="test_leadtime"; echo '<input form="FormcontenuaOnglet'.$splitencours.'-0" name="'.$tbl_soustraitances[$l]['id_tbljob']."-".$titreLigne.'" class="datepicker  modified" type="text" value="'.$tbl_soustraitances[$l][$titreLigne].'"/>';	?></div>	
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</td>					
							</tr>
							<tr>	<!--corps inférieur-->
								<td style="background-color:#086A87; border-color:black">
									<div style="margin:20px; height:95%">

									<!--feuille sous traitance <?php	echo $l;	?>-->

										<?php	//SELECT eprouvette
											$req_ep='
												SELECT id_eprouvette, prefixe, nom_eprouvette, dessin,  d_commentaire, id_tbljob, dessins.id_dessin
												FROM `eprouvettes` 
												left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job 
												left join info_jobs on info_jobs.id_info_job=tbljobs.id_info_job 
												LEFT JOIN dessins ON dessins.id_dessin=tbljobs.id_dessin
												where tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$_GET['id_tbljob'].') 
													and tbljobs.tbljob_actif=1 
													and eprouvette_actif=1 
													and heritage is null';
											//echo $req_ep;
											$req_ep = $db->query($req_ep) or die (mysql_error());	
											unset($liste_ep);	
											while ($tbl_ep = mysqli_fetch_assoc($req_ep)) {
												$liste_ep[]=$tbl_ep;
											}
																		
											$req_heritage='
												SELECT DISTINCT if(heritage is null, id_eprouvette,heritage) id_eprouvette, id_tbljob, d_commentaire
												FROM `eprouvettes` 
												left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job 
												left join info_jobs on info_jobs.id_info_job=tbljobs.id_info_job 
												where tbljobs.id_info_job = (SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$_GET['id_tbljob'].') 
													and tbljobs.tbljob_actif=1 
													and eprouvette_actif=1 ';
											//echo $req_heritage;
											$req_heritage = $db->query($req_heritage) or die (mysql_error());	
											$liste_heritage = array();			//si vide ?

											while ($tbl_heritage = mysqli_fetch_assoc($req_heritage)) {
												$liste_heritage[]=$tbl_heritage;	
												
											}

										//var_dump($liste_ep);
										//var_dump($liste_heritage);


										?>
				
									
										<form id="FormcontenuaOnglet<?php echo $splitencours.'-'.$l; ?>" action="../index.php?page=tbljobs_maj" method="POST" ENCTYPE="multipart/form-data">
											<input type="hidden" class="user" name="<?php echo $tbl_soustraitances[$l]['id_tbljob'];	?>-createur" value="">
											<table class="datajob">
												<tbody>
													<tr>
														<td style="width: 22px; padding: 0px 10px 0px 10px;" class="colored">	<!--Contact-->
															<div class="titre">Contact</div>
															<div class="valeur" style="height:50%; padding-top: 5px;">
																<?php	echo $tbl_soustraitances[$l]['lastname']." ".$tbl_soustraitances[$l]['surname'];?>
															</div>
														</td>											
														<td style="width: 23%; padding: 0px 0px 0px 10px;" class="colored">	<!--Compagnie-->
															<div class="titre">Compagnie</div>
															<div class="valeur" style="height:50%; padding-top: 5px;"><?php	echo $tbl_soustraitances[$l]['compagnie'];	?></div>
														</td>
														<td style="width: 4%">&nbsp;</td>
														<td style="width: 16%; padding: 0px 0px 0px 10px;" class="colored">	<!--matiere-->
															<div class="titre">Matiere</div><div class="valeur" style="height:50%; padding-top: 5px;">
																<INPUT name="<?php	echo $tbl_soustraitances[$l]['id_tbljob'];	?>-material" value="<?php	echo $tbl_soustraitances[$l]['material'];	?>" class="cache modified" style="float:left; font : 12px Batang, arial, serif; width:60%">
																<?php	
														
																	$titreLigne='matiere';	echo '<SELECT name="'.$tbl_soustraitances[$l]['id_tbljob']."-id_".$titreLigne.'" class="cache modified" style="font : 12px Batang, arial, serif; float:left; width:40%">
																	';
																	for($k=0;$k < count($tbl_matieres);$k++)	{
																		$selected=($tbl_matieres[$k]['id_'.$titreLigne]==$tbl_soustraitances[$l]['id_'.$titreLigne])?"selected":"";
																		echo '<option value="'.$tbl_matieres[$k]['id_'.$titreLigne].'" '.$selected.'>'.$tbl_matieres[$k][$titreLigne].'</option>
																		';	
																	}
																	echo '</select>
																	<a class="pascache" style="font : 12px Batang, arial, serif;">'.$tbl_soustraitances[$l]['material']."	-	(".$tbl_soustraitances[$l]['matiere'].')'.'</a>';
																?>	
															</div>
														</td>												
														<td style="width:16%; padding: 0px 0px 0px 10px;" class="colored">	<!--dessin-->
															<div class="titre">Dessin</div><div class="valeur" style="height:50%; padding-top: 5px;">
																<?php		
																	$titreLigne='dessin';	echo '<SELECT name="'.$tbl_soustraitances[$l]['id_tbljob']."-id_".$titreLigne.'" class="cache modified">
																	';
																	for($k=0;$k < count($tbl_dessins);$k++)	{
																		$selected=($tbl_dessins[$k]['id_'.$titreLigne]==$tbl_soustraitances[$l]['id_'.$titreLigne])?"selected":"";
																		echo '<option value="'.$tbl_dessins[$k]['id_'.$titreLigne].'" '.$selected.'>'.$tbl_dessins[$k][$titreLigne].'</option>
																		';	
																	}
																	echo '</select>
																	<a class="pascache" style="font:12px Batang,arial,serif;">'.$tbl_soustraitances[$l]['dessin'].'</a>';
																?>	
																</div>														
														</td>
														<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--envoi eprouvette-->
															<div class="titre">envoi d'eprouvette</div>
															<?php	$titreLigne='reception_eprouvette';	if ($tbl_soustraitances[$l][$titreLigne]==""){		$cache1="pascache";		$cache2="cache";	}	else	{		$cache1="cache";		$cache2="pascache";	}	echo '<div class="valeur '.$cache1.'" style="height:50%;"><input form="FormcontenuaOnglet'.$splitencours.'-'.$l.'" name="'.$tbl_soustraitances[$l]['id_tbljob'].'-'.$titreLigne.'" class="datepicker modified" type="text" value="'.$tbl_soustraitances[$l][$titreLigne].'"/></div>	<div class="valeur '.$cache2.'" style="height:50%;">'.$tbl_soustraitances[$l][$titreLigne].'</div>';	?>
														</td>
													</tr>
													<tr>
														<td style="width:23%;" class="colored" colspan=2 rowspan=2>	<!--Commentaires-->
															<div class="titre" style="height: 10%;">Commentaires</div>
															<div class="valeur" style="height:90%; padding-top: 5px;">
																<textarea class="cache modified" style="font : 18px Batang, arial, serif;" name="<?php	echo $tbl_soustraitances[$l]['id_tbljob'];	?>-tbljob_commentaire"><?php	echo $tbl_soustraitances[$l]['tbljob_commentaire'];	?></textarea>
																<textarea class="pascache" readonly style="font : 18px Batang, arial, serif;" ><?php	echo $tbl_soustraitances[$l]['tbljob_commentaire'];	?></textarea>
															</div>
														</td>
														
														<td style="width: 4%">&nbsp;</td>
														<td style="width:16%;">&nbsp;</td>
														<td style="width:16%; padding: 0px 0px 0px 10px;" class="colored">	<!--Pricing-->
															<div class="titre">Pricing</div>
															<div class="valeur" style="height:50%; padding-top: 5px;"><?php	echo $tbl_soustraitances[$l]['pricing'];	?></div>
														</td>
														<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--reception eprouvette-->
															<div class="titre">Reception d'eprouvette</div>
															<?php	$titreLigne='retour_eprouvette';	if ($tbl_soustraitances[$l][$titreLigne]==""){		$cache1="pascache";		$cache2="cache";	}	else	{		$cache1="cache";		$cache2="pascache";	}	echo '<div class="valeur '.$cache1.'" style="height:50%;"><input form="FormcontenuaOnglet'.$splitencours.'-'.$l.'" name="'.$tbl_soustraitances[$l]['id_tbljob'].'-'.$titreLigne.'" class="datepicker modified" type="text" value="'.$tbl_soustraitances[$l][$titreLigne].'"/></div>	<div class="valeur '.$cache2.'" style="height:50%;">'.$tbl_soustraitances[$l][$titreLigne].'</div>';	?>
														</td>
													</tr>
													<tr>
														
														
														<td style="width: 4%;">&nbsp;</td>
														<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--Instructions Particulières-->
															<div class="titre">Document (pdf)</div>
															<div class="valeur" style="height:50%; padding-top: 5px;">
																<INPUT class="cache modified" TYPE="file" style="font : 8px Batang, arial, serif;" name="<?php	echo $tbl_soustraitances[$l]['id_tbljob'];	?>-instructions_particulieres">
																<a class="pascache" href="javascript:popup('readPDF.php?pathfile=<?php	echo 'C:/Quality/IP/'.$tbl_soustraitances[$l]['instructions_particulieres'];	?>',595,842,'IP')" ><?php	echo $tbl_soustraitances[$l]['instructions_particulieres'];	?></a>
															</div>
														</td>													
														<td style="width: 16%; padding: 0px 0px 0px 10px;" class="colored">	<!--specification-->
															<div class="titre">Specification</div>
															<div class="valeur" style="height:50%; padding-top: 5px; font : 12px Batang, arial, serif;">
																<INPUT name="<?php	echo $tbl_soustraitances[$l]['id_tbljob'];	?>-specification" value="<?php	echo $tbl_soustraitances[$l]['specification'];	?>" class="cache  modified" style="font : 12px Batang, arial, serif;">
																<a class="pascache"><?php	echo $tbl_soustraitances[$l]['specification'];	?></a>
															</div>
														</td>	
														<td style="width:16%; padding: 0px 10px 0px 10px;" class="colored">	<!--reception rapport-->
															<div class="titre">Reception rapport</div>
															<?php	$titreLigne='retour_rapport';	if ($tbl_soustraitances[$l][$titreLigne]==""){		$cache1="pascache";		$cache2="cache";	}	else	{		$cache1="cache";		$cache2="pascache";	}	echo '<div class="valeur '.$cache1.'" style="height:50%;"><input form="FormcontenuaOnglet'.$splitencours.'-'.$l.'" name="'.$tbl_soustraitances[$l]['id_tbljob'].'-'.$titreLigne.'" class="datepicker modified" type="text" value="'.$tbl_soustraitances[$l][$titreLigne].'"/></div>	<div class="valeur '.$cache2.'" style="height:50%;">'.$tbl_soustraitances[$l][$titreLigne].'</div>';	?>
														</td>
													</tr>
													<tr>
														<td style="height: 354px; padding: 0px 10px 0px 10px;" class="colored" colspan=6>	<!--ep-->
															<div class="titre" style="height: 7%;">
																<div style="float:left;">Eprouvettes
																	<button style="margin-left: 30px;" class="append_column" value="Add column" onclick="newRow('ST_<?php	echo $l;	?>','<?php	echo $tbl_soustraitances[$l]['id_tbljob'];	?>')" type="button">
																		<img src="../css/croix.png">
																	</button>
																</div>
																<div style="float:right;">
																	<?php	

																	for($i=0;$i < count($tblsplit);$i++)	{
																		echo '<div class="onglets3" id="STSplit_'.$l.'-'.$tblsplit[$i]['id_tbljob'].'"><a><span onclick="changeSTSplit('.$l.','.$tblsplit[$i]['id_tbljob'].')" id="OngletSplit'.$tblsplit[$i]['split'].'">&nbsp;&nbsp;  '.$tblsplit[$i]['split'].' - '.$tblsplit[$i]['type_essai'].'  &nbsp;&nbsp;</span></a></div>
																	';}
																	
																	for($k=0;$k < count($tbl_soustraitances);$k++)	{
																		if ($k==$l)
																			echo '<div class="onglets3" id="STSplit_'.$l.'-'.$tbl_soustraitances[$k]['id_tbljob'].'" ><a></a></div>';
																		else
																			echo '<div class="onglets3" id="STSplit_'.$l.'-'.$tbl_soustraitances[$k]['id_tbljob'].'" ><a><span onclick="changeSTSplit('.$l.','.$tbl_soustraitances[$k]['id_tbljob'].')">&nbsp;&nbsp;  '.-$tbl_soustraitances[$k]['split'].' - '.$tbl_soustraitances[$k]['type_soustraitance'].'  &nbsp;&nbsp;</span></a></div>';

																	}

																	?>
																</div>
															</div>
															<div class="valeur" style="height:90%; overflow:auto; padding-top: 5px;">



																<!--contenu du tableau de la sous traitance-->												
																<table class="job2"  id="ST_<?php	echo $l;	?>">
																	<tr>
																		<th><input type="checkbox" id="<?php	echo 'btn-'.$l	?>" onchange="toggleCheckbox(this,'<?php	echo $l	?>')" value=""></th>
																		<th>Nom ep</th>
																		<th>Drawing</th>
																		<th style="width:800px;">Commentaires</th>
																	</tr>


																	<?php	//eprouvettes crées
																		for($i=0;$i < count($liste_ep);$i++)	{
																			$checked="";																			
																			$class="";
																			$visibility="";
																			
																			foreach ($liste_heritage as $value){		//recherche des jobs où se trouvent les eprouvettes et heritages
																				if ($value['id_eprouvette']==$liste_ep[$i]['id_eprouvette']){																										
																					$class.='ST_'.$l.'-'.$value['id_tbljob']." ";
																					if($value['id_tbljob']==$tbl_soustraitances[$l]['id_tbljob'])	{
																							$checked="checked";
																							$visibility="visibility:visible";
																							$liste_ep[$i]['d_commentaire']=$value['d_commentaire'];
																					}
																					
																				}
																			}
																			$visibility=($visibility=="visibility:visible")?"visibility:visible":"visibility:collapse";					
																			echo '	
																			<tr class="'.$class.'" style="'.$visibility.'">
																					<INPUT type="hidden" name="ep-'.$liste_ep[$i]['id_eprouvette'].'-id_eprouvette" value="'.$liste_ep[$i]['id_eprouvette'].'">
																				<td><input type="checkbox" id="ST_'.$l.'_'.$liste_ep[$i]['id_eprouvette'].'" class="ST_'.$l.'" value="'.$liste_ep[$i]['id_eprouvette'].'" '.$checked.' style="'.$visibility.'" name="ep-'.$liste_ep[$i]['id_eprouvette'].'-checkbox"></td>
																				<td>
																					'.$liste_ep[$i]['prefixe'].' '.$liste_ep[$i]['nom_eprouvette'].'
																					<INPUT type="hidden" name="ep-'.$liste_ep[$i]['id_eprouvette'].'-prefixe" value="'.$liste_ep[$i]['prefixe'].'">
																					<INPUT type="hidden" name="ep-'.$liste_ep[$i]['id_eprouvette'].'-nom_eprouvette" value="'.$liste_ep[$i]['nom_eprouvette'].'">
																				</td>
																				<td contenteditable="true">
																					'.$liste_ep[$i]['dessin'].'
																				</td>
																				<td>																				
																					<textarea class="cache modified" style="font : 18px Batang, arial, serif;" name="ep-'.$liste_ep[$i]['id_eprouvette'].'-d_commentaire">'.$liste_ep[$i]['d_commentaire'].'</textarea>
																					<div class="pascache" style="font : 18px Batang, arial, serif;">'.$liste_ep[$i]['d_commentaire'].'</div>																				
																				</td>
																			</tr>
																				';
																		}
																	?>

				
																</table>													
			

															</div>
														</td>
													</tr>

												</tbody>
											</table>
										</form>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			<?php
			}
		?>
		</div>

	<?php
	}
?>









<!-- The Modal -->
<div id="myModal" class="modal">	<!--	USER	-->
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
					<a id="login" class="valid" onclick="logon();">
						<img alt="" src="../img/checked.png" style="height:40px; width:35px;" />
					</a>
				</td>
			</tr>
			<tr>
				<span onclick = "modal.style.display = 'none'">CLOSE</span>
			</tr>
		</table>
	</form>
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


// When the user clicks the button, open the modal 
for(var i = 0; i < btn.length; i++) {
	btn[i].onclick= function() {
		modal.style.display = "block";
	}
}



// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
	   if (event.target == modalNewTest) {
        modalNewTest.style.display = "none";
    }
}
</script>
</div>


<!-- The ModalNewTest -->
<div id="myModalNewTest" class="modalNewTest">	<!--	newTest	-->

  <!-- ModalNewTest content -->
  <div class="modalNewTest-content" style="margin:300px; height:200px">
	  <form  action="newTest" method="POST" name="formNewTest" onsubmit="return envoiNewTest()">	
		<table class="datajob">
			<tr>
				<td class="colored">
					<div class="titre">Ep</div>
					<div id="eprouvettenewtest" class="valeur" style="height:50%; padding-top: 5px;">
						nom de l'ep
					</div>
					<input type="hidden" id="id_eprouvettenewTest" name="id_eprouvette" value="">
				</td>
				<td class="colored">
					<div class="titre">Operateur</div>
					<div id ="usernewTest" class="valeur" style="height:50%; padding-top: 5px;">
						Merci de vous connecter avant
					</div>
					<input type="hidden" class="user" name="operateur" value="">
				</td>
			</tr>
			<tr>
				<td class="colored">
					<div class="titre">Poste</div>
					<div class="valeur" style="height:50%; padding-top: 5px;">
						<?php		
							$titreLigne='poste';	echo '<SELECT id="poste" name="'.$titreLigne.'"><option value="0">-</option>
							';
							
							echo '</select>
							';
						?>
					</div>
				</td>
				<td class="colored">
					<div class="titre">Checkeur</div>
					<div class="valeur" style="height:50%; padding-top: 5px;">
						<?php		
							$titreLigne='technicien';	echo '<SELECT id="checkeur" name="checkeur"><option value="0">-</option>
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
				<td>
					<input type="image" src="../img/checked.png" style="height:40px; width:35px;" alt="ok" />.
				</td>
			</tr>
			<tr>
				<span style="color:white" onclick = "modalNewTest.style.display = 'none'">CLOSE</span>
			</tr>
		</table>
	</form>
  </div>

<style>
 /* The ModalNewTest (background) */
.modalNewTest {
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

/* ModalNewTest Content/Box */
.modalNewTest-content {
    background-color: #086a87;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 40%; /* Could be more or less, depending on screen size */
}
</style>
<script type="text/javascript">	//envoi formulaire newTest
//<![CDATA[

function envoiNewTest(){
	// si la valeur du champ chekeur, poste ou operateur est vide
	if((document.formNewTest.checkeur.value == 0)||(document.formNewTest.poste.value == 0)||(document.formNewTest.operateur.value == "")) {
		alert("Veuillez remplir tous les champs");
		// et on indique de ne pas envoyer le formulaire
		return false;
	}
	else {
		// les données sont ok, on peut envoyer le formulaire    
		return true;
	}
}

//]]>
</script>
<script>
// Get the modalNewTest
var modalNewTest = document.getElementById('myModalNewTest');
</script>
</div>


<script>	//couleur si on change une valeur
	$( ".modified" ).change(function() {
		$(this).css('background-color' , '#FF0000');
	});
</script>
	




<!--
A NOTER SI BESOIN
 <input type="text" class="taillemodifiable" placeholder="Placeholder Text" />
  <input type="text" class="taillemodifiable" placeholder="Placeholder Text" />
<style>
.taillemodifiable {
  font-size: 100%;
  outline: none;
  width: 200px;
  height: 30px;
  display: table-cell; 
  vertical-align: middle;
  border: 1px solid #ccc;
}
</style>
<script>
$('.taillemodifiable').keypress(function() {
  
  var textLength = $(this).val().length;
  
  if(textLength < 20) {
   // Do noting 
  } else if (textLength < 30) {
      $(this).css('font-size', '85%');
  } else if (textLength < 40) {
      $(this).css('font-size', '70%');
  } else if (textLength < 50) {
	$(this).css('font-size', '50%');
  } else if (textLength < 60) {  
	$(this).css('font-size', '20%');
  }
  
 console.log(textLength);
});
</script>

-->











</body>
<!--Au chargement de la page-->
<?php	//Affichage du split selectionné du tableau des jobs
$req="select split from tbljobs where id_tbljob=".$_GET['id_tbljob'];
$req_split = $db->query($req);
	$split = mysqli_fetch_array($req_split);

if ( $split['split']>0)	{
	for($k=0;$k < count($tblsplit);$k++)	{
		if ($tblsplit[$k]['id_tbljob']==$_GET['id_tbljob'])	{
				echo '<script type="text/javascript">
					changeSplit('.($k+1).', '.$nombresplit.');
				</script>';
		}
	}
}
else

	for($k=0;$k < count($tbl_soustraitances);$k++)	{
		if ($tbl_soustraitances[$k]['id_tbljob']==$_GET['id_tbljob'])	{
				echo '<script type="text/javascript">
					changeSplit('.($nombresplit-1).', '.$nombresplit.');
					changeOnglet('.($nombresplit-1).','.$k.','.count($tbl_soustraitances).');
				</script>';
		}
	}
?>
<script type="text/javascript">	<!--affichage par defaut des valeurs modifiable ou non / input class="cache/pascache"	-->
	cachetruc();
	showLogged("n");
	logon();
</script>
<!--
<a href="mailto:lastname.firstname@xxx.com
?subject=APPname%20support%20issue
&body=Version%20x.x%0D%0A%0D%0APlease%20make%20some%20descriptions%20here:%0D%0A%0D%0A%0D%0A
&attach=""Y:\BDD\Summary\45939-DataBDD.csv""">Clique ici pour m'envoyer un e-mail</a>
-->