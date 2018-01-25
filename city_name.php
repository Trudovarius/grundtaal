<?php
session_start();
$suradnice = explode("_", $_GET['xy'] );
$x = $suradnice[0];
$y = $suradnice[1];
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$name = $_SESSION['username'];
$city_name = mysqli_query($db_connect, "SELECT * FROM city WHERE Owner = '$name' AND X = '$x' AND Y = '$y' ");
$city = mysqli_fetch_array($city_name);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="updatevillagename.php?xy=<?= $_GET['xy'] ?>" method="post">
<div id="content">
	<div id="profile-detail">
    <span>Actual City Name: <?php echo $city['Name']; ?> </span>
    
    <span>New City Name: </span>
    <input name="city_name" type="text" />
    <br/>
	<input style="margin-top:20px; margin-left:30px;" type="submit" value="Save" />
	</div>
</div>
</form>
</body>
</html>