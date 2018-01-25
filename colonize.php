<?php
if(!isset($_SESSION)){session_start();}
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$id = $_SESSION['id'];
$name = $_SESSION['username'];
date_default_timezone_set('Europe/Bratislava');
$allcities = mysqli_query($db_connect, "SELECT * FROM city WHERE owner = '$name' ");
$count = 0;
//overenie potrebnej urovne pevnosti pre kolonizovanie dalsej dediny
foreach($allcities as $city)
{
$count++;
$id = $city['id'];
$build = mysqli_query($db_connect, "SELECT * FROM buildings WHERE id = '$id'");
$buildLvl = mysqli_fetch_array($build);
$castle[$count] = $buildLvl['Castle'];
}
for($i = 1; $i <= $count; $i++){
	if($castle[$i]  < $count+1){
	Fail();	
	} else {
		$xy = $_GET['xy'];
		header("Location: script.php?script=addCity&xy={$xy}");
	}
}
print_r($castle);
function Fail(){
echo "Fortress low u fat fuk";	
}