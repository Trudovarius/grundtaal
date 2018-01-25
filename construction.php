<?php
if(!isset($_SESSION)){session_start();}
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$id = $_SESSION['id'];
date_default_timezone_set('Europe/Bratislava');
require("resourceBar.php");

// vypocet casu kedy sa stavba dokonci
$now = date('Y-m-d H:i:s');
$date1 = strtotime($now);
$date2 = $_GET['duration'];
$finished = $date1 + $date2;
$finishDate = date('Y-m-d H:i:s', $finished);


$cost = $_GET['cost'];
//ak sa uz nieco stavia vrati ta spat
$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE id = '$id'");
$row = mysqli_fetch_array($db_name);
if($row['buildFinish'] != '0000-00-00 00:00:00')
{
header("Location:interface.php?view=village");
}else 
//ak nemas dost surovin vyhodi alert a vrati ta spat
if($cost > rssNow("Stone") || $cost > rssNow("Wood") || $cost > rssNow("Iron") || $cost > rssNow("Glass")){
	?>
    <script type="text/javascript">
    alert("Not enough resources !!! Wait until you have enough.");
    </script>
    <?php
	header("Location:interface.php?view=village");
} else {
	//odcita naklady od aktualnych surovin a posle ich od databazy
	$stone = rssNow("Stone") - $cost;
	$wood = rssNow("Wood") - $cost;
	$iron = rssNow("Iron") - $cost;
	$glass = rssNow("Glass") - $cost;
	$food = rssNow("Food") - $cost;
			$updateRss = mysqli_query($db_connect, "UPDATE  resources SET  `Glass` =  '$glass', `Iron` =  '$iron', `Stone` =  '$stone', `Wood` =  '$wood', `Food` =  '$food' WHERE  id ='$id';");
			
	//posle vypocitany cas a typ budovy do databazy
	$typ = $_GET['type'];
	$updateTime = mysqli_query($db_connect, "UPDATE  city SET  `buildFinish` =  '$finishDate', `typeOfBuild` =  '$typ' WHERE  `city`.`id` ='$id';");
	
	header("Location:interface.php?view=village");
	
}