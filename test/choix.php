<script language="javascript">
function popup(fic)
// on ouvre dans une fenêtre le fichier passé en paramètre.
// cette ouverture peut être améliorée en passant d'autres
// paramètres que la taille et la position de la fenêtre.
{ window.open(fic,'Choisir','width=400,height=250,top=50,left=50'); }
</script>
<form name="toto">
<a href="javascript:popup('choix.htm')">Département :</a><input type="text" name="w_choix"><br>
<!-- Et on pourrait le faire aussi pour le pays -->
<a href="javascript:popup('pays.htm')">Pays :</a><input type="text" name="w_pays">
</form>