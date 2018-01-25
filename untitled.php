<?php
if(!isset($_SESSION)){session_start();}
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$id = $_SESSION['id'];
$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE id = '$id'");
$row = mysqli_fetch_array($db_name);
date_default_timezone_set('Europe/Bratislava');
function buildDiff(){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$id = $_SESSION['id'];
	$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE `id` = '$id' ");
	$row = mysqli_fetch_array($db_name);
	$resource = mysqli_query($db_connect, "SELECT * FROM resources WHERE `id` = '$id' ");
	$resources = mysqli_fetch_array($resource);
	date_default_timezone_set('Europe/Bratislava');
$now = $row['buildFinish'];
$lastUpdate = $resources['lastUpdate'];
$date1 = strtotime($now);
$date2 = strtotime($lastUpdate);
$difference = $date1 - $date2;
return $difference;
}
function buildRss($type){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$id = $_SESSION['id'];
	$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE `id` = '$id' ");
	$row = mysqli_fetch_array($db_name);
	$resource = mysqli_query($db_connect, "SELECT * FROM resources WHERE `id` = '$id' ");
	$resources = mysqli_fetch_array($resource);
	$building = mysqli_query($db_connect, "SELECT * FROM buildings WHERE `id` = '$id'");
	$buildings = mysqli_fetch_array($building);
	date_default_timezone_set('Europe/Bratislava');
	switch($type)
	{
		case "Food":
			$resourceNow = $resources['Food'] + (buildDiff() * $buildings['Field']);
			break;
		case "Wood":
			$resourceNow = $resources['Wood'] + (buildDiff() * $buildings['Woodcutter']);
			break;
		case "Stone":
			$resourceNow = $resources['Stone'] + (buildDiff() * $buildings['StonePit']);
			break;
		case "Iron":
			$resourceNow = $resources['Iron'] + (buildDiff() * $buildings['Mine']);
			break;
		case "Glass":
			$resourceNow = $resources['Glass'] + (buildDiff() * $buildings['Glassworks']);
			break;
	}
	return $resourceNow;
}

$stone = buildRss("Stone");
echo "<br>",$stone," asdf";
?>