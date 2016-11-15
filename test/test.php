<?php
function cellule($l,$c)	{
	$filename = "my_file.csv";

	$ligne= file($filename); //lit le fichier entier et le place dans un tableau

	$nbTotalLignes=count($ligne);

	for($i=0;$i<$nbTotalLignes;$i++){
		// On place chaque élément séparé par un ; dans un tableau
		$ligneTab = explode(";",$ligne[$i]); 
		$ligne[$i]=$ligneTab;
	}
	return $ligne[$l-1][$c-1];
}


$val=cellule(6,2);
echo $val;
?>