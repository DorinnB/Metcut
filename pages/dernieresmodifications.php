<?php
$nbdefaut=5;
?>
<form method="post" name="nbmodif">
	Affichage des  <INPUT type="text" size="1"name="nb" onchange="document.nbmodif.submit()" value="<?php echo $nb=(isset($_POST['nb']) AND $_POST['nb']>0)? $_POST['nb'] : $nbdefaut ; ?>">
	dernieres modifications faites à la Base de Données.
	</form>



<table class="liste">
	<tr>
		<th rowspan="3">Date</th>
		<th rowspan="3">Table</th>
		<th colspan="100">Titre</th>
	</tr>
	<tr>
		<th colspan="100">Avant</th>
	</tr>
	<tr>
		<th colspan="100">Après</th>
	</tr>
		<?php 
		$nb=(isset($_POST['nb']) AND $_POST['nb']>0)? $_POST['nb'] : $nbdefaut ;
		
		$sql_dernieresmodif='SELECT modifications.date, modifications.tbl, modifications.avant, modifications.apres
			FROM `modifications`
			ORDER BY modifications.id_modification DESC
			LIMIT '.$nb;

		$req_dernieresmodif = $db->query($sql_dernieresmodif);
		if ($req_dernieresmodif) {

			while ($tbl_dernieresmodif = mysqli_fetch_assoc($req_dernieresmodif)) {
			$avant= str_replace(";", "</td><td>", $tbl_dernieresmodif['avant']);
			$apres= str_replace(";", "</td><td>", $tbl_dernieresmodif['apres']);
			$titre='';		
			

			$sql_titre='select * from '.$tbl_dernieresmodif['tbl'].' limit 1';
			$req_titre = $db->query($sql_titre);
			if ($req_titre) {
				$tbl_titre=mysqli_fetch_assoc($req_titre);
				foreach ($tbl_titre as $key=>$value) {
					$titre .= '<td>'.$key.'</td>';
				}
			}
			
			
			echo '<tr><td rowspan="3">'.$tbl_dernieresmodif['date'].'</td><td rowspan="3">'.$tbl_dernieresmodif['tbl'].'</td>'.$titre.'<td colspan="100"></td></tr>
			<tr><td>'.$avant.'</td><td colspan="100"></td></tr>
			<tr><td>'.$apres.'</td><td colspan="100"></td></tr><tr><td colspan="100"></td></tr>';
			
			}
		}
		?>
	</tr>
</table>






<!--<a href="javascript:if(confirm('Etes vous sur ?')) document.location.href='mon_lien.htm'">Mon lien</a> -->

