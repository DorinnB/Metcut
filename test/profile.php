

<?php
	Require("../fonctions.php");
	Connectionsql();
?>



<?php	//Select techniciens

	if(isset($_POST['username']))	{

		$req='SELECT * FROM techniciens WHERE id_technicien='.$_POST['username'];

		$req_technicien = $db->query($req);
	
		if (mysqli_num_rows($req_technicien)==0)
			echo "Inconnu";
		else	{
			$tbl_technicien = mysqli_fetch_assoc($req_technicien);
			if ($_POST['password']==$tbl_technicien['mdp'])
				echo "Correct";
			else
				echo "incorrect";
		}
	}
?>


