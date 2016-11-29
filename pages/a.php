 <?php
 if (isset($_GET['id_user'])){
setcookie("id_user", $_GET['id_user'], time() + (86400 * 1), "/"); //1jours
setcookie("password", $_GET['password'], time() + (86400 * 1), "/"); //1jours
setcookie("machine", $_GET['machine'], time() + (86400 * 1), "/"); //1jours
 }
?>
<?php
	Require("../fonctions.php");
	Connectionsql();
?>
<html>
<body>

<?php
	if(isset($_COOKIE['id_user'])) {
		var_dump($_COOKIE);
		
		echo '
		<p>id_user : '.$_COOKIE['id_user'].'</p>
		<p>password : '.$_COOKIE['password'].'</p>
		<p>Machine : '.$_COOKIE['machine'].'</p>	
		';
	}
?>



	<form method="get">
		<div>	
			<?php	
				$req="SELECT * FROM techniciens ORDER BY technicien;";
				$req_techniciens = $db->query($req);
				while ($w_techniciens = mysqli_fetch_array($req_techniciens)) {
					$tbl_techniciens[]=$w_techniciens;
				}
				
				$titreLigne='technicien';	echo '<label>User : </label><SELECT name="id_user"><option value="0">-</option>
				';
				for($k=0;$k < count($tbl_techniciens);$k++)	{
					if($tbl_techniciens[$k]['technicien_actif']==1)	{
						echo '<option value="'.$tbl_techniciens[$k]['id_'.$titreLigne].'">'.$tbl_techniciens[$k][$titreLigne].'</option>
						';	
					}
				}
				echo '</select>
				';
				echo '
					<label>Password : </label><input type="password" name="password">
				';
			?>
		</div>
		<div>
			<?php	
				$req="SELECT * FROM machines ORDER BY machine;";
				$req_machines = $db->query($req);
				while ($w_machines = mysqli_fetch_array($req_machines)) {
					$tbl_machines[]=$w_machines;
				}
				
				$titreLigne='machine';	echo '<label>Machine : </label><SELECT id="machine" name="machine"><option value="0">-</option>
				';
				for($k=0;$k < count($tbl_machines);$k++)	{
					if($tbl_machines[$k]['machine_actif']==1)	{
						echo '<option value="'.$tbl_machines[$k]['id_'.$titreLigne].'">'.$tbl_machines[$k][$titreLigne].'</option>
						';	
					}
				}
				echo '</select>
				';
			?>
		</div>
<input type="submit" value="valid">		
	</form>				
		
</body>
</html> 