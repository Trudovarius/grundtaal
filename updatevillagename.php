<?php
session_start();
$suradnice = explode("_", $_GET['xy'] );
$x = $suradnice[0];
$y = $suradnice[1];
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$name = $_SESSION['username'];
$city_name = mysqli_query($db_connect, "SELECT * FROM city WHERE Owner = '$name' AND X = '$x' AND Y = '$y'");
$city = mysqli_fetch_array($city_name);

if($_POST['city_name'] == '')
{
	header("Location: interface.php?view=village");
}
else
{
	$new_city_name = $_POST['city_name'];
	$id = $city['ID'];
	$new_name = mysqli_query($db_connect, "UPDATE  `rp_web`.`city` SET  `Name` =  '$new_city_name' WHERE X = '$x' AND Y = '$y' ");
	header("Location: interface.php?view=village");
}