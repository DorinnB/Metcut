<SCRIPT language="javascript">
   function testerRadio(radio) {
      for (var i=0; i<radio.length;i++) {
         if (radio[i].checked) {
			if (window.confirm('Etes vous sur de vouloir modifier l\'essai n° '+radio[i].value+' ?')) {document.modif.submit();} else {return false;}
         }
      }
   }
</SCRIPT>

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
echo '
<FORM method="POST" name="modif" action="index.php?page=modifessais">
<table class="liste">
	<tr>
		<th><a href="index.php?page=listeessais&tri=n_fichier'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">n° Fichier</a>'.fsens("&tri=n_fichier", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=type_essai'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Type d\'Essai</a>'.fsens("&tri=type_essai", $tri, $sens).'</th>		
		<th><a href="index.php?page=listeessais&tri=eprouvettes.temperature'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Température</a>'.fsens("&tri=eprouvettes.temperature", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=n_job'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">n° du Job</a>'.fsens("&tri=n_job", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=n_essai'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">n° Essais</a>'.fsens("&tri=n_essai", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=prefixe'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Prefixe</a>'.fsens("&tri=prefixe", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=nom_eprouvette'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Eprouvette</a>'.fsens("&tri=nom_eprouvette", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=machine'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Machine</a>'.fsens("&tri=machine", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=acquisition'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Acquisition</a>'.fsens("&tri=acquisition", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=enregistrementessais.date'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Date</a>'.fsens("&tri=enregistrementessais.date", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=tech1.technicien'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">operateur</a>'.fsens("&tri=tech1.technicien", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=tech2.technicien'.$sensinv.$temps1.$cat.$val.$nburl.$avance.'#menu">Controleur</a>'.fsens("&tri=tech2.technicien", $tri, $sens).'</th>
		<th><INPUT type="button" value="Edit"
   onClick="testerRadio(this.form.edit)"></th>
	</tr>';

$sql_liste='	
	SELECT tbljobs.id_tbljob, n_fichier, type_essai, eprouvettes.c_temperature, customer, job, split, n_essai, prefixe, nom_eprouvette, machine, acquisition, enregistrementessais.date, tech1.technicien AS operateur, tech2.technicien AS controleur
	FROM enregistrementessais
	LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
	LEFT JOIN tbljobs ON eprouvettes.id_job = tbljobs.id_tbljob
    LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
	LEFT JOIN type_essais ON tbljobs.id_type_essai = type_essais.id_type_essai
	LEFT JOIN postes ON enregistrementessais.id_poste = postes.id_poste
	LEFT JOIN machines ON postes.id_machine = machines.id_machine
	LEFT JOIN outillages o1 ON postes.id_outillage_top = o1.id_outillage
	LEFT JOIN outillages o2 ON postes.id_outillage_bot = o2.id_outillage
	LEFT JOIN extensometres ON postes.id_extensometre = extensometres.id_extensometre
	LEFT JOIN chauffages ON postes.id_chauffage = chauffages.id_chauffage
	LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition
	LEFT OUTER join techniciens tech1 ON enregistrementessais.id_operateur = tech1.id_technicien
	LEFT OUTER join techniciens tech2 ON enregistrementessais.id_controleur = tech2.id_technicien
	'.$filtre.' '.$filtreavance.'
	ORDER BY '.$range.'
	LIMIT '.$nb
	;
$nbligne=0;

    $req_liste = $db->query($sql_liste) or die (mysql_error());
	if ($req_liste) {
		while ($tbl_liste = mysqli_fetch_array($req_liste)) {
		$job=$tbl_liste['customer'].'-'.$tbl_liste['job'].((isset($tbl_liste['split']))? '-'.$tbl_liste['split'] : "");
		$nbligne++;
			echo '<tr>
				<td>'.$tbl_liste['n_fichier'].'</td>
				<td><a href="index.php?page=listeessais&cat=type_essai&val='.$tbl_liste['type_essai'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$tbl_liste['type_essai'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=eprouvettes.temperature&val='.$tbl_liste['c_temperature'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$tbl_liste['c_temperature'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=tbljobs.id_tbljob&val='.$tbl_liste['id_tbljob'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$job.'</a></td>				
				<td>'.$tbl_liste['n_essai'].'</td>
				<td><a href="index.php?page=listeessais&cat=prefixe&val='.$tbl_liste['prefixe'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$tbl_liste['prefixe'].'</a></td>
				<td>'.$tbl_liste['nom_eprouvette'].'</td>
				<td><a href="index.php?page=listeessais&cat=machine&val='.$tbl_liste['machine'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$tbl_liste['machine'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=acquisition&val='.$tbl_liste['acquisition'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$tbl_liste['acquisition'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=enregistrementessais.date&val='.$tbl_liste['date'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.date("d M y", strtotime($tbl_liste['date'])).'</a></td>
				<td><a href="index.php?page=listeessais&cat=tech1.technicien&val='.$tbl_liste['operateur'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$tbl_liste['operateur'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=tech2.technicien&val='.$tbl_liste['controleur'].$temps1.$tri.$sens.$avance.$nburl.'#menu">'.$tbl_liste['controleur'].'</a></td>				
				<td><INPUT TYPE="radio" NAME="edit" VALUE="'.$tbl_liste['n_fichier'].'"></td>
			';
		}
	}

	?>	
</table>
</form>



<?php 
echo 'Nombre de ligne affiché : '.$nbligne.'<br/><br/>';
if(!isset($_GET['nb']))
	echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$avance.$sens.'&nb=30#menu">Afficher les 30 premiers resultats uniquement</a><br/>
		<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$avance.$sens.'&nb=99999#menu">Afficher tous les resultats</a>';

		
if(isset($_GET['nb']))	{
	if($_GET['nb']!=15)
		echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$avance.$sens.'#menu">Afficher les 15 premiers resultats uniquement</a><br/>';
	if($_GET['nb']!=30)
		echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$avance.$sens.'&nb=30#menu">Afficher les 30 premiers resultats uniquement</a><br/>';
	if($_GET['nb']!=99999)
		echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$avance.$sens.'&nb=99999#menu">Afficher tous les resultats</a><br/>';
}

if(isset($_GET['cal']) OR isset($_GET['val']) OR isset($_GET['tri']) OR isset($_GET['SearchField']))
 echo '<br/><br/><a href="index.php?page=listeessais#menu">Ré-initialiser les filtres</a>';

?>






<div style="width: auto; padding: 10px; margin-top: 10px;"><!-- -->
<form method="GET" name="SearchForm" style="padding: 0px; margin: 0px; vertical-align: middle;">
	<input type="hidden" name="page" value="listeessais" />
    <input type="hidden" name="filtreavance" value="1" />
    <b>Recherche avancée : </b> &nbsp;&nbsp;&nbsp

    <select name="SearchField" id="SearchField">
        <option value="1" selected>-</option>
		<option value="jobs.id_job" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="jobs.id_job"))? "selected" : " "; ?>>id du job</option>
		<option value="n_fichier" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="n_fichier"))? "selected" : " "; ?>>N° de fichier</option>
		<option value="control" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="control"))? "selected" : " "; ?>>Mode de control</option>
		<option value="type_essai" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="type_essai"))? "selected" : " "; ?>>Type d'essai</option>
		<option value="eprouvettes.temperature" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="eprouvettes.temperature"))? "selected" : " "; ?>>Temperature d'eprouvette</option>
		<option value="n_client" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="n_client"))? "selected" : " "; ?>>N° client</option>
		<option value="n_job" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="n_job"))? "selected" : " "; ?>>N° du job</option>
		<option value="indice" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="indice"))? "selected" : " "; ?>>Indice du job</option>
		<option value="n_essai" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="n_essai"))? "selected" : " "; ?>>N° d'essai</option>
		<option value="prefixe" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="prefixe"))? "selected" : " "; ?>>Prefixe d'eprouvette</option>
		<option value="nom_eprouvette" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="nom_eprouvette"))? "selected" : " "; ?>>Nom d'eprouvette</option>
		<option value="machine" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="machine"))? "selected" : " "; ?>>Machine</option>
		<option value="acquisition" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="acquisition"))? "selected" : " "; ?>>Mode d'acquisition</option>
		<option value="enregistrementessais.date" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="enregistrementessais.date"))? "selected" : " "; ?>>Date d'enregistrement d'essais</option>
		<option value="tech1.technicien" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="tech1.technicien"))? "selected" : " "; ?>>Operateur</option>
		<option value="tech2.technicien" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="tech2.technicien"))? "selected" : " "; ?>>Controleur</option>
<option value="jobs.rapport" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="rapport"))? "selected" : " "; ?>>Rapport</option>
<option value="Format" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="Format"))? "selected" : " "; ?>>Format d'eprouvette</option>
<option value="Matiere" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="Matiere"))? "selected" : " "; ?>>Matiere d'eprouvette</option>
<option value="jobs.Frequence" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="jobs.Frequence"))? "selected" : " "; ?>>Frequence (job)</option>
<option value="eprouvettes.frequence" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="eprouvettes.frequence"))? "selected" : " "; ?>>Frequence (eprouvette)</option>
<option value="extensometre" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="extensometre"))? "selected" : " "; ?>>Extensometre</option>
<option value="chauffage" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="chauffage"))? "selected" : " "; ?>>Chauffage</option>
<option value="o1.outillage" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="outillage"))? "selected" : " "; ?>>Outillage Top</option>
<option value="o2.outillage" <?php echo (isset($_GET['SearchField']) AND ($_GET['SearchField']=="outillage"))? "selected" : " "; ?>>Outillage Bot</option>
    </select>
&nbsp;
    <select name="FilterType" id="FilterType">
        <option value="=" <?php echo (isset($_GET['FilterType']) AND ($_GET['FilterType']=="="))? "selected" : " "; ?>>&eacute;gal</option>
        <option value="<>" <?php echo (isset($_GET['FilterType']) AND ($_GET['FilterType']=="<>"))? "selected" : " "; ?>>non &eacute;gal</option>
        <option value="<" <?php echo (isset($_GET['FilterType']) AND ($_GET['FilterType']=="<"))? "selected" : " "; ?>>inf&eacute;rieur</option>
        <option value="<=" <?php echo (isset($_GET['FilterType']) AND ($_GET['FilterType']=="<="))? "selected" : " "; ?>>inf&eacute;rieur ou &eacute;gal</option>
        <option value=">" <?php echo (isset($_GET['FilterType']) AND ($_GET['FilterType']==">"))? "selected" : " "; ?>>sup&eacute;rieur</option>
        <option value=">=" <?php echo (isset($_GET['FilterType']) AND ($_GET['FilterType']==">="))? "selected" : " "; ?>>sup&eacute;rieur ou &eacute;gal</option>
        <option value="LIKE" <?php echo (!isset($_GET['FilterType']) OR ($_GET['FilterType']=="LIKE"))? "selected" : " "; ?>>contient</option>
    </select>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" size="20" name="FilterText" id="FilterText" value="<?php echo (isset($_GET['FilterText']))? $_GET['FilterText'] : ""; ?>">
    &nbsp;
    <input type="submit" class="sm_button" value="Lancer la recherche">

</form>

</div>


</br></br></br><div>
<a href="index.php?page=synthese#menu">Synthèse</a><br/>
<a href="index.php?page=listeessaisimpression&tri=n_fichier<?php echo $sens.$temps1.$cat.$val.$avance.$nburl;?>#menu">Exportation sous Excel</a>
</div>

<div class="todo">TODO :<br/>
Ajout plage de date<br/>
Lien vers synthèse en plus joli<br/>
</div>