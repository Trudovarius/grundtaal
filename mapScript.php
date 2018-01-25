<?php
if((isset($_GET['x'])) && (isset($_GET['y']))){
	session_start();
	$x = $_GET['x'];
	$y = $_GET['y'];
}
else{
	$x = $_SESSION['X'];
	$y = $_SESSION['Y'];	
}

$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$name = $_SESSION['username'];
$city_name = mysqli_query($db_connect, "SELECT * FROM city WHERE Owner = '$name' ");
$city = mysqli_fetch_array($city_name);

//ovladanie mapy
include ('mapControl.php');

//mapa
echo '<table id="map_table">';
for($i = $x - 2; $i <= $x + 2; $i++)
{
	echo '<tr>';
	for($j = $y - 2; $j <= $y + 2; $j++){
		$map = mysqli_query($db_connect, "SELECT * FROM city WHERE X = '$i' AND Y = '$j' ");
		$field = mysqli_fetch_array($map);
		if(!isset($field)){
			echo "<td class='map_field'><img class='map_img' id='{$i}_{$j}'  height='100px' width='100px' src='Img/map/grass.jpg'></td>";
			}
			else{
				echo "<td class='map_field'><img class='map_img' id='{$i}_{$j}' height='100px' width='100px' src='" . $field['MapImg'] ."' /></td>";
			}
	}
	echo '</tr>';
}
echo '</table>';