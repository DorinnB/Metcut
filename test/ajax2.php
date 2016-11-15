<?php
   header('Content-type:text/html;charset=ISO-8859-1');
	$co=mysql_connect("localhost","root","");
	$dbnom="METCUT";
	$db=mysql_select_db($dbnom,$co);
   $rch="WHERE t2t1ind='".$_GET["tbl2"]."'";
   $res=mysql_query("SELECT * FROM tabl2 ".$rch,$co);
   $max=@mysql_num_rows($res);
	$t="";
   for ($nb=0;$nb<$max;$nb++)
   {  $i=mysql_result($res,$nb,"t2ind");
      $t.="\t".$i;   
	}	 
	echo $t;
	mysql_close($co);
?>

