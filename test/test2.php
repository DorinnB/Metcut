<?php
	$co=mysql_connect("localhost","root","");
	$dbnom="METCUT";
	$db=mysql_select_db($dbnom,$co);
   //======================================
   //== création de la base de données  ==
   //==  pour les besoins de l'exemple  ==
   //=====================================

   $db=mysql_select_db($dbnom,$co);
   mysql_query("CREATE TABLE tabl1
                  ( t1ind char(20) PRIMARY KEY ) ");
   mysql_query("CREATE TABLE tabl2
                  (	t2t1ind char(20) not null,
                     t2ind   char(20) not null,
                     PRIMARY KEY( t2t1ind, t2ind )  ) ");
   //==============================================
   //== création pour le test des enrgts désirés ==
   //==============================================
   mysql_query("INSERT INTO tabl1 VALUES ('Bretagne')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Bretagne','Côtes-d\'Armor')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Bretagne','Finistère')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Bretagne','Ille-et-Vilaine')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Bretagne','Morbihan')" );
   mysql_query("INSERT INTO tabl1 VALUES ('Centre')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Centre','Cher')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Centre','Eure-et-Loire')" );     
   mysql_query("INSERT INTO tabl2 VALUES ('Centre','Indre')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Centre','Loiret')" );     
   mysql_query("INSERT INTO tabl1 VALUES ('Nord-Pas-de-Calais')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Nord-Pas-de-Calais','Nord')" );
   mysql_query("INSERT INTO tabl2 VALUES ('Nord-Pas-de-Calais','Pas-de-Calais')" );     
?>
