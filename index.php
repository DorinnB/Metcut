<?php session_start();
Require("fonctions.php");
Connectionsql();

?>	 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enregistrement des Essais</title>
<link rel="shortcut icon" href="css/favicon.ico" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
    
	
    <link type="text/css" rel="stylesheet" href="JSCal2/css/jscal2.css" />
    <link type="text/css" rel="stylesheet" href="JSCal2/css/border-radius.css" />
    <script src="JSCal2/js/jscal2.js"></script>
    <script src="JSCal2/js/lang/en.js"></script>

	<script type="text/javascript" src="jquery/jquery-3.1.0.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script>


<?php			//Fonction microtime
function getmicrotime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}
$temps = getmicrotime(); //temps au debut du chargemennt
?>



<div id="Entete" >
<?php  include('pages/menu.htm');   // Nous appelons notre menu ?>
</div>
<a name="menu"></a>






<div id="PagePrincipale">

<?php
// si votre site n'est pas à la racine du serveur, vous pouvez avoir besoin de dire OU se trouve la page index.php
$_chemin = '/';

// la page par defaut, si les valeurs fournies sont incorrect : 
$page_defaut = 'pages/accueil';

// on recupere la valeur passé dans l'url : 
if(isset($_GET["page"]))
  $page=$_GET["page"];
else
  $page=$page_defaut;


//Enlevons les caractères html
$page=htmlentities($page, ENT_QUOTES);

//Si on a des répertoires que l'on ne veut pas accéder, un les liste ici :
$repProteger=array('include', 'libs', 'admin');
$temp=@split('/',$page);
if(in_array($temp[0],$repProteger)){ $page=$page_defaut; }

//Si jamais qq tente de penetre dans le serveur en utilisant des ./ ou :/
if(@eregi("(:/)|(./)",$page)){ $page=$page_defaut; }

//pagesons si la page demandé existe bien en local
if(file_exists('pages/'.$page.'.php'))
  include('pages/'.$page.'.php');
elseif(file_exists($page_defaut.'.php'))
  include($page_defaut.'.php');
else
  exit("Erreur : La page par defaut n'existe pas.");
?>

</div>





<div id="PiedDePage">
	<?php  include('pages/pied.php');   // Nous appelons notre pied ?>
</div>