<?php
$nbdefaut=5;
?>
<form method="post" name="nbjob">
	Affichage des  <INPUT type="text" size="1"name="nb" onchange="document.nbjob.submit()" value="<?php echo $nb=(isset($_POST['nb']) AND $_POST['nb']>0)? $_POST['nb'] : $nbdefaut ; ?>">
	derniers jobs ajoutées dans la Base de Données.
	</form>



<table class="liste">
	<tr>
		<th>Job</th>
		<th>Préparateur</th>
		<th>Controleur</th>
	</tr>
		<?php 
		$nb=(isset($_POST['nb']) AND $_POST['nb']>0)? $_POST['nb'] : $nbdefaut ;
		
		$sql_derniersjob='SELECT jobs.id_job, jobs.n_client, jobs.n_job, jobs.indice, tech1.technicien AS preparateur, tech2.technicien AS controleur
			FROM `jobs`
			LEFT JOIN techniciens tech1 ON jobs.preparateur = tech1.id_technicien
			LEFT JOIN techniciens tech2 ON jobs.controleur = tech2.id_technicien
			WHERE jobs.job_actif =1
			ORDER BY jobs.id_job DESC
			LIMIT '.$nb;

		$req_derniersjob = mysql_query($sql_derniersjob) or die (mysql_error());
		if ($req_derniersjob) {

			while ($tbl_derniersjob = mysql_fetch_assoc($req_derniersjob)) {
				$job=(isset($tbl_derniersjob['indice']))? $tbl_derniersjob['n_client'].'-'.$tbl_derniersjob['n_job'].'-'.$tbl_derniersjob['indice'] : $tbl_derniersjob['n_client'].'-'.$tbl_derniersjob['n_job'];
				echo '<tr><td>'.$job.'</td><td>'.$tbl_derniersjob['preparateur'].'</td><td>'.$tbl_derniersjob['controleur'].'</td><tr>';
			}
		}
		?>
	</tr>
</table>






<!--<a href="javascript:if(confirm('Etes vous sur ?')) document.location.href='mon_lien.htm'">Mon lien</a> -->

