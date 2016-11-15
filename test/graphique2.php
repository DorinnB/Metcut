<?php 
/* SELECT date, count( * ) AS nbrjeux
FROM `liaisonuser`
WHERE iduser =3
GROUP BY date
*/
    $visites = array(138, 254, 381, 652, 896, 720, 140, 556, 663, 331, 407, 768);

    header ("Content-type: image/png"); 
    $largeurImage = 400;
    $hauteurImage = 200;
    $im = ImageCreate ($largeurImage, $hauteurImage) 
            or die ("Erreur lors de la création de l'image");         
    $blanc = ImageColorAllocate ($im, 255, 255, 255); 
    $noir = ImageColorAllocate ($im, 0, 0, 0);  
    $bleu = ImageColorAllocate ($im, 0, 0, 255);         
	    // on dessine un trait vertical pour représenter l'axe du temps    
    ImageLine ($im, 10, $hauteurImage-10, $largeurImage-10, $hauteurImage-10, $noir);
    // on affiche le numéro des 12 mois
    for ($mois=1; $mois<=12; $mois++) {
        ImageString ($im, 0, $mois*30, $hauteurImage-10, $mois, $noir);
    }
    
    // on dessine un trait vertical pour représenter le nombre de visites
    ImageLine ($im, 10, 10, 10, $hauteurImage-10, $noir); 
	    // le nombre maximum de visites
    $visitesMax = 1000;
    
    // tracé des batons
    for ($mois=1; $mois<=12; $mois++) {
        $hauteurImageRectangle = round(($visites[$mois-1]*$hauteurImage)/$visitesMax);
        ImageFilledRectangle ($im, $mois*30-7, $hauteurImage-$hauteurImageRectangle, $mois*30+7, $hauteurImage-10, $bleu);
        ImageString ($im, 0, $mois*30-7, $hauteurImage-$hauteurImageRectangle-10, $visites[$mois-1], $noir);
    }
    
    // et c'est fini...
    ImagePng ($im); 
?>  