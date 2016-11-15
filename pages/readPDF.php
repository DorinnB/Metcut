<?php
$filename = $_GET['pathfile'];

if (file_exists($filename)) {
	$content = file_get_contents($filename);
	header("Content-Disposition: inline; filename=$filename");
	header("Content-type: application/pdf");
	header('Cache-Control: private, max-age=0, must-revalidate');
	header('Pragma: public');
	echo $content;
} else {
    echo "Le fichier ".basename($filename)." n'existe plus sur le PC BDD.<br/>
		Il est disponible toutefois dans //Qualité";
}
?>
