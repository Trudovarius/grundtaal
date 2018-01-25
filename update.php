<?php
session_start();
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$name = $_SESSION['username'];
$city_name = mysqli_query($db_connect, "SELECT * FROM city WHERE Owner = '$name' ");
$city = mysqli_fetch_array($city_name);
if(isset($_POST['city_name']))
	 {
		 if($_POST['city_name'] == '')
		 {
			 $change_city_name_fail = 'Your Village has to have a name. The name didn\'t change<br/>';
			 header("Location: interface.php?view=village");
		 }
		 else
		 {
			$change_city_name_fail = 'Changed Village name.<br/>';
	 	 	$city_name = $_POST['city_name'];
		 	$id = $city['ID'];
	 	 	$new_name = mysqli_query($db_connect, "UPDATE  `rp_web`.`city` SET  `Name` =  '$city_name' WHERE  `city`.`ID` =$id;");
		 	header("Location: interface.php?view=village");
		 }
	 }