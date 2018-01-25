<?php
session_start();
//Overenie ci je meno uzivatela v databaze
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
if(isset($_POST['Name']))
{
	//nacitanie session mena
$login_name = $_POST['Name'];
$db_name = mysqli_query($db_connect, "SELECT * FROM user WHERE `Name` = '$login_name' ");
$row = mysqli_fetch_array($db_name);
$name = $row['Name'];
$password = $row['Password'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php

if(isset($name))
{
	if($password == sha1($_POST['Password']))
	{
		//nacitanie mena sessionu a vsetkych potrebnych udajov
		$_SESSION['username'] = $name;
		
		//nacitanie session aktualnej dediny
		$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE `Owner` = '$name'");
		$row = mysqli_fetch_array($db_name);
		$_SESSION['X'] = $row['X'];
		$_SESSION['Y'] = $row['Y'];
		$_SESSION['id'] = $row['id'];
		//ak sa nieco dostavalo spracuj to
		date_default_timezone_set('Europe/Bratislava');
		if ($row['buildFinish'] == '0000-00-00 00:00:00'){
			//ak sa nic nestavia pokracuj svojej do dediny
			header("Location: interface.php?view=village");
		} else if($row['buildFinish'] < date('Y-m-d H:i:s')){
			$stone = buildRss("Stone");
			$wood = buildRss("Wood");
			$iron = buildRss("Iron");
			$glass = buildRss("Glass");
			$food = buildRss("Food");
			$updateRss = mysqli_query($db_connect, "UPDATE  resources SET  `Glass` =  '$glass', `Iron` =  '$iron', `Stone` =  '$stone', `Wood` =  '$wood', `Food` =  '$food' WHERE  id ='$id';");
			$building = mysqli_query($db_connect, "SELECT * FROM buildings WHERE `id` = '$id'");
			$buildings = mysqli_fetch_array($building);
			switch($row['typeOfBuild'])
			{
		case "Field":
			$newLvl = $buildings['Field'] + 1;
			$lvlUp = mysqli_query($db_connect,  "UPDATE buildings SET `Field` = '$newLvl' WHERE id = '$id'");
			break;
		case "Castle":
			$newLvl = $buildings['Castle'] + 1;
			$lvlUp = mysqli_query($db_connect,  "UPDATE buildings SET `Castle` = '$newLvl' WHERE id = '$id'");
			break;
		case "Houses":
			$newLvl = $buildings['Houses'] + 1;
			$lvlUp = mysqli_query($db_connect,  "UPDATE buildings SET `Houses` = '$newLvl' WHERE id = '$id'");
			break;
		case "Mine":
			$newLvl = $buildings['Mine'] + 1;
			$lvlUp = mysqli_query($db_connect,  "UPDATE buildings SET `Mine` = '$newLvl' WHERE id = '$id'");
			break;
		case "Woodcutter":
			$newLvl = $buildings['Woodcutter'] + 1;
			$lvlUp = mysqli_query($db_connect,  "UPDATE buildings SET `Woodcutter` = '$newLvl' WHERE id = '$id'");
			break;
		case "StonePit":
			$newLvl = $buildings['StonePit'] + 1;
			$lvlUp = mysqli_query($db_connect,  "UPDATE buildings SET `StonePit` = '$newLvl' WHERE id = '$id'");
			break;
		case "Glassworks":
			$newLvl = $buildings['Glassworks'] + 1;
			$lvlUp = mysqli_query($db_connect,  "UPDATE buildings SET `Glassworks` = '$newLvl' WHERE id = '$id'");
			break;
			}
			$updateBuild = mysqli_query($db_connect, "UPDATE city SET `buildFinish` =  '0000-00-00 00:00:00', `typeOfBuild` = '' WHERE id = '$id' ");
		}
		//Ulozenie levelov budov do sessionu, tieto data s zmenia vzdy ked sa vyberie ina dedina
		$id = $city['id'];
		$buildings = mysqli_query($db_connect, "SELECT * FROM buildings WHERE `id` = '$id'");
		$level = mysqli_fetch_array($buildings);
		$_SESSION['pevnost'] = $level['Castle'];
		$_SESSION['domy'] = $level['Houses'];
		$_SESSION['kovac'] = $level['Mine'];
		$_SESSION['mlyn'] = $level['Field'];
		$_SESSION['drevorubac'] = $level['Woodcutter'];
		$_SESSION['bana'] = $level['StonePit'];
		$_SESSION['sklar'] = $level['Glassworks'];
		//pokracuj svojej do dediny
		header("Location: interface.php?view=village");
	}
	else
	{
		$text = "Nesprávne Heslo";
	}
}
if(!isset($name))
{
	$text = "Nesprávne Meno";
}

include("layout1.php");

function buildDiff(){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$id = $_SESSION['id'];
	$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE `id` = '$id' ");
	$row = mysqli_fetch_array($db_name);
	$resource = mysqli_query($db_connect, "SELECT * FROM resources WHERE `id` ='$id' ");
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
 ?>
</body>
</html>