<?php


$bd="metcut"; // identifiant DSN
$user=""; // login
$password=""; // password
$cnx = odbc_connect( $bd , $user, $password );

$sql = "SELECT * FROM techniciens";     // Selectionne une table 
$cur= odbc_exec( $cnx, $sql );  
while( odbc_fetch_row( $cur ) ) { 
       $Dni= odbc_result( $cur, 2 ); 
echo $Dni;
echo "</br>";
}



$mdbFilename="C:/Users/pgo/Document/tech.accdb"; 
$table="tech"; 

if (!$conn = new COM("ADODB.Connection"))                        // Declaration Objet 
     exit("impossible de créer la connection ADODB<br />"); 

$connection = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$mdbFilename", $user, $password);	 

//$connection = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=C:\\wamp\\www\\Metcut\\Microsoft Access Database.accdb", $user, $password); 





?>