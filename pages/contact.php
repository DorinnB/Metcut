<?php
	Require("../fonctions.php");
	Connectionsql();


	if(isset($_GET['id_customer']))	{

		$req='SELECT * FROM contacts WHERE id_customer= '.$_GET['id_customer'].' AND contact_actif=1 ORDER BY surname';

		$req_contacts = $db->query($req);

		
	$liste = "";
    $liste .= '<select name="contact" id="contact">'."\n";	



	
		while($lst_contacts = mysqli_fetch_assoc($req_contacts))	{
			
 $liste .= '  <option value="'. $lst_contacts['id_contact'] .'">'. $lst_contacts['lastname'] .' '. $lst_contacts['surname'] .'</option>'."\n";			
			
			$compagnie=$lst_contacts['compagnie'];
		}
		
		
	    $liste .= '</select>'."\n";	
		
		
	//echo($liste);	
		
		
//exit();		
		
		
		
		
		
		$maReponse = array('liste'=> $liste, "compagnie"=>$compagnie);
		
		
		echo json_encode($maReponse);


	}
	

?>