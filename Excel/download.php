<?php


if(isset($_GET["dwn"])) {

// Ent�te pour Ouvrir avec MSExcel
header("content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$_GET ["dwn"]);

flush(); // Envoie le buffer
readfile($_GET["dwn"]); // Envoie le fichier

}?> 