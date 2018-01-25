<?php
session_start();
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');

//switch kvoli moznym dalsim funkciam v buducnosti
switch($_GET['script'])
{
	//zobrazi informacie o aktualne oznacenom poli na mape
	case 'cityInfo' :
		$suradnice = explode("_", $_GET['xy'] );
		$infoObjekt = mysqli_query($db_connect, "SELECT * FROM city WHERE X = '$suradnice[0]' AND Y = '$suradnice[1]' ");
		$info = mysqli_fetch_array($infoObjekt);
	//zisti ci je pole prazdnem ak je prazdne vypise sa empty a no owner
		if(!isset($info['Name']) || !isset($info['Owner']))
		{
			$info['Name'] = 'empty';
			$info['Owner'] = 'no owner';
		}
		// ak je to pole aktualneho pouzivatela je moznost zmenit meno dediny
		if($info['Owner']==$_SESSION['username']){
			echo "<br/>Name: <a href='city_name.php?xy=",$_GET['xy'],"'>{$info['Name']}</a>";
		}else {
		echo "<br/>Name: {$info['Name']}";
		}
		echo "<br/>X : $suradnice[0] Y: $suradnice[1]";
		//ak pole nema majitela mozeme ho kolonizovat
		if($info['Owner'] == 'no owner'){
		echo "<br/>No Owner,  <a href='colonize.php?xy=", $_GET['xy'] ,"'>Colonize?</a>";
		}
		else if ($info['Owner'] == $_SESSION['username']){
		echo "<br/>Owner: {$info['Owner']}";
		} else {
			$id = $_SESSION['id'];
			echo "<br/>Owner: {$info['Owner']}";
		}
		
		break;
		
		//prida mesto ktore chceme kolonizovat do databazy a je zkolonizovane
	case 'addCity':
		$suradnice = explode("_", $_GET['xy'] );
		$infoObjekt = mysqli_query($db_connect, "SELECT * FROM city WHERE X = '$suradnice[0]' AND Y = '$suradnice[1]' ");
		$info = mysqli_fetch_array($infoObjekt);
		$Name = $_SESSION['username'];
		$suradnice = explode("_", $_GET['xy'] );
		$addCity = "INSERT INTO city( Name, Owner, X, Y, MapImg, VillagePoints) VALUES ('Village', '$Name', '$suradnice[0]', '$suradnice[1]',  'Img/map/mesto.png', '1000' )";
		$objekt_vysledku = mysqli_query($db_connect, $addCity);
		//zaisti aby id v tabulkach city a buildings bolo rovnake a to ich vlastne bude spajat
		$idA = mysqli_query($db_connect," SELECT * FROM city WHERE X = '$suradnice[0]' AND Y = '$suradnice[1]'");
		$idB = mysqli_fetch_assoc($idA);
		$id = $idB['id'];
		//kazdej novej dedine priradi suroviny, ktore ma na zaciatku
	$sql = "INSERT INTO resources( Food, Glass, Iron, Wood, Stone) VALUES ('50000', '50000', '50000', '50000', '50000' )";
	$resources = mysqli_query($db_connect, $sql);
		//nastavi v databaze zakladne urovne jednotlivych budov v dedine
		$buildings = mysqli_query($db_connect," INSERT INTO buildings (id,Castle,Houses,Mine,Field,Woodcutter,StonePit,Glassworks) VALUES ('$id','1','1','1','1','1','1','1') ");
		$troops = mysqli_query($db_connect," INSERT INTO war (id,Troops, Archers, Swordmen, Cavalry, AttackArrive, AttX, AttY) VALUES ('$id','0','0','0','0','0000-00-00 00:00:00','0','0') ");
		header("Location: interface.php?view=map");
		break;
	//trenovanie vojakov
	case 'train':
		$id = $_GET['id'];
		$count = $_GET['count'];
		$cost = $count * 30;
		$type = $_GET['type'];
		$resources = mysqli_query($db_connect, "SELECT * FROM resources WHERE id = '$id'");
		$rss = mysqli_fetch_array($resources);
		$army = mysqli_query($db_connect, "SELECT * FROM war WHERE id = '$id'");
		$war = mysqli_fetch_array($army);
		switch($type){
			case 'Archers':
				$wood = rssNow('Wood') - $cost;
				$food = rssNow('Food');
				$glass = rssNow('Glass');
				$iron = rssNow('Iron');
				$stone = rssNow('Stone');
				if($wood < 0 || $food < 0 || $iron < 0 || $stone < 0|| $glass < 0 ){
					echo "not enough rss";
				} else {
				$updateRss = mysqli_query($db_connect, "UPDATE resources SET  `Glass` =  '$glass', `Iron` =  '$iron', `Stone` =  '$stone', `Wood` =  '$wood', `Food` =  '$food' WHERE  id ='$id'");
				$newCount = $count + $war['Archers'];
				$troops = $newCount + $war['Swordmen'] + $war['Cavalry'];
				$updateWar = mysqli_query($db_connect, "UPDATE war SET `Archers` = '$newCount', `Troops` = '$troops' WHERE  id ='$id' ");
				}
				header("Location: buildingView.php?type=kovac");
				break;
			case 'Swordmen':
				$wood = rssNow('Wood');
				$food = rssNow('Food');
				$glass = rssNow('Glass');
				$iron = rssNow('Iron') - $cost;
				$stone = rssNow('Stone');
				if($wood < 0 || $food < 0 || $iron < 0 || $stone < 0|| $glass < 0 ){
					echo "not enough rss";
				} else {
				$updateRss = mysqli_query($db_connect, "UPDATE resources SET  `Glass` =  '$glass', `Iron` =  '$iron', `Stone` =  '$stone', `Wood` =  '$wood', `Food` =  '$food' WHERE  id ='$id'");
				$newCount = $count + $war['Swordmen'];
				$troops = $newCount + $war['Archers'] + $war['Cavalry'];
				$updateWar = mysqli_query($db_connect, "UPDATE war SET `Swordmen` = '$newCount', `Troops` = '$troops' WHERE  id ='$id' ");
				}
				header("Location: buildingView.php?type=kovac");
				break;
			case 'Cavalry':
				$wood = rssNow('Wood');
				$food = rssNow('Food') - $cost;
				$glass = rssNow('Glass');
				$iron = rssNow('Iron');
				$stone = rssNow('Stone') - $cost;
				if($wood < 0 || $food < 0 || $iron < 0 || $stone < 0|| $glass < 0 ){
					echo "not enough rss";
				} else {
				$updateRss = mysqli_query($db_connect, "UPDATE resources SET  `Glass` =  '$glass', `Iron` =  '$iron', `Stone` =  '$stone', `Wood` =  '$wood', `Food` =  '$food' WHERE  id ='$id'");
				$newCount = $count + $war['Cavalry'];
				$troops = $newCount + $war['Archers'] + $war['Swordmen'];
				$updateWar = mysqli_query($db_connect, "UPDATE war SET `Cavalry` = '$newCount', `Troops` = '$troops' WHERE  id ='$id' ");
				}
				header("Location: buildingView.php?type=kovac");
				break;
		}
		break;
	default:
		break;
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
?>