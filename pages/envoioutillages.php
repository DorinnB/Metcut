<div style="margin: 5px 20px 20px;">
	<div style="margin-bottom: 2px">
		<ins>Code SQL pour l'insertion d'outillages</ins>: <input type="button" value="Show" onclick="
			(function(input, content) {
				input.value = content.style.display == 'none' ? 'Hide' : 'Show';
				content.style.display = content.style.display == 'none' ? 'block' : 'none';
			})(this, this.parentNode.nextSibling.getElementsByTagName('div')[0])" />
	</div><div style="border: 1px inset; margin: 0px; padding: 6px">
		<div style="display: none">
<?php
/*Ouvre le fichier et retourne un tableau contenant une ligne par élément*/
$lines = file('BDD/pile.txt');
/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
foreach ($lines as $lineNumber => $lineContent)
{
	echo $lineNumber,' ',$lineContent,'<br/>';
		mysql_query($lineContent);

	
	
}
?>
</div>
</div>
</div>
Vous pouvez fermer cette page.
Merci
