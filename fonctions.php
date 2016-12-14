<?php
function Connectionsql() {
	global $db; 
	$db = mysqli_connect('localhost', 'root', '', 'Metcut');

		/* Modification du jeu de résultats en utf8 */
	$db->set_charset("utf8");
		//printf("Jeu de caractère : %s\n", $db->character_set_name());

		
		
		// ATTENTION $db est aussi defini dans envoilog. Toute modif doit etre reportée la bas
}
//fleche up/down des tris
function fsens($colonne, $tri, $sens) {
global $fleche;
if($colonne==$tri) {
  if($sens=="&sens=ASC") {
    $fleche='&nbsp; &darr;';}
  else {
    $fleche= '&nbsp; &uarr;';}}
else {
  $fleche='';  }
  
return $fleche;
}

//code couleur des jobs
function colorstatut($val) {
$color='#FFFFFF';
	global $color; 
	if ($val == 0) {
		$color='#FA5858';
	} elseif ($val < 10) {
		$color='#FA8258';
	} elseif ($val < 20) {
		$color='#FE9A2E';
	} elseif ($val < 30) {
		$color='#F7D358';
	} elseif ($val < 40) {
		$color='#F4FA58';
	} elseif ($val < 50) {
		$color='#D0FA58';
	} elseif ($val < 60) {
		$color='#9AFE2E';
	} elseif ($val < 70) {
		$color='#64FE2E';
	} elseif ($val < 80) {
		$color='#64FE2E';
	} elseif ($val < 90) {
		$color='#2EFE2E';
	} elseif ($val < 100) {
		$color='#01DF74';		
	}
	return $color;
}	

//code couleur assigne
function est_assigne($val) {
	global $color;
	if ($val == 1) {
		$color='#E0E0E0';
	}
	else{
		$color='#FFFFFF';
	}
	return $color;
}	





function redirection($url){ 
	if (headers_sent())
		print('<meta http-equiv="refresh" content="0;URL='.$url.'">'); 
	else
		header("Location:$url"); 
} 





function formulaire($type,$var)  {
    $query = mysql_query("SELECT ".$type." FROM listejeux LEFT JOIN liaisontheme ON liaisontheme.idjeux = listejeux.idjeux LEFT JOIN themes ON themes.idtheme = liaisontheme.idtheme LEFT JOIN liaisonmecanisme ON liaisonmecanisme.idjeux = listejeux.idjeux LEFT JOIN mecanismes ON mecanismes.idmecanisme = liaisonmecanisme.idmecanisme LEFT JOIN familles ON familles.idfamille = listejeux.idfamille LEFT JOIN genres ON genres.idgenre = listejeux.idgenre LEFT JOIN liaisonuser ON liaisonuser.idjeux = listejeux.idjeux LEFT JOIN users ON users.iduser = liaisonuser.iduser WHERE ".$type." IS NOT NULL GROUP BY ".$type." ORDER BY ".$type.";") or die (mysql_error());
  if ($query) {
    echo '<select name="'.$var.'">';
    echo '<option value="-">-</option>';
    while ($array = mysql_fetch_assoc($query)) {
        if ($_POST[$var] == $array[$type]) {
            echo '<option value="' . $array[$type] . '" selected>' . $array[$type] . '</option>';
        } else {
            echo '<option value="'.$array[$type] . '">' . $array[$type] . '</option>';
        }
    }
    echo '</select>';
  }
}


function envoilog($table,$nom,$id,$update)	{
	
	$db = mysqli_connect('localhost', 'root', '', 'Metcut');
	$db->set_charset("utf8");
	
	$req_av = $db->query('SELECT * FROM '.$table.' WHERE '.$nom.'='.$id.';');
	
				
	$tbl_av = mysqli_fetch_array($req_av);
	
	$av = mysqli_real_escape_string($db, implode(";", $tbl_av));

	$instruction = mysqli_real_escape_string($db, $update);

	$a = $db->query($update);

	$req_ap = $db->query('SELECT * FROM '.$table.' WHERE '.$nom.'='.$id.';');
	$tbl_ap = mysqli_fetch_array($req_ap);
	$ap = mysqli_real_escape_string($db, implode(";", $tbl_ap));
	
	$modif = $db->query('INSERT INTO modifications (tbl, id_table, avant, instruction, apres) VALUES ("'.$table.'",'.$id.',"'.$av.'","'.$instruction.'","'.$ap.'");') or die (mysql_error());

}

function niveaumaxmin($consigne_type_1, $consigne_type_2, $consigne_type_1_val, $consigne_type_2_val){

global $MAX;
global $MIN;
global $R;
global $A;
	
	$R="";
	$R=($consigne_type_1=="R")?$consigne_type_1_val:$R;
	$R=($consigne_type_2=="R")?$consigne_type_2_val:$R;
	$A="";
	$A=($consigne_type_1=="A")?$consigne_type_1_val:$A;
	$A=($consigne_type_2=="A")?$consigne_type_2_val:$A;
	$MAX="";
	$MAX=($consigne_type_1=="Max")?$consigne_type_1_val:$MAX;
	$MAX=($consigne_type_2=="Max")?$consigne_type_2_val:$MAX;
	$MIN="";
	$MIN=($consigne_type_1=="Min")?$consigne_type_1_val:$MIN;
	$MIN=($consigne_type_2=="Min")?$consigne_type_2_val:$MIN;
	$MEAN="";
	$MEAN=($consigne_type_1=="Mean")?$consigne_type_1_val:$MEAN;
	$MEAN=($consigne_type_2=="Mean")?$consigne_type_2_val:$MEAN;
	$ALT="";
	$ALT=($consigne_type_1=="Alt")?$consigne_type_1_val:$ALT;
	$ALT=($consigne_type_2=="Alt")?$consigne_type_2_val:$ALT;
		$ALT=($consigne_type_1=="Range")?$consigne_type_1_val/2:$ALT;
		$ALT=($consigne_type_2=="Range")?$consigne_type_2_val/2:$ALT;

	If (($R != "") And ($A == ""))	{
		If ($R == -1){
		$A = 'Infini';
		}
		Else
			$A = (1 - $R) / (1 + $R);
	}
	ElseIf (($A != "") And ($R == ""))	{
		$R = (1 - $A) / (1 + $A);
	}


		//Si on a $R (et donc $A), on calcule les autres valeurs selon la 2eme reference

	If ($R != "") {
		If ($MAX != "") {
			$MIN = $MAX * $R;
			$MEAN = ($MAX + $MIN) / 2;
			$ALT = $MAX - $MEAN;
		}
		ElseIf (($MEAN != "") And ($R != -1)) {
			$ALT = $MEAN * $A;
			$MAX = $MEAN + $ALT;
			$MIN = $MEAN - $ALT;
		}
		ElseIf (($ALT != "") And ($R == -1)) {
			$MEAN = 0;
			$MAX = $ALT;
			$MIN = -$ALT;
		}
		ElseIf (($ALT != "") And ($R != -1)) {
			$MEAN = $ALT / $A;
			$MAX = $MEAN + $ALT;
			$MIN = $MEAN - $ALT;
		}
		ElseIf ($MIN != "") {
			$MAX = $MIN / $R;
			$MEAN = ($MAX + $MIN) / 2;
			$ALT = $MAX - $MEAN;
		}

	} 
		//Si l'on a pas $R (et donc ni $A), on calcule les autres valeurs selon les 2 references
	ElseIf ($R == "") {
		If (($MAX != "") And ($MIN != "")) {
			$MEAN = ($MAX + $MIN) / 2;
			$ALT = $MAX - $MEAN;
		}
		ElseIf (($MEAN != "") And ($ALT != "")) {
			$MAX = $MEAN + $ALT;
			$MIN = $MEAN - $ALT;
		}
		ElseIf (($MAX != "") And ($MEAN != "")) {
			$ALT = $MAX - $MEAN;
			$MIN = $MEAN - $ALT;
		}
		ElseIf (($MAX != "") And ($ALT != "")) {
			$MEAN = $MAX - $ALT;
			$MIN = $MEAN - $ALT;
		}
		ElseIf (($MIN != "") And ($MEAN != "")) {
			$ALT = $MEAN - $MIN;
			$MAX = $MEAN + $ALT;
		}
		ElseIf (($MIN != "") And ($ALT != "")) {
			$MEAN = $ALT - $MIN;
			$MAX = $MEAN + $ALT;
		}
		
		
		If (($MAX != 0)||($MAX != ""))
			$R= $MIN / $MAX;
		Else
			$R = 'Infini';
		If ($R == -1)
			$A = 'Infini';
		Else
			$A = (1 - $R) / (1 + $R);
	}

	
}

function nb_dim($format){
	global $denomination;
	if ($format=="Cylindrique")	{
		$denomination=array("Diam.");
		return 1;
	}
	elseif ($format=="Tube")	{
		$denomination=array("OD","ID");
		return 2;
	}
	elseif ($format=="Plate")	{
		$denomination=array("Largeur","Epaisseur");
		return 2;
	}
	elseif ($format=="Plate Percée")	{
		$denomination=array("Largeur","Epaisseur","ø trou");
		return 3;
	}
	else	{
		$denomination=array("rien");
		return 1;
	}
}

function area($format,$dim1,$dim2,$dim3){
	if ($format=="Cylindrique")	{
		return ($dim1*$dim1*pi()/4);
	}
	elseif ($format=="Tube")	{
		return ($dim1*$dim1*pi()/4)-($dim2*$dim2*pi()/4);
	}
	elseif ($format=="Plate")	{
		return ($dim1*$dim2);
	}
	elseif ($format=="Plate Percée")	{
		$denomination=array("Largeur","Epaisseur","ø trou");
		return ($dim1*$dim2-$dim3*$dim2);
	}
	else	{
		return "";
	}
}
?>