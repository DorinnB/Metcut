<?php
	Require("../fonctions.php");
	Connectionsql();


	if(isset($_GET['type_soustraitance']))	{

		$req='SELECT compagnie 
			FROM soustraitants_type_essais 
			LEFT JOIN contacts ON id_contact=id_soustraitant
			WHERE id_type_soustraitance = '.$_GET['type_soustraitance'].' 
				AND soustraitants_type_essais_actif = 1 
			GROUP BY compagnie';
//echo $req;
		$req_compagnie = $db->query($req);


	$liste = "";
    $liste .= '<select name="compagnie" id="compagnie"><option>-</option>'."\n";	



	
		while($lst_compagnie = mysqli_fetch_assoc($req_compagnie))	{
			
 $liste .= '  <option value="'. $lst_compagnie['compagnie'] .'">'.$lst_compagnie['compagnie'].'</option>'."\n";			
			
			$compagnie=$lst_compagnie['compagnie'];
		}
		
		
	    $liste .= '</select>'."\n";	
		
		$maReponse = array('liste'=> $liste, "compagnie"=>$compagnie);
		
		
		echo json_encode($maReponse);


	}
	

?>