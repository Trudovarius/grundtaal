<?php
if( isset($_GET['sessionStart']) ) 
{
	session_start();
}
//nacitanie databazy0
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
if(isset($_GET['name']))
{$_SESSION['username'] = $_GET['name'];}
$name = $_SESSION['username'];
$city_name = mysqli_query($db_connect, "SELECT * FROM city WHERE Owner = '$name' ");
$city = mysqli_fetch_array($city_name);

//ak je zavolany moveUp presun sa na mapScript aj s get udajmi
if((isset($_GET['x'])) && (isset($_GET['y'])))
{
	$x = $_GET['x'];
	$y = $_GET['y'];
	header("Location: mapScript.php?x=" . $x . "&y=" . $y);
}
//ak nie, standardny postup
else{include('mapScript.php');}