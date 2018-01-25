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

if(isset($_GET['type'])){
	$typ = $_GET['type'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome <?= $_SESSION['username']?></title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="Js/js4.js"></script>
<script type="text/javascript">
window.setInterval(function(){
  rssUpdateView(true);
}, 1000);
</script>
</head>

<body>
<div id="content">
		<div id="user-info">
			<div class="prof_container" style="width:440px;">
				<span class="prof_name"><?= $_SESSION['username'] ?></span>
				<span class="prof_info">
                <form id="thisCity" action="citySwitch.php?view=village" method="post">
                <select name="thisCity" onChange="document.getElementById('thisCity').submit()">
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
                <a class="prof_info" href="interface.php?view=village" style="text-align:center;" >Village</a>
			</div>
			<div class="prof_container" id="resources" style="width:500px;">
            <?php include("resourceBar.php"); ?>
			</div>
		</div>
		<?php 
switch($typ){
	case "pevnost":
	include("buildings/pevnost.php");
		break;
	case "drevorubac":
	include("buildings/drevorubac.php");
		break;
	case "bana": 
	include("buildings/bana.php");
		break;
	case "domy": 
	include("buildings/domy.php");
		break;
	case "mlyn": 
	include("buildings/mlyn.php");
		break;
	case "sklar": 
	include("buildings/sklar.php");
		break;
	case "kovac": 
	include("buildings/kovac.php");
		break;
	default: 
		break;
}
?>
		<div id="footer"></div>
</div>
</body>
</html>