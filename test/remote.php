 <?php
	$db = mysql_connect('localhost', 'root', '');
	mysql_select_db('METCUT',$db);
 $query = mysql_query('select n_job from jobs where id_job = '.$_POST['id']);
 $result = mysql_fetch_array($query);
 ?>
 <script language="JavaScript">
 top.formulaire.TraiteReponse('<?php echo $result['titre']; ?>');
 </script>