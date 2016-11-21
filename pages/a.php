 <?php
$cookie_machine = "machine";
$cookie_value = "20021";
setcookie($cookie_machine, $cookie_value, time() + (86400 * 1), "/"); //1jours
$cookie_user = "id_user";
$cookie_value = "15";
setcookie($cookie_user, $cookie_value, time() + (86400 * 30), "/");	//1jours
$cookie_user = "user";
$cookie_value = "PGO";
setcookie($cookie_user, $cookie_value, time() + (86400 * 30), "/");	//1jours

?>
<html>
<body>

<?php
if(isset($_COOKIE[$cookie_machine])) {
	var_dump($_COOKIE);

}
?>

</body>
</html> 