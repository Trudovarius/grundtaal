<?php
if(!isset($_SESSION)){session_start();}

	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$id = $_SESSION['id'];
	$resource = mysqli_query($db_connect, "SELECT * FROM resources WHERE id='$id' ");
	$resources = mysqli_fetch_array($resource);
	$building = mysqli_query($db_connect, "SELECT * FROM buildings WHERE id='$id'");
	$buildings = mysqli_fetch_array($building);
	date_default_timezone_set('Europe/Bratislava');

//vypocet rozdielu medzi poslednou aktualizaciou surovin v databaze a sucasnostou
function timediff(){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$id = $_SESSION['id'];
	$resource = mysqli_query($db_connect, "SELECT * FROM resources WHERE id='$id' ");
	$resources = mysqli_fetch_array($resource);
	date_default_timezone_set('Europe/Bratislava');
$now = date('Y-m-d H:i:s');
$lastUpdate = $resources['lastUpdate'];
$date1 = strtotime($now);
$date2 = strtotime($lastUpdate);
$difference = $date1 - $date2;
return $difference;
}

//surky z databazy + [ rozdiel casov(timediff()) * uroven budovy($buildings['...']) ]
function rssNow($type){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$id = $_SESSION['id'];
	$resource = mysqli_query($db_connect, "SELECT * FROM resources WHERE id='$id' ");
	$resources = mysqli_fetch_array($resource);
	$building = mysqli_query($db_connect, "SELECT * FROM buildings WHERE id='$id'");
	$buildings = mysqli_fetch_array($building);
	date_default_timezone_set('Europe/Bratislava');
	switch($type)
	{
		case "Food":
			$resourceNow = $resources['Food'] + (timediff() * $buildings['Field']);
			break;
		case "Wood":
			$resourceNow = $resources['Wood'] + (timediff() * $buildings['Woodcutter']);
			break;
		case "Stone":
			$resourceNow = $resources['Stone'] + (timediff() * $buildings['StonePit']);
			break;
		case "Iron":
			$resourceNow = $resources['Iron'] + (timediff() * $buildings['Mine']);
			break;
		case "Glass":
			$resourceNow = $resources['Glass'] + (timediff() * $buildings['Glassworks']);
			break;
	}
	return $resourceNow;
}

?>
<script type="text/javascript" src="Js/js4.js"></script>
<link href="css.css" rel="stylesheet" type="text/css" />
<span class="prof_name">Resources:</span>
<span class="prof_info" id="Stone" style="width:80px;" >Stone:<br /><?= rssNow("Stone"); ?></span>
<span class="prof_info" id="Food" style="width:80px;" >Food:<br /><?= rssNow("Food"); ?></span>
<span class="prof_info" id="Iron" style="width:80px;" >Iron:<br /><?= rssNow("Iron"); ?></span>
<span class="prof_info" id="Wood" style="width:80px;" >Wood:<br /><?= rssNow("Wood"); ?></span>
<span class="prof_info" id="Glass" style="width:80px;" >Glass:<br /><?= rssNow("Glass"); ?></span>