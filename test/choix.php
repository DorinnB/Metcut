<script language="javascript">
function popup(fic)
// on ouvre dans une fen�tre le fichier pass� en param�tre.
// cette ouverture peut �tre am�lior�e en passant d'autres
// param�tres que la taille et la position de la fen�tre.
{ window.open(fic,'Choisir','width=400,height=250,top=50,left=50'); }
</script>
<form name="toto">
<a href="javascript:popup('choix.htm')">D�partement :</a><input type="text" name="w_choix"><br>
<!-- Et on pourrait le faire aussi pour le pays -->
<a href="javascript:popup('pays.htm')">Pays :</a><input type="text" name="w_pays">
</form>