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
		<th><a href="index.php?page=listeessais&tri=n_fichier'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">n° Fichier</a>'.fsens("&tri=n_fichier", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=control'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Control</a>'.fsens("&tri=control", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=type_essai'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Type d\'Essai</a>'.fsens("&tri=type_essai", $tri, $sens).'</th>		
		<th><a href="index.php?page=listeessais&tri=eprouvettes.temperature'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Température</a>'.fsens("&tri=eprouvettes.temperature", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=n_job'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">n° du Job</a>'.fsens("&tri=n_job", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=n_essai'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">n° Essais</a>'.fsens("&tri=n_essai", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=prefixe'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Prefixe</a>'.fsens("&tri=prefixe", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=nom_eprouvette'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Eprouvette</a>'.fsens("&tri=nom_eprouvette", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=machine'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Machine</a>'.fsens("&tri=machine", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=acquisition'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Acquisition</a>'.fsens("&tri=acquisition", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=enregistrementessais.date'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Date</a>'.fsens("&tri=enregistrementessais.date", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=tech1.technicien'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">opérateur</a>'.fsens("&tri=tech1.technicien", $tri, $sens).'</th>
		<th><a href="index.php?page=listeessais&tri=tech2.technicien'.$sensinv.$temps1.$cat.$val.$nburl.'#menu">Controleur</a>'.fsens("&tri=tech2.technicien", $tri, $sens).'</th>
		<th><INPUT type="button" value="Edit"
   onClick="testerRadio(this.form.edit)"></th>
	</tr>';

$sql_liste='	
	SELECT jobs.id_job, n_fichier, control, type_essai, eprouvettes.temperature, n_client, n_job, indice, n_essai, prefixe, nom_eprouvette, machine, acquisition, enregistrementessais.date, tech1.technicien AS opérateur, tech2.technicien AS controleur
	FROM enregistrementessais
	LEFT JOIN eprouvettes ON enregistrementessais.id_eprouvette = eprouvettes.id_eprouvette
	LEFT JOIN jobs ON eprouvettes.id_job = jobs.id_job
	LEFT JOIN type_essais ON jobs.id_type_essai = type_essais.id_type_essai
	LEFT JOIN machines ON enregistrementessais.id_machine = machines.id_machine
	LEFT JOIN acquisitions ON enregistrementessais.id_acquisition = acquisitions.id_acquisition
	left outer join techniciens tech1 on enregistrementessais.id_operateur = tech1.id_technicien
	left outer join techniciens tech2 on enregistrementessais.id_controleur = tech2.id_technicien
	'.$filtre.'
	ORDER BY '.$range.'
	LIMIT '.$nb
	;
$nbligne=0;

    $req_liste = mysql_query($sql_liste) or die (mysql_error());
	if ($req_liste) {
		while ($tbl_liste = mysql_fetch_assoc($req_liste)) {
		$job=$tbl_liste['n_client'].'-'.$tbl_liste['n_job'].((isset($tbl_liste['indice']))? '-'.$tbl_liste['indice'] : "");
		$nbligne++;
			echo '<tr>
				<td>'.$tbl_liste['n_fichier'].'</td>
				<td><a href="index.php?page=listeessais&cat=control&val='.$tbl_liste['control'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['control'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=type_essai&val='.$tbl_liste['type_essai'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['type_essai'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=eprouvettes.temperature&val='.$tbl_liste['temperature'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['temperature'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=jobs.id_job&val='.$tbl_liste['id_job'].$temps1.$tri.$sens.$nburl.'#menu">'.$job.'</a></td>				
				<td>'.$tbl_liste['n_essai'].'</td>
				<td><a href="index.php?page=listeessais&cat=prefixe&val='.$tbl_liste['prefixe'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['prefixe'].'</a></td>
				<td>'.$tbl_liste['nom_eprouvette'].'</td>
				<td><a href="index.php?page=listeessais&cat=machine&val='.$tbl_liste['machine'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['machine'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=acquisition&val='.$tbl_liste['acquisition'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['acquisition'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=enregistrementessais.date&val='.$tbl_liste['date'].$temps1.$tri.$sens.$nburl.'#menu">'.date("d M y", strtotime($tbl_liste['date'])).'</a></td>
				<td><a href="index.php?page=listeessais&cat=tech1.technicien&val='.$tbl_liste['opérateur'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['opérateur'].'</a></td>
				<td><a href="index.php?page=listeessais&cat=tech2.technicien&val='.$tbl_liste['controleur'].$temps1.$tri.$sens.$nburl.'#menu">'.$tbl_liste['controleur'].'</a></td>				
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
	echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$sens.'&nb=30#menu">Afficher les 30 premiers resultats uniquement</a><br/>
		<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$sens.'&nb=99999#menu">Afficher tous les resultats</a>';

		
if(isset($_GET['nb']))	{
	if($_GET['nb']!=15)
		echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$sens.'#menu">Afficher les 15 premiers resultats uniquement</a><br/>';
	if($_GET['nb']!=30)
		echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$sens.'&nb=30#menu">Afficher les 30 premiers resultats uniquement</a><br/>';
	if($_GET['nb']!=99999)
		echo '<a href="index.php?page=listeessais'.$temps1.$cat.$val.$tri.$sens.'&nb=99999#menu">Afficher tous les resultats</a><br/>';
}

if(isset($_GET['cal']) OR isset($_GET['val']) OR isset($_GET['tri']))
 echo '<br/><br/><a href="index.php?page=listeessais#menu">Ré-initialiser les filtres</a>';

?>

</br></br></br><div>
<a href="index.php?page=synthese#menu">Synthèse</a><br/>
<a href="index.php?page=listeessaisimpression&tri=n_fichier<?php echo $sens.$temps1.$cat.$val.$nburl;?>#menu">Exportation sous Excel</a>
</div>

<div class="todo">TODO :<br/>
Ajout plage de date<br/>
Lien vers synthèse en plus joli<br/>
</div>