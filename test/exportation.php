<?php

$db_name = 'METCUT' ;   // a définir correctement
$host = 'localhost' ;   // a définir correctement
$user = 'root' ;   // a définir correctement
$password = '' ;   // a définir correctement
$local_dir = "C:\wamp\bin\mysql\mysql5.1.30\bin\ " ;   // a définir correctement

$file_name = $db_name.'-'.date('Y-m-d').".sql" ;
$command  = "C:\wamp\bin\mysql\mysql5.1.30\bin\mysqldump --host=".$host." --user=".$user." --password=".$password ;
$command .= " --skip-opt --compress --add-locks --create-options --disable-keys --quote-names --quick --extended-insert --complete-insert --default-character-set=latin1 --compatible=mysql40 --result-file=".$local_dir.$file_name ;
$command .= " ".$db_name ;

/*
// si tu ne veux sauver que quelques tables, tu rajoutes ça :
$tables = array(
'table1',
'table2',
'table5',
) ;
$command .= " ".implode(' ',$tables) ;
*/

echo ( "Execution de la commande : ".$command ) ;
system($command);

// et eventuellement :
echo ( "Compression du fichier....." );
system("cd ".$local_dir."; gzip ".$file_name);
?>