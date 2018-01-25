<?php 
$urovenBudov = mysqli_query($db_connect,"SELECT * FROM buildings WHERE id = '$id'");
$urovenBudovy = mysqli_fetch_array($urovenBudov);
$casStavania = 30 * 60; //v sekundach
$naklady = 60000;
$naklady *= $urovenBudovy['Mine'];
$id = $_SESSION['id'];
// vyber armady
$army = mysqli_query($db_connect, "SELECT * FROM `war` WHERE `war`.`id` = '$id'");
$war = mysqli_fetch_array($army);
//vyber urovne budovy Houses kvoli populacnemu limitu
$build = mysqli_query($db_connect, "SELECT * FROM `buildings` WHERE `buildings`.`id` = '$id'");
$buildings = mysqli_fetch_array($build);
//overenie populacneho limitu podla urovni Houses, kazda uroven zvysuje o 1000 vojakov
$train = ($buildings['Houses'] * 1000) - $war['Troops'];
//overenie dostupnosti surovin jedna jednotka rozne surky Archer 30 dreva, Swordmen 30 zeleza, Cavalry 30 food a kamen
//pre lukostrelcov
if($train * 30 <= rssNowA('Wood')){
	$Archers = $train;
} else {
	$Archers = rssNowA('Wood') / 30;
}
//pre swordmenov
if($train * 30 <= rssNowA('Iron')){
	$Swordmen = $train;
} else {
	$Swordmen = rssNowA('Iron') / 30;
}
//pre jazdu
if($train * 30 <= rssNowA('Food') && $train * 30 <= rssNowA('Stone')){
	$Cavalry = $train;
} else {
	if ($train * 30 > rssNowA('Food')){
		$Cavalry = rssNowA('Food') / 30;
	} else {
		$Cavalry = rssNowA('Stone') / 30;
	}
}
//vypocet rozdielu medzi poslednou aktualizaciou surovin v databaze a sucasnostou
function timediffA(){
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
function rssNowA($type){
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
			$resourceNow = $resources['Food'] + (timediffA() * $buildings['Field']);
			break;
		case "Wood":
			$resourceNow = $resources['Wood'] + (timediffA() * $buildings['Woodcutter']);
			break;
		case "Stone":
			$resourceNow = $resources['Stone'] + (timediffA() * $buildings['StonePit']);
			break;
		case "Iron":
			$resourceNow = $resources['Iron'] + (timediffA() * $buildings['Mine']);
			break;
		case "Glass":
			$resourceNow = $resources['Glass'] + (timediffA() * $buildings['Glassworks']);
			break;
	}
	return $resourceNow;
}
?>
<div id="navigation">
<h2>Blacksmith</h2>
<p>
<table id="buildCost">
<tr>
	<td>The construction will take:</td>
	<td colspan="4">The construction will cost:</td>
</tr>
<tr>
	<td><?= $casStavania /60 ?> minutes</td>
	<td><?= $naklady?> Iron</td>
    <td><?= $naklady?> Wood</td>
    <td><?= $naklady?> Stone</td>
    <td><?= $naklady?> Glass</td>
</tr>
</table>
<form action="construction.php?duration=<?= $casStavania ?>&cost=<?= $naklady?>&type=Mine" method="post">
<input type="submit" value="Upgrade" />
</form>
</p>
<br />
<p>
In this building you can train troops. There are 3 types of troops: Archers, Swordmen and Cavalry. Train more to be the strongest!!
</p>
<table width="500px" id="train" style="border-top: white solid 1px;">
<tr><td width="15%"></td><td width="21%">Archers</td><td width="21%">Swordmen</td><td width="24%">Cavalry</td></tr>
<tr><td>You own:</td><td><?= $war['Archers']?></td><td><?= $war['Swordmen']?></td><td><?= $war['Cavalry']?></td></tr>
<tr>
<td>Train:</td>
<form action="script.php?script=train&id=<?= $id ?>&type=Archers&count=<?=(int)$Archers;?>" method="post">
<td><input name="Archers" type="text" style="width:90%; margin:0px;" max="<?= (int)$Archers;?>" value="<?= (int)$Archers;?>" />
<input type="submit" value="Train"  style="margin-top:5px;" /></td>
</form>
<form action="script.php?script=train&id=<?= $id ?>&type=Swordmen&count=<?=(int)$Swordmen;?>" method="post">
<td><input name="Swordmen" type="text" style="width:90%; margin:0px;" max="<?= (int)$Swordmen;?>" value="<?= (int)$Swordmen;?>" />
<input type="submit" value="Train"  style="margin-top:5px;" /></td>
</form>
<form action="script.php?script=train&id=<?= $id ?>&type=Cavalry&count=<?=(int)$Cavalry;?>" method="post">
<td><input name="Cavalry" type="text" style="width:90%; margin:0px;" max="<?= (int)$Cavalry;?>" value="<?= (int)$Cavalry;?>" />
<input type="submit" value="Train" style="margin-top:5px;" /></td>
</form>
</tr>
<tr><td>Cost:</td><td><?=  (int)$Archers * 30 ?> Wood</td><td><?=  (int)$Swordmen * 30 ?> Iron</td><td><?=  (int)$Cavalry * 30 ?> Food and Stone</td></tr>
</table>
</div>
<div id="local-info">
<img id="buildImg" src="Img/kovac.png" />
</div>