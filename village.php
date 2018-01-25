<?php
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$x = $_SESSION['X'];
$y = $_SESSION['Y'];
$Name = $_SESSION['username'];
$city_img = mysqli_query($db_connect, "SELECT * FROM city WHERE X = '$x' AND Y = '$y' ");
$city = mysqli_fetch_array($city_img);

$id = $city['id'];
$vysledok = mysqli_query($db_connect, " SELECT * FROM city WHERE Owner = '$Name' AND id != '$id' ");

$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE id = '$id'");
$row = mysqli_fetch_array($db_name);
date_default_timezone_set('Europe/Bratislava');
$now = strtotime(date('Y-m-d H:i:s'));
$time = strtotime($row['buildFinish']);
$timer = $time - $now;

$army = mysqli_query($db_connect, "SELECT * FROM war WHERE id = '$id'");
$war = mysqli_fetch_array($army);

//Ulozenie levelov budov do sessionu, tieto data s zmenia vzdy ked sa vyberie ina dedina
		$buildings = mysqli_query($db_connect, "SELECT * FROM buildings WHERE id = '$id'");
		$level = mysqli_fetch_array($buildings);
		$_SESSION['pevnost'] = $level['Castle'];
		$_SESSION['domy'] = $level['Houses'];
		$_SESSION['kovac'] = $level['Mine'];
		$_SESSION['mlyn'] = $level['Field'];
		$_SESSION['drevorubac'] = $level['Woodcutter'];
		$_SESSION['bana'] = $level['StonePit'];
		$_SESSION['sklar'] = $level['Glassworks'];

//ak sa nieco dostavalo spracuj to
		date_default_timezone_set('Europe/Bratislava');
		if ($row['buildFinish'] == '0000-00-00 00:00:00'){
		} else if($row['buildFinish'] < date('Y-m-d H:i:s')){
			$stone = buildRss("Stone");
			$wood = buildRss("Wood");
			$iron = buildRss("Iron");
			$glass = buildRss("Glass");
			$food = buildRss("Food");
			$updateRss = mysqli_query($db_connect, "UPDATE  resources SET  `Glass` =  '$glass', `Iron` =  '$iron', `Stone` =  '$stone', `Wood` =  '$wood', `Food` =  '$food' WHERE  id ='$id';");
			$building = mysqli_query($db_connect, "SELECT * FROM buildings WHERE id = '$id'");
			$buildings = mysqli_fetch_array($building);
			// update levelov budov
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
			
			//Ulozenie levelov budov do sessionu, tieto data s zmenia vzdy ked sa vyberie ina dedina
			$id = $city['id'];
		$buildings = mysqli_query($db_connect, "SELECT * FROM buildings WHERE id = '$id'");
		$level = mysqli_fetch_array($buildings);
		$_SESSION['pevnost'] = $level['Castle'];
		$_SESSION['domy'] = $level['Houses'];
		$_SESSION['kovac'] = $level['Mine'];
		$_SESSION['mlyn'] = $level['Field'];
		$_SESSION['drevorubac'] = $level['Woodcutter'];
		$_SESSION['bana'] = $level['StonePit'];
		$_SESSION['sklar'] = $level['Glassworks'];
		
} 

//prepocita body
		Points();
		$army = mysqli_query($db_connect, "SELECT * FROM war WHERE id = '$id'");
		$war = mysqli_fetch_array($army);
		
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
$dediny = mysqli_query($db_connect, "SELECT * FROM city WHERE `Owner` = '$name'");
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
?>
<script type="text/javascript" src="Js/js4.js"></script>
<script type="text/javascript" src="Js/js5.js"></script>
<?php
if($row['buildFinish'] == '0000-00-00 00:00:00'){
	?>
    <script type="text/javascript">
		window.setInterval(function(){
		rssUpdateView(true);
		}, 1000);
	</script>
    <?php
		} else if($row['buildFinish'] > date('Y-m-d H:i:s')){
			?>
		<script type="text/javascript">		
		window.setInterval(function(){
		rssUpdateView(true);		
		Timer(true);
		if($("#time").html() == "00:00:00"){
			location.reload();
		}
		}, 1000);		
		</script>
<?php
		}
?>
<div id="content">
		<div id="user-info">
			<div class="prof_container" style="width:440px;">
				<span class="prof_name"><?= $_SESSION['username'] ?></span>
				<span class="prof_info">
                <form id="thisCity" action="citySwitch.php?view=village" method="post">
                <select name="thisCity" onchange="document.getElementById('thisCity').submit()">
                <option value="<?= $city['id'] ?>"><?=$city["Name"]?></option>
                
                <?php foreach($vysledok as $city){ ?>
                <option value="<?=$city["id"]?>">
				<?=$city["Name"]?>
                </option>
                <?php }?>
                </select>
                </form></span>
				<span class="prof_info"><?=$x?>X / <?=$y ?>Y</span>
				<a class="prof_info" href="logout.php" style="text-align:center;" >Log Out</a>
                <a class="prof_info" href="interface.php?view=map" style="text-align:center;" >Map</a>
			</div>
			<div class="prof_container" id="resources" style="width:500px;">
            <?php include("resourceBar.php"); ?>
			</div>
		</div>
        <?php
		$id = $_SESSION['id'];
		$db_name = mysqli_query($db_connect, "SELECT * FROM city WHERE id = '$id'");
		$row = mysqli_fetch_array($db_name);
		date_default_timezone_set('Europe/Bratislava');
		if($row['buildFinish'] == '0000-00-00 00:00:00'){
		} else if($row['buildFinish'] > date('Y-m-d H:i:s')){
			?>
            <style type="text/css">
			#timer{
				float: right;
				width: 325px;
				margin: 20px;
				margin-bottom:10px;
				padding: 10px;
				background-color: #FFF;
				height: 30px;
				z-index: 50;
			}
			#local-info {
				margin-top:10px;
				margin: 20px;
				float: right;
				width: 275px;
				padding: 35px;
				height: 410px;
				background-image:url(Img/localinfoTimer.png);
			}
            </style>
            <div id="timer"><?php include("timer.php");?></div>            
			<?php		
		}
        ?>
		<div id="navigation"><?php include("villageView.php"); ?>
        </div>
		<div id="local-info">
        <table width="100%">
        <tr><td colspan="2"><b>Building levels</b></td></tr>
        <tr><td>Fortress: </td><td><?= $_SESSION['pevnost']?></td></tr>
        <tr><td>Houses: </td><td><?= $_SESSION['domy']?></td></tr>
        <tr><td>Mill:</td><td><?= $_SESSION['mlyn']?></td></tr>
        <tr><td>Woodcutter:</td><td><?= $_SESSION['drevorubac']?></td></tr>
        <tr><td>Blacksmith:</td><td><?= $_SESSION['kovac']?></td></tr>
        <tr><td>Mine:</td><td><?= $_SESSION['bana']?></td></tr>
        <tr><td>Glassworks:</td><td><?= $_SESSION['sklar']?></td></tr>
        </table>
        <table width="100%" style="border-top:#FFF solid 1px;;">
        <tr><td colspan="2"><b>Troops</b></td></tr>
        <tr><td>Archers:</td><td><?= $war['Archers'] ?></td></tr>
        <tr><td>Swordmen:</td><td><?= $war['Swordmen'] ?></td></tr>
        <tr><td>Cavalry:</td><td><?= $war['Cavalry'] ?></td></tr>
        <tr><td>Troops Together:</td><td><?= $war['Troops'] ?></td></tr>
        </table>
        <table width="100%" style="border-top:#FFF solid 1px;;">
        <tr><td width="50%"><b>Points:</b></td><td width="50%"><?= PlayerPoints()?></td></tr>
        <tr><td width="50%"><b>Village Points:</b></td><td width="50%"><?= VillagePoints()?></td></tr>
        <tr><td colspan:"2" align="center" width="100%"><b><a href="highscores.php">View Highscores</a></b></td></tr>
        </table>
        </div>