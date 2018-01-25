 <?php
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$x = $_SESSION['X'];
$y = $_SESSION['Y'];
$Name = $_SESSION['username'];
$city_img = mysqli_query($db_connect, "SELECT * FROM city WHERE X = '$x' AND Y = '$y' ");
$city = mysqli_fetch_array($city_img);

$id = $city['id'];
$vysledok = mysqli_query($db_connect, " SELECT * FROM city WHERE Owner = '$Name' AND id != '$id' ");
?>
<script type="text/javascript" src="Js/js4.js"></script>
<script type="text/javascript">
window.setInterval(function(){
  rssUpdateView(true);
}, 1000);
</script>
<link href="map.css" rel="stylesheet" type="text/css" />
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
		include ('mapLoad.php');
		?>
        </div>
		<div id="local-info">
        </div>