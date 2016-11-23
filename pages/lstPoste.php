<?php
	Require("../fonctions.php");
	Connectionsql();


	if(isset($_POST['id_ep']))	{
		
		$req='SELECT machine, MAX(id_poste) as id_poste
			FROM postes 
			LEFT JOIN machines ON machines.id_machine=postes.id_machine 
			WHERE id_tbljob = ( 
				SELECT id_job 
				FROM eprouvettes 
				WHERE id_eprouvette='.$_POST['id_ep'].'
				) 
			GROUP BY machine
			ORDER BY machine 
			';

		$req_lst = $db->query($req);

		//echo $req;
	$liste = "";
    $liste .= '<select name="poste" id="poste">'."\n";	



	
		while($lst_postes = mysqli_fetch_assoc($req_lst))	{
			
 $liste .= '  <option value="'.$lst_postes['id_poste'].'">'.$lst_postes['machine'].'</option>'."\n";			
			
			$ep=$_POST['id_ep'];
		}
		
		
	    $liste .= '</select>'."\n";	
		
		
	//echo($liste);	
		
		
//exit();		
		
		
		
		
		
		$maReponse = array('liste'=> $liste, "ep"=>$ep);
		
		
		echo json_encode($maReponse);


	}
	

?>