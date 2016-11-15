<?php
function cellule($l,$c)	{
	global $ligne;
	return $ligne[$l-1][$c-1];
}

$filename = $_FILES['fichier']['tmp_name'];	
$ligne= file($filename); //lit le fichier entier et le place dans un tableau
$nbTotalLignes=count($ligne);
for($i=0;$i<$nbTotalLignes;$i++){
	// On place chaque élément séparé par un ; dans un tableau
	$ligneTab = explode(";",$ligne[$i]); 
	$ligne[$i]=$ligneTab;
}
?>

<?php


for($i=1;$i<$nbTotalLignes;$i++)	{	//Check si le nom du job est le meme partout
	if($ligne[1][0]==$ligne[$i][0])	{	}
	else
		exit('Nom de job different dans tout le fichier excel.');
}
$job=$ligne[1][0];
	
	
		//Recuperation des variable du job
$tbljob = explode('-', $job);
if(isset($tbljob[2]))	{
	$indice='indice = "'.$tbljob[2].'"';
	$indice2='"'.$tbljob[2].'"';
}
else	{
	$indice='indice IS NULL';
	$indice2="NULL";
}

$control=$ligne[1][1];

					// temperature
$temp=array();
$R1=array();
$F1=array();
for($i=1;$i<$nbTotalLignes;$i++)	{
	$temp[]=$ligne[$i][2];
	$R1[]=$ligne[$i][3];
	$F1[]=$ligne[$i][4];
}
$temp=array_unique($temp);
$temperature="";
foreach($temp as $key => $value)	{
	if($temperature!="")
		$temperature.=" - ";
	$temperature.=$value;
}

					// R
$R1=array_unique($R1);
$R="";
foreach($R1 as $key => $value)	{
	if($R!="")
		$R.=" - ";
	$R.=$value;
}

					// Frequence
$F1=array_unique($F1);
$F="";
foreach($F1 as $key => $value)	{
	if($F!="")
		$F.=" - ";
	$F.=$value;
}


$format=$ligne[1][5];
$matiere=$ligne[1][6];
$forme_cycle=$ligne[1][7];
$date=date('Y-m-d', mktime(0,0,0,1,$ligne[1][8]-25568,1970));




$preparateur=$ligne[1][9];
//$controleur=$ligne[1][10];
$controleur=$_POST['controleur'];
$id_preparateur=0; //si pas de preparateur connu => 0
$type=$_POST['type'];

$commentaire=($_POST['commentaire']!="")? '"'.mysql_escape_string(addslashes($_POST['commentaire'])).'"' : 'NULL';


$req_operateur = mysql_query('SELECT id_technicien, technicien FROM techniciens WHERE technicien_actif=1;') or die (mysql_error());
while ($tbl_operateur = mysql_fetch_assoc($req_operateur)) {
	if($preparateur == $tbl_operateur['technicien'])
		$id_preparateur=$tbl_operateur['id_technicien'];
}


$STL=$ligne[1][11];
$FSTL=str_replace(',', '.', $ligne[1][12]);
$suivi_extenso=$ligne[1][13];
$arret=$ligne[1][14];
$cycle=$ligne[1][15];




		//Vérification si le job n'existe pas deja dans la BDD
$req_verif = mysql_query('SELECT n_client, n_job, indice, job_actif FROM jobs WHERE n_client = '.$tbljob[0].' AND n_job = '.$tbljob[1].' AND '.$indice.' AND job_actif = 1 ;') or die (mysql_error());
if (mysql_num_rows($req_verif)>=1)
	exit('Probleme lors de l\'enregistrement.<br/> le job est déjà enregistré !');

?>
<div style="margin: 5px 20px 20px;">
	<div style="margin-bottom: 2px">
		<ins>Code SQL pour le job</ins>: <input type="button" value="Show" onclick="
			(function(input, content) {
				input.value = content.style.display == 'none' ? 'Hide' : 'Show';
				content.style.display = content.style.display == 'none' ? 'block' : 'none';
			})(this, this.parentNode.nextSibling.getElementsByTagName('div')[0])" />
	</div><div style="border: 1px inset; margin: 0px; padding: 6px">
		<div style="display: none">

<?php
		//Enregistrement du job dans la BDD
	$ajoutjob='INSERT INTO metcut.jobs (n_client, n_job ,indice, control, id_type_essai, temperature, rapport, frequence, format, matiere, forme_cycle, date, preparateur, controleur, STL, F_STL, suivi_extenso, arret, arret_cycle, job_commentaire, job_actif)
	VALUES ('.$tbljob[0].', '.$tbljob[1].', '.$indice2.', "'.$control.'", "'.$type.'", "'.$temperature.'", "'.$R.'", "'.$F.'", "'.$format.'", "'.$matiere.'", "'.$forme_cycle.'", "'.$date.'", "'.$id_preparateur.'", "'.$controleur.'", "'.$STL.'", "'.$FSTL.'", "'.$suivi_extenso.'", "'.$arret.'", "'.$cycle.'", '.$commentaire.', 0)';


	echo $ajoutjob.'<br/>';	
	mysql_query($ajoutjob);
	$id_job = mysql_insert_id();

	


	
for($i=1;$i<$nbTotalLignes;$i++)	{	// pour chaque ligne
	if($ligne[$i][17]!="" AND $ligne[$i][17]!="0")	{	//s'il y a une eprouvette
	
			//Recuperation des variable de l'eprouvette
		$ep_prefixe=($ligne[$i][16]=="")? "NULL": '"'.$ligne[$i][16].'"';
		$ep_nom=$ligne[$i][17];
		$ep_temp=($ligne[$i][18]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][18]).'"';
		$ep_F=($ligne[$i][19]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][19]).'"';
		$ep_deltaepsilon=($ligne[$i][20]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][20]).'"';
		$ep_epsilonmax=($ligne[$i][21]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][21]).'"';
		$ep_max=($ligne[$i][22]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][22]).'"';
		$ep_moy=($ligne[$i][23]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][23]).'"';
		$ep_alt=($ligne[$i][24]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][24]).'"';
		$ep_min=($ligne[$i][25]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][25]).'"';
		$ep_cyclemin=($ligne[$i][26]=="")? "NULL": '"'.str_replace(',', '.', $ligne[$i][26]).'"';
		
		$ep_assigne=(is_numeric($ligne[$i][18]) OR $ligne[$i][18]=="")? "NULL": "1";

		
				// condition eprouvette "a ne pas tester", ajout assigne=1

			$ajoutep='INSERT INTO metcut.eprouvettes (prefixe, nom_eprouvette, id_job, Temperature, Frequence, Deltaepsilon, Epsilonmax, Niveau_max, Niveau_moy, Niveau_alt, Niveau_min, Cycle_min, assigne, eprouvette_actif)
					VALUES ('.$ep_prefixe.',"'.$ep_nom.'", '.$id_job.', '.$ep_temp.', '.$ep_F.', '.$ep_deltaepsilon.', '.$ep_epsilonmax.', '.$ep_max.', '.$ep_moy.', '.$ep_alt.', '.$ep_min.', '.$ep_cyclemin.', '.$ep_assigne.', 1)';		

		echo $ajoutep.'<br/>';
		mysql_query($ajoutep);
	}
}

// Vérification s'il n'y a pas 2 éprouvettes avec le même nom

?>
		</div>
	</div>
	
	<?php
	$req_doublon=mysql_query("SELECT CONCAT_WS( ' ', prefixe, nom_eprouvette ) , count( CONCAT_WS( ' ', prefixe, nom_eprouvette ) ) , prefixe, nom_eprouvette
		FROM eprouvettes
		LEFT JOIN jobs ON jobs.id_job = eprouvettes.id_job
		WHERE jobs.id_job =".$id_job."
		GROUP BY CONCAT_WS( ' ', prefixe, nom_eprouvette )
		HAVING count( CONCAT_WS( ' ', prefixe, nom_eprouvette ) ) >1") or die (mysql_error());
	if (mysql_num_rows($req_doublon)>=1)
	echo "ATTENTION ! Il y a au moins 1 eprouvette présente 2 fois dans ce job";
	?>
	
	
</div>
	
	
<form method="post" name="envoi" action="index.php?page=listejob">
	<input type="hidden" name="job" value="<?php echo $id_job;	?>">
	<input type="hidden" name="validation" value="1">
	<input type="submit" value="Submit">
</form>