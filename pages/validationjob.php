<?php
	
$modifep='UPDATE metcut.jobs SET job_actif ="'.$_POST['job_actif'].'" WHERE jobs.id_job ='.$_POST['id_job'];
mysql_query($modifep);

?>
<form method="post" name="envoi" action="index.php?page=listejob">
	<input type="hidden" name="job" value="<?php echo $_POST['id_job'];	?>">
	<input type="submit" value="Submit">
</form>

Vous allez être redirigé d'ici peu.

<script type="text/javascript">
	document.envoi.submit();
</script>