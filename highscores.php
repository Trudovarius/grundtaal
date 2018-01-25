<?php
session_start();
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$x = $_SESSION['X'];
$y = $_SESSION['Y'];
$Name = $_SESSION['username'];
$city_img = mysqli_query($db_connect, "SELECT * FROM city WHERE X = '$x' AND Y = '$y' ");
$city = mysqli_fetch_array($city_img);

$id = $city['id'];
$vysledok = mysqli_query($db_connect, " SELECT * FROM city WHERE Owner = '$Name' AND id != '$id' ");

function PlayerRankings(){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$Players = mysqli_query($db_connect, "SELECT * FROM  `user` ORDER BY  `user`.`Points` DESC LIMIT 0,15");
	$count = 1;
	echo '<table width="100%" id="highscores">';
	echo '<tr><td colspan="3"><b>Player Rankings<b/></td></tr>';
	echo '<tr><td>Position:</td><td>Name:</td><td>Points:</td></tr>';
	foreach($Players as $Player){
		echo '<tr><td>',$count,'</td><td>',$Player["Name"],'</td><td>',$Player["Points"],'</td></tr>';
		$count++;
	}
	echo '</table>';
}
function VillageRankings(){
	$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
	$Villages = mysqli_query($db_connect, "SELECT * FROM  `city` ORDER BY  `city`.`VillagePoints` DESC LIMIT 0,15");
	$count = 1;
	echo '<table width="90%" style="margin:35px;" id="highscores">';
	echo '<tr><td colspan="4"><b>Village Rankings<b/></td></tr>';
	echo '<tr><td>Position:</td><td>Name:</td><td>Owner:</td><td>Points:</td></tr>';
	foreach($Villages as $Village){
		echo '<tr><td width="25%">',$count,'</td><td width="25%">',$Village["Name"],'</td><td>',$Village["Owner"],'</td><td>',$Village["VillagePoints"],'</td></tr>';
		$count++;
	}
	echo '</table>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Highscores</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">!window.jQuery && document.write('<script src="Js/jquery-1.11.1.js"><\/script>')</script>
<script type="text/javascript" src="Js/js3.js"></script>
<script type="text/javascript" src="Js/js2.js"></script>
<script type="text/javascript" src="Js/js4.js"></script>
<script type="text/javascript">
window.setInterval(function(){
  rssUpdateView(true);
}, 1000);
</script>
<link href="css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="content">
		<div id="user-info">
			<div class="prof_container" style="width:440px;">
				<span class="prof_name"><?= $_SESSION['username'] ?></span>
				<span class="prof_info">
				<form id="thisCity" action="citySwitch.php?view=map" method="post">
                <select name="thisCity" onchange="document.getElementById('thisCity').submit()">
                <option value="<?= $city['id'] ?>"><?=$city['Name']?></option>
                
                <?php foreach($vysledok as $city){ ?>
                <option value="<?= $city['id'] ?>">
				<?=$city['Name']?>
                </option>
                <?php }?>
                </select>
                </form>
                </span>
				<span class="prof_info"><?=$x?>X / <?=$y ?>Y</span>
				<a class="prof_info" href="logout.php" style="text-align:center;" >Log Out</a>
                <a class="prof_info" href="interface.php?view=village" style="text-align:center;" >Village</a>
			</div>
			<div class="prof_container" id="resources" style="width:500px;">
            <?php include("resourceBar.php"); ?>
			</div>
		</div>
		<div id="navigation">
        <?php
		VillageRankings();
		?>
        </div>
		<div id="local-info">
        <?php		
		PlayerRankings();
		?>
        </div>
</body>
</html>