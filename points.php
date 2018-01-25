<?php 
if(!isset($_SESSION)){session_start();}
function PlayerPoints(){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$name = $_SESSION['username'];
	$user = mysqli_query($db_connect, "SELECT * FROM `user` WHERE `Name` = '$name'");
	$points = mysqli_fetch_array($user);
	return $points['Points'];
}
function VillagePoints(){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$id = $_SESSION['id'];
	$city = mysqli_query($db_connect, "SELECT * FROM `city` WHERE `id` = '$id'");
	$cityPoints = mysqli_fetch_array($city);
	return $cityPoints['VillagePoints'];
}
function Points(){
date_default_timezone_set('Europe/Bratislava');
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$name = $_SESSION['username'];
$dediny = mysqli_query($db_connect, "SELECT * FROM city WHERE Owner = '$name'");
$totalPoints = 0;
$count = 0;
//overenie potrebnej urovne pevnosti pre kolonizovanie dalsej dediny
foreach($dediny as $city)
{
$id = $city['id'];
$build = mysqli_query($db_connect, "SELECT * FROM `buildings` WHERE `id` = '$id'");
$buildLvl = mysqli_fetch_array($build);
$army = mysqli_query($db_connect, "SELECT * FROM `war` WHERE `id` = '$id'");
$war = mysqli_fetch_array($army);
$totalLevel = $buildLvl['Castle'] + $buildLvl['Houses'] + $buildLvl['Mine'] + $buildLvl['Field'] + $buildLvl['Woodcutter'] + $buildLvl['StonePit'] + $buildLvl['Glassworks'];
$troops = $war['Archers'] + $war['Swordmen'] + ($war['Cavalry'] * 2);
$pointsPerCity[$count] = 300 + ($totalLevel * 100) + $troops; //pocet bodov na dedinu, 30 bodov za kazdu dedinu + urovne budov * 100
$points = $pointsPerCity[$count];
// upload bodov mesta do databazy
$cityPoints = mysqli_query($db_connect, "UPDATE `city` SET `VillagePoints` = '$points' WHERE `id` = '$id'");
$count++;
}
for($i = 0; $i < ($count); $i++){
$totalPoints += $pointsPerCity[$i];
}
//upload bodov hraca do databazy
$playerPoints = mysqli_query($db_connect, "UPDATE `user` SET `Points` =  '$totalPoints' WHERE  `user`.`Name` ='$name';");
}