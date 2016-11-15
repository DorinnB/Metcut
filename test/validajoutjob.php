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

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

print_r($ligne[0]);
echo '<br/>'.cellule(6,2);



echo '<table border=1><tr>';
for($i=0;$i<=count($ligneTab);$i++)	{
	echo '<td>'.$ligne[0][$i].'</td>';
}
echo '</tr><tr>';
for($i=0;$i<=count($ligneTab);$i++)	{
	echo '<td>'.$ligne[1][$i].'</td>';
}
echo '</tr><tr>';
for($i=0;$i<=count($ligneTab);$i++)	{
	echo '<td>'.$ligne[11][$i].'</td>';
}
echo '</tr><tr>';
for($i=0;$i<=count($ligneTab);$i++)	{
	echo '<td>'.$ligne[25][$i].'</td>';
}
echo '</tr></table>';

?>